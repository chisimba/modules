<?php

class sectiond extends dbtable{
  var $tablename = "tbl_sectiond";
  public function init(){
    parent::init($this->tablename);
  }

  public function addRecord($coursecode,$user,$question,$value){
    $data = array('coursecode'=>$coursecode,'user'=>$user,'question'=>$question,'value'=>$value);
    $this->insert($data);
  }
  
  public function checkUser($user) {
    if (count($this->getValues($user)) > 0) {
      return 0; //error, user exists
    }
    else {
      return 1;
    }
  }
  public function getRecord(){
		$data = $this->getAll();
		return $data;
  }

	public function getValues($userID){
		$sql = "select * from $this->tablename where user = '$userID';";
		return $this->getArray($sql);
	}

	public function updateRecord($coursecode,$user,$field,$value){
		$sql = "update $this->tablename set value = '$value' where coursecode = '$coursecode' and user = '$user' and question = '$field';";
		return $this->_execute($sql);
	}
}

?>
