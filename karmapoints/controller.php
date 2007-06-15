<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check
class karmapoints extends controller
{
    public $objLog;
    public $objLanguage;
    public $objDbKarma;
    
    /**
     * Constructor method to instantiate objects and get variables
     */
    public function init()
    {
        try {
        	$this->objUser = $this->getObject('user', 'security');
            $this->objLanguage = $this->getObject('language', 'language');
            $this->objDbKarma = $this->getObject('dbkarma');
            //Get the activity logger class
            $this->objLog = $this->newObject('logactivity', 'logger');
            //Log this module call
            $this->objLog->log();
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
            	// show the users account
            	echo "karma"; die();
            	break;
            	
            case 'addpoint':
            	$userid = $this->objUser->userId();
            	echo $this->objDbKarma->addPoints($userid, '1');
            	break;
        }
    }
}
?>