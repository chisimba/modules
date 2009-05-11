<?php
header ( "Content-Type: text/html;charset=utf-8" );
$cssLayout = $this->newObject ( 'csslayout', 'htmlelements' );
$cssLayout->setNumColumns ( 2 );

// get the sidebar object
$this->leftMenu = $this->newObject ( 'usermenu', 'toolbar' );
$this->loadClass ( 'htmlheading', 'htmlelements' );
$this->objFeatureBox = $this->getObject ( 'featurebox', 'navigation' );
$objImView = $this->getObject ( 'viewer' );

$middleColumn = NULL;
$leftColumn = NULL;

// Add in a heading
$header = new htmlHeading ( );
$header->str = $this->objLanguage->languageText ( 'mod_tribe_tribe', 'tribe' ) . " " . $this->objUser->fullName ( $this->objUser->userId() );
$header->type = 1;

$middleColumn .= $header->show();
$middleColumn .= $cloud;

if (! $this->objUser->isLoggedIn ()) {
    //$leftColumn .= $objImView->showUserMenu ();
} else {
    $leftColumn .= $this->leftMenu->show ();
}

$leftColumn .= $objImView->renderBoxen();

$cssLayout->setMiddleColumnContent ( $middleColumn );
$cssLayout->setLeftColumnContent ( $leftColumn );
echo $cssLayout->show ();