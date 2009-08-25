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
        }

        public function addComment($courseid, $comment) {
            $sql = "select max(version) version from tbl_documentstore where coursecode = '".$courseid."'";
            $data = $this->getArray($sql);
            $version = $data[0]['version'];
            
            $data = array('courseid'=>$courseid, 'comment'=>$comment, 'version'=>$version);
            $this->insert($data);
        }

        public function getComments($courseid, $version) {
            $sql = "select comment from ".$this->table." where courseid = '".$courseid."' and version = ".$version;
            $data = $this->getArray($sql);

            return $data;
        }

        public function getNumComments($courseid, $version) {
            $sql = "select count(comment) counter from ".$this->table." where courseid = '".$courseid."' and version = ".$version;
            $data = $this->getArray($sql);

            return $data[0]['counter'];
        }
    }
?>
