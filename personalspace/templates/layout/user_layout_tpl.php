<?php

//$events = $this->objLanguage->LanguageText('mod_personalspace_events','personalspace$

//$this->objcalender = & $this->newObject



$cssLayout =& $this->newObject('csslayout', 'htmlelements');
$objMenu =& $this->newObject('sidemenu','toolbar');
$cssLayout->setLeftColumnContent($objMenu->menuUser());
$cssLayout->setMiddleColumnContent($this->getContent());
$moduleCheck = $this->newObject('modules','modulecatalogue');
if ($moduleCheck->checkIfRegistered('calendar')) {
    $cssLayout->setNumColumns(3);
    $calendar =& $this->newObject('contextcalendar','calendar');
    $cssLayout->setRightColumnContent($calendar->show());
    
}
echo $cssLayout->show();
?>
