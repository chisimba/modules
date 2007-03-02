<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check
class swesos extends controller
{
    public $objLog;
    public $objLanguage;
    public $objSweOps;

    /**
     * Constructor method to instantiate objects and get variables
     */
    public function init()
    {
        try {
            $this->objLanguage = $this->getObject('language', 'language');
            $this->objSweOps = $this->getObject('sweops');
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
            	//return the upload form
            	return 'upload_tpl.php';
            	break;
            case 'crondata':
            	
            	break;
               
            case 'uploaddatafile':
            	$filename = $this->getParam('swefile');
            	$objFile = $this->getObject('dbfile', 'filemanager');
				$fpath = $objFile->getFullFilePath($this->getParam('swefile'));
            	$handle = fopen($fpath, 'r');
            	while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
            		//print_r($data);
            		if(count($data) < 5)
            		{
            			//invaid data row
            			unset($data);
            		}
            		else {
            			$retdata[] = $data;
            		}
				}
				fclose($handle);
				print_r($retdata);
            	break;
        }
    }
}
?>