<?php

$this->loadClass('htmlheading', 'htmlelements');
$header = new htmlheading();
$header->type = 2;
$header->str = $this->objLanguage->languageText('mod_podcaster_myevents', 'podcaster', 'My events');
echo $header->show();

$content = "The system needs to be upgraded with the new group manager for this to work";
if (class_exists('groupops', false)) {
    $content = $this->objEventUtils->getUserGroups();
}
echo $content;
?>