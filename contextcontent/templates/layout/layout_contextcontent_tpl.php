<?php

$objTOC = $this->getObject('tableofcontents');

$cssLayout = $this->newObject('csslayout', 'htmlelements');
$cssLayout->setLeftColumnContent('Nav goes here');//$objTOC->show());
$cssLayout->setMiddleColumnContent($this->getContent());
echo $cssLayout->show();
?>