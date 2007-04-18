<?php
$objmsg = &$this->getObject('timeoutmessage', 'htmlelements');
$this->loadClass('href', 'htmlelements');
$cssLayout = &$this->newObject('csslayout', 'htmlelements');
$objSideBar = $this->newObject('usermenu', 'toolbar');
$objFeatureBox = $this->newObject('featurebox', 'navigation');

// Set columns to 3
$cssLayout->setNumColumns(2);

$leftMenu = NULL;
$rightSideColumn = NULL;
$leftCol = NULL;
$middleColumn = NULL;

if($this->objUser->isLoggedIn())
{
	$leftCol .= $objSideBar->show();
	
}
else {
	$leftCol = null;
	
}
$washer = $this->getObject('washout', 'utilities');
//echo $startdate;
$middleColumn = $this->objblogOps->parseTimeline("MONTH", $startdate, $tlurl); //$washer->parseText('[TIMELINE]<a href="'.$tlurl.'">blogtimeline</a>[/TIMELINE]');

$cssLayout->setMiddleColumnContent($middleColumn);
$cssLayout->setLeftColumnContent($leftCol); //$leftMenu->show());
$cssLayout->setRightColumnContent($rightSideColumn);

echo $cssLayout->show();
?>