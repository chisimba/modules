<?php
//edit cats
$objUi = $this->getObject('blogui');
// left hand blocks
$leftCol = $objUi->leftBlocks($userid);
// right side blocks
$rightSideColumn = $objUi->rightBlocks($userid, NULL);
$cssLayout = $this->newObject('csslayout', 'htmlelements');
if ($leftCol == NULL || $rightSideColumn == NULL) {
    $cssLayout->setNumColumns(2);
} else {
    $cssLayout->setNumColumns(3);
}
$middleColumn = NULL;
//get the posts manager
$middleColumn = $this->objblogOps->catedit($catarr, $userid, $catid);
if ($leftCol == NULL) {
    $leftCol = $rightSideColumn;
    $cssLayout->setMiddleColumnContent($middleColumn);
    $cssLayout->setLeftColumnContent($leftCol);
    //$cssLayout->setRightColumnContent($rightSideColumn);
    echo $cssLayout->show();
} elseif ($rightSideColumn == NULL) {
    $cssLayout->setMiddleColumnContent($middleColumn);
    $cssLayout->setLeftColumnContent($leftCol);
    echo $cssLayout->show();
} else {
    $cssLayout->setMiddleColumnContent($middleColumn);
    $cssLayout->setLeftColumnContent($leftCol);
    $cssLayout->setRightColumnContent($rightSideColumn);
    echo $cssLayout->show();
}
?>