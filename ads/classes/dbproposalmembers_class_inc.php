<?php
class dbproposalmembers extends dbtable{
    var $tablename = "tbl_ads_proposalmembers";
    
    public function init(){
        parent::init($this->tablename);
    }

    public function saveMember($userid,$courseid) {
        $data = array('userid'=>$userid,"courseid"=>$courseid);
        $this->insert($data);
    }

    public function deleteMember($id,$courseid) {
       $sql = "delete from ".$this->tablename." where courseid= '".$courseid."' and id='".$id."'";
        $data = $this->getArray($sql);
    }
     public function getMembers($courseid) {
        $sql = "select * from ".$this->tablename." where courseid= '".$courseid."'";
        $data = $this->getArray($sql);
        return $data;
    }
}
?>