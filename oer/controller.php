<?php

/*
 * Entry point of oer module
 */

class oer extends controller
{

    private $objProductManager;
    private $objThemeManager;
    private $objKeywordsManager;
    private $objInstitutionManager;

    /**
     * Constructor for the Module
     */
    public function init()
    {
        // @DAVID -- ISN'T IT BETTER TO INSTANTIATE THEM WHERE THEY ARE NEEDED?
        $this->objProductManager = $this->getObject('productmanager', 'oer');
        $this->objThemeManager = $this->getObject('thememanager', 'oer');
        $this->objKeywordsManager = $this->getObject('keywordsmanager', 'oer');

        // Set the jQuery version to the one required
        $this->setVar('JQUERY_VERSION', '1.6.1');

    }

    /**
     * this determines if the action received in the controller should need login
     * or not
     * @param type $action
     * @return type 
     */
    function requiresLogin($action='home')
    {
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
    public function dispatch($action)
    {

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
    function getMethod(& $action)
    {
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
    function validAction(& $action)
    {
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
    private function __home()
    {

        return "1a_tpl.php";
    }

    /**
     * this returns a template for creating a new product
     */
    private function __newproduct()
    {

        return "newproduct_tpl.php";
    }

    /**
     * this launches control panel
     * @return type 
     */
    private function __cpanel()
    {
        return "cpanel_tpl.php";
    }

    private function __viewthemes()
    {
        return "themes_tpl.php";
    }

    /////////////////////////////////////////////////////////////////
    /*

      KEYWORDS FUNCTIONS
     */
    //////////////////////////////////////////////////////////////////

    private function __viewkeywords()
    {
        return "keywords_tpl.php";
    }

    private function __newkeyword()
    {
       return "addeditkeywords_tpl.php";
    }

    private function __savenewkeyword()
    {
        return $this->objKeywordsManager->addNewKeyWord();
    }

    /////////////////////////////////////////////////////////////////
    /*

      ORIGINAL PRODUCT FUNCTIONS
     */
    ///////////////////////////////////////////////////////////////
    /**
     * Saves the original product
     */
    private function __saveoriginalproduct()
    {
        return $this->objProductManager->saveNewProduct();
    }

    /**
     * Used to do the actual upload
     *
     */
    function __doajaxupload()
    {
        $params = $this->objProductManager->doajaxupload();
        return $this->nextAction('ajaxuploadresults', $params);
    }

    ///////////////////////////////////////////////////////////////
    /*

      The themes functionss

     */
    ///////////////////////////////////////////////////////////////
    /**
     * returns form for creating new umbrella theme
     * @return type 
     */
    function __newumbrellatheme()
    {
        return "addeditumbrellatheme_tpl.php";
    }

    /**
     * Save a new umbrella theme
     * @return type 
     */
    function __saveumbrellatheme()
    {
        $this->objThemeManager->addNewUmbrellaTheme();
        return $this->nextAction('viewthemes', array());
    }

    /**
     * returns form for creating new theme
     */
    function __newtheme()
    {
        return "addedittheme_tpl.php";
    }

    /**
     * Saves the new theme
     */
    function __savetheme()
    {
        $this->objThemeManager->addNewTheme();
        return $this->nextAction('viewthemes', array());
    }

    /**
     *
     * Method to open the edit/add form for groups
     *     Added by DWK to refactor function __groupEditingForm()
     *     from origional
     *
     * @return string Template
     */
    function __groupedit()
    {
        return 'groupedit_tpl.php';
    }

    /**
     *
     * Method to open the edit/add form for insitutions
     *     Added by DWK to refactor function from origional
     *
     * @return string Template
     */
    function __institutionedit()
    {
        return 'institutionedit_tpl.php';
    }

    /**
     *
     * Method to open the edit/add form for insitutions
     *     Added by DWK to refactor function from origional
     *
     * @return string Template
     */
    function __institutionsave()
    {
        // Initialise the object that will do the saving.
        $objInstitutionManager = $this->getObject('institutionmanager');
        // Get all the params from the form.
        $name = $this->getParam('name');
        $description = $this->getParam('description');
        $type = $this->getParam('type');
        $country = $this->getParam('country');
        $address1 = $this->getParam('address1');
        $address2 = $this->getParam('address2');
        $address3 = $this->getParam('address3');
        $zip = $this->getParam('zip');
        $city = $this->getParam('city');
        $websiteLink = $this->getParam('websitelink');
        $keyword1 = $this->getParam('keyword1');
        $keyword2 = $this->getParam('keyword2');
        $thumbnail = $this->getParam('thumbnail'); // ====== Where is this from?
        $onestepid = $this->getParam('productID'); // ====== Where is this from?
        $groupid = $this->getParam('groupid'); // ====== Where is this from?
        $id = $objInstitutionManager->addInstitution(
          $name, $description,
          $type, $country, $address1,
          $address2, $address3, $zip,
          $city, $websiteLink,
          $keyword1, $keyword2,
          $thumbnail);
        // Note we are not returning a template as this is an AJAX save.
        if ($id !== NULL && $id !== FALSE) {
            die($id);
        } else {
            die("ERROR_DATA_INSERT_FAIL");
        }
    }

    /**
     * Used to push through upload results for AJAX
     */
    function __ajaxuploadresults()
    {
        $this->setVar('pageSuppressToolbar', TRUE);
        $this->setVar('pageSuppressBanner', TRUE);
        $this->setVar('suppressFooter', TRUE);

        $id = $this->getParam('id');
        $this->setVarByRef('id', $id);

        $fileid = $this->getParam('fileid');
        $this->setVarByRef('fileid', $fileid);

        $filename = $this->getParam('filename');
        $this->setVarByRef('filename', $filename);

        return 'ajaxuploadresults_tpl.php';
    }

}

?>