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

echo $policy;

?>
