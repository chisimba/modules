<?php


//set the layout of the choosequestiontype template
$this->setLayoutTemplate('mcqtests_layout_tpl.php');


//Load the classes for the template 

$this->loadclass('htmltable','htmlelements');
$this->loadclass('htmlheading','htmlelements');
$this->loadclass('geticon','htmlelements');
$this->loadclass('link','htmlelements'); 
$this->dbQuestions = $this->newObject('dbquestions');


//Set the language items 
$choosetype=$this->objLanguage->languageText('mod_mcqtests_choosetype','mcqtests');
$typeLabel=$this->objLanguage->languageText('mod_mcqtests_typelabel','mcqtests');
$mcqtestLabel=$this->objLanguage->languageText('mod_mcqtests_mcqtestlabel','mcqtests');
$clozetestLabel=$this->objLanguage->languageText('mod_mcqtests_clozetestlabel','mcqtests');
$freeformLabel=$this->objLanguage->languageText('mod_mcqtests_freeformlabel','mcqtests');
$selectLabel=$this->objLanguage->languageText('mod_mcqtests_selectlabel','mcqtests');

//get the addicon
$objIcon=$this->newObject('geticon', 'htmlelements');
  
$count = count($questions);
	if (empty($questions)) {
		$count = 0;
	}
	
//add a new mcq question 
	$mcqUrl = $this->uri(array(
	'action' => 'addquestion',
		'id' => $data['id'],
		'count' => $count	
	));
// create the add icon 
	$chseMcq = $objIcon->getAddIcon($mcqUrl);


//add a new free form question 
$freeFormUrl = $this->uri(array(
		'action' => 'addfreeform',
		'id' => $data['id'],
		'count' => $count
	));
	//create the add icon 
	$chseFree = $objIcon->getAddIcon($freeFormUrl);


//Set heading

    $this->setVarByRef('heading', $choosetype);



//Header for the table

//$objHead=new htmlheading();
//$objHead->type=3;


$objTable=new htmltable();
$objTable->cellpadding = 5;
$objTable->cellspacing = 3;
$objTable->width = '99%';

$objTable->startRow();
$objTable->addCell('<b><h3>'.$typeLabel.'</h3></b>','','','','heading');
$objTable->addCell('<b><h3>'.$selectLabel.'</h3></b>','','','','heading');
$objTable->endRow();

$objTable->startRow();
$objTable->addCell($mcqtestLabel,'','','','heading');
$objTable->addCell($chseMcq,'','','','heading');
$objTable->endRow();


$objTable->startRow();
$objTable->addCell($freeformLabel,'','','','heading');
$objTable->addCell($chseFree,'','','','heading');
$objTable->endRow();

//show table
$tpl=$objTable->show();
echo $tpl;
?>
