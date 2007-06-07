<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
ini_set("max_execution_time", -1);
// end security check
class adm extends controller
{
	public $objAdmOps;
	public $objLanguage;
	public $objConfig;
	/**
     * Constructor method to instantiate objects and get variables
     */
    public function init()
    {
        try {
            $this->objLanguage = $this->getObject('language', 'language');
            $this->objConfig = $this->getObject('altconfig', 'config');
            $this->objAdmOps = $this->getObject('admops');
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
            	
            case 'maillog':
            	echo $this->objAdmOps->sendLog();
            	break;
            	
            case 'parsemail':
            	// grab the mail off the mail server and parse the heck out of it
            	
        }
    }
}
?>