<?php
/*
* Layout template for essay management.
* @package essay
*/

/**
* @param string $leftNav The left panel on the page containing user information
*/

$leftMenu=& $this->newObject('sidemenu','toolbar');

$objLayer=$this->objLayer;
$objHead=$this->newObject('htmlheading','htmlelements');
$cssLayout =& $this->newObject('csslayout', 'htmlelements');

$objHead->str=$heading;
$objHead->type=1;
$main = $objHead->show();

$objLayer->str = $this->getContent();

$main.=$objLayer->show();

$cssLayout->setLeftColumnContent($leftMenu->menuContext());
$cssLayout->setMiddleColumnContent($main);

echo $cssLayout->show();
?>
