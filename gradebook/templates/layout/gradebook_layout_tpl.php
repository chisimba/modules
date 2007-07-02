<?php
/* -------------------- gradebook layout template ---------------- */
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}

/**
* Template layout for gradebook module
*/

$this->_objDBContext = $this->getObject('dbcontext','context');
if($this->_objDBContext->isInContext())
{
    $objContextUtils = & $this->getObject('utilities','context');
    $cm = $objContextUtils->getHiddenContextMenu('gradebook','none');
} else {
    $cm ='';
}


$cssLayout =& $this->newObject('csslayout', 'htmlelements');
$leftMenu =& $this->newObject('sidemenu','toolbar');

$cssLayout->setLeftColumnContent($leftMenu->menuContext().$cm);
$cssLayout->setMiddleColumnContent($this->getContent());

echo $cssLayout->show();
?>
