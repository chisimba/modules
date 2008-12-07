<?php

$this->loadClass('htmlheading', 'htmlelements');

$header = new htmlheading();
$header->type = 1;
$header->str = 'eduCommons IMS Package Import';
echo $header->show();

$objUpload = $this->newObject('uploadinput', 'filemanager');

$objDropdown = $this->newObject('dropdown', 'htmlelements');
$objDropdown->dropdown('context');
foreach ($contexts as $context) {
    $objDropdown->addOption($context['contextcode']);
}

$objButton = new button('upload', 'Upload');
$objButton->setToSubmit();

$uri = $this->uri(array('action'=>'upload'));

$objForm = new form('upload', $uri);
$objForm->extra = ' ENCTYPE=\'multipart/form-data\'';
$objForm->addToForm('<p>'.$objUpload->show().'</p>');
$objForm->addToForm('<p>'.$objDropdown->show().'</p>');
$objForm->addToForm('<p>'.$objButton->show().'</p>');

echo $objForm->show();
