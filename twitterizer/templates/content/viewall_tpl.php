<?php
header ( "Content-Type: text/html;charset=utf-8" );
$cssLayout = $this->newObject ( 'csslayout', 'htmlelements' );
$cssLayout->setNumColumns ( 3 );

// get the sidebar object
$this->leftMenu = $this->newObject ( 'usermenu', 'toolbar' );
$this->loadClass ( 'htmlheading', 'htmlelements' );
$this->objOps = $this->getObject ( 'tweetops' );
$this->objFeatureBox = $this->getObject ( 'featurebox', 'navigation' );
$objWashout = $this->getObject ( 'washout', 'utilities' );

$this->objDia = $this->getObject('jqdialogue', 'htmlelements');

$middleColumn = NULL;
$middleColumn .= $this->objOps->renderTopBoxen();

$leftColumn = NULL;
$rightColumn = NULL;

$objPagination = $this->newObject ( 'pagination', 'navigation' );
$objPagination->module = 'twitterizer';
$objPagination->action = 'viewallajax';
$objPagination->id = 'twitterizer';
$objPagination->numPageLinks = $pages;
$objPagination->currentPage = $pages - 1;

$middleColumn .= '<br/>' . $objPagination->show ();

$userid = $this->objUser->userid();
if (! $this->objUser->isLoggedIn ()) {

   // $leftColumn .= $objImView->showUserMenu ();

} else {
    //$leftColumn .= $this->leftMenu->show ();
}

$leftColumn .= $this->objOps->renderLeftBoxen();
$rightColumn .= $this->objOps->renderRightBoxen($userid);

$cssLayout->setMiddleColumnContent ( $middleColumn );
$cssLayout->setLeftColumnContent ( $leftColumn );
$cssLayout->setRightColumnContent ( $rightColumn );

echo $cssLayout->show ();
