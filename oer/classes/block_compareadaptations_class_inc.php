<?php

/**
 * this block is used for comparing a product's adaptations
 *
 * @author pwando
 */
class block_compareadaptations extends object {

    function init() {
        $this->title = "";
    }

    function show() {
        $data = explode("|", $this->configData);
        $productId = $data[0];
        $selectedId = $data[1];
        $compareAdaptations = $this->getObject("compareadaptations", "oer");
        $mode = "compare";
        return $compareAdaptations->buildCompareView($productId, "", "", $mode, $selectedId);
    }
}
?>

