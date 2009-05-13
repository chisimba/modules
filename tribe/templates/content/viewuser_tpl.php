<?php
header ( "Content-Type: text/html;charset=utf-8" );
$cssLayout = $this->newObject ( 'csslayout', 'htmlelements' );
$cssLayout->setNumColumns ( 3 );

// get the sidebar object
$this->leftMenu = $this->newObject ( 'usermenu', 'toolbar' );
$this->loadClass ( 'htmlheading', 'htmlelements' );
$objImView = $this->getObject ( 'viewer' );
$this->objFeatureBox = $this->getObject ( 'featurebox', 'navigation' );
$objWashout = $this->getObject ( 'washout', 'utilities' );

$leftColumn = NULL;
$rightColumn = NULL;
$middleColumn = NULL;

$middleColumn .= $objImView->renderTopBoxen();

$objPagination = $this->newObject ( 'pagination', 'navigation' );
$objPagination->module = 'tribe';
$objPagination->action = 'viewuserajax&userid='.$userid;
$objPagination->id = 'tribe';
$objPagination->numPageLinks = $pages;
$objPagination->currentPage = $pages - 1;


$middleColumn .= '<br/>' . $objPagination->show ();
$userid = $this->objUser->userId();
if (! $this->objUser->isLoggedIn ()) {
   // $leftColumn .= $objImView->showUserMenu ();

} else {
    //$leftColumn .= $this->leftMenu->show ();
}

$leftColumn .= $objImView->renderLeftBoxen();
$rightColumn .= $objImView->renderRightBoxen($userid);

$cssLayout->setMiddleColumnContent ( $middleColumn );
$cssLayout->setLeftColumnContent ( $leftColumn );
$cssLayout->setRightColumnContent ( $rightColumn );

echo $cssLayout->show ();