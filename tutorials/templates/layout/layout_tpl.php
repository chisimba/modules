<?php
/**
* @package tutorials
*/

/**
* Default layout for the tutorials module
*/

$cssLayout = $this->newObject('csslayout', 'htmlelements');
$leftMenu = $this->newObject('sidemenu', 'toolbar');

$cssLayout->setLeftColumnContent($leftMenu->menuContext());
$cssLayout->setMiddleColumnContent($this->getContent());

echo $cssLayout->show();
?>