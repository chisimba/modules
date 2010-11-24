<?php

class dbdepartments extends dbtable {

    function init() {
        parent::init("tbl_gift_departments");
    }

    function addDepartment($name) {
        $name = str_replace("'", "\'", $name);
        $data = array(
            "name" => $name,
            "deleted" => 'N'
        );
        $this->insert($data);
    }

    function getDepartments() {
        $sql =
                "select * from tbl_gift_departments where (deleted='N' or deleted is null)";
        return $this->getArray($sql);
    }

    function getDepartment($id) {
        return $this->getRow("id", $id);
    }

    function getDepartmentName($id) {
        $row = $this->getRow("id", $id);
        return $row['name'];
    }

    function updateDepartment($id, $name) {
        $data = array(
            "name"=> $name
        );
        return $this->update("id", $id, $data);
    }

}

?>
