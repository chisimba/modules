<?php
// random blog template
$this->loadClass('href', 'htmlelements');
$cssLayout = &$this->newObject('csslayout', 'htmlelements');
$objUi = $this->getObject('blogui');
// Set columns to 3
$cssLayout->setNumColumns(3);
$middleColumn = NULL;
//check for sticky posts
if (!is_null($stickypost)) {
    $middleColumn.= $this->objblogOps->showPosts($stickypost, TRUE);
}
if (!empty($latestpost) && !empty($posts)) {
    $this->loadClass('htmlheading', 'htmlelements');
    $header = new htmlheading();
    $header->type = 3;
    $header->str = $this->objLanguage->languageText("mod_blog_latestpost", "blog") . ": " . $this->objDbBlog->getCatById($latestpost[0]['post_category']);
    $middleColumn.= $header->show();
    if ($posts[0]['id'] == $latestpost[0]['id']) {
        unset($posts[0]);
    }
    $middleColumn.= $this->objblogOps->showPosts($latestpost);
    $middleColumn.= "<hr />";
    $headerprev = new htmlheading();
    $headerprev->type = 3;
    $headerprev->str = $this->objLanguage->languageText("mod_blog_previousposts", "blog");
    $middleColumn.= $headerprev->show();
    $middleColumn.= ($this->objblogOps->showPosts($posts));
} else {
    $middleColumn.= "<h1><em><center>" . $this->objLanguage->languageText("mod_blog_nopostsincat", "blog") . "</center></em></h1>";
    if ($this->objUser->userId() == $userid) {
        $linker = new href($this->uri(array(
            'module' => 'blog',
            'action' => 'blogadmin',
            'mode' => 'writepost'
        )) , $this->objLanguage->languageText("mod_blog_writepost", "blog") , NULL); //$this->objblogOps->showAdminSection(TRUE);
        $middleColumn.= "<center>" . $linker->show() . "</center>";
    }
}
// left hand blocks
$leftCol = $objUi->leftBlocks($userid);
// right side blocks
$rightSideColumn = $objUi->rightBlocks($userid, $cats);
if (!empty($rss)) {
    foreach($rss as $feeds) {
        $timenow = time();
        if ($timenow-$feeds['rsstime'] > 43200) {
            $url = $feeds['url'];
            $id = $feeds['id'];
            $leftCol.= $this->objblogOps->rssRefresh($url, $feeds['name'], $id);
        } else {
            $url = $feeds['rsscache'];
            $leftCol.= $this->objblogOps->rssBox($url, $feeds['name']);
        }
    }
}
$cssLayout->setMiddleColumnContent($middleColumn);
$cssLayout->setLeftColumnContent($leftCol); //$leftMenu->show());
$cssLayout->setRightColumnContent($rightSideColumn);
echo $cssLayout->show();
?>