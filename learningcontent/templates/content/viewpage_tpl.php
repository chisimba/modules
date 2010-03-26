<?php

$objFile = $this->getObject('dbfile', 'filemanager');
$objHead = $this->newObject('htmlheading', 'htmlelements');
$addLink = new link ($this->uri(array('action'=>'addpage', 'id'=>$page['id'], 'context'=>$this->contextCode, 'chapter'=>$page['chapterid'])));
$addLink->link = $this->objLanguage->languageText('mod_learningcontent_addcontextpages','learningcontent');

$addScormLink = new link ($this->uri(array('action'=>'addscormpage', 'id'=>$page['id'], 'context'=>$this->contextCode, 'chapter'=>$page['chapterid'])));
$addScormLink->link = $this->objLanguage->languageText('mod_learningcontent_addcontextscormpages','learningcontent');


$editLink = new link ($this->uri(array('action'=>'editpage', 'id'=>$page['id'], 'context'=>$this->contextCode)));
$editLink->link = $this->objLanguage->languageText('mod_learningcontent_editcontextpages','learningcontent');

if (($page['rght'] - $page['lft'] - 1) == 0) {
    $deleteLink = new link ($this->uri(array('action'=>'deletepage', 'id'=>$page['id'], 'context'=>$this->contextCode)));
} else {
    $deleteLink = new link ("javascript:alert('".$this->objLanguage->languageText('mod_learningcontent_pagecannotbedeleteduntil','learningcontent').".');");
}
$deleteLink->link = $this->objLanguage->languageText('mod_learningcontent_delcontextpages','learningcontent');

$list = array();

if ($this->isValid('addpage')) {
    $list[] = $addLink->show();
    $list[] = $addScormLink->show();
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
        $middle .= '<span style="color:grey;" title="'.$this->objLanguage->languageText('mod_learningcontent_isfirstpageonlevel','learningcontent').'">'.$this->objLanguage->languageText('mod_learningcontent_movepageup','learningcontent').'</span>';
    } else {
        $link = new link($this->uri(array('action'=>'movepageup', 'id'=>$page['id'])));
        $link->link = $this->objLanguage->languageText('mod_learningcontent_movepageup','learningcontent');
        $middle .= $link->show();
    }
    
    $middle .= ' / ';
    
    if ($isLastPageOnLevel) {
        $middle .= '<span style="color:grey;" title="'.$this->objLanguage->languageText('mod_learningcontent_islastpageonlevel','learningcontent').'">'.$this->objLanguage->languageText('mod_learningcontent_movepagedown','learningcontent').'</span>';
    } else {
        $link = new link($this->uri(array('action'=>'movepagedown', 'id'=>$page['id'])));
        $link->link = $this->objLanguage->languageText('mod_learningcontent_movepagedown','learningcontent');
        $middle .= $link->show();
    }
}


$table = $this->newObject('htmltable', 'htmlelements');
//$table->border='1';
$table->startRow();
$table->addCell($prevPage, '33%', 'top');
$table->addCell($middle, '33%', 'top', 'center');
$table->addCell($nextPage, '33%', 'top', 'right');
$table->endRow();

$table2 = $this->newObject('htmltable', 'htmlelements');
//$table->border='1';
$table2->startRow();
$table2->addCell($prevPage, '33%', 'top');
$table2->addCell('&nbsp;', '33%', 'top', 'center');
$table2->addCell($nextPage, '33%', 'top', 'right');
$table2->endRow();

$topTable = $this->newObject('htmltable', 'htmlelements');
//$topTable->border='1';
$topTable->startRow();
$topTable->addCell($prevPage, '50%', 'top');
$topTable->addCell($nextPage, '50%', 'top', 'right');
$topTable->endRow();


$this->loadClass('link', 'htmlelements');

$this->setVar('pageTitle', htmlentities($this->objContext->getTitle().' - '.$page['menutitle']));

if (trim($page['headerscripts']) != '') {
    
    // Explode into array
    $scripts = explode(',', $page['headerscripts']);
    
    // Loop through array
    foreach ($scripts as $script)
    {
        // Check if valid
        if (trim($script) != '') {
            
            // Get Path
            $fileInfo = $objFile->getFilePath($script);
            
            // If Valid
            if ($fileInfo != FALSE) {
                
                // Check if Script or CSS, and display
                if (substr($fileInfo, -2, 2) == 'js') {
                    $this->appendArrayVar('headerParams', '<script type="text/javascript" src="'.$fileInfo.'"></script>');
                } else {
                    $this->appendArrayVar('headerParams', '<link rel="stylesheet" type="text/css" href="'.$fileInfo.'"');
                }
            }
        }
    }
    
    //$this->appendArrayVar('headerParams', $page['headerscripts']);


}


$objWashout = $this->getObject('washout', 'utilities');
$objFilePreviewFilter = $this->getObject('parse4filepreview', 'filters');
$content = $objWashout->parseText($page['pagecontent']);
$pagepicture = $page['pagepicture'];
//Get the name of each pagepicture
if(!empty($pagepicture)){
 $hpictures = explode(',',$page['pagepicture']);
 $hpics = "";
 foreach($hpictures as $hpicture){
  if(!empty($hpicture)){
   $picname = $this->objFiles->getFileName($hpicture);
   $picid = $hpicture;
   $hpics .= $objFilePreviewFilter->parse('[FILEPREVIEW id="'.$picid.'" comment="'.$picname.'" /]');
  }
 }
}
$pageformula = $page['pageformula'];
//Get the name of each headerscripts
if(!empty($pageformula)){
 $hformulas = explode(',',$page['pageformula']);
 $hformula = "";
 foreach($hformulas as $fmla){
  if(!empty($fmla)){
   $fmlaname = $this->objFiles->getFileName($fmla);
   $fmlaid = $fmla;
   $hformula .= $objFilePreviewFilter->parse('[FILEPREVIEW id="'.$fmlaid.'" comment="'.$fmlaname.'" /]');
  }
 }
}
 $objPHead = $this->newObject('htmlheading', 'htmlelements');
	$wordPicture = $this->objLanguage->languageText('mod_learningcontent_picture','learningcontent');
	$objPHead->type = 2;
	$objPHead->str = $wordPicture;
 $objFHead = $this->newObject('htmlheading', 'htmlelements');
	$wordFormula = $this->objLanguage->languageText('mod_learningcontent_formula','learningcontent');
	$objFHead->type = 2;
	$objFHead->str = $wordFormula;

$content = $content.$objPHead->show()."<p>".$hpics."</p>".$objFHead->show()."<p>".$hformula."</p>";

$form = "";

if (count($chapters) > 1 && $this->isValid('movetochapter')) {
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
    
    $label = new label ($this->objLanguage->languageText('mod_learningcontent_movepagetoanotherchapter','learningcontent').': ', 'input_chapter');
    
    $button = new button ('movepage', $this->objLanguage->languageText('mod_learningcontent_move','learningcontent'));
    $button->setToSubmit();
    
    $form->addToForm($hiddenInput->show().$label->show().$dropdown->show().' '.$button->show());
    
    $form = $form->show();
    
}

if ($this->isValid('addpage')) {
  $objTabs = $this->newObject('tabcontent', 'htmlelements');
  $objTabs->setWidth('98%');
  $objTabs->addTab("Lecturer View",$topTable->show().$content.'<hr />'.$table->show().$form);
  $objTabs->addTab("Student View",$topTable->show().$content.'<hr />'.$table2->show());
  echo $objTabs->show();
}
else {
  echo $topTable->show().$content.'<hr />'.$table->show().$form;
}

if(strtolower($this->objSysConfig->getValue('learningcontent_ENABLECOMMENTS', 'learningcontent')) == 'true')
{
	$head = $this->objLanguage->languageText('mod_learningcontent_word_comment','learningcontent');
	$objHead->type = 1;
	$objHead->str = $head;
	echo '<br/>'.$objHead->show().'<br/>';

	$commentpost = $this->objContextComments->getPageComments($currentPage);
	if (count($commentpost) < 1)
	{
		
		echo $this->objLanguage->languageText('mod_learningcontent_nocomment','learningcontent').'<br/>';
	}
	else{
		$cnt = 0;
		$oddcolor = $this->objSysConfig->getValue('learningcontent_ODD', 'learningcontent');
		$evencolor = $this->objSysConfig->getValue('learningcontent_EVEN', 'learningcontent');

		foreach($commentpost as $comment)
		{
			$objOutput = '<strong>'.$this->objUser->fullname($comment['userid']).'</strong><br/>';
			$objOutput .= '<i>'.$comment['datecreated'].'</i><br/>';
			$objOutput .= $comment['comment'];

			if($cnt%2 == 0)
				{
					echo '<div class="colorbox '.$evencolor.'box">'.$objOutput.'</div>';
				}
			else
				{
					echo '<div class="colorbox '.$oddcolor.'box">'.$objOutput.'</div>';
				}
			$cnt++;
		}
	}
	$this->loadClass('textarea', 'htmlelements');
	$cform = new form('learningcontent', $this->uri(array('action' => 'addcomment', 'pageid' => $currentPage)));
	
	//start a fieldset
	$cfieldset = $this->getObject('fieldset', 'htmlelements');
	$ct = $this->newObject('htmltable', 'htmlelements');
	$ct->cellpadding = 5;

	//Text
	$ct->startRow();
	$ctvlabel = new label($this->objLanguage->languageText('mod_learningcontent_writecomment', 'learningcontent').':','input_cvalue');
	$ct->addCell($ctvlabel->show());
	$ct->endRow();

	//Textarea
	$ct->startRow();
	$ctv = new textarea('comment', '', 8, 70);
	$ct->addCell($ctv->show());
	$ct->endRow();
	//end off the form and add the button
	$this->objconvButton = new button($this->objLanguage->languageText('mod_learningcontent_submitcomment', 'learningcontent'));
	$this->objconvButton->setValue($this->objLanguage->languageText('mod_learningcontent_submitcomment', 'learningcontent'));
	$this->objconvButton->setToSubmit();
	$cfieldset->addContent($ct->show());
	$cform->addToForm($cfieldset->show());
	$cform->addToForm($this->objconvButton->show());
	echo '<br/>'.$cform->show();
}

/*
<script type="text/javascript">
//<![CDATA[

function togglePageOptions()
{
    Effect.toggle('pageoptions', 'slide');
    window.setInterval("adjustLayout();", 200);

}

function changeBookmark (type) {
	var url = 'index.php';
	var pars = 'module=learningcontent&action=changebookmark&id=<?php echo $page['id']; ?>&type='+type;
	var myAjax = new Ajax.Request( url, {method: 'get', parameters: pars, onComplete: showBookmarkResponse} );
}

function showBookmarkResponse (originalRequest) {
	var newData = originalRequest.responseText;
    
    if (newData != '') {
        $('bookmarkOptions').innerHTML = newData;
        adjustLayout();
    }
}
//]]>
</script>
*/

?>
