<?php
// Pull in the NuSOAP code
require_once('lib/nusoap/nusoap.php');
// Create the client instance
//	$client = new soapclient('http://'.$_SERVER['HTTP_HOST'].'/jameel/nextgen/modules/klorserver/server.php?wsdl', true);
//print_r($_SERVER["REQUEST_URI"]);
/**
*Enabling the client 
*/

$client = new soapclient('http://'.'localhost'.'/nextgen/modules/klorserver/server.php?wsdl', true);
	

	//$client = new soapclient('http://'.'fsiu.uwc.ac.za'.'/jameel/nextgen/modules/klorserver/server.php?wsdl', true);
	// Check for an error
	$err = $client->getError();
	if ($err) {
	// Display the error
	echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
	// At this point, you know the call that follows will fail
	}
	//$file =	array('id' =>'klor_7','rating' =>'10');
	//$id ='klor_7';
	//$rating = '10';
	$result = $client->call('filedrop');
	//$result = $client->call('fileList');
	echo '<pre>';
	print_r($result);
	//var_dump($result);



/*
// Display the request and response
echo '<h2>Request</h2>';
echo '<pre>' . htmlspecialchars($client->request, ENT_QUOTES) . '</pre>';
echo '<h2>Response</h2>';
echo '<pre>' . htmlspecialchars($client->response, ENT_QUOTES) . '</pre>';
// Display the debug messages
echo '<h2>Debug</h2>';
echo '<pre>' . htmlspecialchars($client->debug_str, ENT_QUOTES) . '</pre>';
*/


?>
