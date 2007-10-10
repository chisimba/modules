<?php
// Create an instance of the css layout class
$cssLayout =& $this->newObject('csslayout', 'htmlelements');
// Set columns to 3
$cssLayout->setNumColumns(3);

// get the sidebar object
$this->leftMenu = $this->newObject('usermenu', 'toolbar');
$this->objNavigate = $this->getObject('navigate');
// Initialize left column
$leftSideColumn = $this->leftMenu->show();
$rightSideColumn = NULL;
$middleColumn = NULL;
 
$this->objUser = $this->getObject('user', 'security');

//creating a form
$cform = new form('weight', $this->uri(array(
			'action' => 'weight'
		)));

//start a fieldset
$cfieldset = $this->getObject('fieldset', 'htmlelements');
        
$ct = $this->newObject('htmltable', 'htmlelements');
$ct->cellpadding = 5;

//value textfield
$ct->startRow();

$ctvlabel = new label($this->objLanguage->languageText('mod_conversions_value', 'conversions') . ':', 'input_cvalue');
$ctv = new textinput('value');

$ct->addCell($ctvlabel->show());
$ct->addCell($ctv->show());
$ct->endRow();
        
//conversions dropdown
$fromdrop = new dropdown('from');
$fromdrop->addOption(1, $this->objLanguage->languageText("mod_conversions_Kilograms", "conversions"));
$fromdrop->addOption(2, $this->objLanguage->languageText("mod_conversions_Grams", "conversions"));
$fromdrop->addOption(3, $this->objLanguage->languageText("mod_conversions_MetricTon", "conversions"));
$fromdrop->addOption(4, $this->objLanguage->languageText("mod_conversions_Pounds", "conversions"));
$fromdrop->addOption(5, $this->objLanguage->languageText("mod_conversions_Ounces", "conversions"));
$todrop = new dropdown('to');
$todrop->addOption(1, $this->objLanguage->languageText("mod_conversions_Kilograms", "conversions"));
$todrop->addOption(2, $this->objLanguage->languageText("mod_conversions_Grams", "conversions"));
$todrop->addOption(3, $this->objLanguage->languageText("mod_conversions_MetricTon", "conversions"));
$todrop->addOption(4, $this->objLanguage->languageText("mod_conversions_Pounds", "conversions"));
$todrop->addOption(5, $this->objLanguage->languageText("mod_conversions_Ounces", "conversions"));
$ct->startRow();
$flabel = new label($this->objLanguage->languageText('mod_conversions_convertfrom', 'conversions') . ':', 'input_convertfrom');
$ct->addCell($flabel->show());
$ct->addCell($fromdrop->show());
$ct->endRow();

$ct->startRow();
$tlabel = new label($this->objLanguage->languageText('mod_conversions_convertto', 'conversions') . ':', 'input_convertto');
$ct->addCell($tlabel->show());
$ct->addCell($todrop->show());
$ct->endRow();

//end off the form and add the buttons
$this->objconvButton = new button($this->objLanguage->languageText('mod_conversions_convert', 'conversions'));
$this->objconvButton->setValue($this->objLanguage->languageText('mod_conversions_convert', 'conversions'));
$this->objconvButton->setToSubmit();

$cfieldset->addContent($ct->show());
$cform->addToForm($cfieldset->show());
$cform->addToForm($this->objconvButton->show());
$cform = $cform->show();

$objFeatureBox = $this->getObject('featurebox', 'navigation');
$ret = $objFeatureBox->showContent($this->objLanguage->languageText("mod_conversions_weightconverter", "conversions") , $cform);
  


//Putting a navigation form and the answer in the right column
$value = $this->getParam('value');
$from = $this->getParam('from');
$to = $this->getParam('to');
$type = "weight";
$rightSideColumn = $this->objNavigate->conversionsFormNav();
$middleColumn = $ret.$this->objNavigate->answer($value, $from, $to, $type); 
//add left column
$cssLayout->setLeftColumnContent($leftSideColumn);

//add right column
$cssLayout->setRightColumnContent($rightSideColumn);

//add middle column
$cssLayout->setMiddleColumnContent($middleColumn);
echo $cssLayout->show();
?>
