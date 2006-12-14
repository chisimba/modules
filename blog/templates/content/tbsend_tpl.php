<?php
//tbsend template
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

if($this->objUser->isLoggedIn())
{
	$form = $this->objblogOps->sendTrackbackForm($tbarr);
	$leftCol .= $objSideBar->show();
	$middleColumn .= $form;

}
else {
	$leftCol = $this->objblogOps->loginBox(TRUE);
	$objFeatureBox = $this->getObject('featurebox', 'navigation');
	$middleColumn .= $objFeatureBox->show($this->objLanguage->languageText("mod_blog_plslogin", "blog"), $this->objLanguage->languageText("mod_blog_tblogin", "blog"));
}

$cssLayout->setMiddleColumnContent($middleColumn);
$cssLayout->setLeftColumnContent($leftCol); //$leftMenu->show());
$cssLayout->setRightColumnContent($rightSideColumn);
echo $cssLayout->show();
?>