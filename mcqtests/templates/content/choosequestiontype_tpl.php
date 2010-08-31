<?php


//set the layout of the choosequestiontype template
$this->setLayoutTemplate('mcqtests_layout_tpl.php');


//Load the classes for the template 

$this->loadclass('htmltable','htmlelements');
$this->loadclass('htmlheading','htmlelements');
$this->loadclass('geticon','htmlelements');
$this->loadclass('link','htmlelements');
$this->loadClass('label', 'htmlelements');
$this->loadClass('fieldsetex', 'htmlelements');

$extbase = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/adapter/ext/ext-base.js','htmlelements').'" type="text/javascript"></script>';
$extalljs = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/ext-all.js','htmlelements').'" type="text/javascript"></script>';
$extallcss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('ext-3.0-rc2/resources/css/ext-all.css','htmlelements').'"/>';
$mainjs = '<script language="JavaScript" src="'.$this->getResourceUri('js/main.js').'" type="text/javascript"></script>';
$buttoncss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('css/buttons.css').'"/>';

$this->appendArrayVar('headerParams', $extallcss);
$this->appendArrayVar('headerParams', $extbase);
$this->appendArrayVar('headerParams', $extalljs);
$this->appendArrayVar('headerParams', $mainjs);
//$this->appendArrayVar('headerParams', $mcqdb);
$this->appendArrayVar('headerParams', $buttoncss);

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


$mainForm = '<div id="mainform">';
echo '<strong><h1>'.$test['name'].'</h1></strong>';

$existingQuestions = new dropdown('existingQ');
$existingQuestions->setId("existingQ");
$existingQuestions->addOption('-', '[-Select Method-]');
$existingQuestions->addOption('newQ', 'New questions');
$existingQuestions->addOption('oldQ', 'Choose from database');
$existingQuestionsLabel = new label ('Select Method ', 'existingQ');

$batchOptions = new dropdown('qnoption');
$batchOptions->setId("qnoption");
$batchOptions->addOption('-', '[-Select question type-]');
$batchOptions->addOption('mcq', 'MCQ questions');
$batchOptions->addOption('freeform', 'Free form test entry questions');
$batchLabel = new label ('Select question type ', 'input_qnoptionlabel');

$fd=$this->getObject('fieldsetex','htmlelements');

$fd->addLabel('<strong>'.$existingQuestionsLabel->show().'</strong>'.$existingQuestions->show());
$fd->addLabel('<strong>'.$batchLabel->show().'</strong>'.$batchOptions->show());
$fd->setLegend('Select question type');
$formmanager=$this->getObject('formmanager');

$questionContentStr='<div id="addquestion">'.$formmanager->createAddQuestionForm($test).'</div>';
$questionContentStr.='<div id="freeform">'.$formmanager->createAddFreeForm($test).'</div>';
$questionContentStr.='<div id="dbquestions">'.$formmanager->createDatabaseQuestions($oldQuestions, $testid).'</div>';
$questionContentStr.='<div id="mcqGrid"></div>';
$fd->addLabel($questionContentStr);
$mainForm .= $fd->show().'</div>';
echo $mainForm;

$mcqdb = '<script language="JavaScript" src="'.$this->getResourceUri('js/mcqdb.js').'" type="text/javascript"></script>';
echo $mcqdb;
?>