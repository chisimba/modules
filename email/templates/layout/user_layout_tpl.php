<?
$cssLayout =& $this->newObject('csslayout', 'htmlelements');
$leftMenu =& $this->newObject('usermenu','toolbar');

$cssLayout->setLeftColumnContent($leftMenu->show());
$cssLayout->setMiddleColumnContent($this->getContent());

echo $cssLayout->show();
?>
