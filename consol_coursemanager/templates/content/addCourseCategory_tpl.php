<?php

$this->loadClass('link', 'htmlelements');
$this->loadClass('label', 'htmlelements');
$this->loadClass('form', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');
$this->loadClass('textarea', 'htmlelements');

$heading = $this->getObject('htmlheading', 'htmlelements');
$heading->str = 'Add Course for Consol Glass';
echo $heading->show();

$form = new form ('addeditstate', $this->uri(array('action'=>'')));

$table = $this->getObject('htmltable', 'htmlelements');
$table->width = NULL;
$table->cellpadding = 5;


$table->startRow();

$nameLabel = new label ('Category Name:', 'input_name');
$table->addCell($nameLabel->show());

$nameInput = new textinput ('catname');
$nameInput->size = 32;
$table->addCell($nameInput->show());

$table->endRow();

$table->startRow();
$descLabel = new label ('Category Image:', 'input_image');
$table->addCell($descLabel->show());

$descriptionInput = new textinput ('image');
$descriptionInput->size = 32;
$table->addCell($descriptionInput->show());
$table->endRow();

//able->endRow();

$table->startRow();
$table->addCell('&nbsp;');

$submitButton = new button ('submitbutton', ucwords('save'));
$submitButton->setToSubmit();

$cancelButton = new button ('cancel', ucwords('cancel'));
$cancelButton->setOnClick("window.location='".$this->uri(array('action'=>'')));

$table->addCell($submitButton->show().'&nbsp;'.$cancelButton->show());
$table->endRow();
$form->addToForm($table->show());
echo $form->show();


?>