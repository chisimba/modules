<?php

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']){
    die('You cannot view this page directly');
}
class dbcommentsadmin extends dbTable{

    public function init()
    {
        parent::init('tbl_ads_commentsadmin');  //super
        $this->table = 'tbl_ads_commentsadmin';
        $this->objUser = $this->getObject( 'user', 'security' );

    }

    public function addComment($comment,$userid) {
        $data = array('comment_desc'=>$comment, 'userid'=>$userid);
        $this->insert($data);
    }
     public function updateStatus($comment,$userid,$id) {
        $sql = "update ".$this->table." set comment_desc ='".$comment."',userid='".$userid."'
               where  id='".$id."'";
        $this->getArray($sql);
      
    }
    public function getComments() {
        $sql = "select * from ".$this->table;
        $data = $this->getArray($sql);
        return $data;
    }

    public function saveStatus($title, $moderator) {
        $data = array('comment_desc'=>$title, 'userid'=>$moderator);
        $this->insert($data);
    }

}
?>
