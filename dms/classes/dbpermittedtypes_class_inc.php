<?php
class dbpermittedtypes extends dbtable {
    var $tablename = "tbl_dms_permittedtypes";
    
    public function init(){
        parent::init($this->tablename);
    }

    public function saveFileTypes($filetypedesc,$filetypeext) {
        // check if the file type exists
        if(count($this->getRow('ext', $filetypeext)) <= 0) {
            $data = array("name"=>$filetypedesc, "ext"=>$filetypeext);
            $this->insert($data);
        }
    }

    public function getFileTypeData(){
        return $this->getAll();
    }

    public function deleteFileType($id) {
        $this->delete('id', $id);
    }

    public function getFileExtensions() {
        $sql = "select ext from $this->tablename";
        return $this->getArray($sql);
    }

    public function getFileDesc($filetype) {
        $data = $this->getRow('ext', $filetype);
        return $data['name'];
    }

    public function saveNewExt($data) {
        $this->insert($data);
    }
}
?>
