<?php
//tburl template
$cssLayout = &$this->newObject('csslayout', 'htmlelements');
// Set columns to 3
$cssLayout->setNumColumns(3);
$middleColumn = NULL;
//load up a featurebox and display it nicely
$objFeatureBox = $this->getObject('featurebox', 'navigation');
$theurl = $objFeatureBox->show($this->objLanguage->languageText("mod_blog_tbheader","blog"), $theurl);
$middleColumn .= $theurl;
$userid = $this->objUser->userId();
$objUi = $this->getObject('blogui');
// left hand blocks
$leftCol = $objUi->leftBlocks($userid);
// right side blocks
$rightSideColumn = $objUi->rightBlocks($userid, NULL);
$cssLayout->setMiddleColumnContent($middleColumn);
$cssLayout->setLeftColumnContent($leftCol); //$leftMenu->show());
$cssLayout->setRightColumnContent($rightSideColumn);
echo $cssLayout->show();
?>