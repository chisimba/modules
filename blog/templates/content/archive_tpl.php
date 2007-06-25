<?php
//archive template
$cssLayout = &$this->newObject('csslayout', 'htmlelements');
$objUi = $this->getObject('blogui');
// Set columns to 3
$cssLayout->setNumColumns(3);
$middleColumn = NULL;
// left hand blocks
$leftCol = $objUi->leftBlocks($userid);
// right side blocks
$rightSideColumn = $objUi->rightBlocks($userid, NULL);
//show all the posts
if ($this->objblogOps->showPosts($posts) == FALSE) {
    $middleColumn.= "<h1><em><center>" . $this->objLanguage->languageText("mod_blog_nopostsincat", "blog") . "</center></em></h1>";
} else {
    $middleColumn.= ($this->objblogOps->showPosts($posts));
}
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
$cssLayout->setLeftColumnContent($leftCol);
$cssLayout->setRightColumnContent($rightSideColumn);
echo $cssLayout->show();
?>