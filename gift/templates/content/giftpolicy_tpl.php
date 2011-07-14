<?php

//load class
$this->loadclass('link','htmlelements');

$objbackLink = new link($this->uri(array('action'=>'back')));
$objbackLink-> link = 'Back';

$acceptLink = new link($this->uri(array('action'=>'acceptpolicy')));
$acceptLink-> link = 'Accept';

$objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
$policyURL=$objSysConfig->getValue('GIFT_POLICY_URL', 'gift');




$policy='<a href="'.$policyURL.'">Click here to view the policy</a>';



// Add the table to the centered layer and a message of database functionality
echo '<h1>Gift Policy</h1>'. $policy.'<div id="grouping-grid"><br /></div>'.$objbackLink->show().'&nbsp;&nbsp;'.$acceptLink->show();


?>
