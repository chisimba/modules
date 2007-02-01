<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}

/**
* 
* Controller class for Chisimba for the module simple map. It uses the Google Maps API
* to create one or more maps, including a demo map.
*
* @author Derek Keats
* @package simplemap
*
*/
class simplemap extends controller
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
    public $objBuldMap;

    /**
     * Intialiser for the stories controller
     *
     * @param byref $ string $engine the engine object
     */
    public function init()
    {
        $this->objUser = $this->getObject('user', 'security');
        $this->objLanguage = $this->getObject('language', 'language');
        // Create the configuration object
        $this->objConfig = $this->getObject('config', 'config');
        //Instantiate the simplebuildmap class
        $this->objBuildMap = $this->getObject('simplebuildmap', 'simplemap');
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
        $action=$this->getParam('action', 'showdemo');
        /*
        * Convert the action into a method (alternative to 
        * using case selections)
        */
        $method = $this->__getMethod($action);
        //Add map script to page heading
        $this->_addMapScriptToPage();
        //Add the onunload method to the body
        $this->_addOnUnloadToBody();
        
        /*
        * Return the template determined by the method resulting 
        * from action
        */
        return $this->$method();
    }
    
    function _addMapScriptToPage()
    {
    	//Read the API key from sysconfig
    	$apiKey = $this->objBuildMap->getApiKey();
    	//ABQIAAAASzlWuBpqyHQoPD8OwyyFRhS9klZkf-a3YMqrNEgglGl8tlkEvBRUarouiwsLMxDlMc20SE2jC_GQmg
        $hScript = "<script src=\"http://maps.google.com/maps?file=api&amp;v=2&amp;key=" . $apiKey . "\" type=\"text/javascript\"></script>";
        //Add the local script to the page header
        $this->appendArrayVar('headerParams',$hScript);
    }
    
    function _addOnUnloadToBody()
    {
        $bodyParams = "onunload=\"GUnload()\"";
        $this->setVarByRef('bodyParams',$bodyParams);
    }
    
    /*------------- BEGIN: Set of methods to replace case selection ------------*/

    /**
    * 
    * Method corresponding to the view action. It fetches the stories
    * into an array and passes it to a main_tpl content template.
    * @access private
    * 
    */
    private function __showdemo()
    {
    	$this->setVar('pageSuppressXML', TRUE);
        return "demomap_tpl.php";
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
    


    /**
    *
    * This is a method to determine if the user has to 
    * be logged in or not. In this case, the showdemo which
    * shows the demo map can be shown without login
    *
    * @return boolean TRUE|FALSE
    *
    */
    public function requiresLogin()
    {
        $action=$this->getParam('action','NULL');
        switch ($action)
        {
            case 'showdemo':
                return FALSE;
                break;
            default:
                return TRUE;
                break;
        }
     }
}
?>