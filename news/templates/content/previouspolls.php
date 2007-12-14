<?php

$this->loadClass('link', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');

$header = new htmlheading();
$header->type = 1;
$header->str = $this->objLanguage->languageText('mod_news_websitepolls', 'news', 'Website Polls');

echo $header->show();


if (count($polls) == 0) {
    echo '<div class="noRecordsMessage">'.$this->objLanguage->languageText('mod_news_nopollshavebeensetup', 'news', 'No Polls have been setup yet').'</div>';
} else {
    $table = $this->newObject('htmltable', 'htmlelements');
    
    $objIcon = $this->newObject('geticon', 'htmlelements');
    $objIcon->setIcon('delete');
    $delIcon = $objIcon->show();

    foreach ($polls as $poll)
    {
        $table->startRow();
        
        $cell = nl2br('<p><strong>'.$poll['pollquestion'].'</strong>');
        
        if ($this->isValid('deletpoll')) {
            $delLink = new link ($this->uri(array('action'=>'deletepoll', 'id'=>$poll['id'])));
            $delLink->link = $delIcon;
            
            $cell .= ' '.$delLink->show();
        }
        
        $cell .= '</p>';
        
        $cell .= $this->objPolls->showPollResults($poll['id']);
        
        $table->addCell($cell);
        //$table->addCell('<img src="'.$this->uri(array('action'=>'pollimage', 'id'=>$poll['id'])).'" />');
        
        $table->endRow();
        
        $table->startRow();
        $table->addCell('&nbsp;');
        $table->addCell('&nbsp;');
        $table->endRow();
        
    }

    echo $table->show();
}

if ($this->isValid('addpoll')) {
    $link = new link ($this->uri(array('action'=>'addpoll')));
    $link->link = $this->objLanguage->languageText('mod_news_addpoll', 'news', 'Add Poll');

    echo '<p>'.$link->show().'</p>';
}
?>