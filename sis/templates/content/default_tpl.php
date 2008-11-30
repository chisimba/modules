<?
$cssLayout = &$this->newObject('csslayout', 'htmlelements');
$objSideBar = $this->newObject('usermenu', 'toolbar');
$objFeatureBox = $this->newObject('featurebox', 'navigation');

// Set columns to 3
$cssLayout->setNumColumns(3);
$leftMenu = NULL;
$leftCol = NULL;
$middleColumn = NULL;
$rightSideColumn = 'Please complete your profile.';

$leftCol .= $objSideBar->show();

$objSF = $this->getObject('sisforms');
$middleColumn = $objSF->profileForm('123', FALSE);

$cssLayout->setMiddleColumnContent($middleColumn);
$cssLayout->setLeftColumnContent($leftCol); //$leftMenu->show());
$cssLayout->setRightColumnContent($rightSideColumn);
echo $cssLayout->show();
?>
