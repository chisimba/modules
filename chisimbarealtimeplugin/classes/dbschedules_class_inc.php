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
        parent::init('tbl_virtualclassroom_schedules');  //super
        $this->table = 'tbl_virtualclassroom_schedules';
        $this->objUser = $this->getObject ( 'user', 'security' );

    }


    public function getChapters($contextcode){

        $sql="select ct.chaptertitle as chapter from tbl_contextcontent_chaptercontext cx,tbl_contextcontent_chaptercontent ct
 where ct.chapterid = cx.chapterid and
cx.contextcode='".$contextcode."'";
       
        $rows=$this->getArray($sql);
        return $rows;
    }
    public function addSchedule(
        $contextcode,
        $title,
        $category,
        $about,
        $startdate,
        $starttime,
        $enddate,
        $endtime){



        $data = array(
            'contextcode' => $contextcode,
            'title' => $title,
            'category'=>$category ==''?'Default':$category,
            'about'=>$about,
            'owner' => $this->objUser->userId(),
            'participants'=>'0',
            'creation_date' => strftime('%Y-%m-%d %H:%M:%S', mktime()),
            'start_date'=>$startdate,
            'start_time'=>$starttime,
            'end_date'=>$enddate,
            'end_time'=>$endtime,
        );

        $scheduleId = $this->insert($data);
        return $scheduleId;
    }

    public function getSchedules($contextcode)
    {
        $sql="select * from " .$this->table." where contextcode = '".$contextcode."'";
        $rows=$this->getArray($sql);
        return $rows;
    }

    public function getSchedule($id)
    {
        $sql="select * from " .$this->table." where id = '".$id."'";
        $rows=$this->getArray($sql);
        return $rows;
    }

    public function deleteSchedule($id)
    {
        $sql="delete from " .$this->table." where id = '".$id."'";
        $rows=$this->getArray($sql);
        return $rows;
    }

}
?>
