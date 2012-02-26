<?php

/**
 * this block is used for rendering an adaptation
 *
 * @author pwando
 */
class block_fullviewadaptation extends object {

    function init() {
        $this->title = "";
    }

    function show() {
        $id = $this->configData;
        $objFullViewAdaptation = $this->getObject("fullviewadaptation", "oer");
        //return $objFullViewAdaptation->buildAdaptationFullView($id);
        return $objFullViewAdaptation->buildFullView($id);
    }

}

?>
