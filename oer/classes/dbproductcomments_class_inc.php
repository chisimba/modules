<?php

/**
 * This class provides the interface to the database for managing product comments
 *
 * @author pwando
 */
class dbproductcomments extends dbTable {

    private $tableName = 'tbl_oer_productcomments';

    function init() {
        parent::init($this->tableName);
    }
    
    /**
     * gets all the product comments
     * @param type $productId
     * @return Array of the product comments
     */
    function getProductComments($productId) {
        $sql =
                "select * from $this->tableName where product_id = '$productId'";
        return $this->getArray($sql);
    }

    /**
     * returns comment as per specified id
     * @param string $Id comment id
     * @return array
     */

    function getComment($Id) {
        $sql = "select * from tbl_oer_productcomments where id = '$Id'";
        $data = $this->getArray($sql);
        if (count($data) > 0) {
            return $data[0];
        } else {
            return null;
        }
    }
    /**
     * records user comments on a product
     * @param type $data - the comment details
     * @return type ID of the saved section
     */
    function addComment($data) {
        return $this->insert($data);
    }
}
?>