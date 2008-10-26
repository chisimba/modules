<?php

// Load the necessary HTML helper classes
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('href', 'htmlelements');

// Set up the CSS layout
$cssLayout = $this->newObject('csslayout', 'htmlelements');
$cssLayout->setNumColumns(2);

// Set up the left sidebar
$userMenu = $this->newObject('usermenu', 'toolbar');
$leftColumn = $userMenu->show();
$cssLayout->setLeftColumnContent($leftColumn);

// Set up the heading
$header = new htmlHeading();
$header->str = 'Current Location';//$this->objLanguage->languageText('mod_im_recentmessages', 'im');
$header->type = 1;
$middleColumn = $header->show();

$middleColumn .= '<ul>';
$middleColumn .= '<li>' . $this->getVar('locationName') . '</li>';
$middleColumn .= '<li>' . $this->getVar('locationLongitude') . '</li>';
$middleColumn .= '<li>' . $this->getVar('locationLatitude') . '</li>';
$middleColumn .= '</ul>';

if ($this->getVar('locationTwitter')) {
    $uri = $this->uri(array('module'=>'location', 'action'=>'disabletwitter'));
    $href = new href($uri, 'Disable');
    $middleColumn .= '<p>Twitter is currently enabled. '.$href->show().'</p>';
} else {
    $uri = $this->uri(array('module'=>'location', 'action'=>'enabletwitter'));
    $href = new href($uri, 'Enable');
    $middleColumn .= '<p>Twitter is currently disabled. '.$href->show().'</p>';
}

$cssLayout->setMiddleColumnContent($middleColumn);

echo $cssLayout->show();
