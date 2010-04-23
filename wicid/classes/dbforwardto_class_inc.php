<?php
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
class dbforwardto extends dbtable {
    var $tablename = "tbl_wicid_forward";
    var $userid;

    public function init() {
        parent::init($this->tablename);
  
    }

    public function forwardTo($link, $email,$docid){
        $this->objUser=$this->getObject('user','security');
        $this->objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
        $this->objUploadTable = $this->getObject('dbfileuploads');
        $this->userutils=$this->getObject('userutils');
        $data=array(

                'link'=>$link,
                'email'=>$email,
                'docid'=>$docid
        );

        $id=$this->insert($data);

        echo 'success';
        return $id;
    }
}

?>
