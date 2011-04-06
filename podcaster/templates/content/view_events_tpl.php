<?php

$this->loadClass('htmlheading', 'htmlelements');
//Add Group Link
$iconAdd = $this->getObject('geticon', 'htmlelements');
$iconAdd->setIcon('configure');
$iconAdd->title = $this->objLanguage->languageText("mod_podcaster_configureevents", 'podcaster', 'Configure events');
$iconAdd->alt = $this->objLanguage->languageText("mod_podcaster_configureevents", 'podcaster', 'Configure events');

$objLink = &$this->getObject('link', 'htmlelements');
$objLink->link($this->uri(array(
            'module' => 'podcaster',
            'action' => 'configure_events'
        )));

$objLink->link = $iconAdd->show();
$mylinkAdd = $objLink->show();

$header = new htmlheading();
$header->type = 2;
$header->str = $this->objLanguage->languageText('mod_podcaster_eventlist', 'podcaster', 'Event list') . " " . $mylinkAdd;

echo $header->show();

$content = "The system needs to be upgraded with the new group manager for this to work";
if (class_exists('groupops', false)) {
    $content = $this->objEventUtils->getUserEvents();
}
echo $content;
//Add Group Link
$iconAdd = $this->getObject('geticon', 'htmlelements');
$iconAdd->setIcon('configure');
$iconAdd->title = $this->objLanguage->languageText("mod_podcaster_configureevents", 'podcaster', 'Configure events');
$iconAdd->alt = $this->objLanguage->languageText("mod_podcaster_configureevents", 'podcaster', 'Configure events');

$objLink = &$this->getObject('link', 'htmlelements');
$objLink->link($this->uri(array(
            'module' => 'podcaster',
            'action' => 'configure_events'
        )));
$objLink->link = $iconAdd->show();
$mylinkAddImg = $objLink->show();

$objLink->link($this->uri(array(
            'module' => 'podcaster',
            'action' => 'configure_events'
        )));
$objLink->link = $this->objLanguage->languageText("mod_podcaster_configureevents", 'podcaster', 'Configure events');
$mylinkAddTxt = $objLink->show();

echo $mylinkAddTxt . " " . $mylinkAddImg;
?>