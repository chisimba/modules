<?php
/* 
 * Responsibl for insterting, updating and deleting course propaosals table
 */
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']){
    die('You cannot view this page directly');
}
class dbcoursereviews extends dbTable{

    public function init()
    {
        parent::init('tbl_course_reviews');  //super
        $this->table = 'tbl_course_reviews';
        $this->objUser = $this->getObject ( 'user', 'security' );

    }

    public function addCourseReview($title){

        $data = array(
            'review' => $title,
            'userid' => $this->objUser->userId(),
            'creation_date' => strftime('%Y-%m-%d %H:%M:%S', mktime()),
            'status' =>'0',
            'course'=>'',
        );

        $courseProposalId = $this->insert($data);
        return $courseProposalId;
    }
    
    public function getCourseReview($id)
    {
        $sql="select * from " .$this->table." where id = '".$id."'";
        $rows=$this->getArray($sql);
        return $rows;
    }
    
}
?>
