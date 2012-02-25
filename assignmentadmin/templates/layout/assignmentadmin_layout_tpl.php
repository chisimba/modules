<?php
/**
* Template layout for assignment management module
* @package assignmentadmin
*/

/**
* Template layout for assignment management module
*/

$cssLayout = $this->newObject('csslayout', 'htmlelements');
$toolbar = $this->getObject('contextsidebar', 'context');
$objHead = $this->newObject('htmlheading','htmlelements');

//create the context menu if you are in a context
if($this->objContext->isInContext())
{
    $objContextUtils = $this->getObject('utilities','context');
    $cm = $objContextUtils->getHiddenContextMenu('eventscalendar','show');
} else {
    $cm ='';
}

if(!isset($heading)){
    $heading=$objLanguage->languageText('mod_assignmentadmin_name');
}
    
$objHead->str=$heading;
$objHead->type=1;
$head = $objHead->show();

$cssLayout->setLeftColumnContent($toolbar->show());
$cssLayout->setMiddleColumnContent($head.$this->getContent());

echo $cssLayout->show();
?>


