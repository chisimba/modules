<?php

//the main template creates a generated list of links
//
$objH = & $this->newObject('htmlheading', 'htmlelements');
$objTable = & $this->newObject('htmltable', 'htmlelements');
$objIcon = & $this->newObject('geticon', 'htmlelements');
$objLink = & $this->newObject('link', 'htmlelements');
$objForm = & $this->newObject('form', 'htmlelements');
$objFeatureBox = & $this->newObject('featurebox', 'navigation');

//the heading
$objH->str = 'Course Designer';

echo $objH->show();//'the generated list of links will show here';


if(is_array($linkList) && $linkList > 0)
{
    
    $str = 'list of links';
} else {
    
    $str = '<div align="center" style="font-size:large;font-weight:bold;color:#CCCCCC;font-family: Helvetica, sans-serif;">No items are available</div>';
}

echo $objFeatureBox->show('Items', $str);

$objLink->href = $this->uri(array('action' => 'add'));
$objLink->link = 'Add new Links ';

echo $objLink->show().$objIcon->getAddIcon($this->uri(array('action' => 'add')));
?>