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


 
// Create add icon and link to add template
$objAddIcon = $this->newObject('geticon', 'htmlelements');
$objLink = $this->uri(array('action' => 'link'));
$objAddIcon->setIcon("add", "gif");
$objAddIcon->alt = $objLanguage->languageText('mod_phonebook_icon', 'phonebook');
$add = $objAddIcon->getAddIcon($objLink); 

// Create header with add icon
$pgTitle = &$this->getObject('htmlheading', 'htmlelements');
$pgTitle->type = 1;
$pgTitle->str = $objLanguage->languageText('mod_phonebook_head', 'phonebook')."&nbsp;" . $add;

// Create link to add template
//$objAddLink = &$this->newObject('link', 'htmlelements');
//$objAddLink->link($this->uri(array('action' => 'link')));
//$objAddLink->link = $objLanguage->languageText('mod_phonebook_icon', 'phonebook'); 
// Show the add link
//$objLink = &$this->getObject('link', 'htmlelements'); 


    // Create delete icon
    $objDelIcon = $this->newObject('geticon', 'htmlelements'); 
    // Create delete action
    $delLink = array('action' => 'deleteentry',
        'id' => $id,
        'module' => 'phonebook',
        'confirm' => 'yes',
        );
    $deletephrase = $objLanguage->languageText('mod_phonebook_delete', 'phonebook');
    $conf = $objDelIcon->getDeleteIconWithConfirm('', $delLink, 'phonebook', $deletephrase);


// Create the array for the table header
$tableRow = array();
$tableHd[] = $objLanguage->languageText('mod_phonebook_contact', 'phonebook');
$tableHd[] = $objLanguage->languageText('mod_phonebook_email', 'phonebook');
$tableHd[] = $objLanguage->languageText('mod_phonebook_landline', 'phonebook');
$tableHd[] = $objLanguage->languageText('mod_phonebook_cellnumber', 'phonebook');
$tableHd[] = $objLanguage->languageText('mod_phonebook_address', 'phonebook');
$tableHd[] = $objLanguage->languageText('mod_phonebook_update', 'phonebook');
// Create the table header for display
$objTableClass = $this->newObject('htmltable', 'htmlelements');
$objTableClass->addHeader($tableHd, "heading");
$index = 0;
$rowcount = 0;
print_r($records);

$ret = $objTableClass->show();

/* 
maybe script this out later?      
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

$middleColumn =$pgTitle->show().$ret.$conf;

//add left column
$cssLayout->setLeftColumnContent($leftSideColumn);
$cssLayout->setRightColumnContent($rightSideColumn);
//add middle column
$cssLayout->setMiddleColumnContent($middleColumn);
echo $cssLayout->show();
?>
