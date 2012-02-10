<?php

/**
 * renders list of institutions
 *
 * @author davidwaf
 */
class block_institutionlisting extends object {

    function init() {
        $this->title = "";
    }

    function show() {
        $institutionManager = $this->getObject("institutionmanager", "oer");
        return $institutionManager->getAllInstitutions();
    }

}

?>
