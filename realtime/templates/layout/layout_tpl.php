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
$chatBlock = $objBlocks->showBlock('contextchat', 'messaging');
$voiceBlock = $objBlocks->showBlock('voice', 'realtime');


$cssLayout->setLeftColumnContent($chatBlock);
$cssLayout->setMiddleColumnContent($this->getContent());
$cssLayout->setRightColumnContent($voice);

echo $cssLayout->show();
?>