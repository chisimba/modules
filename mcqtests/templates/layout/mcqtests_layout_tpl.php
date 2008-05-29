<?php
/**
 * @package mcqtests
 * Layout template for the mcqtests module
 */
$cssLayout = &$this->newObject('csslayout', 'htmlelements');
$leftMenu = &$this->getObject('contextsidebar', 'context');
$objHead = &$this->newObject('htmlheading', 'htmlelements');
if (!isset($heading)) {
    $heading = $objLanguage->languageText('mod_mcqtests_name', 'mcqtests');
}
$objHead->str = $heading;
$objHead->type = 1;
$head = $objHead->show();
$cssLayout->setLeftColumnContent($leftMenu->show());
$cssLayout->setMiddleColumnContent($head.$this->getContent());
echo $cssLayout->show();
?>