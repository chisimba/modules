<?php

$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('form', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('textarea', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('label', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
    $formAction = 'saveinspectiondata';
    $title = 'Meat Inspection';
   // $buttonText = 'Save';


// Header
$header = new htmlheading();
$header->type = 1;
$header->str = $title;
echo $header->show();

// Create Form
$form = new form ('add', $this->uri(array('action'=>$formAction)));

$formTable = $this->newObject('htmltable', 'htmlelements');

$district = new textinput('district');
$district->size = 50;


//district name
$label = new label ('District', 'input_district');

$formTable->startRow();
$formTable->addCell($label->show());
$formTable->addCell($district->show());
$formTable->endRow();
//date of inspection
$datePicker = $this->newObject('datepicker', 'htmlelements');
$datePicker->name = 'inspectiondate';
$formTable->startRow();
$formTable->addCell('Inspection Date (Date when disease was found)');
$formTable->addCell($datePicker->show());
$formTable->endRow();


//number of cases
$label = new label ('Number of Cases', 'input_no_of_cases');
$num_of_cases= new textinput('num_of_cases');
$num_of_cases->size = 50;
//number at risk
$label2 = new label ('Number at Risk', 'input_no_at_risk');
$num_at_risk = new textinput('num_at_risk');
$num_at_risk->size = 50;

$formTable->startRow();
$formTable->addCell($label->show());
$formTable->addCell($num_of_cases->show());
$formTable->addCell($label2->show());
$formTable->addCell($num_at_risk->show());
$formTable->endRow();

//container-table
$topTable = $this->newObject('htmltable', 'htmlelements');

$topTable->startRow();
$topTable->addCell($formTable->show());
$topTable->endRow();
$form->addToForm($topTable->show());
//buttons
$button = new button ('saveinspectiondata', 'Save');
$button->setToSubmit();

$btcancel = new button ('cancel', 'Cancel');
$btcancel->setToSubmit();

$form->addToForm($button->show());
$form->addToForm($btcancel->show());
echo $form->show();
?>
