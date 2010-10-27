<?php

/**
 * Short description for file
 *
 * Long description (if any) ...
 *
 * PHP version unknow
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
 * @package   Mxit Dictionary
 * @author    Qhamani Fenama <qfenama@gmail.com>
 * @copyright 2007 Qhamani fenama
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id: controller.php 11940 2008-12-29 21:21:54Z qfenama $
 * @link      http://avoir.uwc.ac.za
 * @see       References to other sections (if any)...
 */
// security check - must be included in all scripts
/**
 * Description for $GLOBALS
 * @global unknown $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 */
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}

// end security check

/**
 *
 * Model controller for the table tbl_phonebook
 * @authors: Qhamani Fenama <qfenama@gmail.com>
 * @copyright 2010 University of the Western Cape
 */
class sasicontext extends controller {

    /**
     * Description for public
     * @var    unknown
     * @access public
     */
    public $objLanguage;
    /**
     * Description for public
     * @var    unknown
     * @access public
     */
    public $objConfig;
    /**
     * Description for public
     * @var    object
     * @access public
     */
    public $dbSasicontext;
    /**
     * Description for public
     * @var    object
     * @access public
     */
    public $objUser;

    /**
     * Constructor method to instantiate objects and get variables
     */
    public function init() {
        try {
            $this->objUser = $this->getObject('user', 'security');
            $this->objUsers = $this->getObject('users');
            $this->objLanguage = $this->getObject('language', 'language');
            $this->objConfig = $this->getObject('altconfig', 'config');
            $this->dbSasicontext = $this->getObject('dbsasicontext', 'sasicontext');
            $this->objSasicontext = $this->getObject('sasiwebserver', 'sasicontext');
            $this->objContext = $this->getObject('dbcontext', 'context');
            $this->objSasiUsers = $this->getObject('users');
            $this->contextCode = $this->objContext->getContextCode();
            $this->contextTitle = $this->objContext->getTitle();
            if ($this->contextCode == 'root' || $this->contextCode == NULL) {
                return $this->nextAction ( NULL, NULL, '_default' );
            }
        } catch (customException $e) {
            echo customException::cleanUp();
            die();
        }
    }

    //end of init function

    /**
     * Method to process actions to be taken
     *
     * @param string $action String indicating action to be taken
     */
    public function dispatch($action) {
        switch ($action) {
            default:
            //setup the layout
                $this->setLayoutTemplate('default_layout_tpl.php');
                $arr = array ();
                $arr['site'] = $this->getParam('addtosite');
                $arr['context'] = $this->getParam('addtocontext');
                $arr['removed'] = $this->getParam('removed');
                $this->setVarByRef ( 'addedArray', $arr);
                return 'home_tpl.php';
                break;
            case 'showfac':
                $mydata = $this->getParam('get', 'faculty');
                $this->setVarByRef('mydata', $mydata);
                return 'default_tpl.php';
                break;
            case 'adddata' :
                $faculty = $this->getParam('faculty');
                $department =  $this->getParam('dept');
                $sasiCode =  $this->getParam('subjcode');
                if($this->objSasicontext->addData($this->contextCode, $faculty, $department, $sasiCode)) {
                    echo '<h1>'.$this->objLanguage->code2Txt("mod_sasicontext_success", "sasicontext").'</h1>';
                }
                else {
                    echo '<center><h1>'.$this->objLanguage->code2Txt("mod_sasicontext_success", "sasicontext").'</h1></center>';
                }
                exit(0);
                break;
            case 'synchronize':
                $this->objUsers->synchronizeAll($this->contextCode);
                $this->nextAction(null, array ('addtocontext' =>  $this->objUsers->addtocontext ,'addtosite' => $this->objUsers->addtosite, 'removed' => $this->objUsers->removed));
                exit (0);
                break;
        } //end of switch
    }


}

?>
