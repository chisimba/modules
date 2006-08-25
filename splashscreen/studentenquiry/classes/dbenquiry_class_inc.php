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



class dbenquiry extends object
{

	var $remotedb;
	var $student;
	var $module;

    //Web services variables
    var $NAMESPACE;
    var $server;
    var $test;
    var $test3;
    
	function init(){
		parent::init();
		//$this->remotedb =& $this->getObject('remotedb','remotedatasource');
		$this->student =& $this->getObject('student','studentmodule');
		$this->module = $this->getParam('module');

        //Uses NUSOAP
        require_once("lib/nusoap/nusoap.php");
        $NAMESPACE="http://172.16.65.134/webserviceDEV/studentinfo4.php?wsdl";

        $server2 = new soapclient($NAMESPACE, true);
        $test = $server2->getProxy();
	}

	function enquire($personid){
		//$sql = "select * from tbl_person p inner join tbl_studentFinancialAidEnquiry se ";
        //$sql .= "on p.personID = se.personID where se.personID = '$personid'";
		//echo $sql;
		//return $this->student->runSql($sql,'tbl_contactDetails',$this->module);
        $psinfo = $this->test->getlimitSTDET('STDNUM',"$personid",0,0);
        return $psinfo;
	}
 
    function enquire2($personid){
        $psinfo = $this->test->getlimitSTDET('STDNUM',"$personid",0,0);
        return $psinfo;
    }
     






}
