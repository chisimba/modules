<?php
$cssLayout =& $this->newObject('csslayout', 'htmlelements');
$leftMenu =& $this->newObject('sidemenu', 'toolbar');

//$cssLayout->setLeftColumnContent($leftMenu->show('user'));
$cssLayout->setLeftColumnContent($leftMenu->menuUser());
$cssLayout->setMiddleColumnContent($this->getContent());

echo $cssLayout->show();
?>

