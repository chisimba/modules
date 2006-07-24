<?php
$cssLayout =& $this->newObject('csslayout', 'htmlelements');
$menuBar=& $this->getObject('sidemenu','toolbar');

$cssLayout->setLeftColumnContent($menuBar->menuContext());
$cssLayout->setMiddleColumnContent($this->getContent());

echo $cssLayout->show();
?>