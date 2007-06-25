<?php
//tbsend template
//$this->loadClass('href', 'htmlelements');
//$tt = $this->newObject('domtt', 'htmlelements');
$cssLayout = &$this->newObject('csslayout', 'htmlelements');
//$objSideBar = $this->newObject('usermenu', 'toolbar');
// Set columns to 3
$cssLayout->setNumColumns(3);
$userid = $this->objUser->userId();
$objUi = $this->getObject('blogui');
// left hand blocks
$leftCol = $objUi->leftBlocks($userid);
// right side blocks
$rightSideColumn = $objUi->rightBlocks($userid, NULL);
$middleColumn = NULL;
if ($this->objUser->isLoggedIn()) {
    $form = $this->objblogOps->sendTrackbackForm($tbarr);
    $middleColumn.= $form;
} else {
    $objFeatureBox = $this->getObject('featurebox', 'navigation');
    $middleColumn.= $objFeatureBox->show($this->objLanguage->languageText("mod_blog_plslogin", "blog") , $this->objLanguage->languageText("mod_blog_tblogin", "blog"));
}
$cssLayout->setMiddleColumnContent($middleColumn);
$cssLayout->setLeftColumnContent($leftCol); //$leftMenu->show());
$cssLayout->setRightColumnContent($rightSideColumn);
echo $cssLayout->show();
?>