<?php

class dbdocument extends dbtable{
  var $tablename = "tbl_documentstore";
  public function init(){
    parent::init($this->tablename);
  }

  public function addRecord($courseid, $form, $question, $value, $status, $version, $currentuser) {
    $data = array('coursecode'=>$courseid,'question'=>$question,'value'=>$value, 'status'=>$status, 'version'=>$version, 'currentuser'=>$currentuser, 'formnumber'=>$form);
    $this->insert($data);
  }
  
  public function getRecord(){
		$data = $this->getAll();
		return $data;
  }

	public function getValues($courseid, $form){
		$sql = "select * from $this->tablename where coursecode = '$courseid' and formnumber='$form' and version = (
		select max(version) from $this->tablename where coursecode='$courseid');";
		$rows = $this->getArray($sql);
		//remove slashes
    return $this->removeSlashes($rows);
	}
	
	public function removeSlashes($twoDarray) {
  	foreach ($twoDarray as $two=>$array) {
    	foreach ($array as $key=>$value) {
      	$twoDarray[$two][$key] = stripslashes($value);
    	}
  	}
  	return $twoDarray;
	}

	public function updateRecord($courseid, $form , $question, $field, $currentuser){
		$sql = "update $this->tablename set value = '$field' where coursecode = '$courseid' and question = '$question' and formnumber='$form' and currentuser='$currentuser';";
		$count = $this->_execute($sql)->fetchRow();
		return true;
	}
	
	public function getVersion($courseid, $userid) {
  	$courseid = addslashes($courseid);
  	$userid = addslashes($userid);
  	$sql = "select version, status, currentuser from $this->tablename where coursecode = '$courseid' order by version desc;";
  	$rows = $this->getArray($sql);
  	if (count($rows) == 0) {
    	return array('version'=>'0', 'status'=>'submitted', 'currentuser'=>$userid);
  	}
  	else {
    	return array('version'=>$rows[0]['version'], 'status'=>$rows[0]['status'], 'currentuser'=>$rows[0]['currentuser']);
  	}
	}
  
  public function increaseVersion($courseid, $currentuser, $newversion) {
    $courseid = addslashes($courseid);
    $currentuser = addslashes($currentuser);
    $oldversion = $newversion - 1;
    $sql = "Select coursecode, question, value, 'unsubmitted' as status, '$newversion' as version, '$currentuser' as currentuser, formnumber from $this->tablename where coursecode = '$courseid' and version = '$oldversion';";
    $rows = $this->getArray($sql);
    foreach ($rows as $row) {
      $this->insert($row);
    }
  }
  
  public function submitProposal($courseid, $version) {
    $sql = "update $this->tablename set status = 'submitted' where coursecode = '$courseid' and version = '$version';";
    $count = $this->_execute($sql);
  }
  
  public function getProposal($courseid, $version) {
    $sql = "select * from $this->tablename where coursecode = '$courseid' and version='$version';";
    return $this->removeSlashes($this->getArray($sql));
  }
}

?>