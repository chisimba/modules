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
     * this determines if a product has any sections created
     * @param type $productid The product to check sections for
     * @return type Boolean: True if sectiond exist, FALSE otherwise
     */
    function sectionsExist($productId) {
        $sql =
                "select count(id) as totalnodes from tbl_oer_sectionnodes where product_id = '$productId'";
        $results = $this->getArray($sql);

        if (count($results) > 0) {
            if (array_key_exists('first', $results)) {
                return $results['totalnodes'] > 0 ? TRUE : FALSE;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    /**
     * returns the nodes of the sections
     * @param type $productId
     * @return type 
     */
    function getSectionNodes($productId) {
        $sql =
                "select * from tbl_oer_sectionnodes where product_id = '$productId' order by level";
       
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

    /**
     *  Updates the section info using the supplied id
     * @param type $data
     * @param type $sectionId
     * @return type 
     */
    function updateSectionNode($data,$sectionId){
        return $this->update("id", $sectionId, $data);
    }
}

?>
