<?php
$cssLayout = $this->newObject('csslayout', 'htmlelements');
$cssLayout->setNumColumns(2);
$this->loadClass('htmlheading', 'htmlelements');

$leftColumn = NULL;
$middleColumn = NULL;

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

$cssLayout->setMiddleColumnContent($middleColumn);
$cssLayout->setLeftColumnContent($leftColumn);
echo $cssLayout->show();
