<?php
/* ----------- data class extends dbTable for tbl_email------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
/**
 * Model class for the table tbl_liftclub_cities
 * @author Paul Mungai
 * @copyright 2009 University of the Western Cape
 */
class dbLiftclub_cities extends dbTable
{
    /**
     * Constructor method to define the table
     */
    function init() 
    {
        parent::init('tbl_liftclub_cities');
        $this->objUser = &$this->getObject('user', 'security');
    }
    /**
     * Return all records
     * @param string $id The City ID
     * @return array The entries
     */
    function listAll($id) 
    {
        return $this->getAll("WHERE id='" . $id . "'");
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
    function getByItem($Id) 
    {
        $sql = "SELECT * FROM tbl_liftclub_cities WHERE id = '" . $Id . "'";
        $data = $this->getArray($sql);
        if (!empty($data)) {
            return $data;
        } else {
            return FALSE;
        }
    }
    /**
     * Insert a record
     * @param string $city The city
     */
    function insertSingle($city) 
    {
        $id = $this->insert(array(
            'city' => $type
         ));
        return $id;
    }
    /**
     * Update a record
     * @param string $city The city
     * -- @param string $id The city ID
     */
    function updateSingle($id, $city) 
    {
        $this->update("id", $id, array(
            'city' => $city
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
