<?php

//the main template creates a generated list of links
//
$objH = & $this->newObject('htmlheading', 'htmlelements');
$objTable = & $this->newObject('htmltable', 'htmlelements');
$objIcon = & $this->newObject('geticon', 'htmlelements');
$objLink = & $this->newObject('link', 'htmlelements');
$objForm = & $this->newObject('form', 'htmlelements');
$objForm2 = & $this->newObject('form', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$objInput = new textinput();//& $this->newObject('textinput', 'htmlelements');
$objFeatureBox = & $this->newObject('featurebox', 'navigation');
$inpButton =  $this->newObject('button','htmlelements');

//the heading
$objH->str = ucwords($this->_objLanguage->code2Txt("mod_contextdesigner_toolbarname",'contextdesigner',array('context'=>'Course')));
$objH->type = 1;

echo $objH->show();//'the generated list of links will show here';

//the back button
//Button
$inpButton->cssClass = 'f-submit';
$inpButton->setValue('Back');
$inpButton->setToSubmit();
//administer context
//enter context
$icon = $this->newObject('geticon', 'htmlelements');
$objLink->href = $this->uri(array('action' => 'admincontext', 'contextcode' => $context['contextcode']), 'contextadmin');
$icon->setModuleIcon('contextadmin');
$icon->alt = 'Administer Course';
$objLink->link = $icon->show();

//Form
$objForm2->name = 'impfrm';
$objForm2->action = $this->uri(array('action' => 'default'));
$objForm2->addToForm($objLink->show()."Context Admin".'<br/>');


if(is_array($linkList) && $linkList > 0)
{
    $objForm->action = $this->uri(array('action' => 'reorder'));
    $objForm->name = 'listform';
   // $objForm->id = 'listform';
    $objIcon->setIcon('save_submit');
    $objLink->href = 'javascript: document.forms[\'listform\'].submit()';
   // $objLink->extra = ' onclick="javascript:document.listform.submit();"' ;
    $objLink->link = $objIcon->show();
    
    $objTable->width = '60%';
    
    $objTable->startHeaderRow();
    $headerRow = array('Menu Text', 'Description');
    //$objTable->addHeaderCell('Select');
    $objTable->addHeaderCell('#');
    $objTable->addHeaderCell('Menu Text');
    $objTable->addHeaderCell('Type');
    $objTable->addHeaderCell('Published');
    
    $objTable->addHeaderCell('Reorder');
    $objTable->addHeaderCell('Order'.$objLink->show());
    $objTable->addHeaderCell('&nbsp;');
    
    
    $objTable->endHeaderRow();
    $rowcount = 0;
    $i=1;
    foreach ($linkList as $link)
    {
        $oddOrEven = ($rowcount == 0) ? "even" : "odd";
        
        //published
        if($link['access'] == 'Published')
        {
            $objIcon->setIcon('ok','png');
            $objLink->href = $this->uri(array('action' => 'changeaccess' , 'value' => 'Unpublish', 'id' => $link['id']));
            $objLink->link = $objIcon->show();
        } else {
            $objIcon->setIcon('failed','png');
            $objLink->href = $this->uri(array('action' => 'changeaccess' , 'value' => 'Published', 'id' => $link['id']));
            $objLink->link = $objIcon->show();
        }
        $published =  $objLink->show();
        
        //order        
        $order = $this->_objUtils->getOrderIcons($link['id'], $link['linkorder']);
        
        //reorder
        $objInput->name = 'reorder[]';
        $objInput->value = $link['linkorder'];
        $objInput->size = 4;
        $objInput->extra = ' style="text-align: center; border : 1px solid #ccc;
                                z-index: -3;
                                font-size: 11px;" ';
        $reorder = $objInput->show();
        
        //admin
        $objIcon->setIcon('delete');
        $objLink->href = $this->uri(array('action' => 'deletelink', 'id' => $link['id']));
        $objLink->link = $objIcon->show();
        
        $admin = $objLink->show();
        
		$objIcon->setModuleIcon($link['moduleid']);        
        $tableRow = array($i,
                            ' '.$objIcon->show().' '. $link['menutext'], 
                            $this->_objDBContextModules->getModuleName($link['moduleid']),
                            $published, 
                            $order, 
                            $reorder, 
                            $admin);
        $objTable->addRow($tableRow, $oddOrEven);
        $rowcount = ($rowcount == 0) ? 1 : 0;
        $i++;
    }
    
    $objForm->addToForm($objTable);
    $str =$objForm->show();
} else {
    
    $str = '<div align="center" style="font-size:large;font-weight:bold;color:#CCCCCC;font-family: Helvetica, sans-serif;">No items are available</div>';
}

echo $objFeatureBox->show('Items', $str);

$objLink->href = $this->uri(array('action' => 'add'));
$objLink->link = 'Add new Links ';

echo $objLink->show().$objIcon->getAddIcon($this->uri(array('action' => 'add')));
print $objForm2->show().'<br/>';
?>