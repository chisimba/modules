<?php
//Language items
$default='You are using an unsupported browser. Please switch to Mozilla FireFox available at ( http://getfirefox.com ). Currently the system functionality is limited. Thanks!';
$browserError = $objLanguage->languageText('mod_poll_browserError', 'poll',$default);
// Add JavaScript if User can update blocks
if ($this->objUser->isAdmin()) {

    $objIcon = $this->newObject('geticon', 'htmlelements');
    $objIcon->setIcon('up');
    $upIcon = $objIcon->show();


    $objIcon->setIcon('down');
    $downIcon = $objIcon->show();

    $objIcon->setIcon('delete');
    $deleteIcon = $objIcon->show();
    ?>

<script type="text/javascript">
    // <![CDATA[
    upIcon = '<?php echo $upIcon; ?>';
    downIcon = '<?php echo $downIcon; ?>';
    deleteIcon = '<?php echo $deleteIcon; ?>';
    deleteConfirm = '<?php echo $objLanguage->languageText('mod_context_confirmremoveblock', 'context', 'Are you sure you want to remove the block'); ?>';
    unableMoveBlock = '<?php echo $objLanguage->languageText('mod_context_unablemoveblock', 'context', 'Error - Unable to move block'); ?>';
    unableDeleteBlock = '<?php echo $objLanguage->languageText('mod_context_unabledeleteblock', 'context', 'Error - Unable to delete block'); ?>';
    unableAddBlock = '<?php echo $objLanguage->languageText('mod_context_unableaddblock', 'context', 'Error - Unable to add block'); ?>';
    turnEditingOn = '<?php echo $objLanguage->languageText('mod_context_turneditingon', 'context', 'Turn Editing On'); ?>';
    turnEditingOff = '<?php echo $objLanguage->languageText('mod_context_turneditingoff', 'context', 'Turn Editing Off'); ?>';
    theModule = 'news';
    pageid="story";
    // ]]>
</script>
    <?php
    $this->appendArrayVar('headerParams', $this->getJavaScriptFile('jquery.livequery.js', 'jquery'));

    echo $this->getJavaScriptFile('news_blocks.js');
} // End Addition of JavaScript



$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');

$objCssLayout = $this->getObject('csslayout', 'htmlelements');
$objCssLayout->setNumColumns(3);

if ($this->objUser->isAdmin()) {

    $rightBlocksDropDown = new dropdown ('rightblocks');
    $rightBlocksDropDown->cssId = 'ddrightblocks';
    $rightBlocksDropDown->addOption('', $objLanguage->languageText('phrase_selectone', 'phrase', 'Select One').'...');

    $leftBlocksDropDown = new dropdown ('leftblocks');
    $leftBlocksDropDown->cssId = 'ddleftblocks';
    $leftBlocksDropDown->addOption('', $objLanguage->languageText('phrase_selectone', 'phrase', 'Select One').'...');

    // Create array for sorting
    $smallBlockOptions = array();

    // Add Small Blocks
    foreach ($smallBlocks as $smallBlock) {
        $block = $this->newObject('block_'.$smallBlock['blockname'], $smallBlock['moduleid']);
        $title = $block->title;
        //parse some abstractions
        $title=$this->objLanguage->abstractText($title);
        if ($title == '') {
            $title = $smallBlock['blockname'].'|'.$smallBlock['moduleid'];
        }

        $smallBlockOptions['block|'.$smallBlock['blockname'].'|'.$smallBlock['moduleid']] = htmlentities($title);
    }

    // Sort Alphabetically
    asort($smallBlockOptions);

    // Add Small Blocks for right
    foreach ($smallBlockOptions as $block=>$title) {
        $rightBlocksDropDown->addOption($block, $title);
    }

    //then left too
    foreach ($smallBlockOptions as $block=>$title) {
        $leftBlocksDropDown->addOption($block, $title);
    }

    $button = new button ('addrightblock', $objLanguage->languageText('mod_prelogin_addblock', 'prelogin', 'Add Block'));
    $button->cssId = 'rightbutton';

    $editOnButton = new button ('editonbutton', $objLanguage->languageText('mod_context_turneditingon', 'context', 'Turn Editing On'));
    $editOnButton->cssId = 'editmodeswitchbutton';
    $editOnButton->setOnClick("switchEditMode();");

}

$header = new htmlheading();
$header->type = 3;
$header->str = $objLanguage->languageText('mod_context_addablock', 'context', 'Add a Block');

$objCssLayout->rightColumnContent = '';

if ($this->objUser->isAdmin()) {
    $objCssLayout->rightColumnContent .= '<div id="editmode">'.$editOnButton->show().'</div>';
}
$objCssLayout->rightColumnContent .= '<div id="rightblocks">'.$rightBlocks.'</div>';

if ($this->objUser->isAdmin()) {
    $objCssLayout->rightColumnContent .= '<div id="rightaddblock">'.$header->show().$rightBlocksDropDown->show();
    $objCssLayout->rightColumnContent .= '<div id="rightpreview"><div id="rightpreviewcontent"></div> '.$button->show().' </div>';
    $objCssLayout->rightColumnContent .= '</div>';
}

$button = new button ('addmiddleblock', $objLanguage->languageText('mod_prelogin_addblock', 'prelogin', 'Add Block'));
$button->cssId = 'middlebutton';

if (!isset($middleBlocksStr)) {
    $middleBlocksStr="";
}
$objCssLayout->middleColumnContent = '<div id="middleblocks">'.$middleBlocksStr.'</div>';
$newsheader = new htmlheading();
$newsheader->type = 1;

$newsheader->str = $this->objLanguage->languageText('mod_news_latestnews', 'news', 'Latest News');


// Set Story as Middle Content
$middleContent = '<div id="newsstorycontent">'.$content.'</div>';

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
    $editOptions[] ='<div id="newsliststory">'. $listStoriesLink->show().'</div>';
}

if ( $this->isValid('editmenuitem') && $menuId != FALSE && $this->objUser->inAdminGroup($this->objUser->userId()) ) {
    $editCategoryLink = new link ($this->uri(array('action'=>'editmenuitem', 'id'=>$menuId)));
    $editCategoryLink->link = $this->objLanguage->languageText('mod_news_editcategory', 'news', 'Edit Category');
    $editOptions[] = $editCategoryLink->show();
}

// Loop through permissions and add page.
if (count($editOptions) > 0) {
    $divider = '';
    $middleContent .= '<p class="newstoryp">';
    foreach ($editOptions as $editOption)
    {
        $middleContent .= $divider.$editOption;
        $divider = ' : ';
    }
    $middleContent .= '</p>';
}


$objCssLayout->middleColumnContent .= $middleContent;
$button = new button ('addleftblock', $objLanguage->languageText('mod_prelogin_addblock', 'prelogin', 'Add Block'));
$button->cssId = 'leftbutton';

$leftContent = $this->objNewsMenu->generateMenu();
$leftContent .= $this->objNewsStories->getFeedLinks();

$adminOptions = array();

if ($this->isValid('managecategories')) {
    $newsCategoriesLink = new link ($this->uri(array('action'=>'managecategories')));
    $newsCategoriesLink->link = 'Manage News Categories';
    $adminOptions[] = '<li>'.$newsCategoriesLink->show().'</li>';
}

if ($this->isValid('addstory')) {
    $addNewsStoryLink = new link ($this->uri(array('action'=>'addstory')));
    $addNewsStoryLink->link = 'Add News Story';
    $adminOptions[] = '<li>'.$addNewsStoryLink->show().'</li>';
}
if ($this->objUser->isAdmin()){
if (count($adminOptions) > 0) {

    $leftContent .= '<h3>News Options</h3>';

    $leftContent .= '<ul>';

    foreach ($adminOptions as $option)
    {
        $leftContent .= $option;
    }

    $leftContent .= '</ul>';

}
}

$objCssLayout->leftColumnContent .=$leftContent;
$objCssLayout->leftColumnContent .= '<br/><div id="leftblocks">'.$leftBlocks.'</div>';

if ($this->objUser->isAdmin()) {
    $objCssLayout->leftColumnContent .= '<div id="leftaddblock">'.$header->show().$leftBlocksDropDown->show();
    $objCssLayout->leftColumnContent .= '<div id="leftpreview"><div id="leftpreviewcontent"></div> '.$button->show().' </div>';
    $objCssLayout->leftColumnContent .= '</div>';
}

echo $objCssLayout->show();
?>