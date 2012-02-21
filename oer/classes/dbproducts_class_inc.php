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
        $id = $this->insert($data);
        $objIndexData = $this->getObject('indexdata', 'search');
        $saveDate = date('Y-m-d H:M:S');
        $name = $data['title'];
        $product = $this->getProduct($id);
        $description = $product['description'];
        $url = $this->uri(array('action' => 'vieworiginalproduct', 'id' => $id), 'oer');
        $objTrimStr = $this->getObject('trimstr', 'strings');
        $teaser = $objTrimStr->strTrim(strip_tags($description), 500);
        $objUser = $this->getObject("user", "security");
        $userId = $objUser->userId();
        $module = 'oer';

        $objIndexData->luceneIndex(NULL, $saveDate, $url, $name, NULL, $teaser, $module, $userId, NULL, NULL, NULL);
        return $id;
    }

    /**
     * Updates original product
     * @param  $data fields containing updated data
     * @param  $id ID of product to be updated
     * @return type 
     */
    function updateOriginalProduct($data, $id) {
        $this->update("id", $id, $data);
        // Prepare to add product to search index
        $objIndexData = $this->getObject('indexdata', 'search');
        $saveDate = date('Y-m-d H:M:S');
        $name = $data['title'];
        $product = $this->getProduct($id);
        $description = $product['description'];
        $url = $this->uri(array('action' => 'vieworiginalproduct', 'id' => $id), 'oer');
        $objTrimStr = $this->getObject('trimstr', 'strings');
        $teaser = $objTrimStr->strTrim(strip_tags($description), 500);
        $objUser = $this->getObject("user", "security");
        $userId = $objUser->userId();
        $module = 'oer';

        $objIndexData->luceneIndex(NULL, $saveDate, $url, $name, NULL, $teaser, $module, $userId, NULL, NULL, NULL);

        return $id;
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
        $sql = "select * from $this->productsTableName where id = '$id'";
        $data = $this->getArray($sql);
        if (count($data) > 0) {
            return $data[0];
        } else {
            return NULL;
        }
    }

    /**
     * returns random x number of adaptations
     * @param type $fragment
     * @param type $limit
     * @return type 
     */
    function getRandomAdaptationsByInstitution($fragment, $limit) {
        //$sql='SELECT * FROM tbl_oer_products WHERE RAND()<='.$fragment.' and parent_id is not null limit '.$limit.';';
        $sql = 'SELECT * FROM  tbl_oer_products where  parent_id IS NOT NULL ORDER BY RAND() LIMIT ' . $limit . ';';
        return $this->getArray($sql);
    }

    /**
     *  returns list of adaptations by given institution id
     * @param type $institutionId 
     */
    function getAdaptationsByInstitution($institutionId) {
        $sql = "select * from $this->productsTableName where institutionid = '$institutionId'";

        return $this->getArray($sql);
    }

    /**
     *
     * @param type $institutionId
     * @return type 
     */
    function getProductAdaptationCountByInstitution($institutionId) {
        $sql = "select count(*) as adaptationcount from $this->productsTableName where institutionid = '$institutionId'";
        $data = $this->getArray($sql);
        return $data[0]['adaptationcount'];
    }

    /**
     * returns count of adaptations for a specific product
     * @param  $id the product id
     * @return NULL if product not found, else a count of adaptations
     */
    function getProductAdaptationCount($parentId) {
        $sql = "select count(*) as adaptationcount from $this->productsTableName where parent_id = '$parentId'";
        $data = $this->getArray($sql);
        return $data[0]['adaptationcount'];
    }

    /**
     * returns array of adaptations for a specific product
     * @param  $id the product id
     * @return NULL if product not found, else an array of product adaptations if any
     */
    function getProductAdaptations($parentId) {
        $sql = "select * from $this->productsTableName where parent_id = '$parentId'";
        $data = $this->getArray($sql);
        return $data;
    }

    /**
     * returns count of adaptations for every original product
     * @param  $id the product id
     * @return an array with count of adaptations per product, where parentid is null, gives a count of original products
     */
    function getAllProductAdaptationCount() {
        $sql = "select parent_id, count(*) as count from $this->productsTableName GROUP BY parent_id";
        $data = $this->getArray($sql);
        return $data;
    }

    /**
     * returns product title for a specific id
     * @param  $id the product id
     * @return NULL if product not found, else an array with product details
     */
    function getProductTitle($id) {
        $sql = "select * from $this->productsTableName where id = '$id'";
        $data = $this->getArray($sql);
        if (count($data) > 0) {
            return $data[0]['title'];
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