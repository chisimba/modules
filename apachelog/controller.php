<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check
class apachelog extends controller
{
    public $objDbApachelog;
    public $objLog;
    public $objLanguage;
    public $objlogparser;
    public $objFile;

    /**
     * Constructor method to instantiate objects and get variables
     */
    public function init()
    {
        try {
            $this->objLanguage = $this->getObject('language', 'language');
            $this->objFile = $this->getObject('dbfile', 'filemanager');
            $this->objlogparser = $this->getObject('logparser');
            //$this->objDbApachelog = $this->getObject('dbapachelog');
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
        //$this->setLayoutTemplate('beautifier_layout_tpl.php');

        switch ($action) {
            default:
            case 'uploadlogfile':
            	try {
            		return 'upload_tpl.php';
            	}
            	catch (customException $e)
            	{
            		customException::cleanUp();
            	}
            	break;

            case 'parselogfile':
                    try {
                    	$file = $this->getParam('logfile');
                    	$arr = $this->objlogparser->log2arr($file);
                    	$statsarr = $this->objlogparser->logfileStats($file);

                    	foreach($arr as $line)
                    	{
                    		$insarr = $this->objlogparser->parselogEntry($line);
                    		//insert to the table
							//print_r($insarr);

                    	}


                    }
                    catch(customException $e) {
                        customException::cleanUp();
                    }
                    break;
            case 'viewlogfile':
            	try {

            	}
            	catch (customException $e)
            	{

            	}
        }
     }
}
?>