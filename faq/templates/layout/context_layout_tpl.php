<?php
$this->_objDBContext =& $this->getObject('dbcontext','context');
if($this->_objDBContext->isInContext())
{
    $objContextUtils = & $this->getObject('utilities','context');
    $leftMenu =& $this->newObject('sidemenu','toolbar');
    $cm = $leftMenu->menuUser('context');//.$objContextUtils->getHiddenContextMenu('faq','none');
} else {
    $cm ='';
}
$cssLayout =& $this->newObject('csslayout', 'htmlelements');
$toolbar = $this->getObject('contextsidebar', 'context');
$cssLayout->setLeftColumnContent($toolbar->show());
$cssLayout->setMiddleColumnContent($this->getContent());
echo $cssLayout->show();
?>