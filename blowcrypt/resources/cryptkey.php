<?php
/**
* This webservice returns the session key required by the blowcrypt client.
*
* IMPORTANT NOTE: THIS WEBSERVICE IS NOT DESIGNED TO RUN WITHIN THE FRAMEWORK BUT IS A STANDALONE WEBSERVICE
*
* @package blowcrypt
* @category sems
* @copyright 2004, University of the Western Cape & AVOIR Project
* @license GNU GPL
* @version
* @author Serge Meunier
*/

require_once('SOAP/Server.php');
require_once('SOAP/Disco.php');
require_once('blowcryptserver_class_inc.php');

class UWC
{
	var $__dispatch_map = array();
	var $objCrypt;

    function UWC()
	{
		$this->objCrypt = new blowcryptserver();

         // Define the dispatch map on the Web services method fo TERMS
         $this->__dispatch_map['getSessionKey'] =
             array('in' => array(),
                   'out' => array('Key' => 'string'),
                   );
	}

	function getSessionKey()
	{
		$sessionKey = $this->objCrypt->getEncryptedSessionKey();

		return $sessionKey;
	}


//End of class
}

$server = new SOAP_Server();

// initialize the pathfinder class
$webservice = new UWC();

// set the path finder class as default responder for the WSDL class
$server->addObjectMap($webservice,'http://schemas.xmlsoap.org/soap/envelope/');

// start serve
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']=='POST')
{
    $server->service($HTTP_RAW_POST_DATA);
}
else
{

     $disco = new SOAP_DISCO_Server($server,'UWC');

     header("Content-type: text/xml");

     if (isset($_SERVER['QUERY_STRING']) && strcasecmp($_SERVER['QUERY_STRING'],'wsdl') == 0)
	 {

         // show only the WSDL/XML output if ?wsdl is set in the address bar
         echo $disco->getWSDL();

     }
	 else
	 {
         echo $disco->getDISCO();
     }
}

exit;

?>
