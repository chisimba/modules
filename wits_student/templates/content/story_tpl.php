<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"> 
<?php

$cssLayout = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
$cssLayout->setNumColumns(2);

$nav = $this->getObject('nav', 'wits_student');
$leftSideColumn = $nav->getLeftContent();

$cssLayout->setLeftColumnContent($leftSideColumn);
//$rightSideColumn='<h1>'.$coursedata['title'].'</h1>';
$rightSideColumn='<div style="margin-left:50px; margin-right:50px;padding:10px;">';

//Add the table to the centered layer
$rightSideColumn .= $this->objViewerUtils->getContent($storyid);
$rightSideColumn.= '</div>';
// Add Right Column
$cssLayout->setMiddleColumnContent($rightSideColumn);
echo $cssLayout->show();
?>
