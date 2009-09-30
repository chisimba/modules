<?php
    class dbfacultyschool extends dbtable {
        var  $tablename = "tbl_ads_school";

        public function init(){
            parent::init($this->tablename);
        }
        public function getSchoolData() {
            $filter = "where deletestatus = 0 order by faculty, schoolname";
            $data = $this->getAll($filter);

            return $data;
        }

        public function saveSchool($faculty, $school) {
            $data = array("faculty"=>$faculty, "schoolname"=>$school);
            $this->insert($data);
        }

        public function deleteSchool($faculty, $school) {
            $sql = "update $this->tablename set deletestatus = 1 where faculty = '$faculty' and schoolname = '$school'";
            $this->getArray($sql);
        }
    }
?>
