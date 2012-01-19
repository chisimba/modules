<?php

/**
 * this block is used for rendering an adaptation
 *
 * @author pwando
 */
class block_viewadaptation extends object {

    function init() {
        $this->title = "";
    }

    function show() {
        $id = $this->configData;
        $objViewAdaptation = $this->getObject("viewadaptation", "oer");
        return $objViewAdaptation->buildAdaptationView($id);
    }

}

?>
