<?php

class langadmin extends controller {

    function init() {
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objLangAdmin = $this->getObject("langutil");
        $this->objDbLangText = $this->getObject("dblangaugetext");
        $this->objConfig = $this->getObject('altconfig', 'config');
    }

    /**
     * Standard Dispatch Function for Controller
     * @param <type> $action
     * @return <type>
     */
    public function dispatch($action) {
        /*
         * Convert the action into a method (alternative to
         * using case selections)
         */

        $method = $this->getMethod($action);
        /*
         * Return the template determined by the method resulting
         * from action
         */
        return $this->$method();
    }

    /**
     *
     * Method to convert the action parameter into the name of
     * a method of this class.
     *
     * @access private
     * @param string $action The action parameter passed byref
     * @return string the name of the method
     *
     */
    function getMethod(& $action) {
        if ($this->validAction($action)) {
            return '__' . $action;
        } else {
            return '__home';
        }
    }

    /**
     *
     * Method to check if a given action is a valid method
     * of this class preceded by double underscore (__). If it __action
     * is not a valid method it returns FALSE, if it is a valid method
     * of this class it returns TRUE.
     *
     * @access private
     * @param string $action The action parameter passed byref
     * @return boolean TRUE|FALSE
     *
     */
    function validAction(& $action) {
        if (method_exists($this, '__' . $action)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * Method to show the Home Page of the Module
     */
    public function __home() {

        return "home_tpl.php";
    }

    function __addLang() {
        // set some info about the new lang
        $langid = $this->getParam('langid');
        $name = $this->getParam("name");
        $meta = $this->getParam("meta");
        $errorText = $this->getParam("errortext");


        $langData = array(
            'lang_id' => $langid,
            'table_name' => 'tbl_' . $langid,
            'name' => $name,
            'meta' => $meta,
            'error_text' => $errorText,
            'encoding' => 'UTF-8',
        );
        $this->objLangAdmin->addLanguage($langData);
        return $this->nextAction("home");
    }

    function __showNewLangTemplate() {

        return "addeditlang_tpl.php";
    }

    function __viewLangItems() {
        return "viewlangitems_tpl.php";
    }

    function __editTranslation() {
        $code = $this->getParam("code");
        $item = $this->objDbLangText->getLanguageTextItem($code);
        $this->setVarByRef("code", $code);
        $this->setVarByRef("description", $item[0]['description']);

        return "translate_tpl.php";
    }

    function __addItem() {
        $code = $this->getParam("code");
        $translation = $this->getParam("translation");

        $currLang = $this->objLanguage->currentLanguage();
        $stringArray = array($currLang => $translation);
        $arrName = explode("_", $code);
        $module = $arrName[1];
        $module = $arrName[1];
        if ($module == 'unesco') {
            $module = $module . "_" . $arrName[2];
        }
        $this->objLanguage->addLangItem($code, $module, $stringArray);
        return $this->nextAction("home");
    }

   

}

?>
