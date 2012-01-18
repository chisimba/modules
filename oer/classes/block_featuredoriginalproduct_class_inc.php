<?php

/**
 * This displays the original product
 *
 * @author davidwaf
 */
class block_featuredoriginalproduct extends object {

    function init() {
          $this->objLanguage = $this->getObject('language', 'language');
          $this->title= $this->objLanguage->languageText('mod_oer_featured', 'oer');
    }

    function show() {
      
        return "Featured product";
    }

}

?>
