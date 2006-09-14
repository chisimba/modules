<?php
$cssLayout =& $this->newObject('csslayout', 'htmlelements');
$objMenu =& $this->newObject('sidemenu','toolbar');
$cssLayout->setLeftColumnContent($objMenu->menuUser());
$cssLayout->setMiddleColumnContent($this->getContent());
$moduleCheck = $this->newObject('modules','modulecatalogue');
if ($moduleCheck->checkIfRegistered('calendar')) {
    $cssLayout->setNumColumns(3);
    $calendar =& $this->newObject('usercalendar','calendar');
    $cssLayout->setRightColumnContent($calendar->show());
}
echo $cssLayout->show();
?>
