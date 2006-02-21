<?php

$this->loadClass('link', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');

$addIcon = $this->getObject('geticon', 'htmlelements');
$addIcon->setIcon('add');
$addIcon->title = $this->objLanguage->languageText('mod_calendarbase_addevent');

$addEventLink = new link($this->uri(array('action' => 'add')));
$addEventLink->link = $addIcon->show();

$title = $this->objLanguage->languageText('mod_calendarbase_someonescalendar');

if ($this->isValid('add')) {
    $headerString = str_replace('[someone]', $fullname, $title).' '.$addEventLink->show();
} else {
    $headerString = str_replace('[someone]', $fullname, $title);
}


$heading = new htmlheading();
$heading->str = $headerString;
$heading->type = 1;

echo $heading->show();

$message = $this->getParam('message');
if (isset($message)) {
    switch ($message)
    {
        case 'eventadded' : $text = $this->objLanguage->languageText('mod_calendarbase_eventaddconfirm'); break;
        case 'eventupdated' : $text = $this->objLanguage->languageText('mod_calendarbase_eventeditconfirm'); break;
        case 'eventdeleted' : $text = $this->objLanguage->languageText('mod_calendarbase_eventdeleteconfirm'); break;
        
        default : $text = '';
    }
    
    if ($text != '') {
        $timeOutMessage =& $this->getObject('timeoutmessage', 'htmlelements');
        $timeOutMessage->setMessage($text);
        $timeOutMessage->setHideTypeToHidden();
        
        echo $timeOutMessage->show();
    }
    
}

echo $eventsCalendar;

$addEventLink = new link($this->uri(array('action' => 'add')));

$addEventLink->link = $this->objLanguage->languageText('mod_calendarbase_addevent');

if ($this->isValid('add')) {
    echo '<p>'.$addEventLink->show().'</p>';
}
?>