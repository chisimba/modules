<?php
$mode = $this->getParam("mode", NULL);
if ($mode == "plain") {
    echo $str;
} else {
	//Create an instance of the css layout class
	$cssLayout = $this->newObject('csslayout', 'htmlelements');
	//Set columns to 2
	$cssLayout->setNumColumns(2);
	
	//Initialize NULL content for the left side column
	$leftSideColumn = "";
	//Get the menu creator
	$objMenu = $this->getObject("leftmenu", "simplemap");
	//Add the left menu
	$leftSideColumn = $objMenu->show();
	
	//Add the templage heading to the main layer
	$objH = $this->getObject('htmlheading', 'htmlelements');
	//Heading H3 tag
	$objH->type=3; 
	$objH->str = $this->getParam("title", $objLanguage->languageText("mod_simplemap_title_viewmap", 'simplemap'));
	//Add the heading to the output string for the main display area
	$rightSideColumn = $objH->show();
	
	$rightSideColumn .= $str;
	
	//Add Left column
	$cssLayout->setLeftColumnContent($leftSideColumn);
	// Add Right Column
	$cssLayout->setMiddleColumnContent($rightSideColumn);
	//Output the content to the page
	echo $cssLayout->show();
}


?>  