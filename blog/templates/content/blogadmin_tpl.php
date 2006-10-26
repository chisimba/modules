<?php

//initiate objects
$this->loadClass('label', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('form', 'htmlelements');
//$this->loadClass('heading', 'htmlelements');
$this->loadClass('href', 'htmlelements');
$this->loadClass('htmlarea', 'htmlelements');

$tt = $this->newObject('domtt', 'htmlelements');
$pane = &$this->newObject('tabpane', 'htmlelements');

$cssLayout = &$this->newObject('csslayout', 'htmlelements');
$objSideBar = $this->newObject('sidebar', 'navigation');

// Set columns to 3
$cssLayout->setNumColumns(3);
$leftMenu = &$this->newObject('usermenu', 'toolbar');
$rightSideColumn = NULL;
$leftCol = NULL;
$middleColumn = NULL;

//left menu section
$leftCol = $leftMenu->show();

//get the category editor
$catedit = $this->objblogOps->categoryEditor($userid);

//Middle column - dashboard
$pane->addTab(array(
    'name' => $this->objLanguage->languageText("mod_blog_word_posts", "blog"),
    'content' => $this->objblogOps->postEditor($userid)
));
$pane->addTab(array(
    'name' => $this->objLanguage->languageText("mod_blog_word_categories", "blog"),
    'content' => $catedit
));


$middleColumn .= $pane->show();


$rightSideColumn .= $this->objblogOps->quickCats(TRUE);
$rightSideColumn .= $this->objblogOps->quickPost($userid, TRUE);
$cssLayout->setMiddleColumnContent($middleColumn);
$cssLayout->setLeftColumnContent($leftCol);
$cssLayout->setRightColumnContent($rightSideColumn);
echo $cssLayout->show();
?>