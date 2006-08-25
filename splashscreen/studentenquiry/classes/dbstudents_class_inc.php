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
class dbstudents extends dbTable
{

	var $remotedb;
	var $recordcount;
 //Web services variables
    var $NAMESPACE;
    var $server;
    var $test;
    var $test3;
    /**
    * Constructor method to define the table
    */
    	function init() {
       	 	$this->objUser = & $this->getObject("user", "security");
		//$this->remotedb =& $this->getObject('remotedb','remotedatasource');

        //Uses NUSOAP
        require_once("lib/nusoap/nusoap.php");
        $this->NAMESPACE="http://172.16.65.134/webserviceDEV/studentinfo4.php?wsdl";

        $this->server = new soapclient($this->NAMESPACE, true);
        $this->test = $this->server->getProxy();
    	}
	
	function connect(){
		$this->remotedb =& $this->getObject('remotedb','remotedatasource');
		return $this->remotedb->connectRemotely('tbl_student');
	}


	function getStudent($id = null){
		/*$connection = $this->connect();

		if(is_null($id)){
			$id = $this->getParam('id');

			if(!$id){
				$filter = null;
			}
			else{
				$filter = " where idnumber = '$id'";
			}
			
		}
		else{
			$filter = " where idnumber = '$id'";
		}
		
		//$this->recordcount = $connection->getRecordCount($filter);
  
        return 	$connection->getAll($filter);   */
        $getst = $this->test->getlimitSTDET('STDNUM', '2536415');
        return $getst;
        
	}


	function address($id){
     /* $connection = $this->connect();
	
		$filter = " select * from tbl_contactdatails where addresseeidnumber = '$id'";
		return $connection->getArray($filter);*/
  
        $addr = $this->test->getSTADR('STDNUM', '2536415');
        return $addr;
	}

	function numRecords(){
		return $this->recordcount;
	}
	
	function allRecords(){
		$connection = $this->connect();
		return $connection->getRecordCount();
	}

	function students($filter = null){
		$connection = $this->connect();
		return $connection->getAll($filter);
	}

	function allocated(){
		$filter = "where allocated = 'allocated'";
		return $this->students($filter);
	}

	function declined(){
		$filter = "where allocated = 'declined'";
		return $this->students($filter);
	}
	
	function pending(){
		$filter = "where allocated = 'pending'";
		return $this->students($filter);
	}
	
	function waitingList(){
		$filter = "where allocated = 'waiting_list'";
		return $this->students($filter);
	}

	function search(){
		$stdnum = $this->getParam('studentNumber');
		$applnum = $this->getParam('applicationNumber');
		$surname = $this->getParam('surname');
		$idnumber = $this->getParam('idNumber');

		if(!$stdnum and !$applnum and !$surname and !$idnumber)//0
              $searchst = "";

		if(!$stdnum and !$applnum and !$surname and $idnumber)//1
              $searchst = $this->test->getlimitSTDET('IDN', "$idnumber");

		if(!$stdnum and !$applnum and $surname and !$idnumber)//2
              $searchst = $this->test->getlimitSTDET('SURNAM', "$surname");

		if(!$stdnum and !$applnum and $surname and $idnumber)//3
              $searchst = $this->test->getlimitSTDET('IDN', "$idnumber");
             // $searchst = $this->test->getSTDET('IDN', "$idnumber");
             // $sql = " where surname like '$surname%' and idnumber = '$idnumber'";

		if(!$stdnum and $applnum and !$surname and !$idnumber)//4
              $getapnm = $this->test->getlimitSTDET('APLNUM', "$applnum");
              $searchst = $this->test->getlimitSTDET('STDNUM', "$getapnm[0]["STDNUM"]");
              //$sql = " where applicationNumber = '$applnum'";

		if(!$stdnum and $applnum and !$surname and $idnumber)//5
            $searchst = $this->test->getlimitSTDET('IDN', "$idnumber");
			//$sql = " where applicationNumber = '$applnum' and idnumber = '$idnumber'";

		if(!$stdnum and $applnum and $surname and !$idnumber)//6
            $getapnm = $this->test->getlimitSTDET('APLNUM', "$applnum");
            $searchst = $this->test->getlimitSTDET('STDNUM', "$getapnm[0]["STDNUM"]");
           //$sql = " where applicationNumber = '$applnum' and surname like '$surname%'";
		
		if(!$stdnum and $applnum and $surname and $idnumber)//7
             $getapnm = $this->test->getlimitSTDET('APLNUM', "$applnum");
             $searchst = $this->test->getlimitSTDET('STDNUM', "$getapnm[0]["STDNUM"]");
           //$sql = " where applicationNumber = '$applnum' and surname like '$surname%' and idnumber = '$idnumber'";

		if($stdnum and !$applnum and !$surname and !$idnumber)//8
           $searchst = $this->test->getlimitSTDET('STDNUM', "$stdnum");
           //$sql = " where studentNumber = '$stdnum'";

		if($stdnum and !$applnum and !$surname and $idnumber)//9
            $searchst = $this->test->getlimitSTDET('STDNUM', "$stdnum");
             //$sql = " where studentNumber = '$stdnum' and idnumber = '$idnumber'";

		if($stdnum and !$applnum and $surname and !$idnumber)//10
            $searchst = $this->test->getlimitSTDET('STDNUM', "$stdnum");
             //$sql = " where studentNumber = '$stdnum' and surname like '$surname%'";

		if($stdnum and !$applnum and $surname and $idnumber)//11
            $searchst = $this->test->getlimitSTDET('STDNUM', "$stdnum");
             //$sql = " where studentNumber = '$stdnum' and surname like '$surname%' and idnumber = '$idnumber'";
		
		if($stdnum and $applnum and !$surname and !$idnumber)//12
            $searchst = $this->test->getlimitSTDET('STDNUM', "$stdnum");
             //$sql = "where studentNumber = '$stdnum and applicationNumber = '$applnum'";
		
		if($stdnum and $applnum and !$surname and $idnumber)//13
             $searchst = $this->test->getlimitSTDET('STDNUM', "$stdnum");
             //$sql = "where studentNumber = '$stdnum and applicationNumber = '$applnum' and idnumber = '$idnumber'";
	
		if($stdnum and $applnum and $surname and !$idnumber)//14
            $searchst = $this->test->getlimitSTDET('STDNUM', "$stdnum");
             //$sql = "where studentNumber = '$stdnum and applicationNumber = '$applnum' and idnumber = '$idnumber' and surname like '$surname%'";

		if($stdnum and $applnum and $surname and $idnumber)//15
            $searchst = $this->test->getlimitSTDET('STDNUM', "$stdnum");
             //$sql = "where studentNumber = '$stdnum and applicationNumber = '$applnum' and idnumber = '$idnumber' and surname like '$surname%' and idnumber = '$idnumber'";


	/*
	if(strlen($stdnum) >1){
			if(strlen($applnum) > 1){
				$sql = " where applicationNumber = '$applnum' and studentNumber = '$stdnum'";
			}
		}
		
		if(strlen($stdnum) < 1){
			if(strlen($applnum) > 1){
				$sql = " where applicationNumber = '$applnum'";
			}
		}

		if(strlen($stdnum) > 1){
			if(strlen($applnum) < 1){
				$sql = " where studentNumber = '$stdnum'";
			}
		}

		if(strlen($stdnum) < 1){
			if(strlen($applnum) < 1){
				$sql = null;
			}
		}
		*/
		//return $this->students($sql);
        return $searchst;
	}

	function updateStudent(){
		$connection = $this->connect();

		$filter = array('allocated'=>$this->getParam('status'));
		$connection->update('idnumber',$this->getParam('id'),$filter);
		$residence = $this->getParam('residence');
		$studentid = $this->getParam('id');

		if(isset($residence) and strlen($residence) > 0){
			$date = date("Y-m-d");
			$sql = "insert into tbl_studentres (resID,studentID,signInDate) values ('$residence','$studentid','$date')";
			$connection->query($sql);
		}
		
	}

	function getReasonForDecline(){
		$connection = $this->connect();
		$studentid = $this->getParam('id');
		$filter = "where idnumber = '$studentid'";
		var_dump($filter);
		die();
		$data = $this->students($filter);
		foreach($data as $value){
			if(strlen($value['reasonForDecline']) > 2 and isset($value['reasonForDecline'])){
				return true;
			}
			else{
				return false;
			} 
		}
	}



}
