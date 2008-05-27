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

$middle .= '<br clear="both" />';

$leftContent = $this->objNewsMenu->generateMenu();


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

$right = '';

$objBlocks = $this->getObject('blocks', 'blocks');
$right .= $objBlocks->showBlock('lastten', 'blog', NULL, 20, FALSE, FALSE);
$right .= $objBlocks->showBlock('latestpodcast', 'podcast', NULL, 20, FALSE, FALSE);
$right .= $this->objNewsStories->getFeedLinks();




$cssLayout->setLeftColumnContent($leftContent);
$cssLayout->setMiddleColumnContent($middle);
$cssLayout->setRightColumnContent($right);

echo $cssLayout->show();

?>