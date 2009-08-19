<?php
$homeWelcome = $this->objHome->homePage();

$toSelect = ""; // Nothing to select

// get the links on the left
$form = $this->objHome->getLeftContent($toSelect, $this->getParam('action'), $this->getParam('archived'));

// Create an instance of the css layout class
$cssLayout = &$this->newObject('csslayout', 'htmlelements');// Set columns to 2
$cssLayout->setNumColumns(2);

// links are displayed on the left
$leftSideColumn = $form;
$cssLayout->setLeftColumnContent($leftSideColumn);

// Add the table to the centered layer and a message of database functionality
$rightSideColumn='<div style="padding:10px;">';
$rightSideColumn .= $homeWelcome;
$rightSideColumn .= '</div>';
// Add Right Column
$cssLayout->setMiddleColumnContent($rightSideColumn);

// Output the content to the page
echo $cssLayout->show();
?>
