<?php

$cssLayout = &$this->newObject('csslayout', 'htmlelements');
// Set columns to 3
$cssLayout->setNumColumns(3);
$leftMenu = &$this->newObject('usermenu', 'toolbar');
$rightSideColumn = NULL; //$this->objLanguage->languageText('mod_blog_instructions', 'blog');
$middleColumn = NULL;

//show the link to all blogs
$rightSideColumn .= $this->objblogOps->showBlogsLink(TRUE);

//show the categories menu (if there are cats)
$rightSideColumn .= $this->objblogOps->showCatsMenu($cats, TRUE);

//$rightSideColumn .= "<br />";

//show the link categories (if any)
$rightSideColumn .= $this->objblogOps->showLinkCats($linkcats, TRUE);

//add a break to the righsidecol and carry on with the meta data and admin sections
//$rightSideColumn .= "<br />";

//show the admin section (if user is logged in)
$rightSideColumn .= $this->objblogOps->showAdminSection(TRUE);

//show all the posts
$middleColumn .= nl2br($this->objblogOps->showPosts($posts));

//left menu section
//display the menu
$leftCol = $leftMenu->show();
$leftCol .= "<br />";

//show the feeds section
$leftCol .= $this->objblogOps->showFeeds($userid, TRUE);
$rightSideColumn .= $this->objblogOps->quickPost($userid, TRUE);
//dump the cssLayout to screen
$cssLayout->setMiddleColumnContent($middleColumn);
$cssLayout->setLeftColumnContent($leftCol);
$cssLayout->setRightColumnContent($rightSideColumn);
echo $cssLayout->show();
?>