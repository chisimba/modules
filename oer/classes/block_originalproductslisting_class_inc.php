<?php

$this->loadClass('link', 'htmlelements');

/**
 * This class lists the original products
 *
 * @author davidwaf
 */
class block_originalproductslisting extends object {

    public function init() {
        $this->objLanguage = $this->getObject('language', 'language');
        $this->title = "";
    }

    public function show() {
        $mode = $this->configData;
        $objProductManager = $this->getObject("productmanager", "oer");
        return $objProductManager->getOriginalProductListing($mode);
    }

}

?>
