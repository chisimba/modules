<?php
/**
* @package pbladmin
*/

/**
* Layout template for pbladmin
*/

$cssLayout =& $this->newObject('csslayout', 'htmlelements');
$menuBar =& $this->getObject('sidemenu','toolbar');
$objHead =& $this->newObject('htmlheading','htmlelements');

if(!isset($heading)){
    $heading=$this->objLanguage->languageText('mod_pbladmin_name');
}

$objHead->str = $heading;
$objHead->type = 1;
$head = $objHead->show();

$cssLayout->setLeftColumnContent($menuBar->menuContext());
$cssLayout->setMiddleColumnContent($head.$this->getContent());

echo $cssLayout->show();
?>