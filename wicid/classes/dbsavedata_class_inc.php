<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
*/
class dbsavedata extends dbtable {
    var $tablename = "tbl_wicid_savedata";
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
                'docid'=>$docid
        );

        $this->insert($data);//$formname, $formdata, $docid

        echo 'success';
        //return $id;

    }
}
?>
