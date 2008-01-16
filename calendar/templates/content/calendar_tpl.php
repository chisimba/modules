<?php

$this->loadClass('link', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('form', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('label', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');

$message = $this->getParam('message');
if (isset($message)) {
    switch ($message)
    {
        case 'eventadded' : $text = $this->objLanguage->languageText('mod_calendarbase_eventaddconfirm', 'calendarbase'); break;
        case 'eventupdated' : $text = $this->objLanguage->languageText('mod_calendarbase_eventeditconfirm', 'calendarbase'); break;
        case 'eventdeleted' : $text = $this->objLanguage->languageText('mod_calendarbase_eventdeleteconfirm', 'calendarbase'); break;
        
        default : $text = '';
    }
    
    if ($text != '') {
        $timeOutMessage =& $this->getObject('timeoutmessage', 'htmlelements');
        $timeOutMessage->setMessage($text);
        $timeOutMessage->setHideTypeToHidden();
        
        echo '<div style="float:right">'.$timeOutMessage->show().'</div>';
    }
}

$addIcon = $this->getObject('geticon', 'htmlelements');
$addIcon->setIcon('add');
$addIcon->title = $this->objLanguage->languageText('mod_calendarbase_addevent', 'calendarbase');

$addEventLink = new link($this->uri(array('action' => 'add', 'event'=>$currentList, 'month'=>$month, 'year'=>$year)));
$addEventLink->link = $addIcon->show();

$title = $this->objLanguage->languageText('mod_calendarbase_someonescalendar', 'calendarbase');

$heading = new htmlheading();
$heading->str = str_replace('[someone]', $fullname, $title).' '.$addEventLink->show();
$heading->type = 1;

echo $heading->show();

$form = new form('index.php');
$form->method = 'GET';

$dropdown = new dropdown('events');
if ($isInContext) {
	$courselabel = ucwords($this->objLanguage->code2Txt('mod_calendarbase_personalandcoursecalendar', 'calendarbase', NULL, 'Personal Calendar and {COURSE} [-context-] Calendar'));
	$courselabel = str_replace('{COURSE}', $courseTitle, $courselabel);
	$dropdown->addOption('all', $courselabel);
}
$dropdown->addOption('user', $this->objLanguage->languageText('mod_calendarbase_mypersonalcalendar', 'calendarbase', 'My Personal Calendar'));
if ($isInContext) {
	$courselabel = ucwords($this->objLanguage->code2Txt('mod_calendarbase_coursecalendar', 'calendarbase', NULL, '{COURSE} [-context-] Calendar'));
	$courselabel = str_replace('{COURSE}', $courseTitle, $courselabel);
	$dropdown->addOption('context', $courselabel);
}
$dropdown->addOption('site', $this->objLanguage->languageText('mod_calendarbase_sitecalendar', 'calendarbase', 'Site Calendar'));

$dropdown->setSelected($currentList);
$label = new label('Show: ', 'input_events');

$button = new button('');
$button->value='Go';
$button->setToSubmit();

$form->addToForm($label->show().$dropdown->show().$button->show().'<br />&nbsp;');



$module = new hiddeninput ('module', 'calendar');
$form->addToForm($module->show());

$year = new hiddeninput ('year', $year);
$form->addToForm($year->show());

$month = new hiddeninput ('month', $month);
$form->addToForm($month->show());

echo $form->show();

echo $eventsCalendar;

$addEventLink = new link($this->uri(array('action' => 'add')));

$addEventLink->link = $this->objLanguage->languageText('mod_calendarbase_addevent', 'calendarbase');

echo '<p>'.$addEventLink->show().'</p>';
?>
