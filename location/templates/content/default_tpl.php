<?php

// Set up the CSS layout
$cssLayout = $this->newObject('csslayout', 'htmlelements');
$cssLayout->setNumColumns(2);

// Set up the left sidebar
$userMenu = $this->newObject('usermenu', 'toolbar');
$leftColumn = $userMenu->show();
$cssLayout->setLeftColumnContent($leftColumn);

$this->loadClass('htmlheading', 'htmlelements');

// Set up the heading
$header = new htmlHeading();
$header->str = 'Test';//$this->objLanguage->languageText('mod_im_recentmessages', 'im');
$header->type = 1;

$middleColumn = $header->show();

$middleColumn .= $this->getVar('locationName');
$middleColumn .= $this->getVar('locationLongitude');
$middleColumn .= $this->getVar('locationLatitude');

if ($this->getVar('locationTwitter')) {
    $middleColumn .= 'Twitter is currently enabled.';
} else {
    $middleColumn .= 'Twitter is currently disabled.';
}

$cssLayout->setMiddleColumnContent($middleColumn);

echo $cssLayout->show();
