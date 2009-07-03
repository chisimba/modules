<?php

/* -------------------- rubric class ----------------*/
// security check - must be included in all scripts

if (!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}

/**
* Class for general functions within rubric
* @author Paul Mungai
* @copyright (c) 2009 UoN
* @package rubric
* @version 0.1
*/

class functions_rubric extends object
{

    public function init()
    {
        $this->objWashout = $this->getObject('washout','utilities');
	$this->loadClass('htmlheading', 'htmlelements');
	$this->loadClass('link', 'htmlelements');
        //$objLanguage = &$this->getObject('language', 'language');
        $this->objIcon= $this->newObject('geticon','htmlelements');
	$objPopup=&$this->loadClass('windowpop','htmlelements');
	$this->objDbRubricTables =& $this->getObject('dbrubrictables'); 
	$this->objLanguage =& $this->getObject('language','language');
    }
    
    /**
     * 
     *Method to output student rubrics
     *@param string $contextCode
     * @return array
     *example: displayrubric($contextCode, $userId, $uriModule='eportfolio', $assessmentAction='assessments', $viewTableAction='viewtable'); 
     */
    public function displayrubric($contextCode, $userId=Null, $uriModule, $assessmentAction, $viewTableAction)
    { 
	$tables = $this->objDbRubricTables->listAll($contextCode, $contextCode == 'root' ? $userId : NULL);
	if(!empty($tables)){
	if ($this->contextCode != 'root') {
		$pdtables = $this->objDbRubricTables->listAll("root", $userId);
	}
    // Load needed classes
    $this->loadClass('link', 'htmlelements');
    $tblclassB = $this->newObject('htmltable','htmlelements');
    $tblclassB->width='100%';
    $tblclassB->border='0';
    $tblclassB->cellspacing='0';
    $tblclassB->cellpadding='5';	
        
    $tblclassB->startHeaderRow();
    $tblclassB->addHeaderCell($this->objLanguage->languageText('word_title'), '30%');
    $tblclassB->addHeaderCell($this->objLanguage->languageText('rubric_description','rubric'), '53%');
    $tblclassB->addHeaderCell($this->objLanguage->languageText('word_view'), '17%');
    $tblclassB->endHeaderRow();    
	
    // Display tables.	
    $oddOrEven = "odd";
	foreach ($tables as $table) {        
        $tblclassB->startRow();
        $oddOrEven = ($oddOrEven=="even")? "odd":"even";		    
        $tblclassB->addCell($table['title'], "null", "top", "left", $oddOrEven, null);
        $tblclassB->addCell($table['description'], "null", "top", "left", $oddOrEven, null);		
        
        // Start of Rubric Options
        $options = NULL;
        
        if ($contextCode != "root") {

/*        
	       $icon = $this->getObject('geticon','htmlelements');
               $icon->setIcon('assessments');
               $icon->title = $this->objLanguage->languageText("word_assessment");
	  				$icon->alt = $this->objLanguage->languageText("word_assessment");
            
				$options .= "<a href=\"" . 
					$this->uri(array(
				    	'module'=>$uriModule,
						'action'=>$assessmentAction,
						'tableId'=>$table['id']
					),$uriModule)	
				. "\">" .$icon->show() . "</a>";
				$options .= "&nbsp;";	
*/
		$this->objIcon->title=$this->objLanguage->languageText("word_view")."&nbsp;".$this->objLanguage->languageText("word_assessments","rubric");
		$this->objIcon->setIcon('assessments');
		$commentIconA = $this->objIcon->show();

		$objPopupA = new windowpop();
		$objPopupA->set('location',$this->uri(array('action' => $assessmentAction,'tableId'=>$table['id'],'studentId' => $userId),$uriModule));
		$objPopupA->set('linktext',$commentIconA);
		$objPopupA->set('width','600');
		$objPopupA->set('height','150');
		$objPopupA->set('left','200');
		$objPopupA->set('top','200');
		$objPopupA->set('scrollbars','yes');
		$objPopupA->set('resizable','yes');
		$objPopupA->putJs(); // you only need to do this once per page

		}
	        // View table.

	$this->objIcon->title=$this->objLanguage->languageText("word_view")."&nbsp;".$this->objLanguage->languageText("rubric_rubric","rubric");
	$this->objIcon->setIcon('comment_view');
	$commentIconB = $this->objIcon->show();

	$objPopupB = new windowpop();
	$objPopupB->set('location',$this->uri(array('action' => $viewTableAction,'tableId'=>$table['id'],'studentId' => $userId),$uriModule));
	$objPopupB->set('linktext',$commentIconB);
	$objPopupB->set('width','600');
	$objPopupB->set('height','150');
	$objPopupB->set('left','200');
	$objPopupB->set('top','200');
	$objPopupB->set('scrollbars','yes');
	$objPopupB->set('resizable','yes');
	//$objPopupB->putJs(); // you only need to do this once per page
	/*        
            $icon = $this->getObject('geticon','htmlelements');
            $icon->setIcon('preview');
            $icon->title = $this->objLanguage->languageText("word_view");
            $icon->alt = $this->objLanguage->languageText("word_view");
            $icon->align=false;            
            $options .= "<a href=\"" . 
                $this->uri(array(
                    'module'=>$uriModule,
                    'action'=>$viewTableAction,
                    'tableId'=>$table['id']
                ),$uriModule)	
            . "\">" . $icon->show() . "</a>";        
			$options .= "&nbsp;";
	*/
        if ($contextCode != "root") {
         $tblclassB->addCell($objPopupA->show().$objPopupB->show(), "null", "top", "left", $oddOrEven, null);
	}else{
	 $tblclassB->addCell($objPopupB->show(), "null", "top", "left", $oddOrEven, null);
	}
        $tblclassB->endRow();

	}
    if (empty($tables)) {        

        $tblclassB->startRow();        
        $tblclassB->addCell("<div class=\"noRecordsMessage\">" . $this->objLanguage->languageText('mod_rubric_norecords','rubric') . "</div>", "null", "top", "left", "", 'colspan="3"');
        $tblclassB->endRow();
    }

    //Show predefined rubrics if any	
    if (!empty($pdtables)) {
	$pageTitle = $this->newObject('htmlheading','htmlelements');
	$pageTitle->type=3;
	$pageTitle->align='left';
	$pageTitle->str=$this->objLanguage->languageText('rubric_rubrics','rubric');
    
        $tblclassC=$this->newObject('htmltable','htmlelements');
        $tblclassC->width='100%';
        $tblclassC->border='0';
        $tblclassC->cellspacing='1';
        $tblclassC->cellpadding='5';    
    
        $tblclassC->startRow();
    	
        $tblclassC->addCell($pageTitle->show(), "null", "top", "left", "",null);
        $tblclassC->endRow();

        $tblclassD = $this->newObject('htmltable','htmlelements');
        $tblclassD->width='100%';
        $tblclassD->border='0';
        $tblclassD->cellspacing='1';
        $tblclassD->cellpadding='5';	
        
        $tblclassD->startHeaderRow();
        $tblclassD->addHeaderCell($this->objLanguage->languageText('word_title'), 60);
        $tblclassD->addHeaderCell($this->objLanguage->languageText('rubric_description','rubric'), 60);
        $tblclassD->addHeaderCell("&nbsp;", 20);    
        $tblclassD->endHeaderRow();        
        
        $oddOrEven = "odd";
    if (isset($pdtables)) {
        foreach ($pdtables as $pdtable) {
            $tblclassD->startRow();
            $oddOrEven = ($oddOrEven=="even")? "odd":"even";
        
            $tblclassD->addCell("<b>" . $pdtable['title'] . "</b>", "null", "top", "left", $oddOrEven, null);
            $tblclassD->addCell("<b>" . $pdtable['description'] . "</b>", "null", "top", "left", $oddOrEven, null);        
            /*
            // View table.            
                $icon = $this->getObject('geticon','htmlelements');
                $icon->setIcon('preview');
                $icon->title = $this->objLanguage->languageText("word_view");
                $icon->alt = $this->objLanguage->languageText("word_view");
                $icon->align=false;            
                $options .= "<a href=\"" . 
                    $this->uri(array(
                        'module'=>$uriModule,
                        'action'=>$viewTableAction,
                        'tableId'=>$pdtable['id']
                    ),$uriModule)	
                . "\">" . $icon->show() . "</a>";
                $options .= "&nbsp;";
	*/
	    $this->objIcon->title=$this->objLanguage->languageText("word_view")."&nbsp;".$this->objLanguage->languageText("rubric_rubric","rubric");
	    $this->objIcon->setIcon('comment_view');
	    $commentIconC = $this->objIcon->show();

	    $objPopupC = new windowpop();
	    $objPopupC->set('location',$this->uri(array('action' => $viewTableAction,'tableId'=>$pdtable['id'],'studentId' => $userId),$uriModule));
	    $objPopupC->set('linktext',$commentIconC);
	    $objPopupC->set('width','600');
	    $objPopupC->set('height','150');
	    $objPopupC->set('left','200');
	    $objPopupC->set('top','200');
	    $objPopupC->set('scrollbars','yes');
	    $objPopupC->set('resizable','yes');

            $tblclassD->addCell($objPopupC->show(), "null", "top", "left", $oddOrEven, null);
            $tblclassD->endRow();
        }
    }
    
    if (empty($pdtables)) {
        $tblclassD->startRow();       
        $tblclassD->addCell("<div class=\"noRecordsMessage\">" . $this->objLanguage->languageText('mod_rubric_norecords','rubric') . "</div>", "null", "top", "left", "", 'colspan="3"');
        $tblclassD->endRow();
    }
     return $tblclassB->show().$tblclassC->show().$tblclassD->show();
    }else{
     return $tblclassB->show();
    }
   }else{
    return False;
   }
   }    
}
?>
