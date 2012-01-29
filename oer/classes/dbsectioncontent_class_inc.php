<?php

/**
 * This class provides the interface to the database for managing product sections
 *
 * @author davidwaf
 */
class dbsectioncontent extends dbTable {

    private $tableName = 'tbl_oer_sectioncontent';

    function init() {
        parent::init($this->tableName);
    }

    /**
     * Returns content of a section given a node id
     * @param type $nodeId
     * @return type 
     */
    function getSectionContent($nodeId) {
        $sql =
                "select * from tbl_oer_sectioncontent where node_id = '$nodeId'";
        $data = $this->getArray($sql);
        if (count($data) > 0) {
            return $data[0];
        } else {
            return FALSE;
        }
    }

    /**
     * creates a new section record for a product
     * @param type $data - the section details
     * @return type ID of the saved section
     */
    function addSectionContent($data) {
        return $this->insert($data);
    }

    /** 
     * updates the section data
     * @param type $data
     * @param type $id
     * @return type 
     */
    function updateSectionContent($data,$id){
        return $this->update("id", $id, $data);
    }
}

?>
