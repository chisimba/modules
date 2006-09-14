<?
	$namespace = "http://172.16.64.21/5ive/app/modules/residence/helloserver.php?wsdl";
	//require_once('SOAP/Client.php');
	$info = new SoapClient("http://172.16.64.21/5ive/app/modules/residence/helloserver.php",array('proxy_host'    => "uwcinternet1.uwc.ac.za",
                                           'proxy_port'    => 8080,
                                           'proxy_login'    => "jadam",
                                           'proxy_password' => "adam1234"));
	$params = array("name" => "Peter");
	$response = $info->call("sayhello",$params,$namespace);
	echo $response . "\n";
	exit;
?>
