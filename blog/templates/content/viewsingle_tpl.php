<?php

$cssLayout = &$this->newObject('csslayout', 'htmlelements');
// Set columns to 3
$cssLayout->setNumColumns(3);
$leftMenu = &$this->newObject('usermenu', 'toolbar');
$rightSideColumn = NULL; //$this->objLanguage->languageText('mod_blog_instructions', 'blog');
$middleColumn = NULL;

$rightSideColumn .= $this->objblogOps->blogTagCloud($userid);

//show all the posts
$middleColumn .= nl2br($this->objblogOps->showPosts($posts));
$middleColumn .= $tracks = $this->objblogOps->showTrackbacks($postid);

$leftCol = NULL;

if(!$this->objUser->isLoggedIn())
{
	$leftCol = $this->objblogOps->loginBox(TRUE);
}
//show the feeds section
$leftCol .= $this->objblogOps->showFeeds($userid, TRUE);
$rightSideColumn .= $this->objblogOps->archiveBox($userid, TRUE);

//dump the cssLayout to screen
$cssLayout->setMiddleColumnContent($middleColumn);
$cssLayout->setLeftColumnContent($leftCol);
$cssLayout->setRightColumnContent($rightSideColumn);
echo $cssLayout->show();
?>