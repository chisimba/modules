<?php
//archive template


$cssLayout = &$this->newObject('csslayout', 'htmlelements');
// Set columns to 3
$cssLayout->setNumColumns(3);
$leftMenu = &$this->newObject('usermenu', 'toolbar');
$rightSideColumn = NULL; //$this->objLanguage->languageText('mod_blog_instructions', 'blog');
$middleColumn = NULL;

//show the link to all blogs
$rightSideColumn .= $this->objblogOps->showBlogsLink(TRUE);
$rightSideColumn .= $this->objblogOps->archiveBox($userid, TRUE);

//show the categories menu (if there are cats)
//$rightSideColumn .= $this->objblogOps->showCatsMenu($cats, TRUE);

//$rightSideColumn .= "<br />";

//show the link categories (if any)
//$rightSideColumn .= $this->objblogOps->showLinkCats($linkcats, TRUE);

//add a break to the righsidecol and carry on with the meta data and admin sections
//$rightSideColumn .= "<br />";



//show all the posts
if($this->objblogOps->showPosts($posts) == FALSE)
{
	$middleColumn .= "<h1><em><center>" . $this->objLanguage->languageText("mod_blog_nopostsincat", "blog") . "</center></em></h1>";
}
else {
	$middleColumn .= ($this->objblogOps->showPosts($posts));
}

//left menu section
//display the menu
if(!$this->objUser->isLoggedIn())
{
	$leftCol = $this->objblogOps->loginBox(TRUE);
	$leftCol .= $this->objblogOps->showFullProfile($userid);
}
else {
	$guestid = $this->objUser->userId();
	if($guestid == $userid)
	{
		$leftCol .= $leftMenu->show();
		$leftCol .= $this->objblogOps->showProfile($userid);
	}
	else {
		//echo "guest is diff";
		$leftCol .= $this->objblogOps->showFullProfile($userid);
	}
	$leftCol .= "<br />";
	$leftCol .= $this->objblogOps->showProfile($userid);
	//show the admin section (if user is logged in)
	$rightSideColumn .= $this->objblogOps->showAdminSection(TRUE);
	//show the feeds section
$leftCol .= $this->objblogOps->showFeeds($userid, TRUE);
$leftCol .= $this->objblogOps->showBlinks($userid, TRUE);
$leftCol .= $this->objblogOps->showBroll($userid, TRUE);
if(!empty($rss))
{
	foreach($rss as $feeds)
	{
		$timenow = time();
		if($timenow - $feeds['rsstime'] > 43200)
		{
			$url = $feeds['url'];
			$id = $feeds['id'];
			$leftCol .= $this->objblogOps->rssRefresh($url, $feeds['name'], $id);
		}
		else {
			$url = $feeds['rsscache'];
			$leftCol .= $this->objblogOps->rssBox($url, $feeds['name']);
		}

	}
}
}

//show the feeds section
//$leftCol .= $this->objblogOps->showFeeds($userid, TRUE);
//$rightSideColumn .= $this->objblogOps->quickPost($userid, TRUE);
//dump the cssLayout to screen
$cssLayout->setMiddleColumnContent($middleColumn);
$cssLayout->setLeftColumnContent($leftCol);
$cssLayout->setRightColumnContent($rightSideColumn);
echo $cssLayout->show();
?>