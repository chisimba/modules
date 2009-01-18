<?php

// Load link Class
$this->loadClass('link', 'htmlelements');

// Set Story as Middle Content
$middleContent = $content;

// Generate Right Content
$rightContent = $this->objNewsStories->getRelatedStoriesFormatted($story['id'], $story['storydate'], $story['datecreated']);
$rightContent .= $this->objKeywords->getStoryKeywordsBlock($story['id']);

// Array for list of options based on permissions
$editOptions = array();

if ( $this->objUser->inAdminGroup($this->objUser->userId()) ) {
    if ( $this->isValid('editstory') ) {
        $editStoryLink = new link ($this->uri(array('action'=>'editstory', 'id'=>$story['storyid'])));
        $editStoryLink->link = $this->objLanguage->languageText('mod_news_editstory', 'news', 'Edit Story');
        $editOptions[] = $editStoryLink->show();
    }

    if ( $this->isValid('deletestory') ) {
        $deleteStoryLink = new link ($this->uri(array('action'=>'deletestory', 'id'=>$story['storyid'])));
        $deleteStoryLink->link = $this->objLanguage->languageText('mod_news_deletestory', 'news', 'Delete Story');
        $editOptions[] = $deleteStoryLink->show();
    }

    if ( $this->isValid('addstory') ) {
        $addStoryLink = new link ($this->uri(array('action'=>'addstory', 'id'=>$category['id'])));
        $addStoryLink->link = $this->objLanguage->languageText('mod_news_addstoryincategory', 'news', 'Add Story in this Category');
        $editOptions[] = $addStoryLink->show();
    }
}

if ( $this->isValid('liststories') ) {
    $listStoriesLink = new link ($this->uri(array('action'=>'liststories', 'id'=>$category['id'])));
    $listStoriesLink->link = $this->objLanguage->languageText('mod_news_liststoriesincategory', 'news', 'List Stories in this Category');
    $editOptions[] = $listStoriesLink->show();
}

if ( $this->isValid('editmenuitem') && $menuId != FALSE && $this->objUser->inAdminGroup($this->objUser->userId()) ) {
    $editCategoryLink = new link ($this->uri(array('action'=>'editmenuitem', 'id'=>$menuId)));
    $editCategoryLink->link = $this->objLanguage->languageText('mod_news_editcategory', 'news', 'Edit Category');
    $editOptions[] = $editCategoryLink->show();
}

// Loop through permissions and add page.
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

// Display
echo $middleContent;

?>
