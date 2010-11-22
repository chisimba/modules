<?php

class dbdepartments extends dbtable {

    function init() {
        parent::init("tbl_gift_departments");
    }

    function addDepartment($name) {
        $name=str_replace("'", "\'", $name);
        $data = array(
            "name" => $name
        );
        $this->insert($data);
    }

    function getDepartments() {
        $sql =
                "select * from tbl_gift_departments";
        return $this->getArray($sql);
    }

    function getDepartment($id) {
        return $this->getRow("id",$id);
    }

    function getDepartmentName($id) {
       $row= $this->getRow("id",$id);
       return $row['name'];
    }
}

?>
