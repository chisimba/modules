<?php
include 'SCA/SCA.php';

$smd = file_get_contents('http://127.0.0.1/chisimba_framework/app/packages/webservices/server.php?wsdl');
file_put_contents('HelloService.wsdl', $smd);
$service = SCA::getService('HelloService.wsdl');

var_dump($service);
//echo $service->hello('Paul');
var_dump($service->getBlogPosts(54));

echo "<br />";

$s = new SoapClient('http://127.0.0.1/chisimba_framework/app/packages/webservices/server.php?wsdl');
var_dump($s);
