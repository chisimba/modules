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
        parent::init('tbl_ads_course_proposals');  //super
        $this->table = 'tbl_ads_course_proposals';
        $this->objUser = $this->getObject ( 'user', 'security' );

    }

    public function addCourseProposal($faculty, $title){

        $data = array(
            'faculty' => $faculty,
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
        if($this->objUser->isadmin()){
         $sql=   'select distinct cp.* from tbl_ads_course_proposals cp,tbl_ads_documentstore ds
where cp.deleteStatus <> 1 ';
        }else{
        $sql="select distinct cp.* from tbl_ads_course_proposals cp,tbl_ads_documentstore ds, tbl_ads_proposalmembers ms
where cp.deleteStatus <> 1 and (cp.userid = '".$userid."' or ds.currentuser='".$this->objUser->email()."' or ms.userid = '".$userid."')
     and cp.id=ds.coursecode and cp.id=ms.courseid and ds.coursecode=ms.courseid";
        }

        $rows=$this->getArray($sql);
        return $rows;
    }

    public function getCourseProposal($id)
    {
        $rows=$this->getRow('id', $id, $this->table);
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

    public function getNumberOfCourses() {

        return $this->getRecordCount();
    }

    public function getTitle($id) {
        $data = $this->getRow('id', $id, $this->table);
        return $data['title'];
    }
     public function getStatus($id) {
         $statuscodes=  array(
    "0"=> 'New',
    "1"=>'APO Comment',
    "2"=>'Library comment',
    "3"=>'Subsidy comment',
    "4"=>'Faculty subcommittee',
    "5"=>'Faculty',
    "6"=> 'APDC');
        $data = $this->getRow('id', $id, $this->table);
        return $statuscodes[$data['status']];
    }
    public function getOwnerEmail($id) {
        $data = $this->getRow('id', $id, $this->table);
        return $this->objUser->email($data['userid']);
    }
    public function getOwnerNames($id) {
        $data = $this->getRow('id', $id, $this->table);
        return $this->objUser->fullname($data['userid']);
    }
    public function editProposal($id,$faculty, $title) {
        $data = array('faculty'=>$faculty, 'title'=>$title);
        $courseProposalStatus = $this->update('id', $id, $data, $this->table);
    }

    public function deleteProposal($id) {
        $data = array('deleteStatus'=>'1');
        $courseProposalStatus = $this->update('id', $id, $data, $this->table);

        return $courseProposalStatus;
    }
}
?>
