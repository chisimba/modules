<?php
class dbfileuploads extends dbtable {
    var $tablename = "tbl_dms_fileuploads";

    public function init(){
        parent::init($this->tablename);
    }

    public function saveFileInfo($data) {
        $result = $this->insert($data);
        return $result;
    }

    public function textDocs() {
        $sql = "select * from ".$this->tablename." where filetype = 'txt'";
        $res = $this->getArray($sql);
        return $res;
    }
}
?>