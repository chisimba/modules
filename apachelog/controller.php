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
    public $objGraph;
    public $objdt;

    /**
     * Constructor method to instantiate objects and get variables
     */
    public function init()
    {
        try {
            $this->objLanguage = $this->getObject('language', 'language');
            $this->objFile = $this->getObject('dbfile', 'filemanager');
            $this->objlogparser = $this->getObject('logparser');
            $this->objDbApachelog = $this->getObject('dbapachelog');
            $this->objGraph = $this->getObject('graph');
            $this->objdt = $this->getObject('datetime', 'utilities');

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
                    		$this->objDbApachelog->dumpData($insarr);
                    	}
                    	//nextaction = display
						$this->nextAction('viewlogfile');

                    }
                    catch(customException $e) {
                        customException::cleanUp();
                    }
                    break;
            case 'viewlogfile':
            	try {
            		//set up the graph
            		$this->objGraph->setup(600, 600);
            		//get the record count for the month
            		$year = '2006';
            		$month = '01';
            		$month = $this->objdt->monthFull($month);

            		$yearcount = $this->objDbApachelog->getYearStats($year);
            		echo "Year count of hits is: " .$yearcount . "<br />";
					$start = $this->microtime_float();
            		$montharr = array(1,2,3,4,5,6,7,8,9,10,11,12);
            		foreach ($montharr as $months)
            		{

            			$month = $this->objdt->monthFull($months);
            			$hits = $this->objDbApachelog->getMonthlyStats($month . ' ' . $year);
            			$this->objGraph->addSimpleData(array('month' => $month, 'hits' => $hits ), 'month', 'hits');

            		}
            		$end = $this->microtime_float();
            		$total = $end - $start;
            		echo "Graph with $yearcount rows was built in $total secs";
            		$this->objGraph->addPlotArea('bar', 'black', 'yellow');
            		//$this->objGraph->addPlotArea('smooth_area', 'black', 'red');
            		//$this->objGraph->addPlotArea('smooth_line', 'black', 'blue');

            		$this->objGraph->labelAxes('Month', 'Hits');

            		$this->objGraph->show('/var/www/graphicaltest.png');


            	}
            	catch (customException $e)
            	{

            	}
        }
     }

     public function microtime_float()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }

}
?>