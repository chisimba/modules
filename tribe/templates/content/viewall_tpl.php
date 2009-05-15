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
$this->objShare = $this->getObject('share', 'toolbar');
$this->objShare->setup($this->uri(''), 'My thought stream', 'Interesting! ');
$this->objDia = $this->getObject('jqdialogue', 'htmlelements');
$groupfail = FALSE;
if($groupfail == TRUE) {
    $this->objDia->setTitle('title');
    $this->objDia->setContent('some content');
    echo $this->objDia->show();
}
$middleColumn = $this->objShare->show();
$leftColumn = NULL;
$rightColumn = NULL;

$objPagination = $this->newObject ( 'pagination', 'navigation' );
$objPagination->module = 'tribe';
$objPagination->action = 'viewallajax';
$objPagination->id = 'tribe';
$objPagination->numPageLinks = $pages;
$objPagination->currentPage = $pages - 1;

$middleColumn .= '<br/>' . $objPagination->show ();
//$middleColumn .= $objImView->renderOutputForBrowser($msgs);
$userid = $this->objUser->userid();
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
