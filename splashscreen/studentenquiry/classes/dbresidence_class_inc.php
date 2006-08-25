<?php
/* ----------- data class extends dbTable for tbl_guestbook------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
    {
        die("You cannot view this page directly");
    }


/**
* Model class for the table tbl_residence
*/
class dbresidence extends dbTable
{

	var $name;
	var $surname;
	var $comments;
    //Web services variables
    var $NAMESPACE;
    var $server;
    var $test;
    var $test3;

	function init() {
       		parent::init('tbl_residence');
      		//$this->objUser = & $this->getObject("user", "security");
		//$this->remotedb =& $this->getObject('remotedb','remotedatasource');
           //Uses NUSOAP
           require_once("lib/nusoap/nusoap.php");
           $this->NAMESPACE="http://172.16.65.134/webserviceDEV/studentinfo4.php?wsdl";

           $this->server = new soapclient($this->NAMESPACE, true);
           $this->test = $this->server->getProxy();
    	}
	
	function getData(){
		//$this->name = $this->getParam('name');
		//$this->surname = $this->getParam('surname');
		//$this->comments = $this->getParam('comments');
	}

	function addData(){
/*		$this->getData();
		$fields = array('name'=>$this->name,'surname'=>$this->surname, 'comment'=>$this->comments);
		$objRemote =& $this->remotedb->connectRemotely('tbl_testing');
		$objRemote->insert($fields);
		$objRemote = null;
		*/
	}
}

?>
