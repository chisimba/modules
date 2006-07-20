<?php
// Create an instance of the css layout class
$cssLayout = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
$cssLayout->setNumColumns(3);

// Add the heading to the content of the middle column
$objH =& $this->getObject('htmlheading', 'htmlelements');
$objH->type=3;
$objH->str=$objLanguage->languageText("mod_formcatcher_complete");
$leftSideColumn = $objH->show();
//Set the content of the left side column
$leftSideColumn .= $objLanguage->languageText("mod_formcatcher_doforminfo");


//Add the form
$middleColumn = "";
//Add the form
if (isset($str)) {
    $middleColumn .= $str; 
}



//------------------- RENDER IT OUT -------------------------

// Add Left column
$cssLayout->setLeftColumnContent($leftSideColumn);

// Add middle Column
$cssLayout->setMiddleColumnContent($middleColumn);

// Add Right Column
$cssLayout->setRightColumnContent($objLanguage->languageText("mod_formcatcher_defaultright"));

//Output the content to the page
echo $cssLayout->show();

?>