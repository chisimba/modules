<?php

/**
 * Builds a form for creating add/edit-adaptation form
 *
 * @author pwando
 */
class block_makeadaptation extends object {

    public function init() {
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objUser = $this->getObject('user', 'security');
        $this->title = "";
    }

    /**
     * contructs the form and returns it for display
     * @return type 
     */
    public function show() {
        $objAdaptationManager = $this->getObject("adaptationmanager", "oer");
        $data = explode("|", $this->configData);

        $id = NULL;
        $mode = "new";
        if (count($data) == 2) {
            $id = $data[0];
            $mode = $data[1];
        } else if (!empty($data )) {
            $id = $data[0];
        }
        return $objAdaptationManager->makeNewAdaptation($id, $mode);
        break;
    }
}
?>