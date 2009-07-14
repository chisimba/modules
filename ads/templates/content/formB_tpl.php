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
$header->str = $this->objLanguage->languageText('mod_ads_section_b_rules_and_syllabus', 'ads');


$required = '<span class="warning"> * '.$this->objLanguage->languageText('word_required', 'system', 'Required').'</span>';

$form = new form ('rules', $this->submitAction);



$table = $this->newObject('htmltable', 'htmlelements');
$table->startRow();

$changetype = new textarea('B1');
$changetypeLabel = new label($this->objLanguage->languageText('mod_ads_b1', 'ads').'&nbsp;', 'change_type');
$changetype->value = $this->formValue->getValue("B1");

$table->addCell($changetypeLabel->show(), 150, NULL, 'left');
$table->endRow();
$table->startRow();
$table->addCell($changetype->show().$required);
$table->endRow();


$coursedesc = new textarea('B2');
$coursedescLabel = new label($this->objLanguage->languageText('mod_ads_b2', 'ads').'&nbsp;', 'course_desc');
$coursedesc->value = $this->formValue->getValue("B2");


$table->addCell($coursedescLabel->show(), 150, NULL, 'left');
$table->endRow();
$table->startRow();
$table->addCell($coursedesc->show().$required);
$table->endRow();


$prereq = new textarea('B3a');
$prereqLabel = new label($this->objLanguage->languageText('mod_ads_b3a', 'ads').'&nbsp;', 'pre_req');
$prereq->value = $this->formValue->getValue("B3a");

$table->addCell($prereqLabel->show(), 150, NULL, 'left');
$table->endRow();
$table->startRow();
$table->addCell($prereq->show());
$table->endRow();


$coreq = new textarea('B3b');
$coreqLabel = new label($this->objLanguage->languageText('mod_ads_b3b', 'ads').'&nbsp;', 'co_req');
$coreq->value = $this->formValue->getValue("B3b");

$table->addCell($coreqLabel->show(), 150, NULL, 'left');
$table->endRow();
$table->startRow();
$table->addCell($coreq->show());
$table->endRow();

$b4a = new radio ('B4a');
$b4a->addOption('b4a1', $this->objLanguage->languageText('mod_ads_b4a1', 'ads'));
$b4a->addOption('b4a2', $this->objLanguage->languageText('mod_ads_b4a2', 'ads'));
$b4a->addOption('b4a3', $this->objLanguage->languageText('mod_ads_b4a3', 'ads'));
$b4a->setTableColumns(1);
if($this->formValue->getValue("B4a")=='')
$b4a->setSelected('b4a1');
else
$b4a->setSelected($this->formValue->getValue("B4a"));

$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_ads_b4a','ads').'&nbsp;', 150, NULL, 'left');
$table->endRow();
$table->startRow();
$table->addCell($b4a->showTable());
$table->endRow();


$b4b = new textarea('B4b');
$b4bLabel = new label($this->objLanguage->languageText('mod_ads_b4b', 'ads').'&nbsp;', 'b4_b');
$b4b->value = $this->formValue->getValue("B4b");

$table->addCell($b4bLabel->show(), 150, NULL, 'left');
$table->endRow();
$table->startRow();
$table->addCell($b4b->show());
$table->endRow();



$b4c = new textarea('B4c');
$b4cLabel = new label($this->objLanguage->languageText('mod_ads_b4c', 'ads').'&nbsp;', 'b4_c');
$b4c->value = $this->formValue->getValue("B4c");

$table->addCell($b4cLabel->show(), 150, NULL, 'left');
$table->endRow();
$table->startRow();
$table->addCell($b4c->show());
$table->endRow();

$b5a = new radio ('B5a');
$b5a->addOption('b5_a1', $this->objLanguage->languageText('mod_ads_b5a1', 'ads'));
$b5a->addOption('b5_a2', $this->objLanguage->languageText('mod_ads_b5a2', 'ads'));
$b5a->addOption('b5_a3', $this->objLanguage->languageText('mod_ads_b5a3', 'ads'));
$b5a->addOption('b5_a4', $this->objLanguage->languageText('mod_ads_b5a4', 'ads'));
$b5a->addOption('b5_a5', $this->objLanguage->languageText('mod_ads_b5a5', 'ads'));
$b5a->addOption('b5_a6', $this->objLanguage->languageText('mod_ads_b5a6', 'ads'));
$b5a->addOption('b5_a7', $this->objLanguage->languageText('mod_ads_b5a7', 'ads'));
$b5a->addOption('b5_a8', $this->objLanguage->languageText('mod_ads_b5a8', 'ads'));
$b5a->addOption('b5_a9', $this->objLanguage->languageText('mod_ads_b5a9', 'ads'));
$b5a->setTableColumns(1);
if($this->formValue->getValue("B5a")=='') {
  $b5a->setSelected('b5_a1');
}
else {
  $b5a->setSelected($this->formValue->getValue("B5a"));
}

$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_ads_b5a','ads').'&nbsp;', 150, NULL, 'left');
$table->endRow();
$table->startRow();
$table->addCell($b5a->showTable());
$table->endRow();


$b5b = new textarea('B5b');
$b5bLabel = new label($this->objLanguage->languageText('mod_ads_b5b', 'ads').'&nbsp;', 'b5_b');
$b5b->value = $this->formValue->getValue("B5b");


$table->addCell($b5bLabel->show(), 150, NULL, 'left');
$table->endRow();
$table->startRow();
$table->addCell($b5b->show());
$table->endRow();



$b6a = new radio ('B6a');
$b6a->addOption('b6_a1', $this->objLanguage->languageText('mod_ads_b6a1', 'ads'));
$b6a->addOption('b6_a2', $this->objLanguage->languageText('mod_ads_b6a2', 'ads'));
$b6a->addOption('b6_a3', $this->objLanguage->languageText('mod_ads_b6a3', 'ads'));
$b6a->addOption('b6_a4', $this->objLanguage->languageText('mod_ads_b6a4', 'ads'));
$b6a->addOption('b6_a5', $this->objLanguage->languageText('mod_ads_b6a5', 'ads'));
$b6a->setTableColumns(1);

if ($this->formValue->getValue("B6a") == "") {
    $b6a->setSelected('b6_a1');
} 
else {
    $b6a->setSelected($this->formValue->getValue("B6a"));
}
$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_ads_b6a','ads').'&nbsp;', 150, NULL, 'left');
$table->endRow();
$table->startRow();
$table->addCell($b6a->showTable());
$table->endRow();


$b6b = new textarea('B6b');
$b6bLabel = new label($this->objLanguage->languageText('mod_ads_b6b', 'ads').'&nbsp;', 'b6_b');

    $b6b->value = $this->formValue->getValue("B6b");



$table->addCell($b6bLabel->show(), 150, NULL, 'left');
$table->endRow();
$table->startRow();
$table->addCell($b6b->show());
$table->endRow();


$form->addToForm($table->show());

$saveButton = new button ('submitform', 'Next');
$saveButton->setToSubmit();

$buttons=$saveButton->show();
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
