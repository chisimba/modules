<?php
$cssLayout = $this->newObject('csslayout', 'htmlelements');
// Set columns to 3
$middleColumn = NULL;
$objUi = $this->getObject('blogui');
// left hand blocks
$leftCol = $objUi->leftBlocks($userid);
// right side blocks
if (!isset($cats)) {
    $cats = NULL;
}
$rightSideColumn = $objUi->rightBlocks($userid, $cats);
if ($leftCol == NULL || $rightSideColumn == NULL) {
    $cssLayout->setNumColumns(2);
} else {
    $cssLayout->setNumColumns(3);
}
//show all the posts
if (isset($catid) && empty($posts) && empty($latestpost)) {
    $middleColumn.= "<h1><em><center>" . $this->objLanguage->languageText("mod_blog_nopostsincat", "blog") . "</center></em></h1>";
    if ($this->objUser->userId() == $userid) {
        $linker = new href($this->uri(array(
            'module' => 'blog',
            'action' => 'blogadmin',
            'mode' => 'writepost'
        )) , $this->objLanguage->languageText("mod_blog_writepost", "blog") , NULL); //$this->objblogOps->showAdminSection(TRUE);
        $middleColumn.= "<center>" . $linker->show() . "</center>";
    }
} elseif (!isset($catid) && empty($posts) && empty($latestpost)) {
    $middleColumn.= "<h1><em><center>" . $this->objLanguage->languageText("mod_blog_noposts", "blog") . "</center></em></h1>";
} elseif (isset($catid) && !empty($posts)) {
    //check for sticky posts
    if (!is_null($stickypost)) {
        $middleColumn.= $this->objblogPosts->showPosts($stickypost, TRUE);
    }
    $middleColumn.= ($this->objblogPosts->showPosts($posts));
} elseif (isset($catid) && empty($posts) && empty($latestpost)) {
    $middleColumn.= "<h1><em><center>" . $this->objLanguage->languageText("mod_blog_nopostsincat", "blog") . "</center></em></h1>";
    if ($this->objUser->userId() == $userid) {
        $linker = new href($this->uri(array(
            'module' => 'blog',
            'action' => 'blogadmin',
            'mode' => 'writepost'
        )) , $this->objLanguage->languageText("mod_blog_writepost", "blog") , NULL); //$this->objblogOps->showAdminSection(TRUE);
        $middleColumn.= "<center>" . $linker->show() . "</center>";
    }
} else {
    if (!empty($latestpost) && !empty($posts)) {
        //check for sticky posts
        if (!is_null($stickypost)) {
            $middleColumn.= $this->objblogPosts->showPosts($stickypost, TRUE);
        }
        $this->loadClass('htmlheading', 'htmlelements');
        $header = new htmlheading();
        $header->type = 3;
        $header->str = $this->objLanguage->languageText("mod_blog_latestpost", "blog") . ": " . $this->objDbBlog->getCatById($latestpost[0]['post_category']);
        $middleColumn.= $header->show();
        if ($posts[0]['id'] == $latestpost[0]['id']) {
            unset($posts[0]);
        }
        $middleColumn.= $this->objblogPosts->showPosts($latestpost);
        $middleColumn.= "<hr />";
        $headerprev = new htmlheading();
        $headerprev->type = 3;
        $headerprev->str = $this->objLanguage->languageText("mod_blog_previousposts", "blog");
        $middleColumn.= $headerprev->show();
        $middleColumn.= ($this->objblogPosts->showPosts($posts));
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
}
if (!empty($rss)) {
    foreach($rss as $feeds) {
        $timenow = time();
        if ($timenow-$feeds['rsstime'] > 43200) {
            $url = urldecode($feeds['url']);
            $id = $feeds['id'];
            $leftCol.= $this->objblogRss->rssRefresh($url, $feeds['name'], $id);
        } else {
            $url = $feeds['rsscache'];
            $leftCol.= $this->objblogRss->rssBox($url, $feeds['name']);
        }
    }
}
//dump the cssLayout to screen
if ($leftCol == NULL) {
    $leftCol = $rightSideColumn;
    $cssLayout->setMiddleColumnContent($middleColumn);
    $cssLayout->setLeftColumnContent($leftCol);
    //$cssLayout->setRightColumnContent($rightSideColumn);
    echo $cssLayout->show();
} elseif ($rightSideColumn == NULL) {
    $cssLayout->setMiddleColumnContent($middleColumn);
    $cssLayout->setLeftColumnContent($leftCol);
    echo $cssLayout->show();
} else {
    $cssLayout->setMiddleColumnContent($middleColumn);
    $cssLayout->setLeftColumnContent($leftCol);
    $cssLayout->setRightColumnContent($rightSideColumn);
    echo $cssLayout->show();
}
?>