<?php
/**
* @package email
* Default layout for the new email module
*/
$cssLayout = &$this->newObject('csslayout', 'htmlelements');
$cssLayout->setNumColumns(2);
$leftColumn = &$this->newObject('sidemenu', 'toolbar');
$cssLayout->setLeftColumnContent($leftColumn->show('user'));
$cssLayout->setMiddleColumnContent($this->getContent());
echo $cssLayout->show();
?>