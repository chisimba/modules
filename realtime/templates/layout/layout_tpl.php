<?php
/**
* @package messaging
*/

/**
* Default layout for the messaging module
*/

$cssLayout=&$this->newObject('csslayout','htmlelements');
$cssLayout->setNumColumns(3);

$objBlocks = $this->newObject('blocks', 'blocks');
$returnBlock = $objBlocks->showBlock('chatreturn', 'messaging');

$chatBlock = $objBlocks->showBlock('contextchat', 'messaging');

$cssLayout->setLeftColumnContent($chatBlock);
$cssLayout->setMiddleColumnContent($this->getContent());
$cssLayout->setRightColumnContent("VOICE");

echo $cssLayout->show();
?>