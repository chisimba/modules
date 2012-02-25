<?php

/**
 * This creates a navigation for quick moving in between forms when managing
 * adaptations
 *
 * @author pwando
 */
class block_adaptationformnav extends object {

    function init() {
        $this->title="";   
    }

    public function show() {
        $data = explode("|", $this->configData);
        $id = NULL;
        $step = '1';
        if (count($data) == 2) {
            $id = $data[0];
            $step = $data[1];
        } else if (count($data) == 1){
            $id = $data[0];
        }
        $objAdaptationManager = $this->getObject('adaptationmanager', 'oer');
        
        return $objAdaptationManager->buildAdaptationStepsNav($id,$step);
    }

}

?>
