<?php
$this->loadClass('form', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('radio', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('textarea', 'htmlelements');
$this->loadClass('label', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');

$header = new htmlheading();
$header->type = 3;
$header->str = $this->objLanguage->languageText('mod_ads_section_a_overview', 'ads');

$required = '<span class="warning"> * '.$this->objLanguage->languageText('word_required', 'system', 'Required').'</span>';
$form = new form ('overview', $this->submitAction);
$messages = array();


$table = $this->newObject('htmltable', 'htmlelements');
$table->startRow();

$unitname = new textinput('A1',$this->formValue->getValue('A1'),NULL,50);
$unitnameLabel = new label($this->objLanguage->languageText('mod_ads_unit_name', 'ads').'&nbsp;', 'input_unitname');

$table->addCell($unitnameLabel->show(), 150, NULL, 'left');
$table->endRow();
$table->startRow();
$table->addCell($unitname->show().$required);
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
$table->addCell($this->objLanguage->languageText('mod_ads_thisisa','ads').'&nbsp;', 150, NULL, 'left');
$table->endRow();
$table->startRow();
$table->addCell($unitType->showTable());
$table->endRow();


$table->startRow();
$motivation = new textarea('A3');
$motivationLabel = new label($this->objLanguage->languageText('mod_ads_motiv', 'ads').'&nbsp;', 'input_motivation');
$motivation->value = $this->formValue->getValue("A3")

$table->addCell($motivationLabel->show(), 150, NULL, 'left');
$table->endRow();
$table->startRow();
$table->addCell($motivation->show().$required);
$table->endRow();


$table->startRow();
$qualification = new textarea('A4');
$qualificationLabel = new label($this->objLanguage->languageText('mod_ads_unit_qual', 'ads').'&nbsp;', 'input_motivation');

    $qualification->value = $this->formValue->getValue("A4");



$table->addCell($qualificationLabel->show(), 150, NULL, 'left');
$table->endRow();
$table->startRow();
$table->addCell($qualification->show().$required);
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
$unitType2->setSelected($this->formValue->getValue("A2"));
$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_ads_proposaltype','ads').'&nbsp;', 150, NULL, 'left');
$table->endRow();
$table->startRow();
$table->addCell($unitType2->showTable());
$table->endRow();


$form->addToForm($table->show());

$saveButton = new button ('submitform', 'Next');
$saveButton->setToSubmit();

$buttons.=$saveButton->show();
$cancelButton = new button('cancel','Cancel');
$actionUrl = $this->uri(array('action' => NULL));
$cancelButton->setOnClick("window.location='$actionUrl'");
$buttons.='&nbsp'.$cancelButton->show();

$form->addToForm('<p align="center"><br />'.$buttons.'</p>');

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
