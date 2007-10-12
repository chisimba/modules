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
$objAddIcon->alt = $objLanguage->languageText('mod_phonebook_addicon', 'phonebook');
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


    /* Create delete icon
    $objDelIcon = $this->newObject('geticon', 'htmlelements'); 
    // Create delete action
    $delLink = array('action' => 'deleteentry',
        'id' => $id,
        'module' => 'phonebook',
        'confirm' => 'yes',
        );
    $deletephrase = $objLanguage->languageText('mod_phonebook_delete', 'phonebook');
    $conf = $objDelIcon->getDeleteIconWithConfirm('', $delLink, 'phonebook', $deletephrase);
*/

// Create the array for the table header
$tableRow = array();
$tableHd[] = $objLanguage->languageText('mod_phonebook_contact', 'phonebook');
$tableHd[] = $objLanguage->languageText('mod_phonebook_email', 'phonebook');
$tableHd[] = $objLanguage->languageText('mod_phonebook_landline', 'phonebook');
$tableHd[] = $objLanguage->languageText('mod_phonebook_cellnumber', 'phonebook');
$tableHd[] = $objLanguage->languageText('mod_phonebook_address', 'phonebook');
$tableHd[] = $objLanguage->languageText('mod_phonebook_delete', 'phonebook');
$tableHd[] = $objLanguage->languageText('mod_phonebook_edit', 'phonebook');
// Create the table header for display
$objTableClass = $this->newObject('htmltable', 'htmlelements');
$objTableClass->addHeader($tableHd, "heading");
$index = 0;
$rowcount = 0;
//print_r($records);

	foreach($records as $record){
	$rowcount ++;
	

// Set odd even colour scheme
    $class = ($rowcount % 2 == 0)?'odd':'even';
    $objTableClass->startRow(); 
		
		    
		
		
		//add first name
		$username = $record['firstname'] . '&nbsp;' . $record['lastname'];
		$records == $objUser->userId();
		$objTableClass->addCell($username, '', 'center', 'center', $class);

		//add e-mail
		$email = $record['emailaddress'];
		$records == $objUser->userId();
		$objTableClass->addCell($email, '', 'center', 'center', $class);

		//add landline
		$landline = $record['landlinenumber'];
		$records == $objUser->userId();
		$objTableClass->addCell($landline, '', '', 'center', $class);

		//add cell number
		$cell = $record['cellnumber'];
		$records == $objUser->userId();
		$objTableClass->addCell($cell, '', 'center', 'center', $class);

		//add address
		$address = $record['address'];
		$records == $objUser->userId();
		$objTableClass->addCell($address, '', 'center', 'center', $class);

		//add delete	   
		 $objDelIcon = $this->newObject('geticon', 'htmlelements'); 
    		// Create delete action
		 $delLink = array('action' => 'deleteentry','confirm' => 'yes',
	        );
    		
		$deletephrase = $objLanguage->languageText('mod_phonebook_delete', 'phonebook');
    $conf = $objDelIcon->getDeleteIconWithConfirm('', $delLink, 'phonebook', $deletephrase);
		$update = $conf;
		$records == $objUser->userId();
		$objTableClass->addCell($update, '', '', '', $class);


    // Create edit icon and action
	//	$objEditIcon = $this->newObject('geticon', 'htmlelements'); 
    
		$this->loadClass('link', 'htmlelements');
		$objIcon = $this->newObject('geticon', 'htmlelements');
		$link = new link ($this->uri(array('action'=>'link1',
			'id'=>'editentry')));
		$objIcon->setIcon('edit');
		$link->link = $objIcon->show();
		$update = $link->show();
		
		$objTableClass->addCell($update, '', '', '', $class);
    $objTableClass->endRow();

	}//end of loop



$ret = $objTableClass->show();




$middleColumn =$pgTitle->show().$ret;

//add left column
$cssLayout->setLeftColumnContent($leftSideColumn);
$cssLayout->setRightColumnContent($rightSideColumn);
//add middle column
$cssLayout->setMiddleColumnContent($middleColumn);
echo $cssLayout->show();
?>
