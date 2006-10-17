<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check

class blog extends controller
{
	public $objUser;
	public $objLanguage;
	public $objLog;
	public $objFeed;
	public $objFeedCreator;
	public $objClient;

    /**
     * Constructor method to instantiate objects and get variables
     */
    public function init()
    {
        try {
        	$this->objFeed = $this->getObject('feeds', 'feed');
        	$this->objFeedCreator = $this->getObject('feeder', 'feed');
        	$this->objClient = $this->getObject('client','httpclient');
            $this->objLanguage = $this->getObject('language', 'language');
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
                try {
                		return 'myblog_tpl.php';
                    }
                    catch(customException $e) {
                        customException::cleanUp();
                    }
        }

    }
}
?>