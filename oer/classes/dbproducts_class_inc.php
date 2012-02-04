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
     * this selects original products
     */
    function getAdaptedProducts() {
        $sql = "select * from $this->productsTableName where parent_id is not null";
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
     * Get parent product data
     * @param  $id ID of the product
     * @return array
     */
    function getParentData($id) {
        //Fetch parent id of the adaptation
        $sql = "select * from $this->productsTableName where id = '$id'";
        $data = $this->getArray($sql);
        if (count($data) > 0) {
            $sql = "select * from $this->productsTableName where id = '" . $data[0]["parent_id"] . "'";
            $data = $this->getArray($sql);
            if (count($data) > 0) {
                return $data[0];
            } else {
                return NULL;
            }
        } else {
            return NULL;
        }
    }

    /**
     * returns product details for a specific id
     * @param  $id the product id 
     * @return NULL if product not found, else an array with product details
     */
    function getProduct($id) {
        $sql =
                "select * from $this->productsTableName where id = '$id'";
        $data = $this->getArray($sql);
        if (count($data) > 0) {
            return $data[0];
        } else {
            return NULL;
        }
    }

    /**
     * returns true if product is original-product
     * @param  $id the product id
     * @return True if product does not have parentid, else false
     */
    function isOriginalProduct($id) {
        $sql = "select * from $this->productsTableName where id = '$id'";
        $data = $this->getArray($sql);
        if (count($data) > 0) {
            if (!empty($data[0]['parent_id'])) {
                return False;
            } else {
                return True;
            }
        } else {
            return False;
        }
    }

    /**
     * deletes a product
     * @param $id  ID of the product to be deleted
     */
    function deleteOriginalProduct($id) {
        $this->delete("id", $id);
    }

}

?>