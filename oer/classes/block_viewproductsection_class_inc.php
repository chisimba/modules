<?php

/**
 * this block is used for rendering product details
 *
 * @author davidwaf
 */
class block_viewproductsection extends object {

    function init() {
        $this->title = "";
    }

    function show() {
        $data = explode("|", $this->configData);
        $productId = $data[0];
        $sectionId = $data[1];
        $sectionManager = $this->getObject("sectionmanager", "oer");
        return $sectionManager->getSectionContent($productId, $sectionId);
    }

}
?>

