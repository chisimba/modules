<?php

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']){
    die('You cannot view this page directly');
}
class dbquestioncomments extends dbTable{

    public function init()
    {
        parent::init('tbl_ads_questioncomments');  //super
        $this->table = 'tbl_ads_questioncomments';
        $this->objUser = $this->getObject( 'user', 'security' );
        $this->objDocumentStore = $this->getObject('dbdocument');
    }

    public function addComment($courseid, $formnumber,$question,$comment) {
        $data = array('coursecode'=>$courseid, 'formnumber'=>$formnumber, 'question'=>$question,'comment'=>$comment, 'userid'=>$this->objUser->userId(), 'commentdate'=>strftime('%Y-%m-%d %H:%M:%S', mktime()));
        $this->insert($data);
    }
    public function getCommentsCount($courseid, $formnumber,$question) {
        $sql = "select * from ".$this->table." where coursecode= '".$courseid."' and formnumber = '".$formnumber."' and question='".$question."'";
        $data = $this->getArray($sql);
        $total=count($data);
        return $total;
    }
    public function getComments($courseid, $formnumber,$question) {
        $sql = "select * from ".$this->table." where coursecode= '".$courseid."' and formnumber = '".$formnumber."' and question='".$question."'";
        $data = $this->getArray($sql);
         $total=count($data);
       $buff=
        '{"totalCount":'.$total.',"rows":[';
         $c=0;
       
        foreach($data as $row){
         $buff.='{"qid":"'.$row['id'].'","names":"'.$this->objUser->fullname($row['userid']).'","comment":"'.$row['comment'].'","commentdate":"'.$row['commentdate'].'"}';
         $c++;
         if($c < $total){
         $buff.=",";
         }
        }
        $buff.=']}';
        $contentType = "application/json; charset=utf-8";
        header("Content-Type: {$contentType}");
        header("Content-Size: " . strlen($buff));
        return $buff;
    }

}
?>
