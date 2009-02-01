<?php

$middleColumn = '<div style="padding: 5px;">';
if($this->contextObject->isInContext())
{
    //$objContextUtils = $this->getObject('utilities','context');
    //echo $objContextUtils->getHiddenContextMenu('forum','none');
     
}
$middleColumn .= $this->getContent(); 
$middleColumn .= '</div>';

$middleColumn = $this->getVar('middleColumn');

$cssLayout = $this->newObject('csslayout', 'htmlelements');
$cssLayout->setNumColumns(1);
$cssLayout->setMiddleColumnContent($middleColumn);

echo $cssLayout->show();


?>

