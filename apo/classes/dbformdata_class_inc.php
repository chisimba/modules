<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class dbformdata extends dbtable {

    var $tablename = "tbl_apo_formdata";
    var $userid;

    public function init() {
        parent::init($this->tablename);
    }

    public function saveData($formname, $formdata, $docid) {
        $tablename = "tbl_apo_" . $formname;
        $this->objUser = $this->getObject('user', 'security');
        $this->objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
        $this->objUploadTable = $this->getObject('dbfileuploads');
        $this->userutils = $this->getObject('userutils');
        $data["id"] = $docid;
        $datalength = count($data);
        for ($i = 0; $i < $datalength; $i++) {
            $data[$formdata[$i][$i]] = $formdata[$i][$i + 1];
        }

        /*    'a1' => $formdata["a1"],
          'a2' => $formdata["a2"],
          'a3' => $formdata["a3"],
          'a4' => $formdata["a4"],
          'a5' => $formdata["a5"] */
//'userid'=>$this->userutils->getUserId()
        //);

        if ($this->exists($docid)) {
            print_r("exists");
            $existingdata = $this->getAll("where id='$docid'");

            if (count($existingdata) > 0) {
                print_r("count > 0");
                $updatedata = array();
                for ($i = 0; $i < $datalength; $i++) {
                    $updatedata[$formdata[$i][$i]] = $formdata[$i][$i + 1];
                }
               /* 'a1' => $formdata["a1"],
                'a2' => $formdata["a2"],
                'a3' => $formdata["a3"],
                'a4' => $formdata["a4"],
                'a5' => $formdata["a5"]
//'userid' => $this->userutils->getUserId()
                );*/
                $this->update('id', $existingdata[0]['id'], $updatedata);
                print_r("updated");
            }
        }
        else
            $this->insert($data); //$formname, $formdata, $docid

            echo 'success';
    }

    function exists($docid, $formname) {
        $sql = "select * from tbl_apo_'.$formname.' where docid='$docid'";
        $xmStr = "";
        $rows = $this->getArray($sql);
        if (count($rows) > 0) {
            return TRUE;
        }
        else
            return FALSE;
    }

    public function getFormData($formname, $docid) {

        $sql = "select * from tbl_apo_'.$formname.' where docid='$docid'";
        $xmStr = "";
        $rows = $this->getArray($sql);

        foreach ($rows as $row) {
            print_r($row);
            $xmlStr = $row['formdata'];
        }

        return $xmlStr;
    }

}

?>
