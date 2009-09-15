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

    public function getFacultyData() {
        $objDocumentStore = $this->getObject('dbdocument');
        $objUser = $this->getObject('user', 'security');
        $data = $this->getAllFaculty();
        $rc = $this->getFacultyRC();
        $count = 1;

        $dataStore = "[";
        foreach($data as $data) {
            if($count != $rc) {
                $dataStore .= "['".$data['name']."','";
                if(strlen(trim($data['userid'])) == 0) {
                    $dataStore .= "Not Available']".",";
                }
                else {
                    $dataStore .= $objUser->fullname($objDocumentStore->getUserId(trim($data['userid'])))."']".",";
                }
            }
            else {
                $dataStore .= "['".$data['name']."','";
                if(strlen(trim($data['userid'])) == 0) {
                    $dataStore .= "Not Available']";
                }
                else {
                    $dataStore .= $objUser->fullname($objDocumentStore->getUserId(trim($data['userid'])))."']".",";
                }
            }
            $count++;
        }
        $dataStore .= "]";

        return $dataStore;
    }

    public function getModeratorData() {
        $objDocumentStore = $this->getObject('dbdocument');
        $objUser = $this->getObject('user', 'security');
        $data = $this->getAllFaculty();
        $rc = $this->getFacultyRC();
        $count = 1;

        $dataStore = "[";
        foreach($data as $data) {
            $dataStore .= "['".$data['name']."','";
            if($count != $rc) {
                if(strlen(trim($data['userid'])) == 0) {
                    $dataStore .= "Not Available']".",";
                }
                else {
                    $dataStore .= $objUser->fullname($objDocumentStore->getUserId(trim($data['userid'])))."']".",";
                }
            }
            else {
                if(strlen(trim($data['userid'])) == 0) {
                    $dataStore .= "Not Available']";
                }
                else {
                    $dataStore .= $objUser->fullname($objDocumentStore->getUserId(trim($data['userid'])))."']".",";
                }
            }
            $count++;
        }
        $dataStore .= "]";

        return $dataStore;
    }
}
?>