<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check

class conversions extends controller
{
	public $objLanguage;
	public $objConfig;
        public $objTemp;
        public $objUser;

    /**
     * Constructor method to instantiate objects and get variables
     */
    public function init()
    {
        try {
            $this->objUser = $this->getObject('user', 'security');
            $this->objTemp = $this->getObject('temp');
            $this->objLanguage = $this->getObject('language', 'language');
            $this->objConfig = $this->getObject('altconfig', 'config');
        }
        catch(customException $e) {
            echo customException::cleanUp();
            die();
        }
    }
    /**
     * Method to process actions to be taken
     *
     * @param string $action String indicating action to be taken
     */
    public function dispatch($action = Null)
    {
        switch ($action) {
            default:
            	
           case 'celstofarenheit':
                $firstname = $this->getParam('cels');
                
                return 'convertit_tpl.php';
            	break;

	  case 'convert':
                $type = $this->getParam('converttype');
                $value = $this->getParam('value');
                echo "Type: ".$type."    Value: ".$value; die();
        }
    }
}
?>
