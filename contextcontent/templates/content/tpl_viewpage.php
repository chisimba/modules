<?php

$addLink = new link ($this->uri(array('action'=>'addpage', 'id'=>$page['id'], 'context'=>$this->contextCode)));
$addLink->link = $this->objLanguage->languageText('mod_contextcontent_addcontextpages','contextcontent');

$editLink = new link ($this->uri(array('action'=>'editpage', 'id'=>$page['id'], 'context'=>$this->contextCode)));
$editLink->link = $this->objLanguage->languageText('mod_contextcontent_editcontextpages','contextcontent');

if (($page['rght'] - $page['lft'] - 1) == 0) {
    $deleteLink = new link ($this->uri(array('action'=>'deletepage', 'id'=>$page['id'], 'context'=>$this->contextCode)));
} else {
    $deleteLink = new link ("javascript:alert('This Page cannot be deleted until the Sub Pages are Deleted.');");
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
$link = new link($this->uri(array('action'=>'movepageup', 'id'=>$page['id'])));
$link->link = 'Move Page Up';

$link2 = new link($this->uri(array('action'=>'movepagedown', 'id'=>$page['id'])));
$link2->link = 'Move Page Down';

$middle .= '<br />'.$link->show().' / '.$link2->show();
}


$table = $this->newObject('htmltable', 'htmlelements');
$table->startRow();
$table->addCell($prevPage, '33%', 'top');
$table->addCell($middle, '33%', 'top', 'center');
$table->addCell($nextPage, '33%', 'top', 'right');
$table->endRow();


$this->loadClass('link', 'htmlelements');

$this->setVar('pageTitle', htmlentities($this->objContext->getTitle().' - '.$page['menutitle']));

if (trim($page['headerscripts']) != '') {

    $header = '
<![CDATA[
'.$page['headerscripts'].'
]]>
';
    $this->appendArrayVar('headerParams', $header);


}

//echo $table->show();

echo '<div id="breadcrumb">'.$breadcrumbs.'</div>';

echo $page['pagecontent'];


echo '<hr />';





echo $table->show();



?>