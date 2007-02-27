<?php
/**
* @package messaging
*/

/**
* Chat room layout for the messaging module
*/

$cssLayout = $this->newObject('csslayout','htmlelements');
$cssLayout->setNumColumns(3);

$leftColumn = $this->newObject('sidemenu','toolbar');
$objBlocks = $this->newObject('blocks', 'blocks');
$returnBlock = $objBlocks->showBlock('chatreturn', 'messaging');
$usersBlock = $objBlocks->showBlock('onlineusers', 'messaging', '', '', FALSE);
$smileyBlock = $objBlocks->showBlock('smileys', 'messaging', '', '', FALSE);
$formatBlock = $objBlocks->showBlock('formating', 'messaging', '', '', FALSE);

$cssLayout->setLeftColumnContent($returnBlock.$usersBlock.'<br />');
$cssLayout->setMiddleColumnContent($this->getContent());
$cssLayout->setRightColumnContent($smileyBlock.$formatBlock);

echo $cssLayout->show();
?>