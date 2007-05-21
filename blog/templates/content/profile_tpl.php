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
if($this->objUser->isLoggedIn())
{
	if(isset($profile))
	{
		$middleColumn .= $this->objblogOps->profileEditor($userid, $profile);
	}
	else {
		$middleColumn .= $this->objblogOps->profileEditor($userid);
	}
}
//dump the cssLayout to screen
$cssLayout->setMiddleColumnContent($middleColumn);
$cssLayout->setLeftColumnContent($leftCol);
$cssLayout->setRightColumnContent($rightSideColumn);
echo $cssLayout->show();
?>