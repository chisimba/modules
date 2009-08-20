<?php
    /*
     * @author: Nguni
     * Date Created: 19/08/2009
     *
     */

    class getdata extends object {
        
        public function init() {
            $this->loadElements();
        }

        public function loadElements() {
            $this->objUser =  $this->getObject ('user', 'security');
            $this->objDocumentStore = $this->getObject('dbdocument');
            $this->objCourseProposals = $this->getObject('dbcourseproposals');
            $this->loadclass('link','htmlelements');
        }
        
        public function getcoursehistory($courseid, $formnumber, $info = null) {
            $this->courseid = $courseid;
            $this->formnumber = $formnumber;
            $coursename = $this->getCourseName();
            
            if($info) {
                // we are getting all the data for this course
                $data = $this->getAllCourseData();
                $str = "$data";
            }
            else {
                $data = $this->getHistoryData();
                $str = "[{\n\ttext:'".$coursename."',\n\texpanded: true,\n\tchildren: ".$data."\n}]";
            }
            
            return $str;
        }

        public function getCourseName() {
            $courseName = $this->objCourseProposals->getTitle($this->courseid);
            
            return $courseName;
        }
        
        public function getAllCourseData($version = null, $datemodified = null) {
            $allData = $this->objDocumentStore->getHistory($this->courseid);

            /*foreach($allData  as $value) {
                $numRows += 1;
            }

            $curRow = 1;
            $data = "";
            foreach($allData  as $value) {
                
                
                $dateUpdated = $this->objDocumentStore->getLastModified($value['version'], $value['currentuser']);

                if($curRow != $numRows) {
                    $data .= ",";
                }
                
                $data .= "{\n";
                $data .= "\t\"comment\": '".$comment."',\n";
                $data .= "\t\"editor\": '".$editor."',\n";
                $data .= "\t\"id\": 'ver".$curRow."',\n";
                $data .= "}";
    
                $curRow += 1;
            }*/
            $comment = $this->getComment($allData['version']);
            $editor = $this->getEditor($allData['currentuser']);
            $dateUpdated = $this->objDocumentStore->getLastModified($allData[0]['version'], $allData[0]['currentuser']);

            $date = date_create($dateUpdated);
            
            $info = array('datemodified'=>date_format($date, 'm/d/Y'), 'editor'=>$editor, 'comment'=>$comment);
            return json_encode($info);
        }

        public function getHistoryData() {
            $historyData = $this->objDocumentStore->getHistory($this->courseid);

            foreach($historyData  as $value) {
                $numRows += 1;
            }

            $curRow = 1;
            $data = "[";
            foreach($historyData  as $value) {
                $comment = $this->getComment($value['version']);
                $editor = $this->getEditor($value['currentuser']);
                $dateUpdated = $this->objDocumentStore->getLastModified($value['version'], $value['currentuser']);

                if($curRow != $numRows) {
                    $data .= ",";
                }
                
                $data .= "{\n";
                $data .= "\t\ttext: '".substr($dateUpdated, 0, 10)."_".$value['version']."',\n";
                $data .= "\t\tid: 'ver".$curRow."',\n";
                $data .= "\t\tleaf: true\n";
                $data .= "\t}";
    
                $curRow += 1;
            }
            $data .= "]";

            return $data;
        }
        
        public function getVersion($value, $edit) {
            $version = new link();
            
            if($edit) {
                $version->link($this->uri(array('action'=>'viewform','courseid'=>$this->id, 'formnumber'=>$this->formnumber)));
                $version->link = $value.".00";
            }
            else {
                $version->link($this->uri(array('action'=>'viewform','courseid'=>$this->id, 'formnumber'=>$this->allForms[0], 'edit'=>'NO')));
                $version->link = $value.".00";
            }

            return $version->show();
        }

        public function getComment($version) {
            $comment = new link();
            $comment->link($this->uri(array('action'=>'viewComments','courseid'=>$this->id, 'version'=>$version)));
            $comment->link = 'Click to View Comments';

            return $comment->show();
        }

        public function getEditor($currentuser) {
            $editor = $this->objUser->fullname(trim($this->objDocumentStore->getUserId($currentuser)));
            if($editor == null) {
                $editor = $this->objDocumentStore->getFullName($currentuser, $this->id);
            }

            return $editor;
        }
    }

?>
