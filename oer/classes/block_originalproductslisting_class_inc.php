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
        $modeRaw = $this->configData;
        $modeParts = explode("__", $modeRaw);
        $mode = $modeParts[0];
        $filter = "";
        if (count($modeParts) == 2) {
            $filter = $modeParts[1];
        }
        $objProductManager = $this->getObject("productmanager", "oer");
        return $objProductManager->getOriginalProductListing($mode, $filter);
    }

}

?>
