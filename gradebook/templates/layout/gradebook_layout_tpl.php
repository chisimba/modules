<?php
/* -------------------- gradebook layout template ---------------- */
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}

/**
* Template layout for gradebook module
*/

$cssLayout =& $this->newObject('csslayout', 'htmlelements');
$leftMenu =& $this->newObject('sidemenu','toolbar');

$cssLayout->setLeftColumnContent($leftMenu->menuContext());
$cssLayout->setMiddleColumnContent($this->getContent());

echo $cssLayout->show();
?>
