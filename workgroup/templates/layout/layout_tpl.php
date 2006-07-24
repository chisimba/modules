<?php
/*
$cssLayout =& $this->newObject('csslayout', 'htmlelements');
//$menuBar=& $this->getObject('sidemenu','toolbar');
//$cssLayout->setLeftColumnContent($menuBar->menuContext()); //menuUser
$menuBar=& $this->getObject('workgroupmenu');
$cssLayout->setLeftColumnContent($menuBar->show()); //menuUser
$cssLayout->setMiddleColumnContent($this->getContent());
echo $cssLayout->show();
*/
$cssLayout =& $this->newObject('csslayout', 'htmlelements');
$userMenuBar=& $this->getObject('sidemenu','toolbar');

$cssLayout->setLeftColumnContent($userMenuBar->show('workgroup')); /*menuUser*/
$cssLayout->setMiddleColumnContent($this->getContent());

echo $cssLayout->show();

?>