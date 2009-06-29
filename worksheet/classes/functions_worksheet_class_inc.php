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
    	$this->objWorksheetResults =& $this->getObject('dbworksheetresults', 'worksheet');	
	$this->loadClass('htmlheading', 'htmlelements');
	$this->loadClass('link', 'htmlelements');
        $this->objLanguage = &$this->getObject('language', 'language');
        $this->objContext = &$this->newObject('dbcontext', 'context');
        $this->objIcon= $this->newObject('geticon','htmlelements');
	$objPopup=&$this->loadClass('windowpop','htmlelements');
    }
    
    /**
     * 
     *Method to output worksheets in context
     *@param string $contextCode
     * @return array
     */
    public function displayWorksheets($contextCode, $userId=Null)
    { 
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
		    $table->addHeaderCell($this->objLanguage->languageText('mod_worksheet_worksheetname', 'worksheet', 'Worksheet Name'),'20%');
		    $table->addHeaderCell($this->objLanguage->languageText('mod_worksheet_questions', 'worksheet', 'Questions'),'13%');
		    $table->addHeaderCell($this->objLanguage->languageText('mod_worksheet_closingdate', 'worksheet', 'Closing Date'),'15%');
		    $table->addHeaderCell($this->objLanguage->languageText('mod_worksheet_datecompleted', 'worksheet', 'Date Completed'),'15%');
		    $table->addHeaderCell($this->objLanguage->languageText('mod_worksheet_mark', 'worksheet', 'Mark'),'10%');
		    $table->addHeaderCell($this->objLanguage->languageText('word_view', 'worksheet', 'View'),'13%');
		$table->endHeaderRow();		
		foreach ($worksheets as $worksheet)
		{
		 $wkshtResults = $this->objWorksheetResults->getWorksheetResult($userId, $worksheet['id']);
			 if($wkshtResults['userid']==$userId){
			 if(!empty($wkshtResults['mark'])){
			   $theMark = round(($wkshtResults['mark']/$worksheet['total_mark']*100),2)." %";
			 }else{
			   $theMark = "";
			 }
			    $table->startRow();
				$link = new link ($this->uri(array('action'=>'worksheetinfo', 'id'=>$worksheet['id'])));
				$link->link = $worksheet['name'];
				if(!empty($theMark)){
					$this->objIcon->title=$this->objLanguage->languageText('word_view', 'worksheet', 'View');
				    	$this->objIcon->setIcon('comment_view');
				   	$commentIcon = $this->objIcon->show();

					$objPopup = new windowpop();
				    	$objPopup->set('location',$this->uri(array('action'=>'viewworksheet','id'=>$worksheet['id']),"eportfolio"));
				    	$objPopup->set('linktext',$commentIcon);
				    	$objPopup->set('width','800');
				    	$objPopup->set('height','400');
				    	$objPopup->set('left','200');
				    	$objPopup->set('top','200');
				    	$objPopup->set('scrollbars','yes');
				    	$objPopup->set('resizable','yes');
				    	$objPopup->putJs(); // you only need to do this once per page
				}
				
				$table->addCell($worksheet['name']);
				$table->addCell($worksheet['questions']);
				$table->addCell($worksheet['closing_date']);
				$table->addCell($wkshtResults['last_modified']);
				$table->addCell($theMark);
				if(!empty($theMark)){
				  $table->addCell('<p>'.$objPopup->show().'</p>');
				}else{
				  $table->addCell("&nbsp;");
				}
			    $table->endRow();
			}
		}
     }
	    return $header->show().$table->show();
   }    
}
?>
