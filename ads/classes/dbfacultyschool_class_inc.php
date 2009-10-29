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

        public function getSchoolName($id) {
            $data = $this->getRow('id', $id);
            return $data['schoolname'];
        }

        public function getSchools($faculty){
            $objFaculty = $this->getObject('dbfaculty');
            $myFaculty = $objFaculty->getFacultyName($faculty);
            $sql="select id, schoolname from $this->tablename where faculty like '".$myFaculty."%'";
            $xrows=$this->getArray($sql);
            
            $xtotal = count($xrows);
            $buff='{"totalCount":'.$xtotal.',"rows":[';
            $c=0;
            $total=count($xrows);
            foreach($xrows as $row){
                // $forwardUrl =new $this->uri(array('action'=>'forward',"email"=>$row['emailaddress']));
                $buff.='{"schoolid":"'.$row['id'].'","schoolname":"'.$row['schoolname'].'"}';
                $c++;
                if($c < $total){
                    $buff.=",";
                }
            }
            $buff.=']}';
            $contentType = "application/json; charset=utf-8";
            header("Content-Type: {$contentType}");
            header("Content-Size: " . strlen($buff));
            echo $buff;
        }
    }
?>
