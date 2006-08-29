<?php

$cssLayout =& $this->newObject('csslayout', 'htmlelements');
$cssLayout->setNumColumns(3);
$cssLayout->setLeftColumnContent($this->objLeftBar->show());
$cssLayout->setRightColumnContent($this->objRightBar->show());
$cssLayout->setMiddleColumnContent($this->getContent());

echo $cssLayout->show();

?>
