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


$content = $objFieldset->show();

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
            if ($chapter['pagecount'] == 0) {
                $content .= '<li title="Chapter has no content pages">'.$chapter['chaptertitle'].'</li>';
            } else {
                $link = new link ($this->uri(array('action'=>'viewchapter', 'id'=>$chapter['chapterid'])));
                $link->link = $chapter['chaptertitle'];
                $content .= '<li>'.$link->show().'</li>';
            }
        }
    }
    
    $content .= '</ol>';
}


$cssLayout->setLeftColumnContent($content);

$cssLayout->setMiddleColumnContent($this->getContent());

echo $cssLayout->show();


?>