<?php



$addLink = new link ($this->uri(array('action'=>'addpage', 'id'=>$page['id'], 'context'=>$this->contextCode, 'chapter'=>$page['chapterid'])));
$addLink->link = $this->objLanguage->languageText('mod_contextcontent_addcontextpages','contextcontent');

$editLink = new link ($this->uri(array('action'=>'editpage', 'id'=>$page['id'], 'context'=>$this->contextCode)));
$editLink->link = $this->objLanguage->languageText('mod_contextcontent_editcontextpages','contextcontent');

if (($page['rght'] - $page['lft'] - 1) == 0) {
    $deleteLink = new link ($this->uri(array('action'=>'deletepage', 'id'=>$page['id'], 'context'=>$this->contextCode)));
} else {
    $deleteLink = new link ("javascript:alert('".$this->objLanguage->languageText('mod_contextcontent_pagecannotbedeleteduntil','contextcontent').".');");
}
$deleteLink->link = $this->objLanguage->languageText('mod_contextcontent_delcontextpages','contextcontent');

$list = array();

if ($this->isValid('addpage')) {
    $list[] = $addLink->show();
}

if ($this->isValid('editpage')) {
    $list[] = $editLink->show();
}

if ($this->isValid('deletepage')) {
    $list[] = $deleteLink->show();
}

if (count($list) == 0) {
    $middle = '&nbsp;';
} else {
    $middle = '';
    $divider = '';
    
    foreach ($list as $item)
    {
        $middle .= $divider.$item;
        $divider = ' / ';
    }
}

if ($this->isValid('movepageup')) {
    
    $middle .= '<br />';
    
    if ($isFirstPageOnLevel) {
        $middle .= '<span style="text-decoration: line-through;" title="'.$this->objLanguage->languageText('mod_contextcontent_isfirstpageonlevel','contextcontent').'">'.$this->objLanguage->languageText('mod_contextcontent_movepageup','contextcontent').'</span>';
    } else {
        $link = new link($this->uri(array('action'=>'movepageup', 'id'=>$page['id'])));
        $link->link = $this->objLanguage->languageText('mod_contextcontent_movepageup','contextcontent');
        $middle .= $link->show();
    }
    
    $middle .= ' / ';
    
    if ($isLastPageOnLevel) {
        $middle .= '<span style="text-decoration: line-through;" title="'.$this->objLanguage->languageText('mod_contextcontent_islastpageonlevel','contextcontent').'">'.$this->objLanguage->languageText('mod_contextcontent_movepagedown','contextcontent').'</span>';
    } else {
        $link = new link($this->uri(array('action'=>'movepagedown', 'id'=>$page['id'])));
        $link->link = $this->objLanguage->languageText('mod_contextcontent_movepagedown','contextcontent');
        $middle .= $link->show();
    }
}


$table = $this->newObject('htmltable', 'htmlelements');
$table->startRow();
$table->addCell($prevPage, '33%', 'top');
$table->addCell($middle, '33%', 'top', 'center');
$table->addCell($nextPage, '33%', 'top', 'right');
$table->endRow();

$topTable = $this->newObject('htmltable', 'htmlelements');
$topTable->startRow();
$topTable->addCell($prevPage, '50%', 'top');
$topTable->addCell($nextPage, '50%', 'top', 'right');
$topTable->endRow();


$this->loadClass('link', 'htmlelements');

$this->setVar('pageTitle', htmlentities($this->objContext->getTitle().' - '.$page['menutitle']));

if (trim($page['headerscripts']) != '') {

/*
    $header = '
<![CDATA[
'.$page['headerscripts'].'
]]>
';*/
    $this->appendArrayVar('headerParams', $page['headerscripts']);


}

echo $topTable->show();

$objWashout = $this->getObject('washout', 'utilities');
echo $objWashout->parseText($page['pagecontent']);


echo '<hr />';

echo $table->show();

if (count($chapters) > 0) {
    $this->loadClass('form', 'htmlelements');
    $this->loadClass('dropdown', 'htmlelements');
    $this->loadClass('hiddeninput', 'htmlelements');
    $this->loadClass('button', 'htmlelements');
    $this->loadClass('label', 'htmlelements');

    $form = new form ('movetochapter', $this->uri(array('action'=>'movetochapter')));
    $hiddenInput = new hiddeninput('id', $page['id']);

    $dropdown = new dropdown('chapter');
    foreach ($chapters as $chapterItem)
    {
        $dropdown->addOption($chapterItem['chapterid'], $chapterItem['chaptertitle']);
    }
    $dropdown->setSelected($page['chapterid']);
    
    $label = new label ($this->objLanguage->languageText('mod_contextcontent_movepagetoanotherchapter','contextcontent').': ', 'input_chapter');
    
    $button = new button ('movepage', $this->objLanguage->languageText('mod_contextcontent_move','contextcontent'));
    $button->setToSubmit();
    
    $form->addToForm($hiddenInput->show().$label->show().$dropdown->show().' '.$button->show());
    
    echo $form->show();
    
}


?>