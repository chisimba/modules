<?php
$this->loadClass('checkbox' , 'htmlelements');
$this->loadClass('button' , 'htmlelements');
$this->loadClass('dropdown' , 'htmlelements');
$this->loadClass('textinput' , 'htmlelements');

$objH = & $this->newObject('htmlheading', 'htmlelements');
$objButton2 = new button();
$objButton = new button();
$objIcon = & $this->newObject('geticon', 'htmlelements');
$objLink = & $this->newObject('link', 'htmlelements');
$objForm = & $this->newObject('form', 'htmlelements');
$objCheckBox = new checkbox('selecteditems[]');//& $this->newObject('checkbox', 'htmlelements');

$objDropDown = new dropdown();//& $this->newObject('dropdown', 'htmlelements');
$objFeatureBox = & $this->newObject('featurebox', 'navigation');
$objTable = & $this->newObject('htmltable', 'htmlelements');


$objButton2->value = $this->_objLanguage->languageText('word_cancel');
$objButton2->setOnClick('javascript:document.location = \''.$this->uri(null).'\'');


if($links)
{
    $objTable->width = '60%';
    //$objTable->cellspacing='1';
    $objTable->cellpadding='10';
    
    $objTable->startHeaderRow();
    $headerRow = array('Menu Text', 'Description');
    $objTable->addHeaderCell('#');
    $objTable->addHeaderCell('Select');
    $objTable->addHeaderCell('Menu Text');
    $objTable->addHeaderCell('Type');
    
    $objTable->endHeaderRow();
    $rowcount = 0;
    $i=1;
//	var_dump($links);
    foreach ($links as $link)
    {
        $oddOrEven = ($rowcount == 0) ? "even" : "odd";
        
       // $checkbox = $this->newObject('checkbox', 'htmlelements');
        $objCheckBox->value=$link['itemid'].'==='.$link['menutext'].'==='. $link['description'];
        $objCheckBox->cssId = 'mod_'.$link['moduleid'];
        $objCheckBox->name = 'selecteditems[]';
        $objCheckBox->cssClass = 'f-checkbox';
        
        $objInput = new textinput($link['itemid']);//& $this->newObject('textinput', 'htmlelements');
        //$objInput->name = $link['itemid'];
        $objInput->value = '';
        $objInput->fldType = 'hidden';
        
       
        foreach($link['params'] as $key => $value)
        {
            $objInput->value .= $key.'=>'.$value.','; 
            
        }
      
		        
        $tableRow = array($i,$objCheckBox->show(), $link['menutext'], $link['description']. $objInput->show());
        $objTable->addRow($tableRow, $oddOrEven);
        $rowcount = ($rowcount == 0) ? 1 : 0;
        $i++;
        $objInput = null;
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