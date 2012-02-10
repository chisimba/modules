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
    function  getMostRatedProducts(){
        $sql=
        "select distinct rt.productid from $this->productRatingTableName as rt,tbl_oer_products as pr where  pr.id=rt.productid and pr.parent_id is null order by rt.totalrating DESC limit 3";
        $data=$this->getArray($sql);
        return $data;  
    }

}

?>
