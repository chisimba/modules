<?php

$this->loadClass('link', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('form', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('label', 'htmlelements');
$this->loadClass('radio', 'htmlelements');

if ($mode == 'edit') {
    $formAction = 'updatelocation';
    $title = 'Update Location';
    $buttonText = 'Update Location';
} else {
    $formAction = 'savelocation';
    $title = 'Add New Location';
    $buttonText = 'Add Location';
}

// Header
$header = new htmlheading();
$header->type = 1;
$header->str = $title;
echo $header->show();

// Create Form
$form = new form ('addedit', $this->uri(array('action'=>$formAction)));

$formTable = $this->newObject('htmltable', 'htmlelements');

// Name of Location Text input
$nameLabel = new label ('Name of Location:', 'input_location');
$name = new textinput('location');

// Add to form
$leftCol = '<p>'.$nameLabel->show().' '.$name->show().'</p>';

// Parent Location
$parentLabel = new label ('Parent Location:', 'input_parentlocation');
$leftCol .= '<p>'.$parentLabel->show().' '.$tree.'</p>';


// Location Type
$locationType = new radio('locationtype');
$locationType->addOption('continent', 'Continent');
$locationType->addOption('country', 'Country');
$locationType->addOption('province', 'Province');
$locationType->addOption('majorcity', 'Major City');
$locationType->addOption('city', 'City');
$locationType->addOption('suburb', 'Suburb');
$locationType->addOption('street', 'Street');
$locationType->setBreakSpace('<br />');
$locationType->setSelected('city');

$leftCol .= 'Location Type:<br /><div style="padding-left: 30px;">'.$locationType->show().'</div>';

// Location Image
$objImageSelect = $this->newObject('selectimage', 'filemanager');

$formTable->startRow();
$formTable->addCell($leftCol);
$formTable->addCell('Location Image:<br />'.' '.$objImageSelect->show());

$formTable->endRow();

// Input for Latitude Longitude
$objMapInput = $this->newObject('mapareainput', 'simplemap');
$objMapInput->height = '400px';

// Submit Button
$button = new button ('submitform', $buttonText);
$button->setToSubmit();

$form->addToForm($formTable->show().'<hr />'.$objMapInput->show().$button->show());

echo $form->show();

$homeLink = new link ($this->uri(NULL));
$homeLink->link = 'Return to News Home';
echo $homeLink->show();
?>