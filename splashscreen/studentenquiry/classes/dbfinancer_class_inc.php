<?php
/* ----------- data class extends dbTable for tbl_guestbook------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
    {
        die("You cannot view this page directly");
    }


/**
* Model class for the table  
*/
class dbfinancer extends dbTable
{

	var $remotedb;
    //Web services variables
    var $NAMESPACE;
    var $server;
    var $test;
    var $test3;
    /**
    * Constructor method to define the table
    */
    	function init() {
       	 	//$this->objUser = & $this->getObject("user", "security");
        	//$this->remotedb =& $this->getObject('remotedb','remotedatasource');
         //Uses NUSOAP
           require_once("lib/nusoap/nusoap.php");
           $this->NAMESPACE="http://172.16.65.134/webserviceDEV/studentinfo2.php?wsdl";

           $this->server = new soapclient($this->NAMESPACE, true);
           $this->test = $this->server->getProxy();
    	}

	function connect(){
		//$this->remotedb =& $this->getObject('remotedb','remotedatasource');
		//return $this->remotedb->connectRemotely('tbl_financer');
        //Uses NUSOAP
           require_once("lib/nusoap/nusoap.php");
           $this->NAMESPACE="http://172.16.65.134/webserviceDEV/studentinfo2.php?wsdl";

           $this->server = new soapclient($this->NAMESPACE, true);
           $this->test = $this->server->getProxy();
	}


	function getFinancer($idnumber = null){
/*		$connection = $this->connect();

		if(is_null($idnumber)){
			$id = $this->getParam('idnumber');
		
			if(!$id){
				$filter = null;
			}
			else{
				$filter = " where idnumber = '$id'";
			}
			
		}
		else{
			$filter = " where idnumber = '$idnumber'";
		}
		
		$sql = "select * from tbl_financer $filter";
		//var_dump($sql);
		return 	$connection->getArray($sql); */
	}
	

}
