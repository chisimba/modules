<?php

//initiate objects
$this->loadClass('label', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('form', 'htmlelements');
//$this->loadClass('heading', 'htmlelements');
$this->loadClass('href', 'htmlelements');
$this->loadClass('htmlarea', 'htmlelements');

$tt = $this->newObject('domtt', 'htmlelements');
$pane = &$this->newObject('tabpane', 'htmlelements');

$cssLayout = &$this->newObject('csslayout', 'htmlelements');
$objSideBar = $this->newObject('sidebar', 'navigation');

// Set columns to 3
$cssLayout->setNumColumns(3);
$leftMenu = &$this->newObject('usermenu', 'toolbar');
$rightSideColumn = NULL;
$leftCol = NULL;
$middleColumn = NULL;

//left menu section
$leftCol = $leftMenu->show();

//check if there are previous values
$dsnparse = $this->objblogOps-> getMailDSN();
if($dsnparse == FALSE)
{
	$middleColumn .= $this->objblogOps->showMailSetup(TRUE);
}
else {
	$middleColumn .= $this->objblogOps->showMailSetup(TRUE, $dsnparse);
}


//$rightSideColumn .= $this->objblogOps->quickCats(TRUE);
$rightSideColumn .= $this->objblogOps->showAdminSection(TRUE);
//$rightSideColumn .= $this->objblogOps->quickPost($userid, TRUE);
$cssLayout->setMiddleColumnContent($middleColumn);
$cssLayout->setLeftColumnContent($leftCol);
$cssLayout->setRightColumnContent($rightSideColumn);
echo $cssLayout->show();
?>