<?php
//profile add/edit template
$cssLayout = &$this->newObject('csslayout', 'htmlelements');
// Set columns to 3
$cssLayout->setNumColumns(3);
$leftMenu = &$this->newObject('usermenu', 'toolbar');
$rightSideColumn = NULL; //$this->objLanguage->languageText('mod_blog_instructions', 'blog');
$middleColumn = NULL;

//show the link to all blogs
$rightSideColumn .= $this->objblogOps->showBlogsLink(TRUE);
//show the admin section (if user is logged in)
if(!$this->objUser->isLoggedIn())
{
	$leftCol = $this->objblogOps->loginBox(TRUE);
	$header = new htmlheading();
	$header->type = 3;
	$header->str = $this->objLanguage->languageText("mod_blog_loginexpired", "blog");
	$middleColumn .= $header->show();
}
else {
	//display the menu
	$leftCol = $leftMenu->show();
	$leftCol .= $this->objblogOps->showProfile($userid);
	$leftCol .= "<br />";
	$rightSideColumn .= $this->objblogOps->showAdminSection(TRUE);
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