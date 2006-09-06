<?php

$objTOC = $this->getObject('tableofcontents');

$cssLayout = $this->newObject('csslayout', 'htmlelements');
$cssLayout->setLeftColumnContent($objTOC->show());
$cssLayout->setMiddleColumnContent($this->getContent());
echo $cssLayout->show();
?>