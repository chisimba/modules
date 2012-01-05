<?php

/**
 * Lists current product themes
 *
 * @author davidwaf
 */
class block_umbrellathemes extends object {

    function init() {
        
    }

    function show() {
        $objThemeManager = $this->getObject("thememanager", "oer");
        return $objThemeManager->createThemeListing();
    }

}

?>
