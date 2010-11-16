<?php
/*
* Layout template for essay management.
* @package essay
*/

/*
if (!$this->objContext->isInContext()) {
    $contextMenu ='';
} else {
    $objContextUtils = $this->getObject('utilities','context');
    $contextMenu = $objContextUtils->getHiddenContextMenu('essay','none');
}
*/

$leftMenu = $this->getObject('contextsidebar', 'context');

$objHeading = $this->newObject('htmlheading','htmlelements');
$objLayer = $this->objLayer;

$content = '';
$objHeading->str = $heading;
$objHeading->type = 1;
$content .= $objHeading->show();
$objLayer->str = $this->getContent();
$content .= $objLayer->show();

$cssLayout = $this->newObject('csslayout', 'htmlelements');
$cssLayout->setLeftColumnContent($leftMenu->show());
$cssLayout->setMiddleColumnContent($content);
echo $cssLayout->show();
?>