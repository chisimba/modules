<?php

	/**
	*get a list of all the files all the files
	*/
	$this->objFile =& 


	/**
	* Method to extract base64encoded files 'zipfiles' 
	* 
	*/
	$file = file_get_contents('/var/www/nextgen/usrfiles/content/533/533.zip/');
	$file = base64_encode($file);

	/**
	*Enabling the client 
	*/
	$client = new soapclient('http://localhost/nextgen/modules/klorserver/server.php?wsdl', true);
	// Check for an error
	$err = $client->getError();
	if ($err) {
		// Display the error
		echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
		// At this point, you know the call that follows will fail
	}
	$result = $client->call('fileList', array('file' => $file));





	
// Display the request and response
echo '<h2>Request</h2>';
echo '<pre>' . htmlspecialchars($client->request, ENT_QUOTES) . '</pre>';
echo '<h2>Response</h2>';
echo '<pre>' . htmlspecialchars($client->response, ENT_QUOTES) . '</pre>';
// Display the debug messages
echo '<h2>Debug</h2>';
echo '<pre>' . htmlspecialchars($client->debug_str, ENT_QUOTES) . '</pre>';



?>