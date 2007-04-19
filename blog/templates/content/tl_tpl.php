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
	$leftCol .= $this->objblogOps->showFeeds($userid, TRUE);
	$leftCol .= $this->objblogOps->showProfile($userid);
	$leftCol .= $this->objblogOps->showAdminSection(TRUE);
	$leftCol .= $this->objblogOps->archiveBox($userid, TRUE);
	
}
else {
	$leftCol .= $this->objblogOps->loginBox(TRUE);
	//$leftCol .= $this->objblogOps->showFeeds($userid, TRUE);
	$leftCol .= $this->objblogOps->showProfile($userid);
	$leftCol .= $this->objblogOps->showBlogsLink(TRUE);
	$leftCol .= $this->objblogOps->archiveBox($userid, TRUE);
	
}
$washer = $this->getObject('washout', 'utilities');
//echo $startdate;
$middleColumn = $this->objblogOps->parseTimeline("MONTH", $startdate, $tlurl); //$washer->parseText('[TIMELINE]<a href="'.$tlurl.'">blogtimeline</a>[/TIMELINE]');

$cssLayout->setMiddleColumnContent($middleColumn);
$cssLayout->setLeftColumnContent($leftCol); //$leftMenu->show());
$cssLayout->setRightColumnContent($rightSideColumn);

echo $cssLayout->show();
?>