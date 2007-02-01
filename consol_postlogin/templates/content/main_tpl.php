<?php 
// Create Add icon
$objAddIcon = $this->newObject('geticon', 'htmlelements');
$objLink = $this->uri(array('action' => ''));


//object Add Icon image for Heading
$objAddIcon->setIcon("add", "gif");
$objAddIcon->alt = "Add Course";//$objLanguage->languageText('mod_buddies_addbuddy', 'buddies');
$add = $objAddIcon->getAddIcon($objLink); 

//Create header for consol course manager

$pgTitle = &$this->getObject('htmlheading', 'htmlelements');
$pgTitle->type = 1;
$pgTitle->str = "Consol Glass Home". "&nbsp;";

//Create link to add course categorytemplate
$objAddCatLink = &$this->newObject('link', 'htmlelements');
$objAddCatLink->link($this->uri(array('action' => 'notnull')));
$objAddCatLink->link = "Add Consol Glass Category";//$objLanguage->languageText('mod_buddies_addbuddy', 'buddies'); 
//Create link to add course categorytemplate
$objAddCourrseLink = &$this->newObject('link', 'htmlelements');
$objAddCourrseLink->link($this->uri(array('action' => 'addcourse')));
$objAddCourrseLink->link = "Add Consol Glass Course";//$objLanguage->languageText('mod_buddies_addbuddy', 'buddies'); 

// Create a table
$objTableClass = $this->newObject('htmltable', 'htmlelements');
$objTableClass->cellspacing = "2";
$objTableClass->cellpadding = "2";
$objTableClass->width = "70%";
$objTableClass->attributes = "border='0'";
// Create the array for the table header
$tableRow = array();
// Create the table header for display
$index = 0;

//functionality shoud loop thru existing courses and populate the table


//funtionality to be implemented will make three icons next to each
//other initials read of all categories then it will be 
$objTableClass->startRow();
// if counter < 3
$objTableClass->addCell('content 1', '', 'top', 'center');
$objTableClass->addCell('content 2', '', 'top', 'center');
$objTableClass->addCell('content 3', '', 'top', 'center');
//else end row and start row and add new ceel
$objTableClass->endRow();
//increment the row counter
$index++;


$middleColumnContent = "";
$middleColumnContent .= $pgTitle->show()."<br />";
$middleColumnContent .= $objAddCatLink->show()."<br />";
$middleColumnContent .= $objAddCourrseLink->show()."<br />";
$middleColumnContent .= "<p>"."</p>";
$middleColumnContent .= $objTableClass->show();

echo $middleColumnContent;

?>
