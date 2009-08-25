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
        parent::init('tbl_ads_course_reviews');  //super
        $this->table = 'tbl_ads_course_reviews';
        $this->objUser = $this->getObject ( 'user', 'security' );

    }

    public function addCourseReview($title,$course){

        $data = array(
            'course'=>$course,
            'userid' => $this->objUser->userId(),
            'review_date' => strftime('%Y-%m-%d %H:%M:%S', mktime()),
            'review' => $title,
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
