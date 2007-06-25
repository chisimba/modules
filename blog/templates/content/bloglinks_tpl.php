<?php
//links add/edit template
$cssLayout = &$this->newObject('csslayout', 'htmlelements');
// Set columns to 3
$cssLayout->setNumColumns(3);
$middleColumn = NULL;
$userid = $this->objUser->userId();
$objUi = $this->getObject('blogui');
// left hand blocks
$leftCol = $objUi->leftBlocks($userid);
// right side blocks
$rightSideColumn = $objUi->rightBlocks($userid, NULL);
if ($this->objUser->isLoggedIn()) {
    if (!empty($editvars)) {
        $middleColumn.= $this->objblogOps->editBlinks(TRUE, $editvars);
    } else {
        $middleColumn.= $this->objblogOps->editBlinks(TRUE);
    }
}
//dump the cssLayout to screen
$cssLayout->setMiddleColumnContent($middleColumn);
$cssLayout->setLeftColumnContent($leftCol);
$cssLayout->setRightColumnContent($rightSideColumn);
echo $cssLayout->show();
?>