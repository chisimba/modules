<?php

$objH = & $this->newObject('htmlheading', 'htmlelements');
$objButton = & $this->newObject('button', 'htmlelements');
$objButton2 = & $this->newObject('button', 'htmlelements');
$objIcon = & $this->newObject('geticon', 'htmlelements');
$objLink = & $this->newObject('link', 'htmlelements');
$objForm = & $this->newObject('form', 'htmlelements');
$objDropDown = & $this->newObject('dropdown', 'htmlelements');
$objFeatureBox = & $this->newObject('featurebox', 'navigation');


$objButton2->value = $this->_objLanguage->languageText('word_cancel');
$objButton2->setOnClick('javascript:document.location = \''.$this->uri(null).'\'');
$str = 'I NEED THE LINKS FROM THE MODULE NOW!!!!<br/>'.$objButton2->show();
echo $objFeatureBox->show('Step 2 : Select Items', $str);


?>