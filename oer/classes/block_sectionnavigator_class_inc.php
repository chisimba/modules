<?php

/**
 * Section navigator
 *
 * @author davidwaf
 */
class block_sectionnavigator extends object {

    function init() {
        $this->title = "";
    }

    function show() {
        $sectionManager = $this->getObject("sectionmanager", "oer");
        $productId = $this->configData;
        return $sectionManager->buildSectionsTree($productId,"");
    }

}

?>
