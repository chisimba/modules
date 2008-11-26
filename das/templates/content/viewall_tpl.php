<?php
header("Content-Type: text/html;charset=utf-8");
$cssLayout = $this->newObject('csslayout', 'htmlelements');
$cssLayout->setNumColumns(2);

// get the sidebar object
$this->leftMenu = $this->newObject('usermenu', 'toolbar');
$this->loadClass('htmlheading', 'htmlelements');
$refreshLink = $this->newObject('link', 'htmlelements');
$refreshIcon = $this->newObject('geticon', 'htmlelements');
$configLink = $this->newObject('link', 'htmlelements');
$configIcon = $this->newObject('geticon', 'htmlelements');
$this->objFeatureBox = $this->getObject('featurebox', 'navigation');
$objWashout = $this->getObject('washout', 'utilities');
$this->objImOps = $this->getObject('imops', 'im');

$middleColumn = NULL;
$leftColumn = NULL;

// Add in a heading
$header = new htmlHeading();
$header->str = $this->objLanguage->languageText('mod_im_recentmessages', 'im');
$header->type = 1;

$refreshLink->href = $this->uri(null, 'das');
$refreshIcon->setIcon('refresh');
$refreshLink->link = $refreshIcon->show();



/*$objPagination = $this->newObject('pagination', 'navigation');
$objPagination->module = 'im';
$objPagination->action = 'viewallajax';
$objPagination->id = 'im';
$objPagination->numPageLinks = $pages;
$objPagination->currentPage = $pages - 1;
$middleColumn .= $objPagination->show();*/
if($this->objUser->inAdminGroup($this->objUser->userId()))
{
    $cid = $this->objUser->userId();
    
	$configIcon->setIcon('admin');
	$configLink->href = $this->uri(array('action' => 'viewcounsilors', 'das'));
	$configLink->link = $configIcon->show();
	$config = $configLink->show();
}else{
    $cid = $this->objUser->userId();
    
	$config = "";
}
$outof = '/'.$this->objDbImPres->numOfUserAssigned ($cid);
$msgs = $this->objDbIm->getMessagesByActiveUser ($cid);

$num = count($msgs);
$str = "Currently counsilling $num$outof users";
$objImView = $this->getObject('imviewer', 'im');

$middleColumn .= $header->show().'<br/>'.$config.'  '.$refreshLink->show().'<br/>'.$str;
$middleColumn .= $objImView->renderOutputForBrowser($msgs);


if (!$this->objUser->isLoggedIn()) {
    $leftColumn .= $this->objImOps->loginBox(TRUE);
} else {
   
    $leftColumn .= $objImView->renderLinkList($msgs);
	$leftColumn .= $this->leftMenu->show();
    if($this->objUser->inAdminGroup($this->objUser->userId()))
    {
       $leftColumn .= $this->objImOps->showMassMessageBox(TRUE, TRUE);
    }
}

$cssLayout->setMiddleColumnContent($middleColumn);
$cssLayout->setLeftColumnContent($leftColumn);
echo $cssLayout->show();
