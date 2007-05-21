<?php
//profile view template
$cssLayout = &$this->newObject('csslayout', 'htmlelements');
// Set columns to 3
$cssLayout->setNumColumns(3);
$middleColumn = NULL;
if(!$this->objUser->isLoggedIn())
{
	if(isset($vprofile))
	{
		$middleColumn .= $this->objblogOps->displayProfile($userid, $vprofile);
	}
	else {
		$middleColumn .= $this->objLanguage->languageText("mod_blog_noprofile", "blog");
	}
}
else {
	if(isset($vprofile))
	{
		$middleColumn .= $this->objblogOps->displayProfile($userid, $vprofile);
	}
	else {
		$middleColumn .= $this->objLanguage->languageText("mod_blog_noprofile", "blog");
	}
}
$objUi = $this->getObject('blogui');
// left hand blocks
$leftCol = $objUi->leftBlocks($userid);
// right side blocks
$rightSideColumn = $objUi->rightBlocks($userid, NULL);
//dump the cssLayout to screen
$cssLayout->setMiddleColumnContent($middleColumn);
$cssLayout->setLeftColumnContent($leftCol);
$cssLayout->setRightColumnContent($rightSideColumn);
echo $cssLayout->show();
?>