<?php
header("Content-Type: text/html;charset=utf-8");
$cssLayout = $this->newObject('csslayout', 'htmlelements');
$cssLayout->setNumColumns(2);

// get the sidebar object
$this->leftMenu = $this->newObject('usermenu', 'toolbar');
$this->loadClass('htmlheading', 'htmlelements');
$this->objFeatureBox = $this->getObject('featurebox', 'navigation');
$objWashout = $this->getObject('washout', 'utilities');
$this->objImOps = $this->getObject('imops');

$middleColumn = NULL;
$leftColumn = NULL;

// Add in a heading
$header = new htmlHeading();
$header->str = $this->objLanguage->languageText('mod_im_recentmessages', 'im');
$header->type = 1;

$middleColumn .= $header->show();

/*$objPagination = $this->newObject('pagination', 'navigation');
$objPagination->module = 'im';
$objPagination->action = 'viewallajax';
$objPagination->id = 'im';
$objPagination->numPageLinks = $pages;
$objPagination->currentPage = $pages - 1;
$middleColumn .= $objPagination->show();*/
if(!$this->objUser->isAdmin($this->objUser->userId()))
{
    $cid = $this->objUser->userId();
}else{
    $cid = NULL;
}
$msgs = $this->objDbIm->getMessagesByActiveUser ($cid);
//var_dump($msgs);
$objImView = $this->getObject('imviewer', 'im');
$middleColumn .= $objImView->renderOutputForBrowser($msgs);

if (!$this->objUser->isLoggedIn()) {
    $leftColumn .= $this->objImOps->loginBox(TRUE);
} else {
    $leftColumn .= $this->leftMenu->show();
    if($this->objUser->inAdminGroup($this->objUser->userId()))
    {
       $leftColumn .= $this->objImOps->showMassMessageBox(TRUE, TRUE);
    }
}

$cssLayout->setMiddleColumnContent($middleColumn);
$cssLayout->setLeftColumnContent($leftColumn);
echo $cssLayout->show();
