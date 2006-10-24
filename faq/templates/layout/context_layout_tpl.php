<?php
$cssLayout =& $this->newObject('csslayout', 'htmlelements');
$leftMenu =& $this->newObject('sidemenu','toolbar');
$cssLayout->setLeftColumnContent($leftMenu->menuUser('context'));
$cssLayout->setMiddleColumnContent($this->getContent());
echo $cssLayout->show();
?>
