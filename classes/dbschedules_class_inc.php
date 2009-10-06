<?php
/* 
 * Responsibl for insterting, updating and deleting schedules table
 */
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']){
    die('You cannot view this page directly');
}
class dbschedules extends dbTable{

    public function init()
    {
        parent::init('tbl_realtime_schedules');  //super
        $this->table = 'tbl_realtime_schedules';
        $this->objUser = $this->getObject ( 'user', 'security' );
        $this->members=$this->getObject('dbschedulemembers');

    }

 public function updateSchedule(
        $title,
        $date,
        $starttime,
        $endtime,
        $id){
        
        $data = array(
            'title' => $title,
            'meeting_date'=>$date,
            'start_time'=>$starttime,
            'end_time'=>$endtime,
        );
        $scheduleId = $this->update('id',$id, $data);
        }

    public function addSchedule(

        $title,
        $date,
        $starttime,
        $endtime){
        $data = array(
            'title' => $title,
            'owner' => $this->objUser->userId(),
            'creation_date' => strftime('%Y-%m-%d %H:%M:%S', mktime()),
            'meeting_date'=>$date,
            'start_time'=>$starttime,
            'end_time'=>$endtime,
        );

        if($this->titleExists($title)){
            return FALSE;
        }else{
        $scheduleId = $this->insert($data);
        $this->members->addRoomMember($this->objUser->userId(),$scheduleId);
        return $scheduleId;
        }
    }
    public function getRegisteredUsers()
    {
        $sql="select * from tbl_users";
        $rows=$this->getArray($sql);
        return $rows;
    }

    public function getSchedules()
    {
        $sql="select * from tbl_realtime_schedules sc where sc.owner = '".$this->objUser->userId()."'";
              
        $rows=$this->getArray($sql);
        return $rows;
    }
  
  
    public function isScheduleOwner($id)
    {
        $sql="select owner from " .$this->table." where id = '".$id."'";
        $rows=$this->getArray($sql);
        

        foreach($rows as $row){
            if($row['owner'] == $this->objUser->userId()){
              return TRUE;
            }
        }
        return FALSE;
    }
    public function getSchedule($id)
    {
        $sql="select * from " .$this->table." where id = '".$id."'";
        $rows=$this->getArray($sql);
        return $rows;
    }
    
    public function titleExists($title)
    {
        $sql="select * from " .$this->table." where title = '".$title."' and owner = '".$this->objUser->userId()."'";
        $rows=$this->getArray($sql);
        return count($rows) > 0 ? TRUE:FALSE;
    }
    public function deleteSchedule($id)
    {
        $sql="delete from " .$this->table." where id = '".$id."'";
        $rows=$this->getArray($sql);
        return $rows;
    }

}
?>
