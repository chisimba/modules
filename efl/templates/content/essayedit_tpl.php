<?php

// Create an instance of the css layout class
$cssLayout = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
$cssLayout->setNumColumns(2);

$objEditForm = $this->getObject('editform', 'efl');
$postLoginMenu  = $this->newObject('postloginmenu','toolbar');
$leftSideColumn = $postLoginMenu->show();
$cssLayout->setLeftColumnContent($leftSideColumn);

$rightSideColumn='<div id="gtx"></div><div style="padding:10px;">';

//Add the table to the centered layer
$rightSideColumn .=$objEditForm->show();
$rightSideColumn.= '</div>';
// Add Right Column
$cssLayout->setMiddleColumnContent($rightSideColumn);

$objEditForm = $this->getObject('editform', 'efl');
echo $objEditForm->show();

?>
