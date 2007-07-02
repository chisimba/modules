<?php
/*
* Layout template for essay management.
* @package essay
*/

/**
* @param string $leftNav The left panel on the page containing user information
*/

$this->_objDBContext = $this->getObject('dbcontext','context');
if($this->_objDBContext->isInContext())
{
    $objContextUtils = & $this->getObject('utilities','context');
    $cm = $objContextUtils->getHiddenContextMenu('essay','none');
} else {
    $cm ='';
}

$leftMenu=& $this->newObject('sidemenu','toolbar');

$objLayer=$this->objLayer;
$objHead=$this->newObject('htmlheading','htmlelements');
$cssLayout =& $this->newObject('csslayout', 'htmlelements');

$objHead->str=$heading;
$objHead->type=1;
$main = $objHead->show();

$objLayer->str = $this->getContent();

$main.=$objLayer->show();

$cssLayout->setLeftColumnContent($leftMenu->menuContext().$cm);
$cssLayout->setMiddleColumnContent($main);

echo $cssLayout->show();
?>