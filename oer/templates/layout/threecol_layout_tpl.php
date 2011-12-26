<?php

$cssLayout = $this->getObject('csslayout', 'htmlelements');
$cssLayout->setNumColumns(3);

$cssLayout->setLeftColumnContent("The filter goes here");
$cssLayout->setMiddleColumnContent($this->getContent());
$cssLayout->setRightColumnContent("Featured product goes here");
echo $cssLayout->show();

?>
