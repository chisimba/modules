<?php
//write post template
$cssLayout = &$this->newObject('csslayout', 'htmlelements');
// Set columns to 3
$cssLayout->setNumColumns(3);
$middleColumn = NULL;
//get the posts editor
$middleColumn = $this->objblogOps->postEditor($userid, NULL);
//geotag part
$middleColumn .= $this->objblogOps->geoTagForm();
$objUi = $this->getObject('blogui');
// left hand blocks
$leftCol = $objUi->leftBlocks($userid);
// right side blocks
$rightSideColumn = $objUi->rightBlocks($userid, NULL);
//$rightSideColumn .= $this->objblogOps->quickCats(TRUE);
//$rightSideColumn .= $this->objblogOps->showAdminSection(TRUE);//quickPost($userid, TRUE);
$cssLayout->setMiddleColumnContent($middleColumn);
$cssLayout->setLeftColumnContent($leftCol);
$cssLayout->setRightColumnContent($rightSideColumn);
echo $cssLayout->show();
?>