<?php
class dbsubfacultymoderator extends dbtable{
    var $tablename = "tbl_ads_subfacultymoderators";
    
    public function init(){
        parent::init($this->tablename);
       
    }


    public function saveModerator($faculty, $moderator) {
        $data = array('facultyid'=>$faculty,'userid'=>$moderator);
        $this->insert($data);
    }

    public function getModerators() {
        return $this->getAll("order by userid");
    }
    public function getModeratorEmail($name) {
        $data = $this->getRow('facultyid', $name, $this->tablename);
        return $data['userid'];
    }

     public function isModerator($courseid,$userid) {
        $faculty=$this->objDbcourseproposals->getFaculty($courseid);
        $sql = "select * from ".$this->tablename." where name= '".$faculty."' and userid='".$userid."'";
        $data = $this->getArray($sql);
        return count($data) > 0 ? TRUE:FALSE;
    }

    public function getModeratorData() {
      return $this->getAll("order by userid");
    }
}
?>