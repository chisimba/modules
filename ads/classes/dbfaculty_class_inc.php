<?php
class dbfaculty extends dbtable{
    var $tablename = "tbl_ads_faculty";
    
    public function init(){
        parent::init($this->tablename);
    }

    public function saveFaculty($faculty) {
        $data = array('name'=>$faculty);
        $this->insert($data);
    }

    public function saveModerator($faculty, $moderator) {
        $data = array('userid'=>$moderator);
        $this->update('name', $faculty, $data);
    }

    public function getAllFaculty() {
        return $this->getAll("order by name");
    }

    public function getFacultyRC() {
        return $this->getRecordCount();
    }
}
?>