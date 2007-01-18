<?php
$this->_objDBContext =& $this->getObject('dbcontext','context');
if($this->_objDBContext->isInContext())
{
    $objContextUtils = & $this->getObject('utilities','context');
    $cm = $objContextUtils->getHiddenContextMenu('faq','none');
} else {
    $cm ='';
}
//
$cssLayout =& $this->newObject('csslayout', 'htmlelements');
$leftMenu =& $this->newObject('sidemenu','toolbar');
$cssLayout->setLeftColumnContent($leftMenu->menuUser('context').$cm);
$cssLayout->setMiddleColumnContent($this->getContent());
echo $cssLayout->show();
?>
