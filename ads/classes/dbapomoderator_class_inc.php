<?php
class dbapomoderator extends dbtable{
    var $tablename = "tbl_ads_apomoderators";
    
    public function init(){
        parent::init($this->tablename);
     
    }
    public function saveModerator($faculty, $moderator, $school) {
        $data = array('facultyid'=>$faculty,'userid'=>$moderator, 'schoolid'=>$school, "date_forwarded"=>strftime('%Y-%m-%d %H:%M:%S',mktime()));
        $this->insert($data);
    }
    public function deleteModerator($id) {
       $sql = "delete from ".$this->tablename." where id='".$id."'";
       $data = $this->getArray($sql);
    }
    public function getModerators() {
        return $this->getAll("order by userid");
    }
    public function getModeratorEmail($name) {
        $data = $this->getRow('name', $name, $this->tablename);
        return $data['userid'];
    }

    public function getAPOModeratorEmail($facultyid) {
        $data = $this->getRow('facultyid', $facultyid, $this->tablename);
        return $data['userid'];
    }
    public function getFacultyRC() {
        return $this->getRecordCount();
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