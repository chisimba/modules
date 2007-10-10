<?php
// Create an instance of the css layout class
$cssLayout = $this->newObject('csslayout', 'htmlelements');
// Set columns to 3
$cssLayout->setNumColumns(2);

// get the sidebar object
$this->leftMenu = $this->newObject('usermenu', 'toolbar');

// Initialize left column
$leftSideColumn = $this->leftMenu->show();
$rightSideColumn = NULL;
$middleColumn = NULL;

$objTableClass = $this->newObject('htmltable', 'htmlelements');
$objTableClass->cellspacing = "2";
$objTableClass->cellpadding = "2";
$objTableClass->width = "90%";
$objTableClass->attributes = "border='0'";
// Create the array for the table header
$tableRow = array();
$tableHd[] = $objLanguage->languageText('mod_phonebook_contact', 'phonebook');
$tableHd[] = $objLanguage->languageText('mod_phonebook_email', 'phonebook');
$tableHd[] = $objLanguage->languageText('mod_phonebook_landline', 'phonebook');
$tableHd[] = $objLanguage->languageText('mod_phonebook_cellnumber', 'phonebook');
$tableHd[] = $objLanguage->languageText('mod_phonebook_address', 'phonebook');
$tableHd[] = $objLanguage->languageText('mod_phonebook_update', 'phonebook');
// Create the table header for display
$objTableClass->addHeader($tableHd, "heading");
$index = 0;
$rowcount = 0;
print_r($records);

$ret = $objTableClass->show();

/*       
$cform = new form('phonebook', $this->uri(array('action' => 'view')));

$this->objDbContacts->listAll($this->objUser->userId());

	$this->objconvButton = new button($this->objLanguage->languageText('mod_phonebook_linktoadd', 'phonebook'));
	$this->objconvButton->setValue($this->objLanguage->languageText('mod_phonebook_linktoadd', 'phonebook'));
	$this->objconvButton->setToSubmit();

//I have no idea where the problem is.
	$cform->addToForm($this->objdbContacts->show());
	$cform->addToForm($this->objconvButton->show());
	$cform = $cform->show();

       
$objFeatureBox = $this->getObject('featurebox', 'navigation');
$ret = $objFeatureBox->showContent($this->objLanguage->languageText('mod_phonebook_linktoadd', 'phonebook'), $cform);
//foreach($contacts as $contact)
//{
    // check that the dude is online...
 //   if($this->objUser->isActive($contact['userid']))
  //  { 

 //   }

//}
*/

$middleColumn = $ret;

//add left column
$cssLayout->setLeftColumnContent($leftSideColumn);
$cssLayout->setRightColumnContent($rightSideColumn);
//add middle column
$cssLayout->setMiddleColumnContent($middleColumn);
echo $cssLayout->show();
?>
