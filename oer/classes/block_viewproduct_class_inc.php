<?php

/**
 * this block is used for rendering product details
 *
 * @author davidwaf
 */
class block_viewproduct extends object {

    function init() {
        $this->title = "";
    }

    function show() {
        $id = $this->configData;
        $objProductManager = $this->getObject("viewproduct", "oer");
        return $objProductManager->buildProductDetails($id);
    }

}

?>
