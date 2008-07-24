<?php

$this->loadClass('link', 'htmlelements');
$objIcon = $this->newObject('geticon', 'htmlelements');
$objIcon->align = 'absmiddle';

$objIcon->setIcon('edit');
$editIcon = $objIcon->show();

$objIcon->setIcon('delete');
$deleteIcon = $objIcon->show();

$objIcon->setIcon('create_page');
$objIcon->alt = $this->objLanguage->languageText('mod_contextcontent_addapagetothischapter','contextcontent');
$objIcon->title = $this->objLanguage->languageText('mod_contextcontent_addapagetothischapter','contextcontent');
$addPageIcon = $objIcon->show();


$editLink = new link($this->uri(array('action'=>'editchapter', 'id'=>$chapter['chapterid'])));
$editLink->link = $editIcon;

$deleteLink = new link($this->uri(array('action'=>'deletechapter', 'id'=>$chapter['chapterid'])));
$deleteLink->link = $deleteIcon;

$addPageLink = new link($this->uri(array('action'=>'addpage', 'chapter'=>$chapter['chapterid'])));
$addPageLink->link = $addPageIcon;



$chapters = $this->objContextChapters->getContextChapters($this->contextCode);
$this->setVarByRef('chapters', $chapters);

$this->setLayoutTemplate('layout_firstpage_tpl.php');

echo '<h1>'.$chapter['chaptertitle'];

if ($this->isValid('editchapter')) {
    echo ' '.$editLink->show();
}

if ($this->isValid('deletechapter')) {
    echo ' '.$deleteLink->show();
}

if ($this->isValid('addpage')) {
    echo ' '.$addPageLink->show();
}

echo '</h1>';

if ($this->getParam('message') == 'chaptercreated') {
    echo '<p class="warning">'.$errorTitle.'</p>';
} else {
    echo '<p class="error">'.$errorTitle.'. '.$errorMessage.'</p>';
}


echo '<h3>'.$this->objLanguage->languageText('mod_contextcontent_aboutchapter_introduction', 'contextcontent', 'About Chapter (Introduction)').'</h3>';

$objWashout = $this->getObject('washout', 'utilities');

echo $objWashout->parseText($chapter['introduction']);

$addPageLink = new link ($this->uri(array('action'=>'addpage', 'chapter'=>$chapter['chapterid'])));
$addPageLink->link = $this->objLanguage->languageText('mod_contextcontent_addapagetothischapter','contextcontent');

if ($this->isValid('addpage')) {
    echo $addPageLink->show().' / ';
}

$returnLink = new link ($this->uri(NULL));
$returnLink->link = $this->objLanguage->languageText('mod_contextcontent_returntochapterlist', 'contextcontent', 'Return to Chapter List');

echo $returnLink->show();

?>
