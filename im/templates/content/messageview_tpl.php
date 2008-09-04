<?php
$cssLayout = $this->newObject('csslayout', 'htmlelements');
$cssLayout->setNumColumns(2);

// get the sidebar object
$this->leftMenu = $this->newObject('usermenu', 'toolbar');
$this->loadClass('htmlheading', 'htmlelements');
$this->objFeatureBox = $this->getObject('featurebox', 'navigation');

$middleColumn = NULL;
$leftColumn = NULL;

// Add in a heading
$header = new htmlHeading();
$header->str = $this->objLanguage->languageText('mod_im_recentmessages', 'im');
$header->type = 1;

$middleColumn .= $header->show();

foreach($msgs as $msg)
{
	// whip out a content featurebox and plak the messages in
	$from = explode('/', $msg['msgfrom']);
	$middleColumn .= $this->objFeatureBox->show($from[0], $msg['msgbody']);
}

$leftColumn .= $this->leftMenu->show();

$cssLayout->setMiddleColumnContent($middleColumn);
$cssLayout->setLeftColumnContent($leftColumn);
echo $cssLayout->show();