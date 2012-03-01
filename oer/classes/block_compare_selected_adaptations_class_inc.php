<?php

/**
 * this block is used for comparing selected product adaptation nodes
 *
 * @author pwando
 */
class block_compare_selected_adaptations extends object {

    function init() {
        $this->title = "";
    }

    function show() {
        $data = explode("|", $this->configData);
        $productId = $data[0];
        $selectedId = $data[1];
        $selAdaptId = $data[2];
        $selSecId = $data[3];
        $compareAdaptations = $this->getObject("compareadaptations", "oer");
        $mode = "compare";
        return $compareAdaptations->buildCompareSelectedView($productId, "", $mode, $selectedId, $selSecId, $selAdaptId);
    }
}
?>

