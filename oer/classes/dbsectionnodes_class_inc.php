<?php

/**
 * This class provides the interface to the database for managing product sections
 *
 * @author davidwaf
 */
class dbsectionnodes extends dbTable {

    private $tableName = 'tbl_oer_sectionnodes';

    function init() {
        parent::init($this->tableName);
    }

    /**
     * returns the nodes of the sections
     * @param type $productId
     * @return type 
     */
    function getSectionNodes($productId) {
        $sql =
                "select * from tbl_oer_sectionnodes where product_id = '$productId'";
        return $this->getArray($sql);
    }

    

    function getSectionNode($sectionId) {
        $sql =
                "select * from tbl_oer_sectionnodes where id = '$sectionId'";
        $data = $this->getArray($sql);
        if (count($data) > 0) {
            return $data[0];
        } else {
            return null;
        }
    }

    
    
    
    
    /**
     * creates a new section record for a product
     * @param type $data - the section details
     * @return type ID of the saved section
     */
    function addSectionNode($data) {
        return $this->insert($data);
    }

}

?>
