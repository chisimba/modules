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
$className = $this->getParam('classname', '{UNSPECIFIED}');

//---Do the work of generating the code
//Get an instance of the dbtable class generator
$objGenWrap = $this->getObject('genwrapper');

//Render output to the textarea
$middleColumn .= "<textarea name=\"datamodel\" cols=\"78\" rows=\"30\">" 
  . $objGenWrap->generate($className) . "</textarea>";

//Variable for the rightside column text
$objWiz = $this->getObject('wizlinks');
$rightSideColumn .= $objWiz->show();

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