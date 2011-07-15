<?php
$cssLayout = $this->newObject('csslayout', 'htmlelements');
$leftCol = NULL;
$middleColumn = NULL;

$objUi = $this->getObject('stackui');

$cssLayout->setNumColumns(2);

$middleColumn = $objUi->getGallery($userid);
// $middleColumn .= $objUi->getSocial();

if(!$this->objUser->isLoggedIn()) {
    $leftCol .= $objUi->showSignInBox(TRUE);
}

$cssLayout->setMiddleColumnContent($middleColumn);
$cssLayout->setLeftColumnContent($leftCol);
echo $cssLayout->show();
?>
