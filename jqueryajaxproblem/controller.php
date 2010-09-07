<?php

class jqueryajaxproblem extends controller {

    public function init() {

    }

    public function dispatch() {

//Get action from query string and set default to view
        $action = $this->getParam('action', 'homePage');
        //Convert the action into a method
        $method = $this->__getMethod($action);
        //Return the template determined by the method resulting from action
        return $this->$method();
    }

    private function __homePage() {
        $this->setVar('JQUERY_VERSION', '1.4.2');
        return "home.php";
    }

    private function __produceRadio() {
        $this->setPageTemplate('ajax_template.php');
        return "spitRadio.php";
    }

    private function __produceDatePicker() {
        $this->setPageTemplate('ajax_template.php');
        return "spitDatePicker.php";
    }

    private function __produceTextInput() {
        $this->setPageTemplate('ajax_template.php');
        return "spitTextInput.php";
    }
    
    
    /**
    *
    * Method to return an error when the action is not a valid
    * action method
    *
    * @access private
    * @return string The dump template populated with the error message
    *
    */
    private function __actionError()
    {
        $this->setVar('str', "<h3>"
          . $this->objLanguage->languageText("phrase_unrecognizedaction")
          .": " . $action . "</h3>");
        return 'dump_tpl.php';
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
    private function __validAction(& $action)
    {
        if (method_exists($this, "__".$action)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
    *
    * Method to convert the action parameter into the name of
    * a method of this class.
    *
    * @access private
    * @param string $action The action parameter passed byref
    * @return stromg the name of the method
    *
    */
    function __getMethod(& $action)
    {
        if ($this->__validAction($action)) {
            return "__" . $action;
        } else {
            return "__actionError";
        }
    }

}

?>