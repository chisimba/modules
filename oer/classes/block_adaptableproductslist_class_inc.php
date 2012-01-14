<?php
$this->loadClass('link', 'htmlelements');

/**
 * This class lists the adaptations
 *
 * @author pwando
 */
class block_adaptableproductslist extends object {

    public function init() {
        $this->loadClass("link", "htmlelements");
    }

    public function show() {
        $objAdaptationManager = $this->getObject("adaptationmanager", "oer");
        return $objAdaptationManager->getAdaptatableProductListAsGrid();
    }
}
?>