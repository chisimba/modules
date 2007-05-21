<?php
//mail2friend template
$userid = $m2fdata['bloggerid'];
$cssLayout = &$this->newObject('csslayout', 'htmlelements');
// Set columns to 3
$cssLayout->setNumColumns(3);
$objUi = $this->getObject('blogui');
// left hand blocks
$leftCol = $objUi->leftBlocks($userid);
// right side blocks
$rightSideColumn = $objUi->rightBlocks($userid, NULL);
$middleColumn = NULL;
//load up a featurebox and display it nicely
$objFeatureBox = $this->getObject('featurebox', 'navigation');
//gooi the form with a message and the name thing with email address(es) to send to
$middleColumn .= $this->objblogOps->sendMail2FriendForm($m2fdata);
$cssLayout->setMiddleColumnContent($middleColumn);
$cssLayout->setLeftColumnContent($leftCol); //$leftMenu->show());
$cssLayout->setRightColumnContent($rightSideColumn);
echo $cssLayout->show();
?>