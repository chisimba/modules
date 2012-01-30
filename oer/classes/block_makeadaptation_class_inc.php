<?php

/**
 * Builds a form for creating an adaptation form
 *
 * @author pwando
 */
class block_makeadaptation extends object {

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
        if (count($data == 2)) {
            $id = $data[0];
            $mode = $data[2];
        }
        return $objAdaptationManager->makeNewAdaptation($id, $mode);
        break;
    }
}

?>