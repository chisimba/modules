<?php
// Thi template displays the confirmation of registered staff members

$h3 = $this->getObject('htmlheading', 'htmlelements');
$objLayer = $this->newObject('layer', 'htmlelements');
$objIcon =  $this->newObject('geticon', 'htmlelements');
$h3->str = $objIcon->show().'&nbsp;'. $this->objLanguage->languageText('word_text', 'rimfhe', 'Data Entry Confirmation.');

$objLayer->str = $h3->show();
$objLayer->border = '; float:left; align: left; margin:0px; padding:0px;';
$header = $objLayer->show();

$display = '<p>'.$header.$headShow.'</p><hr />';

//Show Header
echo $display;

echo $this->objLanguage->languageText('word_text2', 'rimfhe', 'The information you entered has been successfully submitted to the database.<br /> Thank you.');
?>
