<?php
$cssLayout = $this->newObject('csslayout', 'htmlelements');
$cssLayout->setNumColumns(2);

// get the sidebar object
$this->leftMenu = $this->newObject('usermenu', 'toolbar');
$this->loadClass('htmlheading', 'htmlelements');
$this->objFeatureBox = $this->getObject('featurebox', 'navigation');
$objWashout = $this->getObject('washout', 'utilities');
$this->objImOps = $this->getObject('imops');
$objImView = $this->getObject('imviewer', 'im');

$middleColumn = NULL;
$leftColumn = NULL;

// Add in a heading
$header = new htmlHeading();
$header->str = $this->objLanguage->languageText('mod_im_recentmessages', 'im');
$header->type = 1;

$middleColumn .= $header->show();
$middleColumn .= $objImView->renderOutputForBrowser($msgs);

if (!$this->objUser->isLoggedIn()) {
	$leftColumn.= $this->objImOps->loginBox(TRUE);
} else {
	$leftColumn .= $this->leftMenu->show();
}

$cssLayout->setMiddleColumnContent($middleColumn);
$cssLayout->setLeftColumnContent($leftColumn);
echo $cssLayout->show();