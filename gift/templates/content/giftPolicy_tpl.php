<?php

//load class
$this->loadclass('link','htmlelements');

$objbackLink = new link($this->uri(array('action'=>'back')));
$objbackLink-> link = 'Back';

$acceptLink = new link($this->uri(array('action'=>'acceptpolicy')));
$acceptLink-> link = 'Accept';

$objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
$policyURL=$objSysConfig->getValue('GIFT_POLICY_URL', 'gift');

$objWashout = $this->getObject('washout', 'utilities');
$policy=$objWashout->parseText('[PDF]'.$policyURL.'[/PDF]');

//CSS
// Create an instance of the css layout class
$cssLayout = &$this->newObject('csslayout', 'htmlelements');
// Set columns to 2
$cssLayout->setNumColumns(2);
// get the links on the left
$form = $this->objHome->getLeftContent($toSelect, $this->getParam('action'), $this->getParam('archived'));
// links are displayed on the left
$leftSideColumn = $form;
$cssLayout->setLeftColumnContent($leftSideColumn);

// Add the table to the centered layer and a message of database functionality
$rightSideColumn = '<h1>Gift Policy</h1>'. $policy.'<div id="grouping-grid"><br /></div>'.$objbackLink->show().'&nbsp;&nbsp;'.$acceptLink->show();
$cssLayout->setMiddleColumnContent($rightSideColumn);


// Output the content to the page
echo $cssLayout->show();

?>
