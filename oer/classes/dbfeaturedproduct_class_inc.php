<?php

/**
 * This is a DB layer that manages original products
 *
 * @author davidwaf
 */
class dbfeaturedproduct extends dbtable {

    private $productsTableName = 'tbl_oer_featuredproduct';

    function init() {
        parent::init($this->productsTableName);
    }

   
    /**
     *returns the id of the featured product
     * @return type 
     */
    function getFeaturedProduct() {
        $sql =
                "select * from $this->productsTableName order by puid desc limit 1";
        $data = $this->getArray($sql);
        if (count($data) > 0) {
            return $data[0]['productid'];
        } else {
            return NULL;
        }
    }

    /**
     * deletes a product
     * @param $id  ID of the product to be deleted
     */
    function setFeaturedProduct($data) {
        $this->insert($data);
    }
}
?>