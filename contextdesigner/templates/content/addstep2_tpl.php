<?php

$objH = & $this->newObject('htmlheading', 'htmlelements');
$objButton = & $this->newObject('button', 'htmlelements');
$objButton2 = & $this->newObject('button', 'htmlelements');
$objIcon = & $this->newObject('geticon', 'htmlelements');
$objLink = & $this->newObject('link', 'htmlelements');
$objForm = & $this->newObject('form', 'htmlelements');
$objCheckBox = & $this->newObject('checkbox', 'htmlelements');
$objDropDown = & $this->newObject('dropdown', 'htmlelements');
$objFeatureBox = & $this->newObject('featurebox', 'navigation');
$objTable = & $this->newObject('htmltable', 'htmlelements');


$objButton2->value = $this->_objLanguage->languageText('word_cancel');
$objButton2->setOnClick('javascript:document.location = \''.$this->uri(null).'\'');
$str = 'I NEED THE LINKS FROM THE MODULE NOW!!!!<br/>';

if(count($links) > 0 )
{
    $objTable->width = '60%';
    
    $objTable->startHeaderRow();
    $headerRow = array('Menu Text', 'Description');
    $objTable->addHeaderCell('Select');
    $objTable->addHeaderCell('Menu Text');
    $objTable->addHeaderCell('Type');
    
    $objTable->endHeaderRow();
    $rowcount = 0;
    foreach ($links as $link)
    {
        $oddOrEven = ($rowcount == 0) ? "even" : "odd";
        
       // $checkbox = $this->newObject('checkbox', 'htmlelements');
        $objCheckBox->value=$link['itemid'].'==='.$link['menutext'].'==='. $link['description'];
        $objCheckBox->cssId = 'mod_'.$link['moduleid'];
        $objCheckBox->name = 'selecteditems[]';
        $objCheckBox->cssClass = 'f-checkbox';
		        
        $tableRow = array($objCheckBox->show(), $link['menutext'], $link['description']);
        $objTable->addRow($tableRow, $oddOrEven);
        $rowcount = ($rowcount == 0) ? 1 : 0;
    }
    
    
    $objButton->value = $this->_objLanguage->languageText('word_next');
    $objButton->setToSubmit();

    $objForm->action  = $this->uri(array('action' => 'saveadd', 'moduleid' => $this->getParam('moduleid')));
    
    $objForm->addToForm($objTable);
    $objForm->addToForm($objButton2);
    $objForm->addToForm($objButton);
   
    $str = $objForm->show();
} else {
   $str = '<div align="center" style="font-size:large;font-weight:bold;color:#CCCCCC;font-family: Helvetica, sans-serif;">No links are available for </div>';
   $str .= $objButton2->show();
}


echo $objFeatureBox->show('Step 2 : Select Items', $str);


?>