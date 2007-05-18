<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
ini_set("max_execution_time", -1);
ini_set("memory_limit", -1);
// end security check
class geonames extends controller
{
    public $objLog;
    public $objLanguage;
    public $objUser;
    public $objGeoOps;
    public $objDbGeo;
    
    /**
     * Constructor method to instantiate objects and get variables
     */
    public function init()
    {
        try {
        	$this->objGeoOps = $this->getObject('geoops');
        	$this->objDbGeo = $this->getObject('dbgeonames');
            $this->objLanguage = $this->getObject('language', 'language');
            $this->objUser = $this->getObject('user', 'security');
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
            	return 'main_tpl.php';
            	break;
            	
            case 'uploaddatafile':
            	$userid = $this->objUser->userId();
            	$file = $this->getParam('zipfile');
            	//file id is returned, so lets go and get the actual file for parsing...
            	$geozip = $this->objGeoOps->unpackPdfs($file);
            	//$dataArr = file($geozip);
            	//print_r($dataArr);
            	// rename the file to a csv
            	$records = $this->objGeoOps->parseCSV($geozip);
            	foreach($records as $entry)
            	{
            		@$insarr = array('userid' => $userid, 'geonameid' => $entry[0], 'name' => $entry[1], 'asciiname' => $entry[2], 'alternatenames' => $entry[3], 
            						'latitude' => $entry[4], 'longitude' => $entry[5], 'featureclass' => $entry[6], 'featurecode' => $entry[7], 
            						'countrycode' => $entry[8], 'cc2' => $entry[9], 'admin1code' => $entry[10], 'admin2code' => $entry[11], 
            						'population' => $entry[12], 'elevation' => $entry[13], 'gtopo30' => $entry[14], 'timezoneid' => $entry[15], 
            						'moddate' => $entry[16]
            						);
            		$this->objDbGeo->insertRecord($insarr);
            	}
            	return 'main_tpl.php';
            	break;
            	  	
        }
    }
}
?>