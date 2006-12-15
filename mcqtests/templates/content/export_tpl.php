<?php
/* -------------------- export template for testadmin ----------------*/

// security check-must be included in all scripts
if(!$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}
// end security check

/**
* @package mcqtests
*/

/**
* Export template for testadmin
* Author Kevin Cyster
* */

    $this->setLayoutTemplate('mcqtests_layout_tpl.php');

// set up html elements
    $objHeader=&$this->newObject('htmlheading','htmlelements');
    $objTable=&$this->newObject('htmltable','htmlelements');
    $objLink=&$this->newObject('link','htmlelements');
    $objButton=&$this->newObject('button','htmlelements');
    $objRadio=&$this->newObject('radio','htmlelements');
    $objForm=&$this->newObject('form','htmlelements');

// set up language items
    $backLabel=$this->objLanguage->languageText('word_back');
    $exportLabel=$this->objLanguage->languageText('mod_mcqtests_export', 'mcqtests');
    $selectLabel=$this->objLanguage->languageText('mod_mcqtests_selectexport', 'mcqtests');
    $answerLabel=$this->objLanguage->languageText('mod_mcqtests_exportanswers', 'mcqtests');
    $resultsLabel=$this->objLanguage->languageText('mod_mcqtests_exportresults', 'mcqtests');
    $submitLabel=$this->objLanguage->languageText('word_submit');
    $errorLabel=$this->objLanguage->languageText('mod_mcqtests_errorselect', 'mcqtests');

    $objHighlightLabels = $this->getObject('highlightlabels', 'htmlelements');
    echo $objHighlightLabels->show();

// set up heading
    $this->setVarByRef('heading',$exportLabel);

// set up htmlelements
    $objRadio=new radio('exporttype');
    $objRadio->addOption('answers',$answerLabel);
    $objRadio->addOption('results',$resultsLabel);
    $objRadio->setBreakSpace('<br />');
    $optionRadio=$objRadio->show();

    $objButton=new button('submitbutton',$submitLabel);
    $objButton->setToSubmit();
    $submitButton=$objButton->show();

// set up table
    $objTable=new htmltable();
    $objTable->cellspacing='2';
    $objTable->cellpadding='2';

    $objTable->startRow();
    $objTable->addCell('<b>'.$selectLabel.'</b>','','','','','');
    $objTable->endRow();

    $objTable->startRow();
    $objTable->addCell($optionRadio,'','','','','');
    $objTable->endRow();
    $optionTable=$objTable->show();

    $objForm=new form('optionform',$this->uri(array('action'=>'doexport','testId'=>$testId)));
    $objForm->addToForm($optionTable.'<br />'.$submitButton);
    $objForm->addRule('exporttype',$errorLabel,'required');
    $optionForm=$objForm->show();

    echo $optionForm;

// set up rerurn link
    $objLink=new link("javascript:history.back()");
    $objLink->link=$backLabel;
    $backLink=$objLink->show();
    echo "<br />".$backLink;

?>