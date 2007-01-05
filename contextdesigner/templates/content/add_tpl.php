<?php

//this template shows the list of modules then shows the list
// of links for that module

//echo 'modules will be listed here then after a module selected a list of links will be displayed with checked boxes that are available for adding into the context designer';
$objH = & $this->newObject('htmlheading', 'htmlelements');
$objButton = & $this->newObject('button', 'htmlelements');
$objButton2 = & $this->newObject('button', 'htmlelements');
$objIcon = & $this->newObject('geticon', 'htmlelements');
$objLink = & $this->newObject('link', 'htmlelements');
$objForm = & $this->newObject('form', 'htmlelements');
$objDropDown = & $this->newObject('dropdown', 'htmlelements');
$objFeatureBox = & $this->newObject('featurebox', 'navigation');


$objForm->action = $this->uri(array('action' => 'addstep2'));

$objButton->value = $this->_objLanguage->languageText('word_next');
$objButton->setToSubmit();

$objButton2->value = $this->_objLanguage->languageText('word_cancel');
$objButton2->setOnClick('javascript:document.location = \''.$this->uri(null).'\'');

$objDropDown->id = 'modules';
foreach ($modules as $module)
{
    $modInfo = $this->_objModule->getModuleInfo($module['moduleid']);
    
    $objDropDown->addOption($module['moduleid'], $modInfo['name'] );    
}


$objForm->addToForm('Select a Module');
$objForm->addToForm($objDropDown);
$objForm->addToForm($objButton2);
$objForm->addToForm($objButton);


echo $objFeatureBox->show('Step 1 : Select Module', $objForm->show());

?>