<?php

//this template shows the list of modules then shows the list
// of links for that module

//echo 'modules will be listed here then after a module selected a list of links will be displayed with checked boxes that are available for adding into the context designer';
$this->loadClass('dropdown' , 'htmlelements');
$this->loadClass('button' , 'htmlelements');

$objH = & $this->newObject('htmlheading', 'htmlelements');
$objButton = new button();//& $this->newObject('button', 'htmlelements');
$objButton2 = new button();//& $this->newObject('button', 'htmlelements');
$objIcon = & $this->newObject('geticon', 'htmlelements');
$objLink = & $this->newObject('link', 'htmlelements');
$objForm = & $this->newObject('form', 'htmlelements');

$objDropDown = new dropdown();//& $this->newObject('dropdown', 'htmlelements');
$objFeatureBox = & $this->newObject('featurebox', 'navigation');


$objForm->action = $this->uri(array('action' => 'addstep2'));

$objButton->value = $this->_objLanguage->languageText('word_next');
$objButton->setToSubmit();

$objButton2->value = $this->_objLanguage->languageText('word_cancel');
$objButton2->setOnClick('javascript:document.location = \''.$this->uri(null).'\'');

$objDropDown->name = 'moduleid';
foreach ($modules as $module)
{
    $modInfo = $this->_objModule->getModuleInfo($module['moduleid']);
    
    $objDropDown->addOption($module['moduleid'], ucwords($modInfo['name']) );    
}


$objForm->addToForm('Select a Module');
$objForm->addToForm($objDropDown);
$objForm->addToForm($objButton2);
$objForm->addToForm($objButton);


echo $objFeatureBox->show('Step 1 : Select a Plugin', $objForm->show());

?>