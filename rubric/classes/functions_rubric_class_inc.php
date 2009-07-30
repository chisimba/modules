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
					$this->objLanguage =& $this->getObject('language','language');
					$this->objDbRubricTables =& $this->getObject('dbrubrictables','rubric'); 
					$this->objDbRubricPerformances =& $this->getObject('dbrubricperformances','rubric'); 
					$this->objDbRubricObjectives =& $this->getObject('dbrubricobjectives','rubric'); 
					$this->objDbRubricCells =& $this->getObject('dbrubriccells','rubric'); 
					$this->objDbRubricAssessments =& $this->getObject('dbrubricassessments','rubric'); 
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
    /**
     * 
     *Method to output student rubrics
     *@param string $contextCode
     * @return array
     *example: displayrubric($contextCode, $userId, $uriModule='eportfolio', $assessmentAction='assessments', $viewTableAction='viewtable'); 
     */
    public function displayrubricFull($contextCode, $userId=Null, $uriModule, $assessmentAction, $viewTableAction)
    { 
					$tables = $this->objDbRubricTables->listAll($contextCode, $contextCode == 'root' ? $userId : NULL);
					if(!empty($tables)){
							if ($this->contextCode != 'root') {
								$pdtables = $this->objDbRubricTables->listAll("root", $userId);
							}
						// Load needed class
						$this->loadClass('link', 'htmlelements');
						$tblclassB = $this->newObject('htmltable','htmlelements');
						$tblclassB->width='100%';
						$tblclassB->border='0';
						$tblclassB->cellspacing='0';
						$tblclassB->cellpadding='0';	

						// Display tables.	
						$oddOrEven = "odd";
						foreach ($tables as $table) {        
						// Start of Rubric Options
							$options = NULL;

							if ($contextCode != "root") {
								$tableInfo = $this->objDbRubricTables->listSingle($table['id']);
								$title = $tableInfo[0]['title'];
								$description = $tableInfo[0]['description'];
								$rows = $tableInfo[0]['rows'];
								$cols = $tableInfo[0]['cols'];
								$maxtotal=$cols*$rows;
								$assessments = $this->objDbRubricAssessments->listAll($table['id']);
								//Do we want to show student names?
								$showStudentNames = 'yes';
								//Get Objects
								$pageTitle = $this->newObject('htmlheading','htmlelements');
								$pageTitle->type=3;
								$pageTitle->align='left';
								$pageTitle->str=$this->objLanguage->languageText('rubric_rubric','rubric')." : " . $title;

								// Show Title
								$fststr = $pageTitle->show();

								// Show Description
								$fststr .= $description."<br />";



								$tblclass = $this->newObject('htmltable','htmlelements');
								$tblclass->width='100%';
								$tblclass->border='0';
								$tblclass->cellspacing='0';
								$tblclass->cellpadding='0';	
								$oddOrEven = "odd";    
								foreach ($assessments as $assessment) {
									$tblclass->startRow();
									$oddOrEven = ($oddOrEven=="even")? "odd":"even";
									$tblclass->addCell("<b>".$this->objLanguage->languageText('rubric_name','rubric').": </b>".$assessment['studentno']);
									$tblclass->endRow();


									$scores = explode(",", $assessment['scores']);
									$total = 0;
									foreach ($scores as $score) {
									$total += $score;
									}
									if ($total==0 || $maxtotal == 0){
										$tblclass->startRow();
										$tblclass->addCell("<b>" . "" . "</b>", "null", "top", "left", $oddOrEven, null);
										$tblclass->endRow();
									}else{
										$tblclass->startRow();
										$tblclass->addCell("<b>".$this->objLanguage->languageText('rubric_score','rubric').": </b>" . "$total/$maxtotal");
										$tblclass->endRow();
									}
									$tblclass->startRow();
									$tblclass->addCell("<b>".ucfirst($this->objLanguage->code2Txt('rubric_teacher','rubric')).": </b>".$assessment['teacher']);
									$tblclass->endRow();
									$tblclass->startRow();
									$tblclass->addCell("<b>".$this->objLanguage->languageText('rubric_date','rubric').": </b>".$assessment['timestamp']);
									$tblclass->endRow();
									
								}								
							}
							// View table.
							$tableInfo = $this->objDbRubricTables->listSingle($table['id']);
							$title = $tableInfo[0]['title'];
							$description = $tableInfo[0]['description'];
							$rows = $tableInfo[0]['rows'];
							$cols = $tableInfo[0]['cols'];
							// Build the performances array
							$performances = array();
							for ($j=0;$j<$cols;$j++) {
								$performance = $this->objDbRubricPerformances->listSingle($table['id'], $j);
								$performances[] = $performance[0]['performance'];
							}				
							// Build the objectives array
							$objectives = array();
							for ($i=0;$i<$rows;$i++) {
								$objective = $this->objDbRubricObjectives->listSingle($table['id'], $i);
								$objectives[] = $objective[0]['objective'];
							}
							// Build the cells matrix
							$cells = array();
							for ($i=0;$i<$rows;$i++) {
								$cells[$i] = array();
								for ($j=0;$j<$cols;$j++) {
									$cell = $this->objDbRubricCells->listSingle($table['id'], $i, $j);
									$cells[$i][$j] = $cell[0]['contents'];
								}
							}

							$pageTitle = $this->newObject('htmlheading','htmlelements');
							$pageTitle->type=3;
							$pageTitle->align='left';
							$pageTitle->str=$this->objLanguage->languageText('rubric_rubric','rubric') . " : " . $title ;
							$sndstr = $pageTitle->show();

							$labelDescription = $description."<br />";

							$sndstr .= $labelDescription;
							// If this is an assessment then display details.
							if (isset($IsAssessment)) {
								$objTable =& $this->newObject('htmltable','htmlelements');
								$objTable->border = '0';
								$objTable->width='100%';        
								$objTable->cellspacing='0';
								$objTable->cellpadding='0';

								$objTable->startRow();
								$objTable->addCell("<b>".ucfirst($this->objLanguage->code2Txt("rubric_teacher","rubric"))."</b>");
								$objTable->addCell($teacher);
								$objTable->endRow();
								$objTable->startRow();
								$objTable->addCell("<b>".ucfirst($this->objLanguage->code2Txt("rubric_studentno","rubric"))."</b>");
								$objTable->addCell($studentNo);
								$objTable->endRow();
								$objTable->startRow();
								$objTable->addCell("<b>".ucfirst($this->objLanguage->code2Txt("rubric_student","rubric"))."</b>");
								$objTable->addCell($student);
								$objTable->endRow();
								$objTable->startRow();
								$objTable->addCell("<b>".$this->objLanguage->languageText("rubric_datesubmitted","rubric")."</b>");
								$objTable->addCell($date);
								$objTable->endRow();
								$sndstr .= $objTable->show();
							}
							$mytable =& $this->newObject("htmltable","htmlelements");
							$mytable->border = '0';
							$mytable->width = '100%';	
							$mytable->cellspacing='0';
							$mytable->cellpadding='0'; 
							
							//Get Rubric
							$tableInfo = $this->objDbRubricTables->listSingle($table['id']);
							$title = $tableInfo[0]['title'];
							$description = $tableInfo[0]['description'];
							$rows = $tableInfo[0]['rows'];
							$cols = $tableInfo[0]['cols'];
							// Build the performances array
							$performances = array();
							for ($j=0;$j<$cols;$j++) {
								$performance = $this->objDbRubricPerformances->listSingle($table['id'], $j);
								$performances[] = $performance[0]['performance'];
							}				
							// Build the objectives array
							$objectives = array();
							for ($i=0;$i<$rows;$i++) {
								$objective = $this->objDbRubricObjectives->listSingle($table['id'], $i);
								$objectives[] = $objective[0]['objective'];
							}
							// Build the cells matrix
							$cells = array();
							for ($i=0;$i<$rows;$i++) {
								$cells[$i] = array();
								for ($j=0;$j<$cols;$j++) {
									$cell = $this->objDbRubricCells->listSingle($table['id'], $i, $j);
									$cells[$i][$j] = $cell[0]['contents'];
								}
							}

							$mytable->startRow();
							$mytable->addCell("<b>".$this->objLanguage->languageText("word_objectives","rubric")."</b>");
							// Display performances.
							if(!empty($performances[$j])){
								for ($j=0;$j<$cols;$j++) {
									if(!empty($performances[$j]))
										$mytable->addCell("<b>".$performances[$j]."</b>");
								}
							}
							if (isset($IsAssessment)) {
								$mytable->addCell("<b>Score</b>");
							}

							$mytable->endRow();							

							for ($i=0;$i<$rows;$i++) {

								$mytable->startRow();
								// Display objective.
								$mytable->addCell($objectives[$i]);
								// Display cells.
								for ($j=0;$j<$cols;$j++) {
									$mytable->addCell($cells[$i][$j]);
								}
								if (isset($IsAssessment)) {
									$mytable->addCell($scores[$i]);
								}
								$mytable->endRow();
							}
							// If this is an assessment display the total score.
							if (isset($IsAssessment)) {

								$mytable->startRow();
								$mytable->addCell("&nbsp;");
								for ($j=0;$j<($cols-1);$j++) {
									$mytable->addCell("&nbsp;");
								}
								$mytable->addCell($this->objLanguage->languageText("rubric_total","rubric") . "&nbsp;", Null, Null, "right");
								if ($total==0 || $maxtotal == 0){
								}else{
								 $mytable->addCell($total/$maxtotal);
								}	
								$mytable->endRow();

							}
							$sndstr .= $mytable->show();
		
							if ($contextCode != "root") {

								$tblclassB->startRow();
								$tblclassB->addCell($fststr.$tblclass->show());
								$tblclassB->endRow();

								$tblclassB->startRow();
								$tblclassB->addCell($sndstr);
								$tblclassB->endRow();

							}else{
								$tblclassB->startRow();
								$tblclassB->addCell($sndstr);
								$tblclassB->endRow();
							}
						}
						if (empty($tables)) {        

						$tblclassB->startRow();        
						$tblclassB->addCell("<div class=\"noRecordsMessage\">" . $this->objLanguage->languageText('mod_rubric_norecords','rubric') . "</div>");
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
						$tblclassC->cellspacing='0';
						$tblclassC->cellpadding='0';    

						$tblclassC->startRow();

						$tblclassC->addCell($pageTitle->show());
						$tblclassC->endRow();

						$tblclassD = $this->newObject('htmltable','htmlelements');
						$tblclassD->width='100%';
						$tblclassD->border='0';
						$tblclassD->cellspacing='0';
						$tblclassD->cellpadding='0';	
						if (isset($pdtables)) {
						foreach ($pdtables as $pdtable) {
							$tblclassD->startRow();
							$tblclassD->addCell("<b>".$this->objLanguage->languageText('word_title').": ". "</b>". $pdtable['title'] );
							$tblclassD->endRow();
							$tblclassD->startRow();						
							$tblclassD->addCell("<b>" .$this->objLanguage->languageText('rubric_description','rubric').": ". "</b>". $pdtable['description'] );
							$tblclassD->endRow();
						
							$tableInfo = $this->objDbRubricTables->listSingle($pdtable['id']);
							$title = $tableInfo[0]['title'];
							$description = $tableInfo[0]['description'];
							$rows = $tableInfo[0]['rows'];
							$cols = $tableInfo[0]['cols'];
							// Build the performances array
							$performances = array();
							for ($j=0;$j<$cols;$j++) {
								$performance = $this->objDbRubricPerformances->listSingle($pdtable['id'], $j);
								$performances[] = $performance[0]['performance'];
							}				
							// Build the objectives array
							$objectives = array();
							for ($i=0;$i<$rows;$i++) {
								$objective = $this->objDbRubricObjectives->listSingle($pdtable['id'], $i);
								$objectives[] = $objective[0]['objective'];
							}
							// Build the cells matrix
							$cells = array();
							for ($i=0;$i<$rows;$i++) {
								$cells[$i] = array();
								for ($j=0;$j<$cols;$j++) {
									$cell = $this->objDbRubricCells->listSingle($pdtable['id'], $i, $j);
									$cells[$i][$j] = $cell[0]['contents'];
								}
							}

							$pageTitle = $this->newObject('htmlheading','htmlelements');
							$pageTitle->type=3;
							$pageTitle->align='left';
							$pageTitle->str=$this->objLanguage->languageText('rubric_rubric','rubric') . " : " . $title ;
							$thrdstr = $pageTitle->show();

							$labelDescription = "" . $description . "<br />";

							$thrdstr .= $labelDescription;

							// If this is an assessment then display details.
							if (isset($IsAssessment)) {
								$objTable =& $this->newObject('htmltable','htmlelements');
								$objTable->border = '0';
								$objTable->width='100%';        
								$objTable->cellspacing='0';
								$objTable->cellpadding='0';

								$objTable->startRow();
								$objTable->addCell("<b>".ucfirst($this->objLanguage->code2Txt("rubric_teacher","rubric"))."</b>");
								$objTable->addCell($teacher);
								$objTable->endRow();
								$objTable->startRow();
								$objTable->addCell("<b>".ucfirst($this->objLanguage->code2Txt("rubric_studentno","rubric"))."</b>");
								$objTable->addCell($studentNo);
								$objTable->endRow();
								$objTable->startRow();
								$objTable->addCell("<b>".ucfirst($this->objLanguage->code2Txt("rubric_student","rubric"))."</b>");
								$objTable->addCell($student);
								$objTable->endRow();
								$objTable->startRow();
								$objTable->addCell("<b>".$this->objLanguage->languageText("rubric_datesubmitted","rubric")."</b>");
								$objTable->addCell($date);
								$objTable->endRow();
								$thrdstr .= $objTable->show();
							}
							$table =& $this->newObject("htmltable","htmlelements");
							$table->border = '0';
							$table->width = '100%';	
							$table->cellspacing='0';
							$table->cellpadding='0'; 
							//$class = 'odd';
							for ($i=0;$i<$rows;$i++) {
								$table->startRow();
								// Display objective.
								$table->addCell("<b>".$this->objLanguage->languageText("word_objectives","rubric").": </b><br />".$objectives[$i]);
								$table->endRow();
								// Display cells.								
								for ($j=0;$j<$cols;$j++) {
									$table->startRow();
									$table->addCell("<b>".$performances[$j].": </b><br />".$cells[$i][$j]);
									$table->endRow();
								}
								if (isset($IsAssessment)) {
								 $table->startRow();
									$table->addCell("<b>"."Score".": </b>".$scores[$i]);
								 $table->endRow();
								}


								$class = $class == 'odd' ? 'even' : 'odd';
							}
							// If this is an assessment display the total score.
							if (isset($IsAssessment)) {
								$table->startRow();
								$table->addCell("&nbsp;");
								for ($j=0;$j<($cols-1);$j++) {
									$table->addCell("&nbsp;");
								}
								$table->addCell($this->objLanguage->languageText("rubric_total","rubric") . "&nbsp;", Null, Null, "right");
								if ($total==0 || $maxtotal == 0){
									$table->addCell("");
								}else{
									$table->addCell($total/$maxtotal);
								}
								$table->endRow();
							}
							$thrdstr .= $table->show();
							$tblclassD->startRow();
							$tblclassD->addCell($thrdstr);
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
