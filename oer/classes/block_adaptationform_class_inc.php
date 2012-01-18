<?php

/**
 * Builds a form for creating an adaptation form
 *
 * @author pwando
 */
class block_adaptationform extends object {

    public function init() {
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objUser = $this->getObject('user', 'security');
    }

    /**
     * contructs the form and returns it for display
     * @return type 
     */
    public function show() {
        $objAdaptationManager = $this->getObject("adaptationmanager", "oer");
        $data = explode("|", $this->configData);
        
        $id = NULL;
        $step = '1';
        if (count($data == 2)) {
            $id = $data[0];
            $step = $data[1];
            $mode = $data[2];
        }
        print_r($id);
        switch ($step) {
            case '1':
                return $objAdaptationManager->buildAdaptationFormStep1($id, $mode);
                break;
            case '2':
                return $objAdaptationManager->buildAdaptationFormStep2($id);
                break;
            case '3':
                return $objAdaptationManager->buildAdaptationFormStep3($id);
                break;
        }
    }
}
?>