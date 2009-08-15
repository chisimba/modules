<?php
$editForm = $this->objEdit->getResults($this->data,$this->status);

$toSelect = $this->objLanguage->languageText('mod_home_editLink','gift');
$form = $this->objHome->getLeftContent($toSelect, $this->getParam('action'), $this->getParam('archived'));

// Create an instance of the css layout class
$cssLayout = &$this->newObject('csslayout', 'htmlelements');// Set columns to 2
$cssLayout->setNumColumns(2);

$leftSideColumn = $form;
$cssLayout->setLeftColumnContent($leftSideColumn);
$rightSideColumn='<div style="padding:10px;">';

//Add the table to the centered layer
$rightSideColumn .= $editForm;
$rightSideColumn .= '</div>';
// Add Right Column
$cssLayout->setMiddleColumnContent($rightSideColumn);

//Output the content to the page
echo $cssLayout->show();
?>
