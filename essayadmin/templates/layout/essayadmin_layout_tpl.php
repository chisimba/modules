<?php
/*
* Layout template for essay management.
* @package essayadmin
*/

/**
* @param string $leftNav The left panel on the page containing user information
*/

$this->loadClass('layer', 'htmlelements');

$leftMenu=& $this->newObject('sidemenu','toolbar');
$objLayer= new layer;

$objHead=$this->newObject('htmlheading','htmlelements');
$cssLayout =& $this->newObject('csslayout', 'htmlelements');

$objHead->str=$heading;
$objHead->type=1;
$main = $objHead->show();
/*
if(isset($body)){
    $main .= $body;
}*/
$objLayer->str = $this->getContent();

$main.=$objLayer->show();
/*
if(isset($foot)){
    $main.=$foot;
}*/

$cssLayout->setLeftColumnContent($leftMenu->menuContext());
$cssLayout->setMiddleColumnContent($main);

echo $cssLayout->show();
?>
