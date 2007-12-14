<?php

$this->loadClass('link', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('form', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('textarea', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');

$objIcon = $this->getObject('geticon', 'htmlelements');

$objDateTime = $this->getObject('dateandtime', 'utilities');
$objUrl = &$this->getObject('url', 'strings'); 
$objThumbnails = $this->getObject('thumbnails', 'filemanager');

$objIcon->setIcon('edit');
$editLink = new link ($this->uri(array('action'=>'editstory', 'id'=>$story['id'])));
$editLink->link = $objIcon->show();

$header = new htmlheading();
$header->type = 1;
$header->str = $story['storytitle'];

if ($this->isValid('editstory')) {
    $header->str .= ' '.$editLink->show();
}


$middleContent = $content;




$leftContent = $this->objNewsMenu->generateMenu();
$leftContent .= $this->objNewsStories->getFeedLinks();

$rightContent = '';


$rightContent .= $this->objNewsStories->getRelatedStoriesFormatted($story['id'], $story['storydate'], $story['datecreated']);
$rightContent .= $this->objKeywords->getStoryKeywordsBlock($story['id']);






$editOptions = array();

if ($this->isValid('editstory')) {
    $editStoryLink = new link ($this->uri(array('action'=>'editstory', 'id'=>$story['storyid'])));
    $editStoryLink->link = $this->objLanguage->languageText('mod_news_editstory', 'news', 'Edit Story');
    $editOptions[] = $editStoryLink->show();
}

if ($this->isValid('deletestory')) {
    $deleteStoryLink = new link ($this->uri(array('action'=>'deletestory', 'id'=>$story['storyid'])));
    $deleteStoryLink->link = $this->objLanguage->languageText('mod_news_deletestory', 'news', 'Delete Story');
    $editOptions[] = $deleteStoryLink->show();
}

if ($this->isValid('addstory')) {
    $addStoryLink = new link ($this->uri(array('action'=>'addstory', 'id'=>$category['id'])));
    $addStoryLink->link = $this->objLanguage->languageText('mod_news_addstoryincategory', 'news', 'Add Story in this Category');
    $editOptions[] = $addStoryLink->show();
}

if ($this->isValid('liststories')) {
    $listStoriesLink = new link ($this->uri(array('action'=>'liststories', 'id'=>$category['id'])));
    $listStoriesLink->link = $this->objLanguage->languageText('mod_news_liststoriesincategory', 'news', 'List Stories in this Category');
    $editOptions[] = $listStoriesLink->show();
}

if ($this->isValid('editmenuitem') && $menuId != FALSE) {
    $editCategoryLink = new link ($this->uri(array('action'=>'editmenuitem', 'id'=>$menuId)));
    $editCategoryLink->link = $this->objLanguage->languageText('mod_news_editcategory', 'news', 'Edit Category');
    $editOptions[] = $editCategoryLink->show();
}

if (count($editOptions) > 0) {
    $divider = '';
    $middleContent .= '<p>';
    foreach ($editOptions as $editOption)
    {
        $middleContent .= $divider.$editOption;
        $divider = ' : ';
    }
    $middleContent .= '</p>';
}

$cssLayout = $this->getObject('csslayout', 'htmlelements');

$cssLayout->setLeftColumnContent($leftContent);
$cssLayout->setMiddleColumnContent($middleContent);

if ($rightContent != '') {
    $cssLayout->setNumColumns(3);
    $cssLayout->setRightColumnContent($rightContent);
}

echo $cssLayout->show();


?>