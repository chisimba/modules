<?php

/**
 * Lists current product themes
 *
 * @author davidwaf
 */


class block_themes extends object {

    function init() {
       
    }

    function show() {
        $objThemeManager=$this->getObject("thememanager","oer");
        return $objThemeManager->createThemeListingTable();
    }

    

}

?>
