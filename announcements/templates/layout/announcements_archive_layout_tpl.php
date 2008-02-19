<?php

$cssLayout = $this->newObject('csslayout', 'htmlelements');
$cssLayout->setNumColumns(2);

$this->leftMenu = $this->newObject('usermenu', 'toolbar');
// Initialize left column
$leftSideColumn = $this->leftMenu->show();
$this->objFeatureBox = $this->newObject('featurebox', 'navigation');
$leftSideColumn=$this->objFeatureBox->show($blocktitle, $leftSideColumn);
$cssLayout->setLeftColumnContent($leftSideColumn);
$cssLayout->setMiddleColumnContent($this->getContent());
//$cssLayout->setRightColumnContent($this->objAnnouncementsTools->getRightBlocks());

echo $cssLayout->show();
?>
