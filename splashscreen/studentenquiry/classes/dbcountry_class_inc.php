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
class dbcountry extends dbTable
{

	var $remotedb;
    /**
    * Constructor method to define the table
    */
    	function init() {
       	 	$this->objUser = & $this->getObject("user", "security");
        	$this->remotedb =& $this->getObject('remotedb','remotedatasource');
    	}

	function connect(){
		$this->remotedb =& $this->getObject('remotedb','remotedatasource');
		return $this->remotedb->connectRemotely('tbl_country');
	}


	function getCountry($countrycode = null){
		$connection = $this->connect();

		if(is_null($countrycode)){
			$filter = null;
		else{
			$countrycode = $this->getParam('countrycode');
		
			if(!$countrycode){
				$filter = null;
			}
			else{
				$filter = " where countryCode = '$countrycode'";
			}
		}
		
		return 	$connection->getAll($filter);
	}

}
