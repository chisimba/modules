<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"> 
<?php
$this->loadClass('link', 'htmlelements');
$content='';

$cssLayout = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
$cssLayout->setNumColumns(2);

$nav = $this->getObject('nav', 'ocsinterface');
$leftSideColumn = $nav->getLeftContent();

$cssLayout->setLeftColumnContent($leftSideColumn);
//$rightSideColumn='<h1>'.$coursedata['title'].'</h1>';
$rightSideColumn='<div id="gtx"></div><div style="margin-left:30px; margin-right:50px;padding:15px;">';
//Add the table to the centered layer
$rightSideColumn .= $this->objViewerUtils->getHomePageContent();
$rightSideColumn.= '</div>';
// Add Right Column
$cssLayout->setMiddleColumnContent($rightSideColumn);
echo $cssLayout->show();
?>
