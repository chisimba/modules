<?php

/**
 * Class the records the pages a user has visited.
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
 * @author    Paul Mungai <paulwando@gmail.com>
 * @copyright @2009 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   Release: @package_version@
 * @link      http://avoir.uwc.ac.za
 * @see       core
 */

class db_learningcontent_activitystreamer extends dbtable
{

    /**
     * Constructor
     */
    public function init()
    {
        parent::init('tbl_learningcontent_activitystreamer');
        $this->objUser =& $this->getObject('user', 'security');
    }
    
    /**
     * Method to add a record.
     *
     * @access public
     * @param string $titleId Record controlling translation group index
     * @param string $menutitle Menu title of the page
     * @param string $content Content of the Page
     * @param string $language Language of the Page
     * @param string $headerScript Any Script to go in the header
     * @return string The title id.
     */
    public function addRecord($userId, $contextItemId, $contextCode)
    {
        $row = array();
        $row['userid'] = $userId;
        $row['contextcode'] = $contextCode;
        $row['contextitemid'] = $contextItemId;
        $row['datecreated'] = strftime('%Y-%m-%d %H:%M:%S', mktime());

        return $this->insert($row);
    }

    /**
     * Checks if record exists.
     *
     * @access public
     * @param string $id The activitystreamer id.
     * @return boolean
     */
    public function idExists($id)
    {
        return $this->valueExists('id', $id);
    }
    /**
     * Method to check if record exists according to userId, contextItemId and contextCode.
     *
     * @access public
     * @param string $userId User ID
     * @param string $contextItemId Context Item Id
     * @param string $contextCode Context Code
     * @return TRUE
     */
    public function getRecord($userId, $contextItemId, $contextCode)
    {
        $where = "WHERE userid = '$userId' AND contextitemid = '$contextItemId' AND contextcode = '$contextCode'";
        $results = $this->getAll($where);
        if (isset($results[0]['id'])) {
            return TRUE;
        } else {
            return FALSE;
        }
    }    
    /**
     * Method to retrieve a record id according to userId, contextItemId and contextCode.
     *
     * @access public
     * @param string $userId User ID
     * @param string $contextItemId Context Item Id
     * @param string $contextCode Context Code
     * @return string Record ID
     */
    public function getRecordId($userId, $contextItemId, $contextCode)
    {
        $where = "WHERE userid = '$userId' AND contextitemid = '$contextItemId' AND contextcode = '$contextCode'";
        $results = $this->getAll($where);
        if (isset($results[0]['id'])) {
            return $results[0]['id'];
        } else {
            return FALSE;
        }
    }
    /**
     * Method to delete a record
     * @param string $contextItemId Context Item Id
     */
    function deleteRecord($contextItemId)
    {
        // Delete the Record
        $this->delete('contextitemid', $contextItemId);
    }
}
?>
