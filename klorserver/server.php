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
	require_once("nusoap/nusoap.php");
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
	
	$server->register('filedrop',                           // method name
			array('filter' => 'xsd:string'),                // input parameters
			array('return' => 'xsd:string'),                // output parameters
			'urn:klorserver',                               // namespace
			'urn:klorserver#filedrop',                      // soapaction
			'rpc',                                          // style
			'encoded',                                      // use
			'Returns a list of files available'             // documentation
	);

	
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
			'Return sends files available'             // documentation
	);

	$server->register('getfile',                           // method name
			array('file' => 'xsd:string'),                // input parameters
			array('return' => 'xsd:string'),                // output parameters
			'urn:klorserver',                               // namespace
			'urn:klorserver#getfile',                      // soapaction
			'rpc',                                          // style
			'encoded',                                      // use
			'Returns files available'             // documentation
	);


	$server->register('file',                           // method name
			array('file' => 'xsd:string'),                // input parameters
			array('params' => 'xsd:string'),                // output parameters
			'urn:klorserver',                               // namespace
			'urn:klorserver#file',                      // soapaction
			'rpc',                                          // style
			'encoded',                                      // use
			'Returns a file available'             // documentation
	);



	$server->register('getRating',                           // method name
			array('filter' => 'xsd:string'),                // input parameters
			array('return' => 'xsd:string'),                // output parameters
			'urn:klorserver',                               // namespace
			'urn:klorserver#getRating',                      // soapaction
			'rpc',                                          // style
			'encoded',                                      // use
			'Returns a list of files available'             // documentation
	);



	/** Remote side metadata
	*
	*/
	
//---------------------------Normal Methods----------------------

	//drops a file	
	function filedrop(){
			$post	= '&action=filedrop';
			//Init Url for posting
			$url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER["REQUEST_URI"]; 
			$url = str_replace('modules/klorserver/server.php','',$url);
			$url = $url . "index.php?module=klorserver&action=filedrop";
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


	function getRating(){
			$post	= '&action=getRating';
			//Init Url for posting
			//fixed links dont work so i'm using a dynamic way of doing thinks
			//strip away the /var/www & index.php we end up with 
			//$url_path = str_replace('index.php','',$_SERVER[SCRIPT_FILENAME]);
			//$url_path = str_replace('/var/www','',$url);
			//old way
			
			$url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER["REQUEST_URI"]; 
			$url = str_replace('modules/klorserver/server.php','',$url);
			$url = $url . "index.php?module=klorserver&action=getRating";
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
//			/nextgen/modules/klorclient/client.php
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
//---------------------------Normal Methods----------------------



//-----------------------------these methods send stuff to server , i'm a newbie at this so....?
	/**
	*author:Jameel Adam
	*@param array xsd, 
	*method to insert into database from remote client
	*eg:client submits metadata and adds a rating on a course
	*then we get the param unserialize the meta data. with rating
	*/
//------------------------------------list insert table start
/*
array('listinsert' 	=> 'xsd:string',
		'userId'		=>  'xsd:string',
		'destination'		=>  'xsd:string',
		'link'			=>  'xsd:string',
		'title' 		=>  'xsd:string',
		'type' 			=>  'xsd:string',
		'name' 			=>  'xsd:string',
		'size' 			=>  'xsd:string',
		'description' 		=>  'xsd:string',
		'version' 		=>  'xsd:string',
		'datatype' 		=>  'xsd:string',
		'path' 			=>  'xsd:string',
		'filedate' 		=>  'xsd:string',
		'category' 		=> 'xsd:string'),
//,$userId,$destination,$link,$title,$type,$name,$size,$description,$version,$datatype,$path,$filedate,$category){
	//&userId=$userId&destination=$destination&link=$link&title=$title&type=$type&name=$name&size=$size&description=$description&version=$size&datatype=$datatype&path=$filepath&filedate=filedate&category=$category";


*/		 		





$server->register('listinsert',                           // method name
		array('listinsert'=>'xsd:string'), 
                	// input parameters
			array('return'=> 'xsd:string'),                // output parameters
			'urn:klorserver',                               // namespace
			'urn:klorserver#listinsert',                      // soapaction
			'rpc',                                          // style
			'encoded',                                      // use
			'Returns metadata available'             // documentation
	);



	function listinsert($listinsert){
	//---Prince's implemnt.&action=listinsert
	 $post = "&listinsert=$listinsert";
	///nextgen/modules/klorclient/client.php
	$url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER["REQUEST_URI"]; 
	$url = str_replace('modules/klorserver/server.php','',$url);
	$url = $url . "index.php?action=listinsert&module=klorserver";

	
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
			
			//array('rating' => 'xsd:string','id'=>'xsd:string')			
			
			if (curl_errno($ch)) 
			{
				return new soapval('listinsert','xsd:string',$listinsert);
			} 
			else {
				curl_close($ch);
				//return  new soapval('id','xsd:string',$id);
				//return  new soapval('rating','xsd:string',$rating);
				return new soapval('return','xsd:string',$result);
				//return new soapval('listinsert','xsd:string',$listinsert);
			}
		//return new soapval('listinsert','xsd:string',$listinsert);
		}
//------------------------------------insert into table end


	$server->register('insertrating',                           // method name
			array('rating' => 'xsd:string','id'=>'xsd:string'),
                	// input parameters
			array('return'=> 'xsd:string'),                // output parameters
			'urn:klorserver',                               // namespace
			'urn:klorserver#insertrating',                      // soapaction
			'rpc',                                          // style
			'encoded',                                      // use
			'Returns metadata available'             // documentation
	);



	function insertrating($rating,$id){
	//---Prince's implemnt.
        $post = "&rating=".$rating."&id=".$id;
	$url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER["REQUEST_URI"]; 
	$url = str_replace('modules/klorserver/server.php','',$url);
	$url = $url . "index.php?action=insertrating&module=klorserver";


     
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
			
			//array('rating' => 'xsd:string','id'=>'xsd:string')			

			if (curl_errno($ch)) 
			{
				return new soapval('rating','xsd:string',$rating);
			} 
			else {
				curl_close($ch);
				//return  new soapval('id','xsd:string',$id);
				//return  new soapval('rating','xsd:string',$rating);
				return new soapval('return','xsd:string',$result);
			}
		}
//----------------------------End rating method 

/**
	*author:Jameel Adam
	*@param array xsd, 
	*method to insert into database from remote client
	*eg:client submits metadata and adds a rating on a course
	*then we get the param unserialize the meta data. with rating
	*/



	$server->register('insertmetadata',                           // method name
			array('insertmetadata' => 'xsd:string'),
                	// input parameters
			array('return'=> 'xsd:string'),                // output parameters
			'urn:klorserver',                               // namespace
			'urn:klorserver#insertmetadata',                      // soapaction
			'rpc',                                          // style
			'encoded',                                      // use
			'Returns metadata available'             // documentation
	);



	function insertmetadata($insertmetadata){
	//---Prince's implemnt.
	$post = "&insertmetadata=".$insertmetadata;
        $url = "http://" .$_SERVER['HTTP_HOST'] . $_SERVER["REQUEST_URI"]; 
	$url = str_replace('modules/klorserver/server.php','',$url);
	$url = $url . "index.php?action=insertmetadata&module=klorserver";


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
			
			//array('rating' => 'xsd:string','id'=>'xsd:string')			

			if (curl_errno($ch)) 
			{
				return new soapval('insertmetadata','xsd:string',$rating);
			} 
			else {
				curl_close($ch);
				
				return new soapval('return','xsd:string',$result);
			}
		}



//------------------------End Of Requests----------------------

	// Use the request to (try to) invoke the service
	$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
	$server->service($HTTP_RAW_POST_DATA);
?>
