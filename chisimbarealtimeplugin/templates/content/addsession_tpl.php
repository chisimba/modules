<?php
$this->loadClass('link', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('form', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');
$this->loadClass('textarea', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('label', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');

$objIcon = $this->newObject('geticon', 'htmlelements');
$objIcon->setIcon('loader');

$loadingIcon = $objIcon->show();

if ($mode == 'edit') {
    $formAction = 'updatesession';
    $title = 'Edit Session';
    $buttonText = "Update Session";
} else {
    $formAction = 'savestory';
    $title ='New Session';
    $buttonText = "Save Session";
}

// Header
$header = new htmlheading();
$header->type = 1;
$header->str = $title;
echo $header->show();



$datePicker = $this->newObject('datepicker', 'htmlelements');
$datePicker->name = 'startdate';
// Create Form
$form = new form ('addedit', $this->uri(array('action'=>$formAction)));

$formTable = $this->newObject('htmltable', 'htmlelements');

$sessionTitle = new textarea('sessiontitle');
$sessionTitle->size = 60;
$formTable->startRow();
$formTable->addCell('Session Title');
$formTable->addCell($sessionTitle->show());
$formTable->endRow();

$objTimePicker = $this->newObject('timepicker', 'htmlelements');

if ($mode == 'add') {
	$radio->setSelected('now');
	$objTimePicker->setSelectedNow();
}



$startDate = $this->newObject('htmltable', 'htmlelements');
$startDate->width = NULL;
$startDate->startRow();
$startDate->addCell($datePicker->show());
$startDate->addCell(' at ', 20);
$startDate->addCell($objTimePicker->show());
$startDate->endRow();

$formTable->startRow();
$formTable->addCell('End Date');
$formTable->addCell($startDate->show());
$formTable->endRow();

$objTimePicker = $this->newObject('timepicker', 'htmlelements');

if ($mode == 'add') {
	$radio->setSelected('now');
	$objTimePicker->setSelectedNow();
}

$endDate = $this->newObject('htmltable', 'htmlelements');
$endDate->width = NULL;
$endDate->startRow();
$endDate->addCell($datePicker->show());
$endDate->addCell(' at ', 20);
$endDate->addCell($objTimePicker->show());
$endDate->endRow();

$formTable->startRow();
$formTable->addCell('Start Date');
$formTable->addCell($startDate->show());
$formTable->endRow();

// Create an instance of the css layout class
$cssLayout = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
$cssLayout->setNumColumns(2);


$addButton = new button('add-session','Add Schedule');
$returnUrl = $this->uri(array('action' => 'addsession'));
$addButton->setOnClick("window.location='$returnUrl'");

$postLoginMenu  = $this->newObject('postloginmenu','toolbar');
$cssLayout->setLeftColumnContent($postLoginMenu->show());
$rightSideColumn =  $formTable->show();
$cssLayout->setMiddleColumnContent($rightSideColumn);
echo $cssLayout->show();
?>