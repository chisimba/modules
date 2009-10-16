<?php
/**
 * Class wsgetapplicationstatus extends object
 * @package essay
 * @filesource
 */

require_once("SOAP/Client.php");

require_once 'SOAP/Base.php';
require_once 'SOAP/Fault.php';
require_once 'SOAP/Parser.php';
require_once 'SOAP/Value.php';
require_once 'SOAP/WSDL.php';

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']){
	die('You cannot view this page directly');
}
// end security check

class wsgetapplicationstatus extends object
{
public $result = array();
	/**
	 * Constructor method to define the table
	 */
	public function init()
	{

	}

	
function  obj2array(&$obj) {
  $out = array();
  foreach ($obj as $key => $val) {
    switch(true) {
        case is_object($val):
         $out[$key] = $this->obj2array($val);
         break;
      case is_array($val):
         $out[$key] = $this->obj2array($val);
         break;
      default:
        $out[$key] = $val;
    }
  }
  return $out;
}

public function getExample($personnumber){
	$result = array('personNumber' => '123654',
		            'surname' => 'Fairbrother',
					'firtnames' => 'Caroline Rose Somerset',
					'program' => 'AB0000',
			        'programDesc' => 'Bachelor of Arts',
			        'yos' => '1',
			        'statusDesc' => 'No decision has been taken yet (application incomplete)');
	return $result;

}
	
public function getStatus($personnumber) 
{
	try {
		$wsdl_url = 'http://paxdev.wits.ac.za:8080/wits-student-service/services/student.wsdl';
        //$wsdl_url = 'http://192.168.1.101:8080/wits-student-service/services/student.wsdl';
		
		//$wsdl_url = 'http://localhost:8080/wits-student-service/services/student.wsdl';

//		echo ('2');
			
		$WSDL     = new SOAP_WSDL($wsdl_url);

//		echo ('3');
			
		$client   = $WSDL->getProxy();
		//$client   = $WSDL->getEndPoint('Student');
		//$client   = $WSDL->getPortName('Student');
	    //$client = new SOAPClient($wsdl_url); 
	    //$client = new SOAP_Client($wsdl_url);
			
//		echo ('4');
			
		$params   = array('sch:StudentNumber' => $personnumber,
					      'sch:EffectiveDate' => '2009-08-31'
					      );

//		echo ('4');

		$result = $client->Student($params);
		//$result = $client->__SOAPCall("Student",$params );
		//$result = $client->call('Student',$params, array('namespace' => 'http://wits.ac.za/ss/schema') );

//		echo ('5---------------------');

		//var_dump($result);
//		echo ('6---------------------');
		if ($result != null){ 
		  $result2 = $this->obj2array($result);
		}
		// var_dump($result2);
//		echo ('7---------------------');
		return $result2;
	}
	catch (FaultException $e){
		echo "something is wrong";
		die();
	}
}

}

?>
