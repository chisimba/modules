<?php
/**
* @package wiki version 2
*/

/**
* Default layout for the wiki version 2 module
*/

$cssLayout=&$this->newObject('csslayout','htmlelements');
$cssLayout->setNumColumns(2);

$leftColumn=$this->newObject('wikidisplay','wiki');

$cssLayout->setLeftColumnContent($leftColumn->showWikiToolbar());
$cssLayout->setMiddleColumnContent($this->getContent());

echo $cssLayout->show();
?>