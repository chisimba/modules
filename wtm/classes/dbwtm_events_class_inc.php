<?php
/**
* WTM Events class
*
* This file provides a database access class for the WTM module's
* events database. Its purpose is allow administrators to manage
* the database.
*
* @category Chisimba
* @package WTM
* @author Yen-Hsiang Huang <wtm.jason@gmail.com>
* @copyright 2007 AVOIR
* @license http://www.gnu.org/licenses/gpl-2.0.txt The GNU General
Public License
* @version CVS: $Id:$
* @link: http://avoir.uwc.ac.za */


// security check
/**
* The $GLOBALS is an array used to control access to certain constants.
* Here it is used to check if the file is opening in engine, if not it
* stops the file from running.
*
* @global entry point $GLOBALS['kewl_entry_point_run']
* @name $kewl_entry_point_run
*/
if (!$GLOBALS['kewl_entry_point_run'])
{
	die("You cannot view this page directly");
}
// end security check


class dbwtm_events extends dbtable
{

/**
     * Constructor method to define the table
     */
    function init() 
    {
        parent::init('tbl_wtm_events');
    }
    /**
     * Return all records
     * @param string $userid The User ID
     * @return array The entries
     */
    function listAll() 
    {
        return $this->getAll();
    }
    /**
     * Return all records
     * @param string $building The building
     * @return array The entries
     */
    function listbuilding($event) 
    {
        return $this->getAll("WHERE event LIKE '%" . $event . "%'");
    }
    /**
     * Return a single record
     * @param string $id ID
     * @return array The values
     */
    function listSingle($id) 
    {
        return $this->getAll("WHERE id='" . $id . "'");
    }
    /**
     * Insert a record
     * @param string $building building
     * @param string $coordinate coordinates
     * -- @param string $userId The user ID
     */
    function insertSingle($buildingid,$event,$date,$description,$imagename,$videoname) 
    {
        $id = $this->insert(array(
				'buildingid' => $buildingid,
				'event' => $event,
				'date' => $date,
				'description' => $description,
				'imagename' => $imagename,
				'videoname' => $videoname
        ));
        return $id;
    }
    /**
     * Update a record
     * @param string $id ID
     * @param string $category Category
     * -- @param string $userId The user ID
     */
    function updateSingle($id,$buildingid,$event,$date,$description,$imagename,$videoname) 
    {
        $this->update("id", $id, array(
				'buildingid' => $buildingid,
				'event' => $event,
				'date' => $date,
				'description' => $description,
				'medianame' => $imagename,
				'videoname' => $videoname,
				'modified' => TRUE
        ));
    }
    /**
     * Delete a record
     * @param string $id ID
     */
    function deleteSingle($id) 
    {
        $this->delete("id", $id);
    }

}
?>

























