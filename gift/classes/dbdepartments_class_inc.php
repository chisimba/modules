<?php

class dbdepartments extends dbtable {

    function init() {
        parent::init("tbl_gift_departments");
    }

    function addDepartment($name, $path) {
        
        $name = str_replace("'", "\'", $name);
        $data = array(
            "name" => $name,
            "deleted" => 'N',
            "path"=>$path,
            "level" => count(explode("/", $path))
        );

        $id = $this->insert($data);
        return $id;
    }

    function getDepartments() {
        $sql =
                "select * from tbl_gift_departments where (deleted='N' or deleted is null) order by level";
        return $this->getArray($sql);
    }

    function getParentPath($id) {
        return $this->getRow("id", $id);
    }

    function getDepartment($id) {
        return $this->getRow("id", $id);
    }

    function getDepartmentName($id) {
        $row = $this->getRow("id", $id);
        return $row['name'];
    }

    function getSubDepartmentsCount($id){
        $dept=$this->getDepartment($id);
        $path=$dept['path'];
        $sql=
        "select count(*) as totalsubs from tbl_gift_departments where path like '$path%' and  (deleted='N' or deleted is null)";

        $data=$this->getArray($sql);
        $total=0;
        foreach($data as $row){
            $total=$row['totalsubs'].",";
        }
        return $total;
    }
    function updateDepartment($id, $name) {
        $data = array(
            "name" => $name
        );
        return $this->update("id", $id, $data);
    }

     public function deleteDepartment($id) {
        $data = array("deleted" => "Y");
        $result = $this->update('id', $id, $data);
        return $result;
    }

}

?>
