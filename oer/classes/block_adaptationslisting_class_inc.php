<?php

$this->loadClass('link', 'htmlelements');

/**
 * This class lists the adaptations
 *
 * @author pwando
 */
class block_adaptationslisting extends object {

    public function init() {
        $this->loadClass("link", "htmlelements");
        $this->objLanguage = $this->getObject('language', 'language');
        $this->title = "";
    }

    public function show() {
        $filter = $this->configData;

        $objAdaptationManager = $this->getObject("adaptationmanager", "oer");
        return $objAdaptationManager->getAdaptationsListingAsGrid($filter);
    }

}

?>
