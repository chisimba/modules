<?php

/**
 * renders the most adapted, rated and commented product
 *
 * @author davidwaf
 */
class block_mostarc extends object {

    function init() {
        $this->title = "";
    }

    function show() {
        $objProductManager = $this->getObject("productmanager", "oer");
        return $objProductManager->getMostARC();
    }

}

?>
