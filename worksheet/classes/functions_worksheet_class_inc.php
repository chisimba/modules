<?php

/* -------------------- dbTable class ----------------*/
// security check - must be included in all scripts

if (!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}

/**
* Class for general functions within worksheet
* @author Paul Mungai
* @copyright (c) 2009 UoN
* @package worksheet
* @version 0.1
*/

class functions_worksheet extends object
{

    public function init()
    {
    	$this->objWorksheet =& $this->getObject('dbworksheet', 'worksheet');	
	$this->loadClass('htmlheading', 'htmlelements');
	$this->loadClass('link', 'htmlelements');
        $this->objLanguage = &$this->getObject('language', 'language');
        $this->objContext = &$this->newObject('dbcontext', 'context');
    }
    
    /**
     * 
     *Method to output worksheets in context
     *@param string $contextCode
     * @return array
     */
    public function displayWorksheets($contextCode)
    { 
/*          
		$bigArr = array();
        
        $worksheets = $this->objWorksheet->getWorksheetsInContext($contextCode);	
		//print_r($worksheets);
 		foreach ($worksheets as $worksheet)
       {
              $newArr = array();    
              $newArr['menutext'] = $worksheet['name'];
              $newArr['description'] = $worksheet['description'];
              $newArr['itemid'] = $worksheet['id'];
              $newArr['moduleid'] = 'worksheet';
              $newArr['params'] = array('action' => 'selectforanswer','id'=>$worksheet['id']);
              $bigArr[] = $newArr;
              //echo $bigArr;
        }
          
        return $bigArr;
*/
	//Get worksheets
	$worksheets = $this->objWorksheet->getWorksheetsInContext($contextCode);

	$header = new htmlheading();
	$header->type = 3;
	$header->str = $this->objContext->getTitle($contextCode).': '.$this->objLanguage->languageText('mod_worksheet_worksheets', 'worksheet', 'Worksheets');
	//Load Table Object
	$table = $this->newObject('htmltable', 'htmlelements');

	if (count($worksheets) == 0) {
	    $table->startRow();
	        $table->addCell('<div class="noRecordsMessage">No Worksheets at present</div>');
	    $table->endRow();
	} else {
		$table->startHeaderRow();
		    $table->addHeaderCell($this->objLanguage->languageText('mod_worksheet_worksheetname', 'worksheet', 'Worksheet Name'));
		    $table->addHeaderCell($this->objLanguage->languageText('mod_worksheet_questions', 'worksheet', 'Questions'));
		    $table->addHeaderCell($this->objLanguage->languageText('mod_worksheet_activitystatus', 'worksheet', 'Activity Status'));
		    $table->addHeaderCell($this->objLanguage->languageText('mod_worksheet_percentage', 'worksheet', 'Percentage'));
		    $table->addHeaderCell($this->objLanguage->languageText('mod_worksheet_totalmark', 'worksheet', 'Total Mark'));
		    $table->addHeaderCell($this->objLanguage->languageText('mod_worksheet_closingdate', 'worksheet', 'Closing Date'));
		$table->endHeaderRow();		
		foreach ($worksheets as $worksheet)
		{
		    $table->startRow();
			$link = new link ($this->uri(array('action'=>'worksheetinfo', 'id'=>$worksheet['id'])));
			$link->link = $worksheet['name'];
			$table->addCell($link->show());
			$table->addCell($worksheet['questions']);
			$table->addCell($this->objWorksheet->getStatusText($worksheet['activity_status']));
			$table->addCell($worksheet['percentage']);
			$table->addCell($worksheet['total_mark']);
			$table->addCell($worksheet['closing_date']);
		    $table->endRow();
		}
     }
	    return $header->show().$table->show();
   }    
}
?>
