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
        $tblclassB->addCell("<b>" . $table['title'] . "</b>", "null", "top", "left", $oddOrEven, null);
        $tblclassB->addCell("<b>" . $table['description'] . "</b>", "null", "top", "left", $oddOrEven, null);		
        
        // Start of Rubric Options
        $options = NULL;
        
        if ($contextCode != "root") {
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
		}
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
                    'tableId'=>$table['id']
                ),$uriModule)	
            . "\">" . $icon->show() . "</a>";        
			$options .= "&nbsp;";
        $tblclassB->addCell($options, "null", "top", "left", $oddOrEven, null);
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
            $tblclass->startRow();
            $oddOrEven = ($oddOrEven=="even")? "odd":"even";
        
            $tblclassD->addCell("<b>" . $pdtable['title'] . "</b>", "null", "top", "left", $oddOrEven, null);
            $tblclassD->addCell("<b>" . $pdtable['description'] . "</b>", "null", "top", "left", $oddOrEven, null);        
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
            $tblclassD->addCell($options, "null", "top", "left", $oddOrEven, null);
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
