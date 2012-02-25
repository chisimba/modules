<?php

/**
 * This provides a db layer that links themes to a  product
 *
 * @author davidwaf
 */
class dbproductthemes extends dbtable {

    function init() {
        parent::init("tbl_oer_product_themes");
    }

    /**
     * updates selected themes for this product. If the product has existing 
     * themes, they are deleted first and new entry inserted
     * @param type $data
     * @param type $contextcode 
     */
    function updateProductThemes($themes, $productid) {
      
        $this->delete("productid", $productid);
      
        foreach ($themes as $theme) {
            $data = array(
                "productid" => $productid,
                "themeid" => $theme
            );
            $this->insert($data);
        }
    }

    /**
     * get all the institutions that belong to this group
     * @param type $contextcode
     * @return type 
     */
    function getProductThemes($producttheme) {
        $sql =
                "select * from tbl_oer_group_institutions where group_id = '" . $contextcode . "'";
        return $this->getArray($sql);
    }

}

?>
