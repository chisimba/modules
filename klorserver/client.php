<?php
// Pull in the NuSOAP code
require_once('lib/nusoap/nusoap.php');
 include('XML/Serializer.php');	
$RES = $_SERVER["REQUEST_URI"];
$url = $RES;

			
			

print_r($_SERVER["REQUEST_URI"]);
// Create the client instance
	$client = new soapclient('http://'.$_SERVER['HTTP_HOST'].'/nextgen/modules/klorserver/server.php?wsdl', true);

	/**
	*Enabling the client 
	*/
	$client = new soapclient('http://'.$_SERVER['HTTP_HOST'].'/nextgen/modules/klorserver/server.php?wsdl', true);
	// Check for an error
	$err = $client->getError();
	if ($err) {
		// Display the error
		echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
		// At this point, you know the call that follows will fail
	}
	
$listinsert['listinsert']=array(
		'userId'		=>  'blink',
		'destination'	=>  'blink',
		'link'			=>  'blink',
		'title' 		=>  'blink',
		'type' 			=>  'blink',
		'name' 			=>  'blink',
		'size' 			=>  'blink',
		'description' 	=>  'blink',
		'version' 		=>  'blink',
		'datatype' 		=>  'blink',
		'path' 			=>  'blink',
		'license' 		=>  'blink',
		'filedate' 		=>  date('F j, Y, g:i a'),
		'category' 		=> "CoursewareUpload"
	);

//$listinsert=array('listinsert'=> 'blink');
	$result = $client->call('listinsert');
	echo '<pre>';
	print_r($result);

 
	
// Display the request and response
echo '<h2>Request</h2>';
echo '<pre>' . htmlspecialchars($client->request, ENT_QUOTES) . '</pre>';
echo '<h2>Response</h2>';
echo '<pre>' . htmlspecialchars($client->response, ENT_QUOTES) . '</pre>';
// Display the debug messages
echo '<h2>Debug</h2>';
echo '<pre>' . htmlspecialchars($client->debug_str, ENT_QUOTES) . '</pre>';

?>
