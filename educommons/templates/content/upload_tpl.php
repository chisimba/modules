<?php

$this->loadClass('htmlheading', 'htmlelements');

$header = new htmlheading();
$header->type = 1;
$header->str = 'eduCommons IMS Package Import';
echo $header->show();

$objFileLabel = $this->newObject('label', 'htmlelements');
$objFileLabel->label('IMS Package', 'input_fileupload');

$objFileUpload = $this->newObject('uploadinput', 'filemanager');

$objContextLabel = $this->newObject('label', 'htmlelements');
$objContextLabel->label('Context', 'input_context');

$objContextDropdown = $this->newObject('dropdown', 'htmlelements');
$objContextDropdown->dropdown('context');
foreach ($contexts as $context) {
    $objContextDropdown->addOption($context['contextcode']);
}

$objButton = new button('upload', 'Upload');
$objButton->setToSubmit();

$uri = $this->uri(array('action'=>'upload'));

$objForm = new form('upload', $uri);
$objForm->extra = ' ENCTYPE=\'multipart/form-data\'';
$objForm->addToForm('<p>'.$objFileLabel->show().': '.$objFileUpload->show().'</p>');
$objForm->addToForm('<p>'.$objContextLabel->show().': '.$objContextDropdown->show().'</p>');
$objForm->addToForm('<p>'.$objButton->show().'</p>');

echo $objForm->show();
