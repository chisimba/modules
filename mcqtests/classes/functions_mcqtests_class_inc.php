<?php

/* -------------------- dbTable class ----------------*/
// security check - must be included in all scripts

if (!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}

/**
* Class for general functions within mcqtests
* @author Paul Mungai
* @copyright (c) 2009 UoN
* @package mcqtests
* @version 0.1
*/

class functions_mcqtests extends object
{

    public function init()
    {
        $this->dbTestadmin = $this->newObject('dbtestadmin', 'mcqtests');
        $this->dbQuestions = $this->newObject('dbquestions', 'mcqtests');
        $this->dbResults = $this->newObject('dbresults', 'mcqtests');
        $this->dbMarked = $this->newObject('dbmarked', 'mcqtests');
        $this->objWashout = $this->getObject('washout','utilities');
	$this->loadClass('htmlheading', 'htmlelements');
	$this->loadClass('link', 'htmlelements');
        $this->objLanguage = &$this->getObject('language', 'language');
        $this->objContext = &$this->newObject('dbcontext', 'context');
        $this->objIcon= $this->newObject('geticon','htmlelements');
	$objPopup=&$this->loadClass('windowpop','htmlelements');
    }
    
    /**
     * 
     *Method to output student mcq's
     *@param string $contextCode
     * @return array
     *example: $this->objMcqtestsFunctions->displaymcq($contextCode, $userId, $uriAction='showtest', $uriModule='eportfolio'); 
     */
    public function displaymcq($contextCode, $userId=Null, $uriAction, $uriModule)
    { 
	 $data = $this->dbTestadmin->getTests($contextCode);

	 if (!empty($data)) {
		$objmcqTable = $this->newObject('htmltable', 'htmlelements');
		$objmcqTable->startHeaderRow();
		$objmcqTable->addHeaderCell($this->objLanguage->languageText('word_name', 'system', 'Name'),'23%');
		$objmcqTable->addHeaderCell($this->objLanguage->languageText('mod_mcqtests_percentage', 'mcqtests', 'Percentage')." ".$this->objLanguage->languageText('mod_mcqtests_mark', 'mcqtests', 'Mark'), '15%');
		$objmcqTable->addHeaderCell($this->objLanguage->languageText('mod_mcqtests_mark', 'mcqtests', 'Mark'),'5%');
		$objmcqTable->addHeaderCell($this->objLanguage->languageText('mod_mcqtests_closingdate', 'mcqtests', 'Closing Date'),'15%');
		$objmcqTable->addHeaderCell($this->objLanguage->languageText('mod_assignment_datesubmitted', 'assignment', 'Date Submitted'),'27%');
		$objmcqTable->addHeaderCell($this->objLanguage->languageText('mod_eportfolio_view', 'eportfolio', 'View'),'15%');
		$objmcqTable->endHeaderRow();
	  foreach($data as $myData){
	   $studentMark = "";
	   $studentSubmitDate = "";   
	   $totalmark = $this->dbQuestions->sumTotalmark($myData['id']);
	   $resultsData = $this->dbResults->getResults($myData['id']);
	   $stdntTests = $this->dbTestadmin->getStudentTests($contextCode, $userId);
	   if(!empty($stdntTests)){
	    foreach($stdntTests as $stdntTest){
	     if($stdntTest["testid"] == $myData['id'] && $stdntTest["studentid"] == $userId){
	       $studentSubmitDate = $stdntTest["endtime"];
	     }

	    }
	   }

	   foreach($resultsData as $myResults){
	    if($myResults["studentid"] == $userId){
	      //var_dump($myResults);

	      $studentMark = $myResults["mark"];
	    }
	    $objLink = new link($this->uri(array('action' => 'showtest','id' => $myData['id'],'studentId' => $userId)));

		$this->objIcon->title=$this->objLanguage->languageText("mod_eportfolio_view", 'eportfolio');
		$this->objIcon->setIcon('comment_view');
		$commentIcon = $this->objIcon->show();

		$objPopup = new windowpop();
		$objPopup->set('location',$this->uri(array('action' => $uriAction,'id' => $myData['id'],'studentId' => $userId),$uriModule));
		$objPopup->set('linktext',$commentIcon);
		$objPopup->set('width','600');
		$objPopup->set('height','350');
		$objPopup->set('left','200');
		$objPopup->set('top','200');
	    	$objPopup->set('scrollbars','yes');
	    	$objPopup->set('resizable','yes');
		$objPopup->putJs(); // you only need to do this once per page
		//echo $objPopup->show();
	
	   //var_dump($myData['id'].' - '.$myData['name'].' - Closing Date - '.$myData["closingdate"].' - Date Submitted - '.$studentSubmitDate." - Percentage Mark - ".$myData["percentage"]." Total Mark - ".$totalmark."<br>"." Student Mark - ".$studentMark);
		$objmcqTable->startRow();
		$objmcqTable->addCell($myData['name'],'','','','','');
		$objmcqTable->addCell($myData["percentage"]." %",'','','','','');
		$objmcqTable->addCell(round(($studentMark/$totalmark*100),2)." %",'','','','','');
		$objmcqTable->addCell($myData["closingdate"],'','','','','');
		$objmcqTable->addCell($studentSubmitDate,'','','','','');
		$objmcqTable->addCell($objPopup->show(),'','','','','');
		$objmcqTable->endRow();

	   }
	  }
	    return $objmcqTable->show();
	 }else{
	  return False;
	 }	
   }    
}
?>
