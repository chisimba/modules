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
        $id = Null;
        $productid = Null;
        $mode = "new";
        if (count($data) == 3) {
            $productid = $data[0];
            $mode = $data[1];
            $id = $data[2];
        } else if (count($data) == 2) {
            $productid = $data[0];
            $mode = $data[1];
        } else if (!empty($data )) {
            $productid = $data[0];
        }
        return $objAdaptationManager->makeNewAdaptation($mode, $id, $productid);
        break;
    }
}
?>