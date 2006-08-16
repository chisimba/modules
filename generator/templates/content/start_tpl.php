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
$objH->str=$objLanguage->languageText("mod_generator_startheading", "generator");
$middleColumn = $objH->show();

//Add the form to the template
$objStart = $this->getObject('tpstart');
$middleColumn .= $objLanguage->languageText("mod_generator_starttext","generator");
//$objStart->show();


//Variable for the rightside column text

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