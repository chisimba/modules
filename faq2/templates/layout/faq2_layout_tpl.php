<?php

$cssLayout = $this->newObject('csslayout', 'htmlelements');
$cssLayout->setNumColumns(2);

$cssLayout->setLeftColumnContent($this->faq2Tools->getLeftBlocks());
$cssLayout->setMiddleColumnContent($this->getContent());

echo $cssLayout->show();
?>
