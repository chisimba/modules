<?php
class dbproposalmembers extends dbtable{
    var $tablename = "tbl_ads_proposalmembers";
    
    public function init(){
        parent::init($this->tablename);
       $this->objUser = $this->getObject ( 'user', 'security' );
    }

    public function saveMember($userid,$courseid,$unit,$unittype,$phase) {
        if(!$this->isMember($courseid, $userid)){
        $data = array('userid'=>$userid,"courseid"=>$courseid,"unit"=>$unit,"unit_type"=>$unittype,"phase"=>$phase);
        $this->insert($data);
        }
    }

    public function deleteMember($id,$courseid) {
        if(!$this->invalidDelete($courseid, $id)){
       $sql = "delete from ".$this->tablename." where courseid= '".$courseid."' and id='".$id."'";
        $data = $this->getArray($sql);
        }
    }

    public function deleteMembers($courseid) {
       $sql = "delete from ".$this->tablename." where courseid= '".$courseid."'";
        $data = $this->getArray($sql);
    }
     public function getMembers($courseid,$phase) {
        $sql = "select * from ".$this->tablename." where courseid= '".$courseid."' and  unit ='n' and phase ='$phase'";
        $data = $this->getArray($sql);
        return $data;
    }
     public function isMember($courseid,$userid) {
        $sql = "select * from ".$this->tablename." where courseid= '".$courseid."' and userid='".$userid."'";
        $data = $this->getArray($sql);
        return count($data) > 0 ? TRUE:FALSE;
    }

    function invalidDelete($courseid,$id){
     $objProposals=$this->getObject('dbcourseproposals');
     $objDocuments=$this->getObject('dbdocument');
     $ownerEmail=$objProposals->getOwnerEmail($courseid);//email too
     $currentEditor=$objDocuments->getCurrentEditor($courseid);//its an email
     $memberemail=$this->getMemberEmail($id);
     return ($memberemail == $ownerEmail || $memberemail == $currentEditor);
    }

    function getMemberEmail($id){
       $data = $this->getRow('id', $id, $this->table);
       return $this->objUser->email($data['userid']);
    }
    function getUnitMember($userid){
       $data = $this->getRow('userid', $userid, $this->table);
       return $data;
    }
    function getUnitCommentors($courseid,$phase){
       $sql = "select * from ".$this->tablename." where courseid= '".$courseid."' and unit='y' and phase = '$phase'";
       $data = $this->getArray($sql);
       return $data;
    }

}
?>