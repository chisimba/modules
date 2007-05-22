<?php

$this->loadClass('link', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('form', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');

$header = new htmlheading();
$header->type = 1;
$header->str = 'Manage Locations';
echo $header->show();

$form = new form('viewlocation', $this->uri(array('action'=>'viewlocation')));

$form->method = 'GET';
$action = new hiddeninput('action', 'viewlocation');
$module = new hiddeninput('module', 'news');
$form->addToForm($module->show().$action->show());

$form->addToForm($tree);

$button = new button ('', 'Go');
$button->setToSubmit();

$form->addToForm(' '.$button->show());

echo $form->show();

$addLocationLink = new link ($this->uri(array('action'=>'addlocation')));
$addLocationLink->link = 'Add New Location';
echo $addLocationLink->show();

echo ' / ';

$homeLink = new link ($this->uri(NULL));
$homeLink->link = 'Return to News Home';
echo $homeLink->show();
?>