<?php
// Create an instance of the css layout class
$cssLayout = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
$cssLayout->setNumColumns(3);

//-------------------- LEFT COLUMN DATA ---------------------
$objH =& $this->getObject('htmlheading', 'htmlelements');
$objH->type=3;
$objH->str=$objLanguage->languageText("mod_formcatcher_name");
$leftSideColumn = $objH->show();
$leftSideColumn .= $objLanguage->languageText("mod_formcatcher_leftcolpostproc");


//-------------------- MIDDLE COLUMN DATA ---------------------


$objH->str=$objLanguage->languageText("word_results");
$middleColumn = $objH->show();
$middleColumn .=  $objLanguage->languageText("mod_formcatcher_result_" 
  . $this->getParam('processmethod', 'errornomethod')); 
if (isset($str)) {
    $middleColumn .= "<br /><hr />" . $str; 
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