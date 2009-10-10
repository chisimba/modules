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
    public function sendMail($to,$fromemail,$id){
        $linkUrl = $this->uri(array("action"=>'home'));
        $link=' '. str_replace("amp;", "", $linkUrl);

        $body= "Thank you for registering for $fromemail live. To access the session, please click here $link.";
        $body.= ' To successfully join the live session, please make sure you have latest java from http://java.com and flash player from http://www.adobe.com/go/EN_US-H-GET-FLASH.';
        $subject="Live webinar: $fromemail registration";

        $objMailer = $this->getObject('email', 'mail');
        $objMailer->setValue('to', array($to));
        $objMailer->setValue('from', $fromemail);
        $objMailer->setValue('fromName', $fromemail);
        $objMailer->setValue('subject', $subject);
        $objMailer->setValue('body', $body);

        $objMailer->send();
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
            $sc=$this->getObject('dbschedules');
            $sessiondata=$sc->getSchedule($sessionid);
            $scheduleId = $this->insert($data);
            $this->sendMail($this->objUser->email($userid), $sessiondata[0]['title'],$scheduleId);
            return $scheduleId;
        }
    }

 
    public function sessionExists($userid,$sessionid)
    {
      //  $sql="select * from " .$this->table." where userid = '".$userid."' and sessionid = '".$sessionid."'";
      //  $rows=$this->getArray($sql);
       return $this->valueExists('userid', $userid, $this->table) && $this->valueExists('sessionid', $sessionid, $this->table);
       // return count($rows) > 0 ? TRUE:FALSE;
    }

    public function getSessionsThatAmAMember(){
        $sql="select * from tbl_realtime_schedule_members ms, tbl_realtime_schedules sc
         where ms.userid ='".$this->objUser->userId()."' and ms.sessionid=sc.id ";
        $rows=$this->getArray($sql);
        return $rows+$this->getPublicSessionsNotOwnerByMe();
    }

    public function getPublicSessionsNotOwnerByMe(){
        $sql="select title from tbl_realtime_schedules where session_type='public' and owner <> '".$this->objUser->userid()."'";
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
