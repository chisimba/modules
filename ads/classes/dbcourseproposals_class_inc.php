<?php
/* 
 * Responsibl for insterting, updating and deleting course propaosals table
 */
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']){
    die('You cannot view this page directly');
}
class dbcourseproposals extends dbTable{

    public function init()
    {
        parent::init('tbl_course_proposals');  //super
        $this->table = 'tbl_course_proposals';
        $this->objUser = $this->getObject ( 'user', 'security' );

    }

    public function addCourseProposal($title){

        $data = array(
            'title' => $title,
            'userid' => $this->objUser->userId(),
            'creation_date' => strftime('%Y-%m-%d %H:%M:%S', mktime()),
            'status' =>'0',
        );

        $courseProposalId = $this->insert($data);
        return $courseProposalId;
    }

    public function getCourseProposals($userid)
    {
        $sql="select * from " .$this->table;//." where userid = '".$userid."'";
        $rows=$this->getArray($sql);
        return $rows;
    }

    public function getCourseProposal($id)
    {
        $sql="select * from " .$this->table." where id = '".$id."'";
        $rows=$this->getArray($sql);
        return $rows;
    }


    public function changeTitle($id,$newtitle)
    {
	$newtitle = addslashes($newtitle);
	$sql="update ".$this->table." set title='$newtitle' where id = '$id';";
	$this->_execute($sql);
    }
    
    public function courseExists($id) {
      $course = $this->getCourseProposal($id);
      if (count($course) == 0) {
        return false;
      }
      else {
        return true;
      }
    }

    public function updateProposalStatus($id, $status) {
        $data = array('status'=>$status);
        $courseProposalStatus = $this->update('id', $id, $data, $this->table);

        return $courseProposalStatus;
    }
}
?>
