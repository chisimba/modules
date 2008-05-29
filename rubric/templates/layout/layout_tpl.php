<?php
$objDBContext = & $this->getObject('dbcontext','context');
if($objDBContext->isInContext())
{
    $objContextUtils = & $this->getObject('utilities','context');
    $cm = $objContextUtils->getHiddenContextMenu('rubric','show');
} else {
    $cm = '';//$this->getMenu();
}

$cssLayout =& $this->newObject('csslayout', 'htmlelements');
if ($this->objUser->isContextLecturer()|| $this->objUser->isContextStudent() ) {
	$userMenuBar=& $this->getObject('sidemenu','toolbar');
}
else if ($this->objUser->isLecturer()) {
	$userMenuBar=& $this->getObject('sidemenu','toolbar');
}
else {
	die('Access denied');
}
$toolbar = $this->getObject('contextsidebar', 'context');
$cssLayout->setLeftColumnContent($toolbar->show());
//$this->sideMenuBar=& $this->getObject('sidemenu','toolbar');
////$sideMenuBar=& $this->getObject('sidemenu','toolbar');

//$cssLayout->setLeftColumnContent($this->sideMenuBar->menuContext()); //change to what needs to be done
$cssLayout->setMiddleColumnContent($this->getContent());

echo $cssLayout->show();
?>
