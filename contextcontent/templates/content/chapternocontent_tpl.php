<?php

$this->loadClass('link', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');
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

$chapterlink=new htmlheading();
$chapterlink->type=1;
$con=$chapter['chaptertitle'];

if ($this->isValid('editchapter')) {
    $con.= ' '.$editLink->show();
}

if ($this->isValid('deletechapter')) {
    $con.= ' '.$deleteLink->show();
}

if ($this->isValid('addpage')) {
    $con.= ' '.$addPageLink->show();
}
$chapterlink->str=$con;
echo  $chapterlink->show();

if ($this->getParam('message') == 'chaptercreated') {
    echo '<p class="warning">'.$errorTitle.'</p>';
} else {
    echo '<p class="error">'.$errorTitle.'. '.$errorMessage.'</p>';
}

$introheader=new htmlheading();
$introheader->type=3;
$introheader->str=$this->objLanguage->languageText('mod_contextcontent_aboutchapter_introduction', 'contextcontent', 'About Chapter (Introduction)');
echo $introheader->show();

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
