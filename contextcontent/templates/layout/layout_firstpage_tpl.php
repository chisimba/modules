<?php


$this->loadClass('link', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('form', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('label', 'htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');

$cssLayout = $this->newObject('csslayout', 'htmlelements');

$form = new form ('searchform', $this->uri(array('action'=>'search')));
$form->method = 'GET';

$hiddenInput = new hiddeninput('module', 'contextcontent');
$form->addToForm($hiddenInput->show());

$hiddenInput = new hiddeninput('action', 'search');
$form->addToForm($hiddenInput->show());

$textinput = new textinput ('contentsearch', $this->getParam('contentsearch'));
$button = new button ('searchgo', 'Go');
$button->setToSubmit();

$form->addToForm($textinput->show().' '.$button->show());

$objFieldset = $this->newObject('fieldset', 'htmlelements');
$label = new label ('Search for:', 'input_contentsearch');

$objFieldset->setLegend($label->show());
$objFieldset->contents = $form->show();

$header = new htmlHeading();
$header->str = ucwords($this->objLanguage->code2Txt('mod_contextcontent_name', 'contextcontent', NULL, '[-context-] Content'));
$header->type = 2;

$content = $header->show();

$content .= $objFieldset->show();

$content .= '<h3>Chapters:</h3>';
$chapters = $this->objContextChapters->getContextChapters($this->contextCode);

if (count($chapters) > 0) {
    
    $content .= '<ol>';
    
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
            $bookmarkLink = new link("#{$chapter['chapterid']}");
            $bookmarkLink->link = "->";
            $bookmarkLink->title = "Scroll to Chapter";
            //if ($chapter['pagecount'] == 0) {
            //    $content .= '<li title="Chapter has no content pages">'.$chapter['chaptertitle'];
            //} else {
                $link = new link ($this->uri(array('action'=>'viewchapter', 'id'=>$chapter['chapterid'])));
                $link->link = $chapter['chaptertitle'];
                $content .= '<li>'.$link->show();
            //}
            
            if (isset($showScrollLinks) && $showScrollLinks) {
                $content .= " ".$bookmarkLink->show().'</li>';
            }
            
        }
    }
    
    $content .= '</ol>';
}

if ($this->isValid('addchapter')) {
    $link = new link ($this->uri(array('action'=>'addchapter')));
    $link->link = $this->objLanguage->languageText('mod_contextcontent_addanewchapter','contextcontent');
    
    $content .=  '<br /><p>'.$link->show().'</p>';
}


$cssLayout->setLeftColumnContent($content);

$cssLayout->setMiddleColumnContent($this->getContent());

echo $cssLayout->show();
//Check if poll module is installed
$pollInstalled = $this->objModuleCatalogue->checkIfRegistered('poll');
//get the version number
$pollModuleVer = $this->objModuleCatalogue->getVersion('poll');
//Display polls if poll is installed & version is higher than 0.121
if($pollModuleVer>='0.121' && $pollInstalled==True){
	if ($this->getParam('message')==Null) {
		    $alertBox = $this->getObject('alertbox', 'htmlelements');
		    $alertBox->putJs();
		    echo "<script>
		 jQuery.facebox(function() {
		  jQuery.get('".str_replace('&amp;', '&', $this->uri(array('action'=>'happyeval'), 'poll'))."', function(data) {
		    jQuery.facebox(data);
		  })
		})
		</script>";
	}
}
?>
