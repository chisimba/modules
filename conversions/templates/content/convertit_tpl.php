<?php
// Create an instance of the css layout class
$cssLayout = &$this->newObject('csslayout', 'htmlelements');
// Set columns to 3
$cssLayout->setNumColumns(3);
// get the sidebar object
$this->leftMenu = $this->newObject('usermenu', 'toolbar');
// Initialize left column
$leftSideColumn = $this->leftMenu->show();
$rightSideColumn = NULL;
$middleColumn = NULL;
$this->objUser = $this->getObject('user', 'security');
$this->objNav = $this->getObject('navigate');
if ($goTo == NULL) {
    $description = wordwrap($this->objLanguage->languageText("mod_conversions_description", "conversions") , 100, "<br />\n");
    $objFeatureBox = $this->getObject('featurebox', 'navigation');
    $ret = $objFeatureBox->showContent($this->objLanguage->languageText("mod_conversions_mainPage", "conversions") , $description);
    $middleColumn = $ret;
} elseif ($goTo == "dist") {
    $middleColumn = $this->objNav->dist();
    $middleColumn.= $this->objNav->answer($value, $from, $to, $action);
} elseif ($goTo == "temp") {
    $middleColumn = $this->objNav->temp();
    $middleColumn.= $this->objNav->answer($value, $from, $to, $action);
} elseif ($goTo == "vol") {
    $middleColumn = $this->objNav->vol();
    $middleColumn.= $this->objNav->answer($value, $from, $to, $action);
} elseif ($goTo == "weight") {
    $middleColumn = $this->objNav->weight();
    $middleColumn.= $this->objNav->answer($value, $from, $to, $action);
}
$rightSideColumn = $this->objNav->conversionsFormNav();
//add left column
$cssLayout->setLeftColumnContent($leftSideColumn);
$cssLayout->setRightColumnContent($rightSideColumn);
//add middle column
$cssLayout->setMiddleColumnContent($middleColumn);
echo $cssLayout->show();
?>
