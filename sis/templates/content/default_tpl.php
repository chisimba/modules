<?
$cssLayout = &$this->newObject('csslayout', 'htmlelements');
$objSideBar = $this->newObject('usermenu', 'toolbar');
$objFeatureBox = $this->newObject('featurebox', 'navigation');
$objSF = $this->getObject('sisforms');
// Set columns to 3
$cssLayout->setNumColumns(3);
$leftMenu = NULL;
$leftCol = NULL;
$middleColumn = NULL;
$rightSideColumn = $objSF->parentMenu(TRUE);

$leftCol .= $objSideBar->show();

echo $message;
$middleColumn = $objSF->listKids();

$cssLayout->setMiddleColumnContent($middleColumn);
$cssLayout->setLeftColumnContent($leftCol); //$leftMenu->show());
$cssLayout->setRightColumnContent($rightSideColumn);
echo $cssLayout->show();
?>
