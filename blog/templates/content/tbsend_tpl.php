<?php
//tbsend template
$cssLayout = &$this->newObject('csslayout', 'htmlelements');
$userid = $this->objUser->userId();
$objUi = $this->getObject('blogui');
// left hand blocks
$leftCol = $objUi->leftBlocks($userid);
// right side blocks
$rightSideColumn = $objUi->rightBlocks($userid, NULL);
if($leftCol == NULL || $rightSideColumn == NULL)
{
	$cssLayout->setNumColumns(2);
}
else {
	$cssLayout->setNumColumns(3);
}

$middleColumn = NULL;
if ($this->objUser->isLoggedIn()) {
    $form = $this->objblogOps->sendTrackbackForm($tbarr);
    $middleColumn.= $form;
} else {
    $objFeatureBox = $this->getObject('featurebox', 'navigation');
    $middleColumn.= $objFeatureBox->show($this->objLanguage->languageText("mod_blog_plslogin", "blog") , $this->objLanguage->languageText("mod_blog_tblogin", "blog"));
}
if($leftCol == NULL)
{
	$leftCol = $rightSideColumn;
	$cssLayout->setMiddleColumnContent($middleColumn);
	$cssLayout->setLeftColumnContent($leftCol);
	//$cssLayout->setRightColumnContent($rightSideColumn);
	echo $cssLayout->show();
}
elseif($rightSideColumn == NULL)
{
	$cssLayout->setMiddleColumnContent($middleColumn);
	$cssLayout->setLeftColumnContent($leftCol);
	echo $cssLayout->show();
}
else {
	$cssLayout->setMiddleColumnContent($middleColumn);
	$cssLayout->setLeftColumnContent($leftCol);
	$cssLayout->setRightColumnContent($rightSideColumn);
	echo $cssLayout->show();
}
?>