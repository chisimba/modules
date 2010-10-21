<?php
$cssLayout = $this->newObject('csslayout', 'htmlelements');
$cssLayout->setNumColumns(3);
$this->loadClass('htmlheading', 'htmlelements');
$objFeatureBox = $this->getObject('featurebox', 'navigation');
        

$leftColumn = NULL;
$middleColumn = NULL;
$rightColumn = NULL;

if($this->objUser->isloggedIn()) {
    // get the sidebar object
    $this->leftMenu = $this->newObject('usermenu', 'toolbar');
    $leftColumn .= $this->leftMenu->show();
}
else {
    $leftColumn .= $this->objReviewOps->showSignInBox();
    $leftColumn .= $this->objReviewOps->showSignUpBox();
}

// Add in a heading
$headern = new htmlHeading();
$headern->str = $row['prodname'];
$headern->type = 1;

$middleColumn .= $headern->show();
$middleColumn .= $row['longdesc'];

$rightColumn .= $objFeatureBox->show($this->objLanguage->languageText("mod_qrreview_qrcode", "qrreview"), '<img src="'.$row['qr'].'">');

$cssLayout->setMiddleColumnContent($middleColumn);
$cssLayout->setLeftColumnContent($leftColumn);
$cssLayout->setRightColumnContent($rightColumn);
echo $cssLayout->show();
