<?php

/**
*Template for the default page of the WikiWriter 
*
*@author  Ryan Whitney, ryan@greenlikeme.org
*@package wikiwriter
*/

// Load the necessary objects
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('textarea', 'htmlelements');
$this->loadClass('form', 'htmlelements');
$this->loadClass('htmlTable', 'htmlelements');

// Instantiate the Form and build
$objForm = new form('Publisher', $this->uri(array()));

	// Add hidden action field
	$objForm->addToForm(new textinput('action', 'publish', 'hidden'));
	
	// Create a Table for form layout
	$objFormTable = new htmltable();
		$objFormTable->border = 1;
		$objFormTable->cellspacing = 1;
		$objFormTable->cellpadding = 4;

		// On the first row, add the textarea for taking URLs
		$objFormTable->startRow();
			$taURLList = new textarea('URLList', NULL, 10, 60);
			$objFormTable->addCell($taURLList->show());
		$objFormTable->endRow();

		// On the second row, add the submit button
		$objFormTable->startRow();
			$btnSubmit = new button('publish', 'Publish PDF');
			$btnSubmit->setToSubmit();
			$objFormTable->addCell($btnSubmit->show());
		$objFormTable->endRow();

	// Add table to the Form
	$objForm->addToForm($objFormTable->show());

// Print Form 
echo $objForm->show();


?>
