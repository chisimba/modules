<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check
class tagging extends controller
{
    public $objLog;
    public $objLanguage;
    public $objDbTags;
    
    /**
     * Constructor method to instantiate objects and get variables
     */
    public function init()
    {
        try {
            $this->objLanguage = $this->getObject('language', 'language');
            $this->objDbTags = $this->getObject('dbtags');
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
            	echo $this->objLanguage->languageText("mod_tagging_nofunctionality", "tagging");
            	break;
            	
            case 'importblogs':
            	$this->objDbTags->migrateBlogTags();
            	break;
        }
    }
}
?>