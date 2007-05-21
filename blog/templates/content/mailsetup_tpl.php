<?php
$userid = $this->objUser->userId();
$objUi = $this->getObject('blogui');
// left hand blocks
$leftCol = $objUi->leftBlocks($userid);
// right side blocks
$rightSideColumn = $objUi->rightBlocks($userid, NULL);
$cssLayout = &$this->newObject('csslayout', 'htmlelements');
// Set columns to 3
$cssLayout->setNumColumns(3);
$middleColumn = NULL;
//check if there are previous values
$dsnparse = $this->objblogOps-> getMailDSN();
if($dsnparse == FALSE)
{
	$middleColumn .= $this->objblogOps->showMailSetup(TRUE);
}
else {
	$middleColumn .= $this->objblogOps->showMailSetup(TRUE, $dsnparse);
}
$cssLayout->setMiddleColumnContent($middleColumn);
$cssLayout->setLeftColumnContent($leftCol);
$cssLayout->setRightColumnContent($rightSideColumn);
echo $cssLayout->show();
?>