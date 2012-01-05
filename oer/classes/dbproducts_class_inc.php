<?php

/**
 * This is a DB layer that manages original products
 *
 * @author davidwaf
 */
class dbproducts extends dbtable {

    private $productsTableName = 'tbl_oer_products';

    function init() {
        parent::init($this->productsTableName);
    }

    /**
     * this selects original products
     */
    function getOriginalProducts() {
        $sql = "select * from $this->productsTableName where parent_id is null";
        return $this->getArray($sql);
    }

    /**
     * saves original product into db
     */
    function saveOriginalProduct($data) {
        return $this->insert($data);
    }

    /**
     * Updates original product
     * @param  $data fields containing updated data
     * @param  $id ID of product to be updated
     * @return type 
     */
    function updateOriginalProduct($data, $id) {
        return $this->update("id", $id, $data);
    }
    
    /**
     * returns product details for a specific id
     * @param  $id the product id 
     * @return NULL if product not found, else an array with product details
     */
    function getProduct($id){
        $sql=
        "select * from $this->productsTableName where id = '$id'";
        $data=$this->getArray($sql);
        if(count($data) > 0){
            return $data[0];
        }else{
            return NULL;
        }
    }

    /**
     * deletes a product
     * @param $id  ID of the product to be deleted
     */
    function  deleteOriginalProduct($id){
        $this->delete("id", $id);
    }
}

?>
