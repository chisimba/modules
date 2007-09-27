<?php
// Create an instance of the css layout class
$cssLayout =& $this->newObject('csslayout', 'htmlelements');
// Set columns to 3
$cssLayout->setNumColumns(3);

// get the sidebar object
$this->leftMenu = $this->newObject('usermenu', 'toolbar');

// Initialize left column
$leftSideColumn = $this->leftMenu->show();
$rightSideColumn = NULL;
$middleColumn = NULL;

	$this->objUser = $this->getObject('user', 'security');
        
	$cform = new form('conversion', $this->uri(array(
            'action' => 'convert'
        )));

        //start a fieldset
        $cfieldset = $this->getObject('fieldset', 'htmlelements');
        // $cfieldset->setLegend($this->objLanguage->languageText('mod_conversions_formhead', 'conversions'));
        
	$ct = $this->newObject('htmltable', 'htmlelements');
        $ct->cellpadding = 5;
        
	//conversions dropdown
        $convdrop = new dropdown('converttype');
        $convdrop->addOption("cels2farenheit", $this->objLanguage->languageText("mod_conversions_cels2faren", "conversions"));
        $convdrop->addOption("cels2kelvin", $this->objLanguage->languageText("mod_conversions_cels2kelvin", "conversions"));
    
	$ct->startRow();
        $convlabel = new label($this->objLanguage->languageText('mod_conversions_convertfrom', 'conversions') . ':', 'input_convertfrom');
        $ct->addCell($convlabel->show());
        $ct->addCell($convdrop->show());
        $ct->endRow();

        //value textfield
        $ct->startRow();
        $ctvlabel = new label($this->objLanguage->languageText('mod_conversions_value', 'conversions') . ':', 'input_cvalue');
        $ctv = new textinput('value');
        
        $ct->addCell($ctvlabel->show());
        $ct->addCell($ctv->show());
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
        $ret = $objFeatureBox->showContent($this->objLanguage->languageText("mod_conversions_convertvalue", "conversions") , $cform);
  
$middleColumn = $ret; 



//add left column
$cssLayout->setLeftColumnContent($leftSideColumn);
$cssLayout->setRightColumnContent($rightSideColumn);
//add middle column
$cssLayout->setMiddleColumnContent($middleColumn);
echo $cssLayout->show();
?>
