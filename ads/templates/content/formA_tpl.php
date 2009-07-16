<?php
$this->loadClass('form', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('radio', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('textarea', 'htmlelements');
$this->loadClass('label', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');

$header = new htmlheading($this->objLanguage->languageText('mod_ads_titleA','ads'), 2);


$required = '<span class="warning"> * '.$this->objLanguage->languageText('word_required', 'system', 'Required').'</span>';
$form = new form ('overview', $this->submitAction);
$messages = array();


$table = $this->newObject('htmltable', 'htmlelements');
$table->cellspacing = 10;
$table->startRow();


$unitname = new textinput('A1',$this->formValue->getValue('A1'),NULL,50);
$unitnameLabel = new label("<b>" . $this->objLanguage->languageText('mod_ads_unit_name', 'ads'). "</b>".'&nbsp;', 'input_unitname');

$table->addCell($unitnameLabel->show(), 150, NULL, 'left');
$table->endRow();
$table->startRow();
$table->addCell($unitname->show() . "<br />" . $this->formError->getError('A1'));
$table->endRow();

$unitType = new radio ('A2');
$unitType->addOption('new', $this->objLanguage->languageText('mod_ads_newunit', 'ads'));
$unitType->addOption('edit', $this->objLanguage->languageText('mod_ads_changeunit', 'ads'));
$unitType->setTableColumns(1);
if ($this->formValue->getValue("A2") != "") {
  $unitType->setSelected($this->formValue->getValue("A2"));
}
else {
  $unitType->setSelected('new');
}
$table->startRow();
$table->addCell("<b>" . $this->objLanguage->languageText('mod_ads_thisisa','ads'). "</b>".'&nbsp;', 150, NULL, 'left');
$table->endRow();
$table->startRow();
$table->addCell($unitType->showTable());
$table->endRow();


$table->startRow();
$motivation = new textarea('A3');
$motivationLabel = new label("<b>" . $this->objLanguage->languageText('mod_ads_motiv', 'ads'). "</b>".'&nbsp;', 'input_motivation');
$motivation->value = $this->formValue->getValue("A3");

$table->addCell($motivationLabel->show(), 150, NULL, 'left');
$table->endRow();
$table->startRow();
$table->addCell($motivation->show() . "<br />" . $this->formError->getError('A3'));
$table->endRow();


$table->startRow();
$qualification = new textarea('A4');
$qualificationLabel = new label("<b>" . $this->objLanguage->languageText('mod_ads_unit_qual', 'ads'). "</b>".'&nbsp;', 'input_motivation');

    $qualification->value = $this->formValue->getValue("A4");



$table->addCell($qualificationLabel->show(), 150, NULL, 'left');
$table->endRow();
$table->startRow();
$table->addCell($qualification->show() . "<br />" . $this->formError->getError('A4'));
$table->endRow();


$unitType2 = new radio ('A5');
$unitType2->addOption('changetype1', $this->objLanguage->languageText('mod_ads_changetype1', 'ads'));
$unitType2->addOption('changetype2', $this->objLanguage->languageText('mod_ads_changetype2', 'ads'));
$unitType2->addOption('changetype3', $this->objLanguage->languageText('mod_ads_changetype3', 'ads'));
$unitType2->addOption('changetype4', $this->objLanguage->languageText('mod_ads_changetype4', 'ads'));
$unitType2->addOption('changetype5', $this->objLanguage->languageText('mod_ads_changetype5', 'ads'));
$unitType2->setTableColumns(1);

if($this->formValue->getValue("A5")=='')
$unitType2->setSelected('changetype5');
else
$unitType2->setSelected($this->formValue->getValue("A5"));
$table->startRow();
$table->addCell("<b>" . $this->objLanguage->languageText('mod_ads_proposaltype','ads'). "</b>" .'&nbsp;', 150, NULL, 'left');
$table->endRow();
$table->startRow();
$table->addCell($unitType2->showTable());
$table->endRow();

$form->addToForm($header->show(). "<br />");
$form->addToForm($table->show());

$saveButton = new button ('submitform', 'Next');
$saveButton->setToSubmit();


$buttons=$saveButton->show();
/*
$cancelButton = new button('cancel','Cancel');
$actionUrl = $this->uri(array('action' => NULL));
$cancelButton->setOnClick("window.location='$actionUrl'");
$buttons.='&nbsp'.$cancelButton->show();
 *
 */

$form->addToForm("<br>".$buttons);

// Create an instance of the css layout class
$cssLayout = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
$cssLayout->setNumColumns(2);

$nav = $this->getObject('nav', 'ads');
$toSelect=$this->objLanguage->languageText('mod_ads_section_a_overview', 'ads');
$leftSideColumn = $nav->getLeftContent($toSelect, $this->getParam('action'), $this->getParam('courseid'));
$cssLayout->setLeftColumnContent($leftSideColumn);
//$rightSideColumn='<h1>'.$coursedata['title'].'</h1>';
$rightSideColumn='<div style="padding:10px;">';

//Add the table to the centered layer
$rightSideColumn .= $form->show();
$rightSideColumn.= '</div>';
// Add Right Column
$cssLayout->setMiddleColumnContent($rightSideColumn);

//Output the content to the page
echo $cssLayout->show();
?>
