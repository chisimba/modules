<?php

$cssLayout = &$this->newObject('csslayout', 'htmlelements');
// Set columns to 3
$cssLayout->setNumColumns(3);
$leftMenu = &$this->newObject('usermenu', 'toolbar');
$rightSideColumn = NULL; //$this->objLanguage->languageText('mod_blog_instructions', 'blog');
$middleColumn = NULL;

$rightSideColumn .= $this->objblogOps->showBlogsLink(TRUE);


$rightSideColumn .= $this->objblogOps->blogTagCloud($userid);

//$rightSideColumn .= "<br />";

//show the link categories (if any)
//$rightSideColumn .= $this->objblogOps->showLinkCats($linkcats, TRUE);

//show the admin section (if user is logged in)
if(!$this->objUser->isLoggedIn())
{
    $leftCol = $this->objblogOps->loginBox(TRUE);
    $rightSideColumn .= $this->objblogOps->showAdminSection(TRUE);
}
else {
    //show the categories menu (if there are cats)
    $rightSideColumn .= $this->objblogOps->showCatsMenu($cats, TRUE);
    //left menu section
    //display the menu
  //  $leftCol = $leftMenu->show();
   // $leftCol .= "<br />";
   // $rightSideColumn .= $this->objblogOps->showAdminSection(TRUE);
}


//show all the posts
if(isset($catid) && empty($posts))
{
    $middleColumn .= "<h1><em><center>" . $this->objLanguage->languageText("mod_blog_nopostsincat", "blog") . "</center></em></h1>";
    if($this->objUser->userId() == $userid)
    {
        $linker = new href($this->uri(array('module' => 'blog', 'action' => 'blogadmin', 'mode' => 'writepost')), $this->objLanguage->languageText("mod_blog_writepost", "blog"), NULL); //$this->objblogOps->showAdminSection(TRUE);
        $middleColumn .= "<center>" . $linker->show() . "</center>";
    }
}
elseif(!isset($catid) && empty($posts))
{
    $middleColumn .= "<h1><em><center>" . $this->objLanguage->languageText("mod_blog_noposts", "blog") . "</center></em></h1>";
}
else {
    $middleColumn .= nl2br($this->objblogOps->showPosts($posts));
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