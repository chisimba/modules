<?php

$this->loadClass('link', 'htmlelements');


//mod_contextcontent_contextpagesfor
echo '<h1>'.$this->objLanguage->languageText("mod_contextcontent_contextpagesfor",'contextcontent')." ".$this->objContext->getTitle().'</h1>';

if ($this->isValid('addpage')) {

	
    echo"<div class= 'noRecordsMessage' >".$this->objLanguage->languageText('mod_contextcontent_nocontextpages','contextcontent').'</div>';

    $addLink = new link($this->uri(array('action'=>'addpage', 'context'=>$this->contextCode)));
    $addLink->link = $this->objLanguage->languageText('mod_contextcontent_addnewcontextpages','contextcontent');

    echo '<p>'.$addLink->show().'</p>';
} else {
    echo '<div class="noRecordsMessage" >'.$this->objLanguage->languageText('mod_contextcontent_nocontextpage','contextcontent').'</div>';
}
?>