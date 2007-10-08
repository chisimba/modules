<?php

/**
* Database Table Phonebook
* @author Jacques Cilliers<2618315@uwc.ac.za>
* @copyright 2007 University of the Western Cape
*/

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
        
	$cform = new form('phonebook', $this->uri(array(
            'action' => 'addentry'
        )));

        //start a fieldset
        $cfieldset = $this->getObject('fieldset', 'htmlelements');
       	$ct = $this->newObject('htmltable', 'htmlelements');
        $ct->cellpadding = 5;

	//value textfield
        $ct->startRow();
        $ctvlabel = new label($this->objLanguage->languageText('mod_phonebook_firstname', 'phonebook') . ':', 'input_cvalue');
        $ctv = new textinput('firstname');
        
        $ct->addCell($ctvlabel->show());
        $ct->addCell($ctv->show());
        $ct->endRow();

	//value textfield
        $ct->startRow();
        $ctvlabel = new label($this->objLanguage->languageText('mod_phonebook_lastname', 'phonebook') . ':', 'input_cvalue');
        $ctv = new textinput('lastname');
        
        $ct->addCell($ctvlabel->show());
        $ct->addCell($ctv->show());
        $ct->endRow();

	//value textfield
        $ct->startRow();
        $ctvlabel = new label($this->objLanguage->languageText('mod_phonebook_emailaddress', 'phonebook') . ':', 'input_cvalue');
        $ctv = new textinput('emailaddress');
        
        $ct->addCell($ctvlabel->show());
        $ct->addCell($ctv->show());
        $ct->endRow();

	//value textfield
        $ct->startRow();
        $ctvlabel = new label($this->objLanguage->languageText('mod_phonebook_landlinenumber', 'phonebook') . ':', 'input_cvalue');
        $ctv = new textinput('landlinenumber');
        
        $ct->addCell($ctvlabel->show());
        $ct->addCell($ctv->show());
        $ct->endRow();

	//value textfield
        $ct->startRow();
        $ctvlabel = new label($this->objLanguage->languageText('mod_phonebook_cellnumber', 'phonebook') . ':', 'input_cvalue');
        $ctv = new textinput('cellnumber');
        
        $ct->addCell($ctvlabel->show());
        $ct->addCell($ctv->show());
        $ct->endRow();

	//end off the form and add the buttons
        $this->objconvButton = new button($this->objLanguage->languageText('mod_phonebook_add', 'phonebook'));
        $this->objconvButton->setValue($this->objLanguage->languageText('mod_phonebook_add', 'phonebook'));
        $this->objconvButton->setToSubmit();
        
	$cfieldset->addContent($ct->show());
        $cform->addToForm($cfieldset->show());
        $cform->addToForm($this->objconvButton->show());
        $cform = $cform->show();
        
	$objFeatureBox = $this->getObject('featurebox', 'navigation');
        $ret = $objFeatureBox->showContent($this->objLanguage->languageText("mod_phonebook_add", "phonebook") , $cform);


$middleColumn = $ret;



//add left column
$cssLayout->setLeftColumnContent($leftSideColumn);
$cssLayout->setRightColumnContent($rightSideColumn);
//add middle column
$cssLayout->setMiddleColumnContent($middleColumn);
echo $cssLayout->show();
?>
