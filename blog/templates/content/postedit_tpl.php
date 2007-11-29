<?php
//post edit template
$cssLayout = $this->newObject('csslayout', 'htmlelements');
$middleColumn = NULL;
$objUi = $this->getObject('blogui');
// left hand blocks
$leftCol = $objUi->leftBlocks($userid);
// right side blocks
$rightSideColumn = $objUi->rightBlocks($userid, NULL);
if($leftCol == NULL || $rightSideColumn == NULL)
{
	$cssLayout->setNumColumns(2);
}
else {
	$cssLayout->setNumColumns(3);
}

//get the posts editor
$middleColumn = $this->objblogPosts->postEditor($userid, $editid);

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