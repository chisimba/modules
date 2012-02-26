<?php

/**
 * renders browse by map 
 *
 * @author davidwaf
 */
class block_browsebymap extends object {

    function init() {
        $this->title = '';
    }

    function show() {
        $mapFactory = $this->getObject("mapfactory", "oer");
        return $mapFactory->getBrowseByMap();
    }

}

?>
