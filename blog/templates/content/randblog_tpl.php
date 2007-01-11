<?php

//random blog template

$this->loadClass('href', 'htmlelements');
$tt = $this->newObject('domtt', 'htmlelements');
$cssLayout = &$this->newObject('csslayout', 'htmlelements');
$objSideBar = $this->newObject('usermenu', 'toolbar');
// Set columns to 3
$cssLayout->setNumColumns(3);
$leftMenu = NULL;

$rightSideColumn = NULL;

//set up a link to the other users blogs...
//show the link to all blogs
$rightSideColumn .= $this->objblogOps->showBlogsLink(TRUE);
$rightSideColumn .= $this->objblogOps->blogTagCloud($userid);

//show the categories menu (if there are cats)
$rightSideColumn .= $this->objblogOps->showCatsMenu($cats, TRUE, $userid);

$leftCol = NULL;
$middleColumn = NULL;
//var_dump($latestpost);

if(!empty($latestpost) && !empty($posts))
{
	$this->loadClass('htmlheading', 'htmlelements');
	$header = new htmlheading();
	$header->type = 3;
	$header->str = $this->objLanguage->languageText("mod_blog_latestpost", "blog") . ": " . $this->objDbBlog->getCatById($latestpost[0]['post_category']);
	$middleColumn .= $header->show();

	if($posts[0]['id'] == $latestpost[0]['id'])
	{
		unset($posts[0]);
	}
	$middleColumn .= $this->objblogOps->showPosts($latestpost);
	$middleColumn .= "<hr />";

	$headerprev = new htmlheading();
	$headerprev->type = 3;
	$headerprev->str = $this->objLanguage->languageText("mod_blog_previousposts", "blog");
	$middleColumn .= $headerprev->show();
	$middleColumn .= ($this->objblogOps->showPosts($posts));
}
else {
	$middleColumn .= "<h1><em><center>" . $this->objLanguage->languageText("mod_blog_nopostsincat", "blog") . "</center></em></h1>";
	if($this->objUser->userId() == $userid)
	{
		$linker = new href($this->uri(array('module' => 'blog', 'action' => 'blogadmin', 'mode' => 'writepost')), $this->objLanguage->languageText("mod_blog_writepost", "blog"), NULL); //$this->objblogOps->showAdminSection(TRUE);
		$middleColumn .= "<center>" . $linker->show() . "</center>";
	}
}
//left menu section
$leftCol = NULL;
//check if the user is logged in or not...
if($this->objUser->isLoggedIn())
{
	$leftCol .= $objSideBar->show();
	$rightSideColumn .=$this->objblogOps->quickPost($this->objUser->userId(), TRUE);
	$rightSideColumn .= $this->objblogOps->archiveBox($userid, TRUE);
}
else {
	$leftCol = $this->objblogOps->loginBox(TRUE);
	//$rightSideColumn .= $this->objblogOps->showBlogsLink(TRUE);
}
//show the feeds section
$leftCol .= $this->objblogOps->showFeeds(&$userid, TRUE);
//$leftCol .= $this->objblogOps->rssBox('http://slashdot.org/slashdot.rdf', 'Slashdot');

$cssLayout->setMiddleColumnContent($middleColumn);
$cssLayout->setLeftColumnContent($leftCol); //$leftMenu->show());
$cssLayout->setRightColumnContent($rightSideColumn);
echo $cssLayout->show();
?>