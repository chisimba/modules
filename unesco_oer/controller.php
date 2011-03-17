<?php

class unesco_oer extends controller {

    public $objProductUtil;
    public $objDbProducts;

    /**
     *
     * @var string $objLanguage String object property for holding the
     * language object
     * @access public
     *
     */
    public $objLanguage;

    function init() {
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objProductUtil = $this->getObject('productutil');
        $this->objDbProducts = $this->getObject('dbproducts');
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
        $this->setLayoutTemplate("gift_layout_tpl.php");
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
        return "1a_tpl.php";
    }

    public function __1b() {
        return "1b_tpl.php";
    }

    public function __2a() {
        return "2a_tpl.php";
    }

    public function __2b() {
        return "2b_tpl.php";
    }

     public function __FilterProducts() {





        return "2b_tpl.php";
    }

    public function requiresLogin() {
        return false;
    }

    public function __productsUi() {
        return "tbl_products_ui_tpl.php";
    }

    public function __uploadSubmit(){

        $uploadedFile = $this->getObject('uploadinput', 'filemanager');
        $uploadedFile->enableOverwriteIncrement = TRUE;
        $uploadedFile->customuploadpath = 'unesco_oer/'.$this->getParam('title').'/thumbnail/';

        $results = $uploadedFile->handleUpload();

        if ($results == FALSE) {
            return NULL;
        } else {
            // If successfully Uploaded
            if (!$results['success']) return NULL;
        }

        $data=array(

            'parent_id'=>NULL,
            'title'=>$this->getParam('title'),
            'creator'=>$this->getParam('creator'),
            'keywords'=>trim($this->getParam('keywords')),
            'description'=>$this->getParam('description'),
            'created_on'=>NULL,
            'resource_type'=>NULL,
            'content_type'=>NULL,
            'format'=>NULL,
            'source'=>NULL,
            'theme'=>$this->getParam('theme'),
            'language'=>$this->getParam('language'),
            'content'=>NULL,
            'thumbnail'=>'usrfiles/'.$results['path']
        );

        $this->objDbProducts->insert($data);

        return "tbl_products_ui_tpl.php";
    }

}

?>
