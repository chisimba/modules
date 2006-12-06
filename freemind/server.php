<?php

/**
 *Description of file
 *This is the webservice for KEWLNEXTGEN.
 *
 *Uses the nusoap library for HTTP transport
 *@access public
 *@copyright (c) 2004 UWC
 *@$id
 *@Author Prince Mbekwa
*/

require_once("lib/nusoap.php");

/**
 *
 *Start the nusoap server object
 *
 *Setup the WSDL that will be used by the client
 *
 *Register the the methods that will be exposed by the service
*/

$server = new soap_server();
$server->configureWSDL('freemind', 'urn:freemind');

/**
 *---------------Registering exposed Methods-----------*
 */

/**
 *
 *@register post_Map  method as available
 * Method to post a freemind map to KNG Server
 * @var string $contextCode: The context code being edited
 * @var string $node: The value of the node key to insert into
 * @var string $Map: an XML string containing the map
*/
$server->register('post_Map',
array('contextCode' => 'xsd:string','title' =>'xsd:string',
'map'=>'xsd:string'),
array('return'=> 'xsd:string'),
 		'urn:freemind',                                 // namespace
        'urn:freemind#post_Map',                        // soapaction
        'rpc',                                          // style
        'encoded',                                      // use
        'posts a freemind map to nextgen');

/**
 *-------------------Register Context Methods-----------------*
 *@register Authenticate as available
 *Method to authenticat server requests
 *@params $userName and $passWord
 *
 *@returns True or False
*/
$server->register('authenticate',
array('userName'=> 'xsd:string','passWord'=>'xsd:string'),
array('return'=> 'xsd:string'),
		'urn:freemind',                                 // namespace
        'urn:freemind#authenticate',                        // soapaction
        'rpc',                                          // style
        'encoded',                                      // use
        'Authentication call to nextgen');

/**
 *@register getContext List as available
 *Method to get contexts stored in Mysql tables
 *@params Username and passWord
 *@return XML stream
*/
$server->register('getContextList',
array('ContextCode'=>'xsd:string','userName'=> 'xsd:string',
		'passWord'=>'xsd:string'),
array('return'=> 'xsd:string'),
		'urn:freemind',                                 // namespace
        'urn:freemind#getContextList',                        // soapaction
        'rpc',                                          // style
        'encoded',                                      // use
        'Gets a list of all contexts existing in nextgen');

/**
 *@register get_Map_List List as available
 *Method to get contexts stored in Mysql tables
 *@params Username and passWord
 *@return XML stream
*/
$server->register('get_Map_List',
array('ContextCode'=>'xsd:string',
		'userName'=> 'xsd:string','passWord'=>'xsd:string'),
array('return'=> 'xsd:string'),
		'urn:freemind',                                 // namespace
        'urn:freemind#get_Map_List',                        // soapaction
        'rpc',                                          // style
        'encoded',                                      // use
        'Gets a list of maps existing in nextgen');
/**
 *@register get_Map List as available
 *Method to get contexts stored in Mysql tables
 *@params Username and passWord
 *@return XML stream
*/
$server->register('get_Map',
array('mapid'=>'xsd:string','ContextCode'=>'xsd:string',
		'userName'=> 'xsd:string',
		'passWord'=>'xsd:string'),
array('return'=> 'xsd:string'),
		'urn:freemind',                                 // namespace
        'urn:freemind#get_Map',                        // soapaction
        'rpc',                                          // style
        'encoded',                                      // use
        'Gets an existing map existing in nextgen');

/**
 *
 *@function Post  method as available
 * Method to post a freemind map to KNG Server
 * @var string $contextCode: The context code being edited
 * @var string $node: The value of the node key to insert into
 * @var string $Map: an XML string containing the map
*/
function post_Map($contextCode,$title,$map)
{
$XPost = "contextCode=$contextCode&title=$title&map=$map";
$url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER["REQUEST_URI"];
$url = str_replace('modules/freemind/server.php','',$url);
$url = $url . "index.php?module=freemind&action=savemap";

    $ch = curl_init();    // initialize curl handle
    curl_setopt($ch, CURLOPT_URL,$url); // set url to post to
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
    curl_setopt($ch, CURLOPT_TIMEOUT, 4); // times out after 4s
    curl_setopt($ch, CURLOPT_POSTFIELDS, $XPost); // add POST fields
    $result = curl_exec($ch); // run the whole process

    // initialize curl handle
    $ch = curl_init();
    // set url to post to
    curl_setopt($ch, CURLOPT_URL,$url);
    // return into a variable
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    // times out after 4s
    curl_setopt($ch, CURLOPT_TIMEOUT, 4);
    // add POST fields
    curl_setopt($ch, CURLOPT_POSTFIELDS, $XPost);
    // run the whole process
    $result = curl_exec($ch);
    $result = utf8_encode($result);


     return new soapval('return','xsd:string',$result);
}

/**
 *
 * Web service Authenticate function
 *@param $Username and $passWord
 *@returns a Boolean value
*/

function authenticate($userName,$passWord)
{
     $XPost = "userName=$userName&passWord=$passWord";
     //Init Url for posting
     $url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER["REQUEST_URI"];
	 $url = str_replace('modules/freemind/server.php','',$url);
	 $url = $url . "index.php?module=webservices&action=authenticate";
	 // initialize curl handle
     $ch = curl_init();
     // set url to post to
     curl_setopt($ch, CURLOPT_URL,$url);
     // return into a variable
     curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
     // times out after 4s
     curl_setopt($ch, CURLOPT_TIMEOUT, 4);
     // add POST fields
     curl_setopt($ch, CURLOPT_POSTFIELDS, $XPost);
     // run the whole process
     $result = curl_exec($ch);
     $result = utf8_encode($result);
     return new soapval('return','xsd:string',$result);
}
/**
 *
 *getContext function
 *Method get context list from tbl_context
 *@returns XML stream
*/
function getContextList($userName,$passWord)
{
     $XPost = "userName=".$userName."&passWord=".$passWord."";
     $url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER["REQUEST_URI"];
	 $url = str_replace('modules/freemind/server.php','',$url);
	 $url = $url . "index.php?module=webservices&action=getContextList";

     // initialize curl handle
     $ch = curl_init();
     // set url to post to
     curl_setopt($ch, CURLOPT_URL,$url);
     // return into a variable
     curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
     // times out after 4s
     curl_setopt($ch, CURLOPT_TIMEOUT, 4);
     // add POST fields
     curl_setopt($ch, CURLOPT_POSTFIELDS, $XPost);
     // run the whole process
     $result = curl_exec($ch);
     $result = utf8_encode($result);

     return new soapval('return','xsd:string',$result);
}

/**
 *
 *getContext function
 *Method get context list from tbl_context
 *@returns XML stream
*/
function get_Map_List($contextCode,$userName,$passWord)
{
     $XPost = "contextCode=".$contextCode."&userName=".$userName."&passWord=".$passWord."";
     $url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER["REQUEST_URI"];
	 $url = str_replace('modules/freemind/server.php','',$url);
	 $url = $url . "index.php?module=freemind&action=get_Map_List";

     // initialize curl handle
     $ch = curl_init();
     // set url to post to
     curl_setopt($ch, CURLOPT_URL,$url);
     // return into a variable
     curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
     // times out after 4s
     curl_setopt($ch, CURLOPT_TIMEOUT, 4);
     // add POST fields
     curl_setopt($ch, CURLOPT_POSTFIELDS, $XPost);
     // run the whole process
     $result = curl_exec($ch);
     $result = utf8_encode($result);

     return new soapval('return','xsd:string',$result);
}

/**
 *
 *getContext function
 *Method get context list from tbl_context
 *@returns XML stream
*/
function get_Map($contextCode,$mapid,$userName,$passWord)
{
     $XPost = "contextCode=".$contextCode."&mapid=".$mapid."&userName=".$userName."&passWord=".$passWord."";
     $url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER["REQUEST_URI"];
	 $url = str_replace('modules/freemind/server.php','',$url);
	 $url = $url . "index.php?module=freemind&action=get_Map";

     // initialize curl handle
     $ch = curl_init();
     // set url to post to
     curl_setopt($ch, CURLOPT_URL,$url);
     // return into a variable
     curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
     // times out after 4s
     curl_setopt($ch, CURLOPT_TIMEOUT, 4);
     // add POST fields
     curl_setopt($ch, CURLOPT_POSTFIELDS, $XPost);
     // run the whole process
     $result = curl_exec($ch);
     $result = utf8_encode($result);

     return new soapval('return','xsd:string',$result);
}

/**
 *Start the HTTP listener service which will post data to the framework
 *HTTP_RAW_POST_DATA enables the service to post data to the framework
 *First evaluate if HTTP_RAW_POST_DATA has been set previously
 * If not set HTTP_RAW_POST_DATA.
*/
 $HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA :'';
 $server->service($HTTP_RAW_POST_DATA);
  exit();
?>
