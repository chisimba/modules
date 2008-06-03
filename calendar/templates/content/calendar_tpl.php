<script language="JavaScript" type="text/javascript" >

 jQuery(document).ready(function(){
        setupCalendarCheckbox('userbox', 'event_user');
        setupCalendarCheckbox('contextbox', 'event_context');
        setupCalendarCheckbox('otherbox', 'event_othercontext');
        setupCalendarCheckbox('sitebox', 'event_site');
    });
    
    
function setupCalendarCheckbox(checkId, itemClass)
{
    jQuery("#"+checkId).livequery('click', function() {
            jQuery("."+itemClass).toggle();
        });
}
</script>


<?php

$this->loadClass('link', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('label', 'htmlelements');
$this->loadclass('checkbox','htmlelements');

$this->appendArrayVar('headerParams', $this->getJavaScriptFile('jquery/jquery.livequery.js', 'htmlelements'));


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
        
        //echo '<div style="float:right">'.$timeOutMessage->show().'</div>';
    }
}

$addIcon = $this->getObject('geticon', 'htmlelements');
$addIcon->setIcon('add');
$addIcon->title = $this->objLanguage->languageText('mod_calendarbase_addevent', 'calendarbase');

$addEventLink = new link($this->uri(array('action' => 'add', 'month'=>$month, 'year'=>$year)));
$addEventLink->link = $addIcon->show();

$title = $this->objLanguage->languageText('mod_calendarbase_someonescalendar', 'calendarbase');

$heading = new htmlheading();
$heading->str = str_replace('[someone]', $fullname, $title).' '.$addEventLink->show();
$heading->type = 1;

echo $heading->show();


$checkboxes = array();

$checkbox = new checkbox('userbox', NULL, TRUE);
$checkbox->cssId='userbox';
$label = new label('User Events ('.$userEvents.')', 'userbox');
$checkboxes[] = $checkbox->show().' '.$label->show();

if ($this->contextCode == 'root') {
    $checkbox = new checkbox('otherbox', NULL, TRUE);
    $checkbox->cssId='otherbox';
    $label = new label('My Courses ('.$otherContextEvents.')', 'otherbox');
    $checkboxes[] = $checkbox->show().' '.$label->show();

} else {
    $checkbox = new checkbox('contextbox', NULL, TRUE);
    $checkbox->cssId='contextbox';
    $label = new label('Current Course: '.$this->contextTitle.' ('.$contextEvents.')', 'contextbox');
    $checkboxes[] = $checkbox->show().' '.$label->show();
    
    $checkbox = new checkbox('otherbox', NULL, TRUE);
    $checkbox->cssId='otherbox';
    $label = new label('Other Courses ('.$otherContextEvents.')', 'otherbox');
    $checkboxes[] = $checkbox->show().' '.$label->show();
}

$checkbox = new checkbox('sitebox', NULL, TRUE);
$checkbox->cssId='sitebox';
$label = new label('Site Events ('.$siteEvents.')', 'sitebox');
$checkboxes[] = $checkbox->show().$label->show();

$divider = '';
foreach ($checkboxes as $option)
{
    echo $divider.$option;
    $divider = ' &nbsp; &nbsp; &nbsp; ';
}



echo $calendarNavigation.$eventsCalendar;

echo $eventsList;

$addEventLink = new link($this->uri(array('action' => 'add')));

$addEventLink->link = $this->objLanguage->languageText('mod_calendarbase_addevent', 'calendarbase');

echo '<p>'.$addEventLink->show().'</p>';



?>