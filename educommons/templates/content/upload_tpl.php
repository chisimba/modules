<?php

$objUpload =  $this->newObject('uploadinput', 'filemanager');

$objButton = new button('upload', 'Upload');
$objButton->setToSubmit();

$uri = $this->uri(array('action'=>'upload'));

$objForm = new form('upload', $uri);
$objForm->extra = ' ENCTYPE=\'multipart/form-data\'';
$objForm->addToForm($objUpload->show());
$objForm->addToForm($objButton->show());

echo $objForm->show();
