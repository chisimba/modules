<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"> 
<?php
$this->loadClass('link', 'htmlelements');
$content='';

$cssLayout = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
$cssLayout->setNumColumns(3);

$nav = $this->getObject('nav', 'ocsinterface');
$leftSideColumn = $nav->getLeftContent();
$rightSideColumn=$nav->getRightContent();

$cssLayout->setLeftColumnContent($leftSideColumn);
$cssLayout->setRightColumnContent($rightSideColumn);

//$rightSideColumn='<h1>'.$coursedata['title'].'</h1>';
$middleSideColumn='<div id="gtx"></div><div style="margin-left:30px; margin-right:50px;padding:15px;">';
//Add the table to the centered layer
$middleSideColumn .= $this->objViewerUtils->getHomePageContent();
$middleSideColumn.= '</div>';
// Add Right Column
$cssLayout->setMiddleColumnContent($middleSideColumn);
echo $cssLayout->show();
?>
