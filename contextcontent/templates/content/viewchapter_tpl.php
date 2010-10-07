<?php
$objFile = $this->getObject('dbfile', 'filemanager');
$objHead = $this->newObject('htmlheading', 'htmlelements');
$this->loadClass('link', 'htmlelements');

$nextPage = "";
$chapterList = "";
$editChapter = "";

if($firstPage != FALSE) {
    //Create Next Page Link
    $link = new link ($this->uri(array('action'=>'viewpage', 'id'=>$firstPage, 'prevchapterid'=>$chapterId), 'contextcontent'));
    $link->link = $this->objLanguage->languageText('mod_contextcontent_nextpage','learningcontent','Next Page').': '.htmlentities($page['menutitle']).' &#187;';
    $nextPage = $link->show();
}

//Create Return to Chapter List Link
$link = new link ($this->uri(array('action'=>'showcontextchapters', 'chapterId'=>$chapter['id'], 'prevchapterid'=>$chapterId), 'contextcontent'));
$link->link = '&#171; '.$this->objLanguage->languageText('mod_contextcontent_returntochapterlist','learningcontent','Return to Chapter List');
$chapterList = $link->show();

if ($this->isValid('addpage')) {
    //Create Edit Chapter Link
    $link = new link ($this->uri(array('action'=>'editchapter', 'id'=>$chapterId, 'prevaction'=>'viewchapter'), 'contextcontent'));
    $link->link = $this->objLanguage->languageText('mod_contextcontent_editchapter','learningcontent','Edit Chapter');
    $editChapter = $link->show();
}

$table = $this->newObject('htmltable', 'htmlelements');
//$table->border='1';
$table->startRow();
$table->cssClass = "pagenavigation";
$table->addCell($chapterList, '33%', 'top');
$table->addCell($editChapter, '33%', 'top');
$table->addCell($nextPage, '33%', 'top', 'right');
$table->endRow();

$topTable = $this->newObject('htmltable', 'htmlelements');
//$topTable->border='1';
$topTable->startRow();
$topTable->cssClass = "toppagenavigation";
$topTable->addCell($chapterList, '33%', 'top');
$topTable->addCell($editChapter, '33%', 'top');
$topTable->addCell($nextPage, '33%', 'top', 'right');
$topTable->endRow();

$objWashout = $this->getObject('washout', 'utilities');

$content = "";

$introheader = new htmlheading();
$introheader->type = 3;
$introheader->str =$chapter['chaptertitle'].'&nbsp;&nbsp;-&nbsp;&nbsp;'. $this->objLanguage->languageText('mod_contextcontent_aboutchapter_introduction', 'contextcontent', 'Introduction)');

$content.= $introheader->show().$objWashout->parseText($chapter['introduction']);

if ($this->isValid('addpage')) {
    echo '<div id="tablenav">'.$topTable->show() . $content . '<hr />' . $table->show().'</div>';
} else {
    echo '<div id="tablenav">'.$topTable->show() . $content . '<hr />' . $table->show().'</div>';
}

?>
