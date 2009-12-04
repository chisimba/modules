<?php

	//load class
	$this->loadclass('link','htmlelements');
	
	$objbackLink = new link($this->uri(array('action'=>'back')));
    $objbackLink-> link = 'Back';

	//CSS
	// Create an instance of the css layout class
	$cssLayout = &$this->newObject('csslayout', 'htmlelements');
	// Set columns to 2
	$cssLayout->setNumColumns(2);
	// get the links on the left
	$form = $this->objHome->getLeftContent($toSelect, $this->getParam('action'), $this->getParam('archived'));
	// links are displayed on the left
	$leftSideColumn = $form;
	$cssLayout->setLeftColumnContent($leftSideColumn);
	
	// Add the table to the centered layer and a message of database functionality
	$rightSideColumn =  '<h1>This  is the gift policy</h1><div id="grouping-grid"><br /></div>'.$objbackLink->show();
	$cssLayout->setMiddleColumnContent($rightSideColumn);
	

	// Output the content to the page
	echo $cssLayout->show();

?>
