<?php
//edit cats
$cssLayout = &$this->newObject('csslayout', 'htmlelements');
// Set columns to 3
$cssLayout->setNumColumns(3);
$middleColumn = NULL;

$objUi = $this->getObject('blogui');
// left hand blocks
$leftCol = $objUi->leftBlocks($userid);
// right side blocks
$rightSideColumn = $objUi->rightBlocks($userid, NULL);

//get the posts manager
$middleColumn = $this->objblogOps->categoryEditor($userid);
$cssLayout->setMiddleColumnContent($middleColumn);
$cssLayout->setLeftColumnContent($leftCol);
$cssLayout->setRightColumnContent($rightSideColumn);
echo $cssLayout->show();
?>