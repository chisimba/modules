<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
ini_set("max_execution_time", -1);
// end security check
class hrwizard extends controller
{
    public $objLog;
    public $objLanguage;
    public $objHrOps;

    /**
     * Constructor method to instantiate objects and get variables
     */
    public function init()
    {
        try {
        	$this->objHrOps = $this->getObject('hrwizardops');
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
            	return 'upload_tpl.php';
            	break;
            	
            case 'uploaddatafile':
            	$file = $this->getParam('zipfile');
            	//file id is returned, so lets go and get the actual file for parsing...
            	$pdfzip = $this->objHrOps->unpackPdfs($file);
            	return 'upload2_tpl.php';
            	break;
            	
            case 'uploadcsvfile':
            	$csv = $this->getParam('csvfile');
            	$recarr = $this->objHrOps->parseCSV($csv);
            	$this->objHrOps->sendMails($recarr, "a message!");
            	return 'done_tpl.php';
            	break;
            	
            	
        }
    }
}
?>