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

        if ($this->exists($docid,$formname)) {
            print_r("exists");
                $existingdata=$this->getAll("where formname='$formname' and docid='$docid'");

            if(count($existingdata) > 0) {
                print_r("count > 0");
                $updatedata=array(

                        'formdata'=>$formdata,
                        'userid'=>$this->userutils->getUserId() 

                );
                $this->update('id',$existingdata[0]['id'], $updatedata);
                print_r("updated");
            }
        }
        else
            $this->insert($data);//$formname, $formdata, $docid

        echo 'success';
    }

    function  exists($docid, $formname) {
        $sql="select * from tbl_wicid_formdata where formname='$formname' and docid='$docid'";
        $xmStr="";
        $rows=$this->getArray($sql);
        if(count($rows) > 0) {
            return TRUE;
        }
        else 
            return FALSE;
        
    }

    public function  getFormData($formname, $docid) {

        $sql="select * from tbl_wicid_formdata where formname='$formname' and docid='$docid'";
        $xmStr="";
        $rows=$this->getArray($sql);

        foreach($rows as $row) {
            print_r($row);
            $xmlStr=$row['formdata'];
        }

        return $xmlStr;
    }

    public function getCommentData($docid, $formname){
        $sql="select commentdata from tbl_wicid_formdata where formname='$formname' and docid='$docid'";
        $commentdata="";
        $rows=$this->getArray($sql);

        foreach($rows as $row) {
            $commentdata=$row['commentdata'];
        }

        return $commentdata;
    }

    public function addCommentData($docid, $formname, $commentdata){
        $sql="select commentdata from tbl_wicid_formdata where formname='$formname' and docid='$docid'";
        $comments="";
        $rows=$this->getArray($sql);

        foreach($rows as $row) {
            $comments=$row['commentdata'];
        }

        $comments.="\n".$commentdata;

        $sql= "update tbl_wicid_documents set commentdata = '$comments' where id = '$docid' and formname = '$formname'";;
        echo $comments;
    }
}
?>
