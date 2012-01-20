<?php


/**
 * this block is used to render form for adding / editing section info
 * of a product
 *
 * @author davidwaf
 */
class block_sectionnode extends object {
    function  init(){
        $this->title="";
    }
    
    function show(){
        $productid=$this->configData;
        $objSectionManager=$this->getObject("sectionmanager", "oer");
        return $objSectionManager->getCreateSectionNodeForm($productid);
    }
}

?>
