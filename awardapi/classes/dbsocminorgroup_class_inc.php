<?php
/**
 * AWARD index data access class
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
 * @package   award
 * @author    Nic Appleby <nappleby@uwc.ac.za>
 * @copyright 2008 Nic Appleby
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id$
 * @link      http://avoir.uwc.ac.za
 * @see       core,api
 */
// security check - must be included in all scripts
if (!
/**
 * Description for $GLOBALS
 * @global entry point $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 */
$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check


/**
 * AWARD XML_RPC & data access class
 * 
 * Class to provide AWARD SIC Major Div information from the database
 * 
 * @category  Chisimba
 * @package   award
 * @author    Nic Appleby <nappleby@uwc.ac.za>
 * @copyright 2008 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id$
 * @link      http://avoir.uwc.ac.za
 */

class dbsocminorgroup extends dbTable {

    /**
    * Class Constructor
    *
    * @access public
    * @return void
    */
    public function init() {
        try {
            parent::init('tbl_award_socminorgroup');
        } catch (Exception $e){
            throw customException($e->getMessage());
            exit();
        }
    }

function saveRecord($mode)
     {
        if ($mode == 'add') {
            $fields = array('majorGroupId' => $this->getParam('majorGroupId'),
                'subMajorGroupId' => $this->getParam('subMajorGroupId'),
                'description' => $this->getParam('description'),
                //'creatorId' => $this->objUser->userId(),
                //'dateCreated' => date('Y-m-d H:i')
                );
            $this->insert($fields);
        } 
        if ($mode == 'edit') {
            $id = $this->getParam('minorGroupId');
            $minorGroup = $this->getRow('id', $id);
            $fields = array('majorGroupId' => $this->getParam('majorGroupId'),
                'subMajorGroupId' => $this->getParam('subMajorGroupId'),
                'description' => $this->getParam('description'),
                //'creatorId' => $minorGroup['creatorId'],
                //'dateCreated' => $minorGroup['dateCreated'],
                //'modifierId' => $this->objUser->userId(),
                //'dateModified' => date('Y-m-d H:i')
                );
            $this->update('id', $id, $fields);
        } 
    }  
 }       

?>