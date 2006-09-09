<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}

/**
* 
* Controller class for Chisimba for the module {yourmodulename}
*
* @author Derek Keats
* @package generator
*
*/
class generator extends controller
{
    
    /**
    * @var $objConfig String object property for holding the 
    * configuration object
    */
    public $objConfig;
    
    /**
    * @var $objLanguage String object property for holding the 
    * language object
    */
    public $objLanguage;
    /**
    * @var $objLog String object property for holding the 
    * logger object for logging user activity
    */
    public $objLog;

    /**
     * 
     * Constructor for the generator controller
     *
     */
    public function init()
    {
        $this->objUser = $this->getObject('user', 'security');
        $this->objLanguage = $this->getObject('language', 'language');
        // Create the configuration object
        $this->objConfig = $this->getObject('config', 'config');
        //Get the activity logger class
        $this->objLog=$this->newObject('logactivity', 'logger');
        //Log this module call
        $this->objLog->log();
    }
    
    
    /**
     * 
     * The standard dispatch method for the {yourmodulename} module.
     * The dispatch method uses methods determined from the action 
     * parameter of the  querystring and executes the appropriate method, 
     * returning its appropriate template. This template contains the code 
     * which renders the module output.
     * 
     */
    public function dispatch()
    {
        //Get action from query string and set default to view
        $action=$this->getParam('action', 'start'); //buildcontroller
        // retrieve the mode (edit/add/translate) from the querystring
        $mode = $this->getParam("mode", null);
        // retrieve the sort order from the querystring
        $order = $this->getParam("order", null);
        /*
        * Convert the action into a method (alternative to 
        * using case selections)
        */
        $method = $this->__getMethod($action);
        /*
        * Return the template determined by the method resulting 
        * from action
        */
        return $this->$method();
    }
    
    
    /*------------- BEGIN: Set of methods to replace case selection ------------*/
    
    /**
    *
    * Method to get the User Interface for a particular generator
    * The user interface is built by parsing a file in the generators
    * directory under the object type (e.g controller/) called
    * OBJECTYPE_ui_form.xml.
    *
    * @param string $generator The name of the generator we are working with
    * @access Public
    *
    */
    public function __getui()
    {
    	$objUi = $this->getObject('uimanager');
    	//Get the type of object that we are generating
    	$objectType = $this->getParam('objecttype', NULL);
    	$objUi->readFormXml($objectType);
    	
    	$str=$objUi->generateForm();
    	
    	$this->setVar('str', $str);
    	return 'dump_tpl.php';    	
    }
    
    /**
    *
    * Method to process the results of form input for a particular generator
    *
    * @param string $generator The name of the generator we are working with
    * @access Public
    *
    */
    public function __processresults()
    {
    
    }
    
    
    
    
    
    
    
    /**
    * 
    * Method corresponding to the start action. It presents a screen for use in 
    * designing the module being generated.
    * @access private
    * 
    */
    private function __start()
    {
    	$this->setVar('page', 1);
        return 'start_tpl.php';
    }
    
    /**
    * 
    * Method corresponding to the page2 action. It presents a screen for use in 
    * designing the module being generated.
    * @access private
    * 
    */
    private function __page2()
    {
    	$this->setVar('page', 2);
        return 'page2_tpl.php';
    }
    
    /**
    * 
    * Method corresponding to the page3 action. It presents a screen for use in 
    * building register.conf
    * @access private
    * 
    */
    private function __page3()
    {
    	$this->setVar('page', 3);
        return 'page3_tpl.php';
    }
    
    /**
    * 
    * Method corresponding to the page3 action. It presents a screen for use in 
    * entering the table name for the dbTable class to build.
    * @access private
    * 
    */
    private function __page4()
    {
    	$this->setVar('page', 4);
        return 'page4_tpl.php';
    }
    
    /**
    * 
    * Method corresponding to the page4 action. It presents a screen for building
    * the edit/add template
    * 
    * @access private
    * 
    */
    private function __page5()
    {
    	$this->setVar('page', 5);
        return 'page5_tpl.php';
    }
    
    /**
    * 
    * Method corresponding to the page5 action. It presents a screen for building
    * the foreign class wrapper template
    * 
    * @access private
    * 
    */
    private function __page6()
    {
    	$this->setVar('page', 6);
        return 'page6_tpl.php';
    }

    /**
    * 
    * Method corresponding to the page3 action. It presents a screen for use in 
    * entering the table name for the dbTable class to build.
    * @access private
    * 
    */
    private function __builddbtable()
    {
    	$this->setVar('page', 3);
        return 'gendbtable_tpl.php';
    }

    /**
    * 
    * Method corresponding to the view action. It fetches the stories
    * into an array and passes it to a main_tpl content template.
    * @access private
    * 
    */
    private function __buildcontroller()
    {
        $className = $this->getParam('classname', 'change_my_name');
    	$objGenController = $this->getObject('gencontroller');
        $this->setVar('cont', $objGenController->generate($className));
        //$objGenRegister = $this->getObject('genregister');
        //$this->setVar('reg', $objGenRegister->generate($className));
        unset($objGenController);
        //unset($objGenRegister);
        return "controller_tpl.php";
    }
    
    /**
    * 
    * Method corresponding to the view action. It fetches the stories
    * into an array and passes it to a main_tpl content template.
    * @access private
    * 
    */
    private function __buildregister()
    {
        $className = $this->getParam('classname', 'change_my_name');
        $objGenRegister = $this->getObject('genregister');
        $this->setVar('reg', $objGenRegister->generate($className));
        unset($objGenRegister);
        return "register_tpl.php";
    }
    
    /**
    * 
    * Method corresponding to the genedit action. It generates an
    * edit template and puts it into a text box.
    * 
    * @access private
    * 
    */
    private function __genedit()
    {
        $this->setVar('page', 5);
        return "genedit_tpl.php";
    }
    
    /**
    * 
    * Method corresponding to the genedit action. It generates an
    * edit template and puts it into a text box.
    * 
    * @access private
    * 
    */
    private function __genwrapper()
    {
        $this->setVar('page', 6);
        return "genwrapper_tpl.php";
    }
    
    
    /**
    * 
    * Method to get a database schema from the database
    * 
    */
    private function __getxmlschema()
    {
    	$objSch = $this->getObject('getschema');
        $this->setVar('str', $objSch->getXmlSchema());
        return "dump_tpl.php";
    } 
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    /**
    * 
    * Method corresponding to the edit action. It sets the mode to 
    * edit and returns the edit template.
    * @access private
    * 
    */
    private function __edit()
    {
        $this->setvar('mode', "edit");
        return 'editform_tpl.php';
    }

    /**
    * 
    * Method corresponding to the add action. It sets the mode to 
    * add and returns the edit content template.
    * @access private
    * 
    */
    private function __add()
    {
        $this->setvar('mode', 'add');
        return 'editform_tpl.php';
    }
    
   
    /**
    * 
    * Method corresponding to the save action. It gets the mode from 
    * the querystring to and saves the data then sets nextAction to be 
    * null, which returns the {yourmodulename} module in view mode. 
    * 
    * @access private
    * 
    */
    private function __save()
    {
        $mode = $this->getParam("mode", NULL);
        $this->objDb{yourmodulename}->save($mode);
        return $this->nextAction(NULL);
    }
    
    /**
    * 
    * Method corresponding to the delete action. It requires a 
    * confirmation, and then delets the item, and then sets 
    * nextAction to be null, which returns the {yourmodulename} module 
    * in view mode. 
    * 
    * @access private
    * 
    */
    private function __delete()
    {
        // retrieve the confirmation code from the querystring
        $confirm=$this->getParam("confirm", "no");
        if ($confirm=="yes") {
            $this->deleteItem();
            return $this->nextAction(NULL);
        }
    }
    
    /**
     * 
     * Method to show all generator template tags as used
     * in the XML templates
     * 
     */
    function __showTemplateTags()
    {
        return 'showtemplatetags_tpl.php';
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
    function __validAction(& $action)
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
    
    /*------------- END: Set of methods to replace case selection ------------*/
    

}
?>