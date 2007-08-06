<?php
//write post template
$objUi = $this->getObject('blogui');
// left hand blocks
$leftCol = $objUi->leftBlocks($userid);
// right side blocks
$rightSideColumn = $objUi->rightBlocks($userid, NULL);
$cssLayout = $this->newObject('csslayout', 'htmlelements');
if($leftCol == NULL || $rightSideColumn == NULL)
{
	$cssLayout->setNumColumns(2);
}
else {
	$cssLayout->setNumColumns(3);
}
$objSideBar = $this->newObject('sidebar', 'navigation');

//get the posts manager
$middleColumn = $this->objblogOps->managePosts($userid);

if($leftCol == NULL)
{
	$leftCol = $rightSideColumn;
	$cssLayout->setMiddleColumnContent($middleColumn);
	$cssLayout->setLeftColumnContent($leftCol);
	//$cssLayout->setRightColumnContent($rightSideColumn);
	echo $cssLayout->show();
}
elseif($rightSideColumn == NULL)
{
	$cssLayout->setMiddleColumnContent($middleColumn);
	$cssLayout->setLeftColumnContent($leftCol);
	echo $cssLayout->show();
}
else {
	$cssLayout->setMiddleColumnContent($middleColumn);
	$cssLayout->setLeftColumnContent($leftCol);
	$cssLayout->setRightColumnContent($rightSideColumn);
	echo $cssLayout->show();
}
?>