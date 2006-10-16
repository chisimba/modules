<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}


/**
*
* Controller class for the mathml stuff
*
* @author Paul Scott
* @package mathml
*
* @version $Id$
* @copyright 2005 UWC
*
*/
class mathml extends controller
{

    /**
    * @var string $action The action parameter from the querystring 
    */
    public $action;
    public $sym;
    public $maths;
    public $ml;

    /**
    * Standard constructor method 
    */
    public function init()
    {
        //Retrieve the action parameter from the querystring
        $this->action = $this->getParam('action', Null);
        //Create an instance of the User object
        $this->objUser =  & $this->getObject("user", "security");
        //Create an instance of the language object
        $this->objLanguage = &$this->getObject("language", "language");
        //get the mathml parser class
        $this->objMaths = $this->getObject("mathmlparser");
        
        //Get the activity logger class
        $this->objLog=$this->newObject('logactivity', 'logger');
        //Log this module call
        $this->objLog->log();
    }

    /**
    * Standard dispatch method 
    */
    public function dispatch()
    {
        switch ($this->action) {
            case null:
            case "test":
                $expression = $this->getParam('expression');
                If(!isset($expression))
                {
            		$expression = "int_-1^1 sqrt(1-x^2)dx = pi/2";
                }
				
                $ar = $this->objMaths->mathmlreturn($expression);
                // Parse the MathML
                
				$this->setVarByRef('ar', $ar);
                return "main_tpl.php";
                break;
			case 'render':
				$formula = $this->getParam('formula','');
				$this->setVar('str',$this->objMaths->mathmlreturn($formula));
				$this->setPageTemplate('xml_tpl.php');
				return "xml_tpl.php";
        }
    }
}
