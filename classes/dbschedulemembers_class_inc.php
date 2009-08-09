<?php
/* 
 * Responsibl for insterting, updating and deleting schedules table
 */
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']){
    die('You cannot view this page directly');
}
class dbschedulemembers extends dbTable{

    public function init()
    {
        parent::init('tbl_realtime_schedule_members');  //super
        $this->table = 'tbl_realtime_schedule_members';
        $this->objUser = $this->getObject ( 'user', 'security' );
    }

    public function addRoomMember(
        $userid,
        $sessionid
    ){
        $data = array(
            'sessionid' => $sessionid,
            'userid' => $userid
        );

        if($this->sessionExists($userid,$sessionid)){

            return FALSE;
        }else{

            $scheduleId = $this->insert($data);
            return $scheduleId;
        }
    }
    public function sessionExists($userid,$sessionid)
    {
        $sql="select * from " .$this->table." where userid = '".$userid."' and sessionid = '".$sessionid."'";
        $rows=$this->getArray($sql);

        return count($rows) > 0 ? TRUE:FALSE;
    }

    public function getSessionsThatAmAMember(){
        $sql="select * from " .$this->table." where userid ='".$this->objUser->userId()."'";
        $rows=$this->getArray($sql);
        return $rows;
    }
    public function getUsers(){
        $sql="select * from tbl_users";
        $rows=$this->getArray($sql);
        return $rows;
    }
    public function getScheduleMembers($sessionid)
    {
        $sql="select * from " .$this->table." where sessionid ='".$sessionid."'";
        $rows=$this->getArray($sql);
        return $rows;
    }

    public function deleteRoomMember($userid)
    {
        $sql="delete from " .$this->table." where userid = '".$userid."'";
        $rows=$this->getArray($sql);
        return $rows;
    }
    public function deleteSession($id)
    {
        $sql="delete from " .$this->table." where sessionid = '".$id."'";
        $rows=$this->getArray($sql);
        return $rows;
    }
}
?>
