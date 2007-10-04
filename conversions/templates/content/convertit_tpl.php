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
        // $cfieldset->setLegend($this->objLanguage->languageText('mod_conversions_head', 'conversions'));
        
	$ct = $this->newObject('htmltable', 'htmlelements');
        $ct->cellpadding = 5;
	//to dropdown
        $todrop = new dropdown('to');
        $todrop->addOption(1, $this->objLanguage->languageText("mod_conversions_Distance", "conversions"));
        $todrop->addOption(2, $this->objLanguage->languageText("mod_conversions_Temperature", "conversions"));
        $todrop->addOption(3, $this->objLanguage->languageText("mod_conversions_Volume", "conversions"));
        $todrop->addOption(4, $this->objLanguage->languageText("mod_conversions_Weight", "conversions"));
        $ct->startRow();
        $tlabel = new label($this->objLanguage->languageText('mod_conversions_goTo', 'conversions') . ':', 'input_convertto');
        $ct->addCell($tlabel->show());
        $ct->addCell($todrop->show());
        $ct->endRow();


	//end off the form and add the buttons
        $this->objconvButton = new button($this->objLanguage->languageText('mod_conversions_goTo', 'conversions'));
        $this->objconvButton->setValue($this->objLanguage->languageText('mod_conversions_goTo', 'conversions'));
        $this->objconvButton->setToSubmit();
	$cfieldset->addContent($ct->show());
        $cform->addToForm($cfieldset->show());
        $cform->addToForm($this->objconvButton->show());
        $cform = $cform->show();
        
	$objFeatureBox = $this->getObject('featurebox', 'navigation');
        $ret = $objFeatureBox->showContent($this->objLanguage->languageText("mod_conversions_goTo", "conversions") , $cform);

  
$middleColumn = $ret; 




//add left column
$cssLayout->setLeftColumnContent($leftSideColumn);
$cssLayout->setRightColumnContent($rightSideColumn);
//add middle column
$cssLayout->setMiddleColumnContent($middleColumn);
echo $cssLayout->show();
?>
