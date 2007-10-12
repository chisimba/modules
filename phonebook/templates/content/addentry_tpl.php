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

// Create link icon and link to view template
$objAddIcon = $this->newObject('geticon', 'htmlelements');
$objLink = $this->uri(array('action' => 'default'));
$objAddIcon->setIcon("comment_view", "gif");
$objAddIcon->alt = $objLanguage->languageText('mod_phonebook_return', 'phonebook');
$add = $objAddIcon->getAddIcon($objLink); 

// Create header with add icon
$pgTitle = &$this->getObject('htmlheading', 'htmlelements');
$pgTitle->type = 1;
$pgTitle->str = $objLanguage->languageText('mod_phonebook_return', 'phonebook')."&nbsp;" . $add;


 
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
	
	//value textfield
        $ct->startRow();
        $ctvlabel = new label($this->objLanguage->languageText('mod_phonebook_address', 'phonebook') . ':', 'input_cvalue');
        $ctv = new textinput('address');
        
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


$middleColumn = $pgTitle->show().$ret;
// Create link back to my view template
$objBackLink = &$this->getObject('link', 'htmlelements');
$objBackLink->link($this->uri(array('module' => 'phonebook')));
$objBackLink->link = $objLanguage->languageText('mod_phonebook_return', 'phonebook'); 



//add left column
$cssLayout->setLeftColumnContent($leftSideColumn);
$cssLayout->setRightColumnContent($rightSideColumn);
//add middle column
$cssLayout->setMiddleColumnContent($middleColumn);
echo $cssLayout->show();
?>
