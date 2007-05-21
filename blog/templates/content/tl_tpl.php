<?php
$cssLayout = &$this->newObject('csslayout', 'htmlelements');
$objFeatureBox = $this->newObject('featurebox', 'navigation');
// Set columns to 3
$cssLayout->setNumColumns(3);
$middleColumn = NULL;
$objUi = $this->getObject('blogui');
// left hand blocks
$leftCol = $objUi->leftBlocks($userid);
// right side blocks
$rightSideColumn = $objUi->rightBlocks($userid, NULL);
$washer = $this->getObject('washout', 'utilities');
$middleColumn = $this->objblogOps->parseTimeline("MONTH", $startdate, $tlurl); //$washer->parseText('[TIMELINE]<a href="'.$tlurl.'">blogtimeline</a>[/TIMELINE]');
$cssLayout->setMiddleColumnContent($middleColumn);
$cssLayout->setLeftColumnContent($leftCol); //$leftMenu->show());
$cssLayout->setRightColumnContent($rightSideColumn);
echo $cssLayout->show();
?>