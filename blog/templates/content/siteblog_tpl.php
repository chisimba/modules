<?php
$cssLayout = &$this->newObject('csslayout', 'htmlelements');
// Set columns to 3
$cssLayout->setNumColumns(3);
$middleColumn = NULL;
$objUi = $this->getObject('blogui');
// left hand blocks
$leftCol = $objUi->leftBlocks($userid);
// right side blocks
$rightSideColumn = $objUi->rightBlocks($userid, NULL);
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
    $middleColumn .= ($this->objblogOps->showPosts($posts));
}
//dump the cssLayout to screen
$cssLayout->setMiddleColumnContent($middleColumn);
$cssLayout->setLeftColumnContent($leftCol);
$cssLayout->setRightColumnContent($rightSideColumn);
echo $cssLayout->show();
?>