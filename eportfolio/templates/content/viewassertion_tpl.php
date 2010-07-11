<?php
// Load classes.
$this->loadClass("form", "htmlelements");
$this->loadClass("textinput", "htmlelements");
$this->loadClass('textarea', 'htmlelements');
$this->loadClass("button", "htmlelements");
$this->loadClass("htmltable", 'htmlelements');
//reflectId
$singleView = $this->objGetall->viewSingleAssertion($assertionId);
echo $singleView;
$form = new form("add", $this->uri(array(
    'module' => 'eportfolio',
    'action' => 'postcomment',
    'prevaction' => 'singleassertion',
    'eportpartidvarname' => 'assertionId',
    'eportfoliopartid' => $assertionId
)));
$objHeading = &$this->getObject('htmlheading', 'htmlelements');
$objHeading->type = 3;
$objHeading->str = $objLanguage->languageText("mod_eportfolio_postcomment", 'eportfolio');
//table object
$reflecTable = &$this->newObject("htmltable", "htmlelements");
$reflecTable->width = '100%';
//$reflecTable->attributes = " align='left' border='0'";
$reflecTable->cellspacing = '5';
//row for author comments
$reflecTable->startRow();
$reflecTable->addCell($objHeading->show());
$reflecTable->endRow();
//new comment text field
$textinput = new textarea("newcomment", '');
$form->addRule('newcomment', 'Please type a comment', 'required');
$reflecTable->startRow();
$reflecTable->addCell($textinput->show());
$reflecTable->endRow();
//Submit button
$button = new button("submit", $objLanguage->languageText("word_submit", "system"));
$button->setToSubmit();
$reflecTable->startRow();
$reflecTable->addCell($button->show());
$reflecTable->endRow();
$reflecTable->startRow();
$reflecTable->addCell("&nbsp;");
$reflecTable->endRow();
$form->addToForm($reflecTable->show());
echo $form->show();
//Get Object
$this->objIcon = &$this->newObject('geticon', 'htmlelements');
$objLayer3 = $this->newObject('layer', 'htmlelements');
$this->objIcon->setIcon('close');
$this->objIcon->extra = " onclick='javascript:window.close()'";
$objLayer3->align = 'center';
$objLayer3->str = $this->objIcon->show();
echo $objLayer3->show();
?>
