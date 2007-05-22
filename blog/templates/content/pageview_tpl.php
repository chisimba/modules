<?php
//tburl template
$cssLayout = &$this->newObject('csslayout', 'htmlelements');
// Set columns to 3
$cssLayout->setNumColumns(3);
// parse with the washout class
$washer = $this->getObject('washout', 'utilities');
$displaypage = $washer->parseText($page[0]['page_content']);
$middleColumn = NULL;
//load up a featurebox and display it nicely
$middleColumn .= stripslashes($displaypage);
$userid = $page[0]['userid'];
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