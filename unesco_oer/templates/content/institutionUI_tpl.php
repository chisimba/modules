<?php


$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('fieldset', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');
$this->loadClass('label', 'htmlelements');
$this->loadClass('checkbox', 'htmlelements');
$this->loadClass('button', 'htmlelements');



$heading2= new htmlheading();
$heading2->type=2;
$heading2->str='Institution Management';
echo $heading2->show();

$table = $this->newObject('htmltable', 'htmlelements');

$textinput = new textinput('Institution Name');
$textinput->size = 60;
$table->startRow();
$table->addCell('Institution(s)');
$table->addCell($textinput->show());
$table->endRow();


$textinput = new textinput('Delete');
$textinput->size = 60;
$table->startRow();
$table->addCell('DELETE');
$table->addCell($textinput->show());
$table->endRow();

$textinput = new textinput('EDIT');
$textinput->size = 60;
$table->startRow();
$table->addCell('EDIT');
$table->addCell($textinput->show());
$table->endRow();

$products = $this->objDbInstitution->getArray('select id,name,loclat,loclong,thumbnai,puid from tbl_unesco_oer_institiutions');



?>
