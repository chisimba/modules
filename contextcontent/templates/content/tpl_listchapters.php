<?php

$this->loadClass('link', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('form', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');
$this->loadClass('label', 'htmlelements');

$objModules = $this->getObject('modules', 'modulecatalogue');
$pdfHtmlDoc = $objModules->checkIfRegistered('htmldoc');



$objIcon = $this->newObject('geticon', 'htmlelements');
$objIcon->align = 'absmiddle';

$objIcon->setIcon('edit');
$editIcon = $objIcon->show();

$objIcon->setIcon('delete');
$deleteIcon = $objIcon->show();

$objIcon->setIcon('add');
$objIcon->alt = $this->objLanguage->languageText('mod_contextcontent_addanewchapter','contextcontent');
$objIcon->title = $this->objLanguage->languageText('mod_contextcontent_addanewchapter','contextcontent');
$addIcon = $objIcon->show();

$objIcon->setIcon('create_page');
$objIcon->alt = $this->objLanguage->languageText('mod_contextcontent_addapagetothischapter','contextcontent');
$objIcon->title = $this->objLanguage->languageText('mod_contextcontent_addapagetothischapter','contextcontent');
$addPageIcon = $objIcon->show();

$objIcon->setIcon('pdf');
$objIcon->alt = $this->objLanguage->languageText('mod_contextcontent_downloadchapterinpdfformat','contextcontent');
$objIcon->title = $this->objLanguage->languageText('mod_contextcontent_downloadchapterinpdfformat','contextcontent');
$pdfIcon = $objIcon->show();

if ($this->isValid('addchapter')) {
    $link = new link ($this->uri(array('action'=>'addchapter')));
    $link->link = $addIcon;
    
    $addChapter = $link->show();
} else {
	$addChapter = '';
}

echo '<h1>'.$this->objLanguage->languageText("mod_contextcontent_contextpagesfor",'contextcontent')." ".$this->objContext->getTitle().' '.$addChapter.'</h1>';

$counter = 1;
$notVisibleCounter=0;
$addedCounter=0;

// Form for Quick Jump to a Chapter
$form = new form($this->uri(array('action'=>'viewchapter')));
$form->method = 'GET';

$module = new hiddeninput('module', 'contextcontent');
$form->addToForm($module->show());

$action = new hiddeninput('action', 'viewchapter');
$form->addToForm($action->show());

$label = new label($this->objLanguage->languageText('mod_contextcontent_jumptochapter','contextcontent').': ', 'input_id');
$form->addToForm($label->show());

$dropdown = new dropdown('id');

// End Form

$chapterList = '<div id="allchapters">';

//print_r($chapters);

foreach ($chapters as $chapter)
{
    $showChapter = TRUE;
    
    if ($chapter['visibility'] == 'N') {
        $showChapter = FALSE;
    }
    
    if ($this->isValid('viewhiddencontent')) {
        $showChapter = TRUE;
    }
    
    if ($showChapter) {
        
        $addedCounter++;
        
		// Get List of Pages in the Chapter
        $chapterPages = $this->objContentOrder->getTree($this->contextCode, $chapter['chapterid'], 'htmllist');
		
		if (trim($chapterPages) == '<ul class="htmlliststyle"></ul>') {
			$hasPages = FALSE;
			$dropdown->addOption($chapter['chapterid'], $chapter['chaptertitle'], ' disabled="disabled" title="'.$this->objLanguage->languageText('mod_contextcontent_chapterhasnopages','contextcontent').'"');
            $notVisibleCounter++;
		} else {
			$hasPages = TRUE;
			$dropdown->addOption($chapter['chapterid'], $chapter['chaptertitle']);
		}
		
        $editLink = new link($this->uri(array('action'=>'editchapter', 'id'=>$chapter['chapterid'])));
        $editLink->link = $editIcon;
        
        $deleteLink = new link($this->uri(array('action'=>'deletechapter', 'id'=>$chapter['chapterid'])));
        $deleteLink->link = $deleteIcon;
		
		$addPageLink = new link($this->uri(array('action'=>'addpage', 'chapter'=>$chapter['chapterid'])));
        $addPageLink->link = $addPageIcon;
        
		$chapterLink = new link($this->uri(array('action'=>'viewchapter', 'id'=>$chapter['chapterid'])));
		$chapterLink->link = $chapter['chaptertitle'];
        
		if (trim($chapterPages) == '<ul class="htmlliststyle"></ul>') {
			$content = '<h1>'.$chapter['chaptertitle'];
		} else {
			$content = '<h1>'.$chapterLink->show();
		}
        
        if ($this->isValid('editchapter')) {
            $content .= ' '.$editLink->show();
        }
        
        if ($this->isValid('deletechapter')) {
            $content .= ' '.$deleteLink->show();
        }
		
		if ($this->isValid('addpage')) {
            $content .= ' '.$addPageLink->show();
        }
		
        
        if ($pdfHtmlDoc && trim($chapterPages) != '<ul class="htmlliststyle"></ul>') {
            
            $pdfLink = new link($this->uri(array('action'=>'viewprintchapter', 'id'=>$chapter['chapterid'])));
            $pdfLink->link = $pdfIcon;
            
            $content .= ' '.$pdfLink->show();
        }
        
        $content .= '</h1>';
        
        //print_r($chapter);
        
        if ($this->isValid('viewhiddencontent') && $chapter['visibility'] != 'Y') {
            
            switch ($chapter['visibility'])
            {
                case 'I': $notice = $this->objLanguage->code2Txt('mod_contextcontent_studentcanonlyviewintro','contextcontent'); break;
                case 'N': $notice = $this->objLanguage->code2Txt('mod_contextcontent_chapternotvisibletostudents','contextcontent'); break;
                default: $notice = ''; break;
            }
            $content .= '<p class="warning"><strong>'.$this->objLanguage->languageText('mod_contextcontent_note','contextcontent').': </strong>'.$notice.'</p>';
        }
        
        $content .= $chapter['introduction'];
        
        if ($chapter['visibility'] == 'I' && !$this->isValid('viewhiddencontent')) {
            $content .= '<p class="warning">'.ucfirst($this->objLanguage->code2Txt('mod_contextcontent_studentscannotaccesscontent','contextcontent')).'.</p>';
				
				// Empty variable for use later on
				$chapterPages = '';
        } else {
			
            if (trim($chapterPages) == '<ul class="htmlliststyle"></ul>' && $this->isValid('viewhiddencontent')) {
                $content .= '<div class="noRecordsMessage">'.$this->objLanguage->languageText('mod_contextcontent_chapterhasnocontentpages','contextcontent').'</div>';
				
				// Empty variable for use later on
				$chapterPages = '';
				
            } else if (trim($chapterPages) == '<ul class="htmlliststyle"></ul>') {
                $content .= '<div class="noRecordsMessage">'.$this->objLanguage->languageText('mod_contextcontent_chapterhasnocontentpages','contextcontent').'</div>';
				
				// Empty variable for use later on
				$chapterPages = '';
            } else {
                $chapterPages = '<div style="display:none" id="toc_'.$chapter['chapterid'].'"><p><strong>Content:</strong></p>'.$chapterPages.'</div>'.'<a href="javascript:showHideChapter(\'toc_'.$chapter['chapterid'].'\');"><strong>'.$this->objLanguage->languageText('mod_contextcontent_showhidecontents','contextcontent').' ...</strong></a>';
				
				$content .= $chapterPages;
            }
        }
        
        $addPageLink = new link ($this->uri(array('action'=>'addpage', 'chapter'=>$chapter['chapterid'])));
        $addPageLink->link = $this->objLanguage->languageText('mod_contextcontent_addapagetothischapter','contextcontent');
        
        $moveUpLink = new link ($this->uri(array('action'=>'movechapterup', 'id'=>$chapter['contextchapterid'])));
        $moveUpLink->link = $this->objLanguage->languageText('mod_contextcontent_movechapterup','contextcontent');
        
        $moveDownLink = new link ($this->uri(array('action'=>'movechapterdown', 'id'=>$chapter['contextchapterid'])));
        $moveDownLink->link = $this->objLanguage->languageText('mod_contextcontent_movechapterdown','contextcontent');
		
		//$content .= '<br />';
        
        if ($this->isValid('addpage')) {
            //$content .= $addPageLink->show();
        }
        
        if (count($chapters) > 1 && $counter > 1 && $this->isValid('movechapterup')) {
            $content .= ' / '.$moveUpLink->show();
        }
        
        if ($counter < count($chapters) && $this->isValid('movechapterdown')) {
            $content .= ' / '.$moveDownLink->show();
        }
        
        $chapterList .= '<div>'.$content.'</div><hr />';
    }
    
    $counter++;
}

$chapterList .= '</div>';


if (count($chapters) > 1) {
	$form->addToForm($dropdown->show());

	$button = new button ('', 'Go');
	$button->setToSubmit();
    
    if ($notVisibleCounter == $addedCounter) {
        $button->extra = ' disabled="disabled" ';
    }
    
	$form->addToForm(' '.$button->show());
	
	echo $form->show();
}

echo $chapterList;

if ($this->isValid('addchapter')) {
    $link = new link ($this->uri(array('action'=>'addchapter')));
    $link->link = $this->objLanguage->languageText('mod_contextcontent_addanewchapter','contextcontent');
    
    echo $link->show();
}


?>
<script type="text/javascript">
//<![CDATA[
	
	function showHideChapter(chapterId)
	{
		Effect.toggle(chapterId, 'appear', {oncomplete: function() {
                adjustLayout();
			}});
		var oTime = window.setTimeout('adjustLayout()',1000); 
	}
//]]>
</script>