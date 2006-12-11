<?php
//tburl template

$this->loadClass('href', 'htmlelements');
$tt = $this->newObject('domtt', 'htmlelements');
$cssLayout = &$this->newObject('csslayout', 'htmlelements');
$objSideBar = $this->newObject('usermenu', 'toolbar');
// Set columns to 3
$cssLayout->setNumColumns(3);
$leftMenu = NULL;

$rightSideColumn = NULL;

$leftCol = NULL;
$middleColumn = NULL;

//load up a featurebox and display it nicely
$objFeatureBox = $this->getObject('featurebox', 'navigation');
$theurl = $objFeatureBox->show($this->objLanguage->languageText("mod_blog_tbheader","blog"), $theurl);
$middleColumn .= $theurl;

if($this->objUser->isLoggedIn())
{
	$leftCol .= $objSideBar->show();
}
else {
	$leftCol = $this->objblogOps->loginBox(TRUE);
}

$cssLayout->setMiddleColumnContent($middleColumn);
$cssLayout->setLeftColumnContent($leftCol); //$leftMenu->show());
$cssLayout->setRightColumnContent($rightSideColumn);
echo $cssLayout->show();
?>