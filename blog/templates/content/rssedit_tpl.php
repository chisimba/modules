<?php
//rss add/edit template

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
}
else {
	//display the menu
	$leftCol = $leftMenu->show();
	$leftCol .= "<br />";
	$rightSideColumn .= $this->objblogOps->showAdminSection(TRUE);
	$middleColumn .= $this->objblogOps->rssEditor($featurebox = FALSE);
}
if(!empty($rss))
{
	foreach($rss as $feeds)
	{
		$timenow = time();
		if($timenow - $feeds['rsstime'] > 43200)
		{
			$url = $feeds['url'];
		}
		else {
			$url = $feeds['rsscache'];
		}
		$leftCol .= $this->objblogOps->rssBox($url, $feeds['name']);
	}
}

//dump the cssLayout to screen
$cssLayout->setMiddleColumnContent($middleColumn);
$cssLayout->setLeftColumnContent($leftCol);
$cssLayout->setRightColumnContent($rightSideColumn);
echo $cssLayout->show();
?>