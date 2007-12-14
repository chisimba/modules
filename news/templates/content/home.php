<?php

$this->loadClass('link', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');

$cssLayout = $this->getObject('csslayout', 'htmlelements');
$cssLayout->setNumColumns(3);

$header = new htmlheading();
$header->type = 1;
$header->str = $this->objLanguage->languageText('mod_news_latestnews', 'news', 'Latest News');

$middle = $this->objNewsMenu->toolbar('home');

$middle .= $header->show();

$middle .= $topStories;

$leftContent = $this->objNewsMenu->generateMenu();
$leftContent .= $this->objNewsStories->getFeedLinks();

$adminOptions = array();

if ($this->isValid('managecategories')) {
    $newsCategoriesLink = new link ($this->uri(array('action'=>'managecategories')));
    $newsCategoriesLink->link = $this->objLanguage->languageText('mod_news_managenewscategories', 'news', 'Manage News Categories');
    $adminOptions[] = '<li>'.$newsCategoriesLink->show().'</li>';
}

if ($this->isValid('addstory')) {
    $addNewsStoryLink = new link ($this->uri(array('action'=>'addstory')));
    $addNewsStoryLink->link = $this->objLanguage->languageText('mod_news_addnewsstory', 'news', 'Add News Story');
    $adminOptions[] = '<li>'.$addNewsStoryLink->show().'</li>';
}

if (count($adminOptions) > 0) {

    $leftContent .= '<h3>'.$this->objLanguage->languageText('mod_news_newsoptions', 'news', 'News Options').'</h3>';

    $leftContent .= '<ul>';

    foreach ($adminOptions as $option)
    {
        $leftContent .= $option;
    }

    $leftContent .= '</ul>';

}

$pollLink = new link ($this->uri(array('action'=>'previouspolls')));
$pollLink->link = $this->objLanguage->languageText('mod_news_viewpreviouspolls', 'news', 'View Previous Polls');

$latestPoll = $this->getObject('latestpoll');

$right = $latestPoll->show().'<p>'.$pollLink->show();

if ($this->isValid('addpoll')) {
    $pollLink = new link ($this->uri(array('action'=>'addpoll')));
    $pollLink->link = $this->objLanguage->languageText('mod_news_addpoll', 'news', 'Add Poll');
    
    $right .= ' / '.$pollLink->show();
}

$right .= '</p>';

if (count($categories) > 0) {
    
    $table = $this->newObject('htmltable', 'htmlelements');
    //print_r($topStoriesId);
    $counter = 0;
	foreach ($categories as $category)
	{
		if ($category['blockonfrontpage'] == 'Y') {
            $nonTopStories = $this->objNewsStories->getNonTopStoriesFormatted($category['id'], $topStoriesId);
            if ($nonTopStories != '') {
                
                if ($counter%2 == 0) {
                    $middle .= '<br clear="all" />';
                }
                $middle .= '<div style="width:50%; float:left; "><h3>'.$category['categoryname'].'</h3>';
                $middle .= $nonTopStories.'</div>';
                
                $counter++;
            }
        }
		
	}	
}

if (count($albums) > 0) {
    $album = $albums[0];
    
        $right .= '<h3>'.$this->objLanguage->languageText('mod_news_latestalbums', 'news', 'Latest Albums').'</h3>';
        $right .= '<div >';
        
        $objThumbnails = $this->getObject('thumbnails', 'filemanager');
        $objPhotos = $this->getObject('dbnewsphotos', 'news');
        
        $firstPhoto = $objPhotos->getFirstAlbumPhoto($album['id']);
        
        $image = is_array($firstPhoto) ? '<br /><img src="'.$objThumbnails->getThumbnail($firstPhoto['fileid'], $firstPhoto['filename']).'" />' : '<br /><br />'.$this->objLanguage->languageText('mod_news_nophotos', 'news', 'No Photos').'<br />&nbsp;';
        
        $albumLink = new link ($this->uri(array('action'=>'viewalbum', 'id'=>$album['id'])));
        $albumLink->link = $album['albumname'].$image;
        
        $right .= '<h3>'.$albumLink->show().'</h3>';
        
        $right .= '</div>';
        
        $albumLink = new link ($this->uri(array('action'=>'photoalbums')));
        $albumLink->link = $this->objLanguage->languageText('mod_news_viewallalbums', 'news', 'View All Albums');
        
        $right .= '<p>'.$albumLink->show().'</p>';
    
}




$cssLayout->setLeftColumnContent($leftContent);
$cssLayout->setMiddleColumnContent($middle);
$cssLayout->setRightColumnContent($right);

echo $cssLayout->show();

//$nonTopStories = $this->objNewsStories->getNonTopStories($topStories['topstoryids']);
?>
<style type="text/css">
.album {
    float: left;
    border: 1px solid blue;
    padding: 3px;
    text-align: center;
    width: 150px;
    text-overflow: hidden;
    margin-right: 10px;
}
.album a {display: block;}


</style>