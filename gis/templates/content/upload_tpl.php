<?php
$cssLayout = &$this->newObject('csslayout', 'htmlelements');
$objSideBar = $this->newObject('usermenu', 'toolbar');
$objFeatureBox = $this->newObject('featurebox', 'navigation');

// Set columns to 3
$cssLayout->setNumColumns(3);
$leftMenu = NULL;

$rightSideColumn = NULL;

$leftCol = NULL;
$middleColumn = NULL;

$leftCol .= $objSideBar->show();
$rightSideColumn .= "Please upload a zip file containing the .shp, .dbf and .shx files for the geometry";

$middleColumn .= $this->objGisOps->uploadDataFile();
$middleColumn .= "<br />"; // html break to make it look pretty...
$middleColumn .= "<img src='$themap'>";

$cssLayout->setMiddleColumnContent($middleColumn);
$cssLayout->setLeftColumnContent($leftCol); //$leftMenu->show());
$cssLayout->setRightColumnContent($rightSideColumn);
echo $cssLayout->show();
?>