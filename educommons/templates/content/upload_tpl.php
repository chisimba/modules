<?php

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
$objForm->addToForm($objUpload->show());
$objForm->addToForm($objDropdown->show());
$objForm->addToForm($objButton->show());

echo $objForm->show();
