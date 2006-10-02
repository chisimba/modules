<?php

//header("Content-Type: text/xml; charset=utf-8"); 
/**
 * Service providor WSDL for klorserver module
 * This procedural file will provide a dynamic WSDL
 * @author Paul Scott
 * @author Jameel Adam
 * @package klorserver
 * @copyright GNU/GPL v2 AVOIR UWC
 */
	//pull in the nusoap code
	require_once("lib/nusoap.php");
	
	// Create the server instance
	$server = new soap_server();
	// Initialize WSDL support and namespaces
	$server->configureWSDL('klorserver', 'urn:klorserver');
	// Register the method to expose

/**
 * TODO: 1. retrieve files from filesystem and sent to remote
 *       2. Retrive list of available courses (files)
 *
 */

/**
 * WSDL function to list the files available
 * The function will return an array of filenames
 * that are found on the local server
 * We will take a single param as a filter
 * NOTE: the PHP function to drive this thing is called fileList
 *
 * @param array $register
 * @return void
 */
	
	$server->register('fileList',                           // method name
			array('filter' => 'xsd:string'),                // input parameters
			array('return' => 'xsd:string'),                // output parameters
			'urn:klorserver',                               // namespace
			'urn:klorserver#fileList',                      // soapaction
			'rpc',                                          // style
			'encoded',                                      // use
			'Returns a list of files available'             // documentation
	);


	$server->register('sendfile',                           // method name
			array('file' => 'xsd:string'),                // input parameters
			array('return' => 'xsd:string'),                // output parameters
			'urn:klorserver',                               // namespace
			'urn:klorserver#sendfile',                      // soapaction
			'rpc',                                          // style
			'encoded',                                      // use
			'Returns a list of files available'             // documentation
	);

	$server->register('getfile',                           // method name
			array('file' => 'xsd:string'),                // input parameters
			array('return' => 'xsd:string'),                // output parameters
			'urn:klorserver',                               // namespace
			'urn:klorserver#getfile',                      // soapaction
			'rpc',                                          // style
			'encoded',                                      // use
			'Returns a list of files available'             // documentation
	);



	function getfile(){
			$post	= '&action=getfile';
			//Init Url for posting
			$url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER["REQUEST_URI"]; 
			$url = str_replace('modules/klorserver/server.php','',$url);
			$url = $url . "index.php?module=klorserver&action=getfile";
			//echo $url;
			// initialize curl handle
			$ch = curl_init();
			// set url to post to
			curl_setopt($ch, CURLOPT_URL,$url);
			// return into a variable
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			// times out after 4s
			curl_setopt($ch, CURLOPT_TIMEOUT, 4);
			// add POST fields
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			// run the whole process
			$result = curl_exec($ch);
			if (curl_errno($ch)) 
			{
				return new soapval('return','xsd:string',$result);
			} 
			else {
				curl_close($ch);
				return new soapval('return','xsd:string',$result);
			}
		}



	function sendfile(){
			$post	= '&action=sendfile';
			//Init Url for posting
			$url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER["REQUEST_URI"]; 
			$url = str_replace('modules/klorserver/server.php','',$url);
			$url = $url . "index.php?module=klorserver&action=sendfile";
			//echo $url;
			// initialize curl handle
			$ch = curl_init();
			// set url to post to
			curl_setopt($ch, CURLOPT_URL,$url);
			// return into a variable
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			// times out after 4s
			curl_setopt($ch, CURLOPT_TIMEOUT, 4);
			// add POST fields
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			// run the whole process
			$result = curl_exec($ch);
			if (curl_errno($ch)) 
			{
				return new soapval('return','xsd:string',$result);
			} 
			else {
				curl_close($ch);
				return new soapval('return','xsd:string',$result);
			}
		}

	
	function fileList(){
			$post	= '&action=fileList';
			//Init Url for posting
			$url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER["REQUEST_URI"]; 
			$url = str_replace('modules/klorserver/server.php','',$url);
			$url = $url . "index.php?module=klorserver&action=fileList";
			//echo $url;
			// initialize curl handle
			$ch = curl_init();
			// set url to post to
			curl_setopt($ch, CURLOPT_URL,$url);
			// return into a variable
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			// times out after 4s
			curl_setopt($ch, CURLOPT_TIMEOUT, 4);
			// add POST fields
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			// run the whole process
			$result = curl_exec($ch);
			if (curl_errno($ch)) 
			{
				return new soapval('return','xsd:string',$result);
			} 
			else {
				curl_close($ch);
				return new soapval('return','xsd:string',$result);
			}
		}

	// Use the request to (try to) invoke the service
	$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
	$server->service($HTTP_RAW_POST_DATA);
?>
