<?php

/* ----------- data class extends dbTable for tbl_oer_downloaders------------ */
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}

/**
 * Model class for the table tbl_oer_downloaders
 * @author Paul Mungai
 * @copyright 2012 Kengasolutions
 */
class dboer_downloaders extends dbTable {

    /**
     * Constructor method to define the table
     */
    function init() {
        parent::init('tbl_oer_downloaders');
        $this->objUser = &$this->getObject('user', 'security');        
    }

    /**
     * Return all records as per params
     * @param string $columname
     * @param string $colvalue
     * @return array
     */
    function listAll($columname, $colvalue) {
        return $this->getAll("WHERE " . $columname . "='" . $colvalue . "'");
    }

    /**
     * Return a single record
     * @param string $id ID
     * @return array The values
     */
    function listSingle($id) {
        return $this->getAll("WHERE id='" . $id . "'");
    }

    /**
     * save adaptation into db
     * @param Array $data
     * @return String $id Id of newly inserted record
     */
    function insertSingle($data) {
        $id = $this->insert($data);
        return $id;
    }

    /**
     * Updates A Record
     * @param string $id ID of the record to be updated
     * @return string
     */
    function updateSingle($id) {
        $data = array(
            'fname' => $this->getParam("fname"),
            'lname' => $this->getParam("lname"),
            'email' => $this->getParam("email"),
            'organisation' => $this->getParam("organisation"),
            'occupation' => $this->getParam("occupation"),
            'downloadreason' => $this->getParam("downloadreason"),
            'useterms' => $this->getParam("useterms")
        );

        return $this->update("id", $id, $data);
    }

    /**
     * Delete a record
     * @param string $id ID
     */
    function deleteSingle($id) {
        $this->delete("id", $id);
    }

}

?>