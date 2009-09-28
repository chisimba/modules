<?php

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']){
    die('You cannot view this page directly');
}
class dbcoursecomments extends dbTable{

    public function init()
    {
        parent::init('tbl_ads_course_comments');  //super
        $this->table = 'tbl_ads_course_comments';
        $this->objUser = $this->getObject( 'user', 'security' );
        $this->objDocumentStore = $this->getObject('dbdocument');
        $this->objCommentAdmin=$this->getObject('dbcommentsadmin');
    }

    public function addComment($courseid, $comment,$status) {
        $version = $this->objDocumentStore->getMaxVersion($courseid);
        $userid=$this->objUser->userId();
        $objMembers = $this->getObject('dbproposalmembers');
        $data=$objMembers->getUnitMember($userid);
        $data = array('unit_type'=>$data['unit_type'], 'courseid'=>$courseid, 'comment'=>$comment, 'version'=>$version,'status'=>$status, 'username'=>$userid, 'datemodified'=>strftime('%Y-%m-%d %H:%M:%S', mktime()));
       
       // $data = array('courseid'=>$courseid, 'comment'=>$comment, 'version'=>$version,'status'=>$status, 'username'=>$userid, 'datemodified'=>strftime('%Y-%m-%d %H:%M:%S', mktime()));
        $this->insert($data);
    }

    public function getComments($courseid, $version) {
        $sql = "select comment from ".$this->table." where courseid = '".$courseid."' and version = ".$version;
        $data = $this->getArray($sql);

        return $data;
    }
    public function getAllComments($courseid) {
        $statuscodes=  array(
              "0"=> 'Proposal Phase',
              "1"=>'APO Comment',
              "2"=>'Faculty Approval');

        $sql = "select * from ".$this->table." where courseid = '".$courseid."' order by status, unit_type desc";
        $data = $this->getArray($sql);
        $comments="";
        $unformatedComments="";
        $status=-1;
        foreach($data as $xc){

            if($status != $xc['status']){
                $status= $xc['status'];
                $unformatedComments.='<font color="red"><h2>'.$statuscodes[$status].' comments:</h2></font>';
            }
          
            $unitType=$this->objCommentAdmin->getCommentType($xc['unit_type']);
           $unitType='<font color="green"><h4>'.$unitType.'</h4></font>';
            $unformatedComments.='<h4>'.$this->objUser->fullname($xc['username']).'</h4><h4>'.$xc['datemodified'].'</h4>'.$unitType.''.$xc['comment'].'<br/>---------------------<br/>';
        }

        $order   = array("\r\n", "\n", "\r");
        $replace = '<br />';
        // Processes \r\n's first so they aren't converted twice.
        $comments = str_replace($order, $replace, $unformatedComments);
        return $comments;
    }

    public function getNumComments($courseid, $version) {
        $sql = "select count(comment) counter from ".$this->table." where courseid = '".$courseid."' and version = ".$version;
        $data = $this->getArray($sql);

        return $data[0]['counter'];
    }
}
?>
