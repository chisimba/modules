<?php

/**
 * Description of block_filteroriginalproduct_class_inc
 *
 * @author davidwaf
 */
class block_filteroriginalproduct extends object {

    function init() {
        $this->objLanguage = $this->getObject('language', 'language');
        $this->title = $this->objLanguage->languageText('mod_oer_filterproducts', 'oer');
    }

    function show() {
        $filtermanager = $this->getObject("filtermanager", "oer");
        return $filtermanager->buildFilterProductsForm('filteroriginalproduct','mod_oer_typeofproduct');
    }

}

?>
