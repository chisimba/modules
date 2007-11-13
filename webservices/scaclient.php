<?php
include 'SCA/SCA.php';

$smd = file_get_contents('http://127.0.0.1/chisimba_framework/app/packages/webservices/server.php?wsdl');
file_put_contents('HelloService.wsdl', $smd);
$service = SCA::getService('server.php');

var_dump($service);
echo $service->lookup('William Shakespeare');

//var_dump($service->getBlogPosts(54));

echo "<br />";
echo $service->hello('Paul');

$s = new SoapClient('http://127.0.0.1/chisimba_framework/app/packages/webservices/server.php?wsdl', array('location' =>
'http://fsiu.uwc.ac.za/chisimba_modules/webservices/server.php?wsdl'));
var_dump($s->lookup(array('name' => 'William Shakespeare')));
var_dump($s);

?>