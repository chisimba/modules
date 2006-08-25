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
class dbjoinqueries extends dbTable
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
		//return $this->remotedb->connectRemotely('tbl_student');
           //Uses NUSOAP
           require_once("lib/nusoap/nusoap.php");
           $this->NAMESPACE="http://172.16.65.134/webserviceDEV/studentinfo2.php?wsdl";

           $this->server = new soapclient($this->NAMESPACE, true);
           $this->test = $this->server->getProxy();
	}

	function courseResults(){
	/*	$connection = $this->connect();
		$idnumber = $this->getParam('id');

		$sql = "select st.title,st.name,st.surname,st.studyLevel,sc.courseid, r.type,r.mark from tbl_student st inner join tbl_studentcourse sc on st.idnumber = sc.idnumber inner join tbl_results r on sc.id = r.studentcourseid where sc.yearofstudy like '2005%' and st.idnumber = '$idnumber'";
		
		return $connection->getArray($sql);      */
		
	}

	function courseAmount(){
	/*	$connection = $this->connect();
		$idnumber = $this->getParam('id');

		$sql = "select sum(c.coursecost) as amount,st.name,st.surname,st.title, st.studyLevel from tbl_student st inner join tbl_studentcourse sc on st.idnumber = sc.idnumber inner join tbl_course c on sc.courseid = c.courseid where sc.yearofstudy like '2005%' and st.idnumber = '$idnumber' group by st.name";
		return $connection->getArray($sql);   */
	}

	function courseAvarageMark(){
/*		$connection = $this->connect();
		$idnumber = $this->getParam('id');

		$sql = "select avg(r.mark) as ave from tbl_student st inner join tbl_studentcourse sc on st.idnumber = sc.idnumber inner join tbl_results r on sc.id = r.studentcourseid where sc.yearofstudy like '2005%' and st.idnumber = '$idnumber'";
		return $connection->getArray($sql); */
	}

}


