<?php
//profile add/edit template
$cssLayout = &$this->newObject('csslayout', 'htmlelements');
// Set columns to 3
$cssLayout->setNumColumns(3);
$middleColumn = NULL;
$objUi = $this->getObject('blogui');
// left hand blocks
$leftCol = $objUi->leftBlocks($userid);
// right side blocks
$rightSideColumn = $objUi->rightBlocks($userid, NULL);
if ($this->objUser->isLoggedIn()) {
    if (isset($pagetoedit) && isset($check)) {
        //echo "editing a page";
        $middleColumn.= $this->objblogOps->pageEditor($userid, $check, $pagetoedit);
    } else {
        $middleColumn.= $this->objblogOps->pageEditor($userid, $check);
    }
}
//dump the cssLayout to screen
$cssLayout->setMiddleColumnContent($middleColumn);
$cssLayout->setLeftColumnContent($leftCol);
$cssLayout->setRightColumnContent($rightSideColumn);
echo $cssLayout->show();
?>