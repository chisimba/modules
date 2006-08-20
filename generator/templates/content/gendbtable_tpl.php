<?php
// Create an instance of the css layout class
$cssLayout = & $this->newObject('csslayout', 'htmlelements');
// Set columns to 3
$cssLayout->setNumColumns(3);
//Create the wizzard links object
$objWiz = $this->getObject('wizlinks');

//Put in the standard left column text
$leftSideColumn = $objWiz->putStandardLeftTxt();

// Add the heading to the content
$objH =& $this->getObject('htmlheading', 'htmlelements');
//Heading <h3>
$objH->type=3; 
$objH->str=$objLanguage->languageText("mod_generator_page" . $page . "_instructions", "generator");
$middleColumn = $objH->show();

//Get the table & class names
$table = $this->getParam('tablename', 'tbl_users');
$className = $this->getParam('databaseclass', '{UNSPECIFIED_CLASS_NAME_IN_GENDBTABLE_TPL}');

//---Do the work of generating the code
//Get an instance of the dbtable class generator
$objDbTb = $this->getObject('gendbtable');
//Get an instance of the schema generator
$objSchema = $this->getObject('getschema');
//Get the fields
$arFlds = $objSchema->getFieldNamesAsArray($table);
//Pass the fields to the data object
$objDbTb->arrayOfFields = $arFlds;




$middleColumn .= "<textarea name=\"datamodel\" cols=\"90\" rows=\"30\">" 
  . $objDbTb->generate($className) . "</textarea>";

//Variable for the rightside column text
$objWiz = $this->getObject('wizlinks');
$rightSideColumn = $objWiz->show();

//------------------- RENDER IT OUT -------------------------

// Add Left column
$cssLayout->setLeftColumnContent($leftSideColumn);

// Add middle Column
$cssLayout->setMiddleColumnContent($middleColumn);

// Add Right Column
$cssLayout->setRightColumnContent($rightSideColumn);

//Output the content to the page
echo $cssLayout->show();
?>