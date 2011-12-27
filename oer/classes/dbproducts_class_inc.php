<?php



/**
 * This is a DB layer that manages original products
 *
 * @author davidwaf
 */
class dbproducts extends dbtable {
    private $productsTableName='tbl_oer_products';
    
    function init() {
        parent::init($this->productsTableName);
    }
    
    /**
     * this selects original products
     */
    function getOriginalProducts(){
        $sql="select * from $this->productsTableName where parent_id is null";
        return $this->getArray($sql);
    }
}

?>
