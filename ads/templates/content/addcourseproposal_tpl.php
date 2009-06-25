<?php
$this->loadClass('htmltable','htmlelements');
$this->loadClass('form','htmlelements');
$this->loadClass('button','htmlelements');
$this->loadClass('textinput','htmlelements');



//constructs the table
$objTable = new htmltable();

$objTable->startRow();

$objTable->addCell($objLanguage->languageText('mod_ads_unitname','ads'), 150, NULL, 'left');
$objTable->endRow();
$objTable->startRow();
$coursenamefield =  new textinput('title', '');
$objTable->addCell($coursenamefield->show(),400,NULL,'left');

$objTable->endRow();



$objTable->row_attributes=' height="10"';
$buttons='';
/************** Build form **********************/
$saveButton = new button('save',$objLanguage->languageText('mod_ads_save','ads'));
$saveButton->setToSubmit();


$buttons.=$saveButton->show();
$cancelButton = new button('cancel','Cancel');
$actionUrl = $this->uri(array('action' => NULL));
$cancelButton->setOnClick("window.location='$actionUrl'");
$buttons.='&nbsp'.$cancelButton->show();
//$objForm = new form('FormName',$this->uri(array('action'=>'savestudent')));
$objForm = new form('FormName',$this->uri(array('action'=>'savecourseproposal')));
$objForm->addToForm($objTable->show());
$objForm->addToForm($buttons);


// Create an instance of the css layout class
$cssLayout = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
$cssLayout->setNumColumns(2);

$postLoginMenu  = $this->newObject('postloginmenu','toolbar');
$cssLayout->setLeftColumnContent($postLoginMenu->show());
$rightSideColumn.='<h1>'. $objLanguage->languageText('mod_ads_addcourseproposal','ads').'</h1>';
//Add the table to the centered layer
$rightSideColumn .= $objForm->show();
// Add Right Column
$cssLayout->setMiddleColumnContent($rightSideColumn);

//Output the content to the page
echo $cssLayout->show();
?>