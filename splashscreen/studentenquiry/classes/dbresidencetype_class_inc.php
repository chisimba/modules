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
class dbsresidencetype extends dbTable
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

	

}
