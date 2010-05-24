<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
*/
class dbformdata extends dbtable {
    var $tablename = "tbl_wicid_formdata";
    var $userid;

    public function init() {
        parent::init($this->tablename);

    }

    public function saveData( $formname, $formdata, $docid) {
        $this->objUser=$this->getObject('user','security');
        $this->objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
        $this->objUploadTable = $this->getObject('dbfileuploads');
        $this->userutils=$this->getObject('userutils');
        $data=array(

                'formname'=>$formname,
                'formdata'=>$formdata,
                'docid'=>$docid,
                'userid'=>$this->userutils->getUserId()
        );

        $this->insert($data);//$formname, $formdata, $docid

        echo 'success';
        //return $id;

    }


    public function  getFormData($formname, $docid){

        $sql="select * from tbl_wicid_formdata where formname='$formname' and docid='$docid'";
        $xmStr="";
        $rows=$this->getArray($sql);
        print_r($rows);
        foreach($rows as $row){
            $xmlStr=$row['formname'];
        }

        return $xmlStr;
    }
}
?>
