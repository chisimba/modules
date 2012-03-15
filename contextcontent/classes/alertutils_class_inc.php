<?php
/**
 * this class contains utilities for sending alerts as emails, tweets, etc
 *
 *
 * PHP version 5
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the
 * Free Software Foundation, Inc.,
 * 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 * @category  Chisimba
 * @package   contextcontent
 * @author    Paul Mungai <paulwando@gmail.com>
 * @copyright @2009 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id: db_contextcontent_titles_class_inc.php 11385 2008-11-07 00:52:41Z charlvn $
 * @link      http://avoir.uwc.ac.za
 * @see       core
 */

// security check - must be included in all scripts
if (!
/**
 * Description for $GLOBALS
 * @global unknown $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 */
$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check

/**
 * Class the records the pages a user has visited.
 *
 * It doesn't contain the content of pages, just the index to track which pages
 * are translations of each other.
 *
 * @category  Chisimba
 * @package   contextcontent
 * @author    Davi Wafula
 * @copyright @2010 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 */

class alertutils extends object {
    function init() {
        $this->objUser=$this->getObject('user','security');
        $this->objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
        $this->objGroupOps=$this->getObject('groupops','groupadmin');
        $this->objGroups = $this->getObject('groupadminmodel','groupadmin');
        $this->objManageGroups = $this->getObject('managegroups', 'contextgroups');
    }
    function sendEmailAlert($contextcode, $title) {
        $students = $this->objManageGroups->contextUsers('Students', $contextcode, array( 'tbl_users.userid','emailaddress', 'firstname', 'surname'));
        $subject = $this->objSysConfig->getValue('CONTEXTCONTENT_EMAIL_ALERT_SUB', 'contextcontent');
        $subject = str_replace("{course}", $title, $subject);
        $objMailer = $this->getObject('email', 'mail');
        foreach ($students as $student) {
            $body = $this->objSysConfig->getValue('CONTEXTCONTENT_EMAIL_ALERT_BDY', 'contextcontent');
            $linkUrl = $this->uri(array('action'=>'joincontext', 'contextcode'=>$contextcode, 'passthroughlogin'=>'true'));
            $linkUrl = str_replace('&amp;', '&', $linkUrl);
            $body = str_replace("{link}", $linkUrl, $body);
            $body = str_replace("{firstname}", $student['firstname'], $body);
            $body = str_replace("{lastname}", $student['surname'], $body);
            $body = str_replace("{course}", "'".$title."'", $body);
            $body = str_replace("{instructor}", $this->objUser->getTitle().'. '.$this->objUser->fullname().',', $body);
            //trigger_error($student['emailaddress']);
            //$addressArr = ;
            //trigger_error(var_export($addressArr, TRUE));
            $objMailer->clearAddresses();
            $objMailer->clearCCs();
            $objMailer->clearBCCs();
            $objMailer->setValue('to', array($student['emailaddress']));
            $objMailer->setValue('from', $this->objUser->email());
            $objMailer->setValue('fromName', $this->objUser->fullname());
            $objMailer->setValue('subject', $subject);
            $objMailer->setValue('body', strip_tags($body));
            $objMailer->setValue('AltBody', strip_tags($body));
            $objMailer->send();
        }
    }

}
?>
