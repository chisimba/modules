<?php
$cssLayout = $this->newObject('csslayout', 'htmlelements');
$leftCol = NULL;
$middleColumn = NULL;

$objUi = $this->getObject('stackui');

$cssLayout->setNumColumns(2);

$middleColumn = $objUi->albumEditor($userid, $mode, $albumarr);

$cssLayout->setMiddleColumnContent($middleColumn);
$cssLayout->setLeftColumnContent($leftCol);
echo $cssLayout->show();
?>
