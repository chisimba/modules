<?php

/*
 * Entry point of oer module
 */

class oer extends controller {

    private $objProductManager;
    /**
     * Constructor for the Module
     */
    public function init() {
        $this->objProductManager=$this->getObject('productmanager','oer');
    }

    /**
     * this determines if the action received in the controller should need login
     * or not
     * @param type $action
     * @return type 
     */
    function requiresLogin($action='home') {
        $allowedActions = array(NULL, 'home');
        if (in_array($action, $allowedActions)) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /**
     * Standard Dispatch Function for Controller
     *
     * @access public
     * @param string $action Action being run
     * @return string Filename of template to be displayed
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
        $this->setLayoutTemplate('layout_tpl.php');
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

    // Beginning of Functions Relating to Actions in the Controller //

    /**
     *
     * This is the default function
     */
    private function __home() {
      
        return "1a_tpl.php";
    }
    
    
    /**
     * this returns a template for creating a new product
     */
    private function __newproduct(){
       
        return "newproduct_tpl.php";
    }
    
    /**
     * Saves the original product
     */
    private function __saveoriginalproduct(){
         return $this->objProductManager->saveNewProduct();
    }

}

?>
