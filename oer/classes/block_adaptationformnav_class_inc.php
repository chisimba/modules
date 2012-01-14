<?php

/**
 * This creates a navigation for quick moving in between forms when managing
 * adaptations
 *
 * @author pwando
 */
class block_adaptationformnav extends object {

    function init() {
        
    }

    public function show() {
        $id = $this->configData;
        $objAdaptationManager = $this->getObject('adaptationmanager', 'oer');
        return $objAdaptationManager->buildAdaptationStepsNav($id);
    }

}

?>
