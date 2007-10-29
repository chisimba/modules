<?php
	//default gateway
    include "../flashservices/app/Gateway.php";
	
    $gateway = new Gateway();
    $gateway->setBaseClassPath("./services/");
    $gateway->service();

?>
