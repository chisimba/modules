<?php

//random blog template

$this->loadClass('href', 'htmlelements');
$tt = $this->newObject('domtt', 'htmlelements');
$cssLayout = &$this->newObject('csslayout', 'htmlelements');
$objSideBar = $this->newObject('sidebar', 'navigation');
// Set columns to 3
$cssLayout->setNumColumns(3);
$leftMenu = NULL;

$rightSideColumn = NULL;

//set up a link to the other users blogs...
//show the link to all blogs
$rightSideColumn .= $this->objblogOps->showBlogsLink(TRUE);

//show the categories menu (if there are cats)
$rightSideColumn .= $this->objblogOps->showCatsMenu($cats, TRUE);

$leftCol = NULL;
$middleColumn = NULL;

$middleColumn .= nl2br($this->objblogOps->showPosts($posts));

//left menu section
$leftCol = NULL;
$leftCol = $this->objblogOps->loginBox(TRUE);

//show the feeds section
$leftCol .= $this->objblogOps->showFeeds(&$userid, TRUE);

$cssLayout->setMiddleColumnContent($middleColumn);
$cssLayout->setLeftColumnContent($leftCol); //$leftMenu->show());
$cssLayout->setRightColumnContent($rightSideColumn);
echo $cssLayout->show();
?>