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

    public function getcoursehistory($courseid, $formnumber, $info = null, $version = 1) {
        $this->courseid = $courseid;
        $this->formnumber = $formnumber;
        if(strlen($version) == 0) {
            $this->version = $this->objDocumentStore->getMaxVersion($this->courseid);
        }
        else {
            $this->version = $version;
        }
        $coursename = $this->getCourseName();

        if($info) {
            // we are getting all the data for this course
            $data = $this->getAllCourseData();
            $str = $data;
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

    public function getAllCourseData($datemodified = null) {
        $info = "";
        $allData = $this->objDocumentStore->getHistory($this->courseid);
        $cVersion=0;
        $currentUser = $this->objDocumentStore->getCurrentEditor($this->courseid);
        $currentUser = $this->getEditor($currentUser);
        foreach($allData as $allData) {
            if($allData['version'] > $cVersion) {
                $cVersion=$allData['version'];
            }
            if($allData['version'] == $this->version) {
                $comment = $this->getComment($allData['version']);
                $editor = $this->getEditor($allData['currentuser']);
                $dateUpdated = $this->objDocumentStore->getLastModified($this->courseid, $allData[0]['version'], $allData[0]['currentuser']);

                $date = date_create($dateUpdated);

                $info = array('datemodified'=>date_format($date, 'm/d/Y'), 'editor'=>$editor, 'comment'=>$comment,'version'=>$this->objDocumentStore->getMaxVersion($this->courseid), 'currentuser'=>$currentUser);
            }
            else {
                // version data not found.
            }
        }

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
            $dateUpdated = $this->objDocumentStore->getLastModified($this->courseid, $value['version'], $value['currentuser']);
            $date = date_create($dateUpdated);

            $data .= "{\n";
            $data .= "\t\ttext: '".date_format($date, 'm/d/Y')."_".$value['version']."',\n";
            $data .= "\t\tid: 'ver".$curRow."',\n";
            $data .= "\t\tleaf: true\n";
            $data .= "\t}";

            if($curRow != $numRows) {
                $data .= ",";
            }

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
            /*$comment = new link();
            $comment->link($this->uri(array('action'=>'viewComments','courseid'=>$this->courseid, 'version'=>$version)));
            $comment->link = 'Click to View Comments';

            return $comment->show();
             **/
        return "<p>These are the comments <b> hohoh </b></p>";
    }

    

    public function getEditor($currentuser) {
        $editor = $this->objUser->fullname(trim($this->objDocumentStore->getUserId($currentuser)));
        if($editor == null) {
            $editor = $this->objDocumentStore->getFullName($currentuser, $this->courseid);
        }

        return $editor;
    }


}

?>
