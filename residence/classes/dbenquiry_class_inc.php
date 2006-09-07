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

	function init(){
		parent::init();
		//$this->remotedb =& $this->getObject('remotedb','remotedatasource');
		$this->student =& $this->getObject('student','studentmodule');
		$this->module = $this->getParam('module');
	}

	function enquire($personid){
		$sql = "select * from tbl_person p inner join tbl_studentFinancialAidEnquiry se on p.personID = se.personID where se.personID = '$personid'";
		echo $sql;
		return $this->student->runSql($sql,'tbl_contactDetails',$this->module);
	}


}
