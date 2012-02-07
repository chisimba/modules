<?php
/**
 * This class provides access to  rating table in the db
 */
class dbproductrating extends dbtable {

    private $productRatingTableName = 'tbl_oer_productrating';

    function init() {
        parent::init($this->productRatingTableName);
    }
    
    /**
     * Stores the rating value
     * @param type $data
     * @return type 
     */
    function  addRating($data){
        return $this->insert($data);
    }
    
    /**
     * Rating the total rating for a given product
     * @param type $productId
     * @return type 
     */
    function getTotalRating($productId){
        $sql=
        "select sum(rate) as rating from $this->productRatingTableName where productid = '$productId'";
        $data=$this->getArray($sql);
        return $data[0]['rating'];
    }
    
    
    /**
     * gets the most rated product
     * @return type 
     */
    function  getMostRatedProduct(){
        $sql=
        "select * from $this->productRatingTableName order by totalrating DESC limit 1";
        $data=$this->getArray($sql);
        return $data[0]['productid'];  
    }

}

?>
