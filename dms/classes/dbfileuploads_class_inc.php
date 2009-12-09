<?php
class dbfileuploads extends dbtable {
    var $tablename = "tbl_dms_fileuploads";
    var $userid;

    public function init(){
        parent::init($this->tablename);
    }

    public function setUserId($userid) {
        $this->userid = $userid;
    }
    public function saveFileInfo($data) {
        $result = $this->insert($data);
        return $result;
    }

    public function getFileTypes() {
        $sql = "select distinct filetype from ".$this->tablename. " where userid = '".$this->userid."'";
        $res = $this->getArray($sql);

        return $res;
    }

    public function getDocs($filetype) {
        $sql = "select * from ".$this->tablename." where filetype = '".$filetype."'". " and userid = '".$this->userid."'";
        $res = $this->getArray($sql);

        return $res;
    }

    public function getAllFiles() {
        $sql = "select * from ".$this->tablename." where userid = '".$this->userid."' order by date_uploaded desc, filename limit 10";
        $res = $this->getArray($sql);

        return $res;
    }

    public function deleteFileRecord($id) {
        $this->delete('id', $id);
    }

    public function getFileName($id) {
        $data = $this->getRow('id', $id);
        return $data;
    }
}
?>