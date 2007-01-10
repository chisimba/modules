<?php
/**
* @package pbl
*/

/**
* Layout template for the pbl module
*/

$leftMenu =& $this->newObject('sidemenu','toolbar');
$objHead =& $this->newObject('htmlheading','htmlelements');
$cssLayout =& $this->newObject('csslayout', 'htmlelements');
$cssLayout->setNumColumns(3);

$heading=$objLanguage->languageText('mod_pbl_name');

$objHead->str=$heading;
$objHead->type=1;
$head = $objHead->show();

$cssLayout->setLeftColumnContent($leftMenu->menuContext());
$cssLayout->setMiddleColumnContent($head.$this->getContent());
$cssLayout->setRightColumnContent($rightContent);

echo $cssLayout->show();
?>