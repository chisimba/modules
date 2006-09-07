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

//require_once('../../remotedatasource/classes/remotedb_class_inc.php');

class dbmiscinfo extends object
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

	function getLookupInfo($id){
		$filter = "where lookupID = '$id'";
		return $this->student->getInfo('tbl_lookUp',$this->module,null,$filter);

	}

	function getCourseInfo($id){
		$sql = "select * from tbl_studentCourse sc inner join tbl_course c on sc.courseid = c.courseID where sc.personID = '$id'";
		return $this->student->runSql($sql,'tbl_student',$this->module);
	}

	

}
