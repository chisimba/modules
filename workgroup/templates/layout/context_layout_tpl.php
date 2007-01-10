<?php
$objDBContext = & $this->getObject('dbcontext','context');
if($objDBContext->isInContext())
{
    $objContextUtils = & $this->getObject('utilities','context');
    $cm = $objContextUtils->getHiddenContextMenu('workgroup','show');
} else {
    $cm = $this->getMenu();
}

$cssLayout =& $this->newObject('csslayout', 'htmlelements');
//$userMenuBar=& $this->getObject('sidemenu','toolbar');
$cssLayout->setLeftColumnContent($cm/*$userMenuBar->show('context')*/); /*menuUser*/
$cssLayout->setMiddleColumnContent($this->getContent());
echo $cssLayout->show();
?>