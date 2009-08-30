<?php

class dbdocument extends dbtable{
    var $tablename = "tbl_ads_documentstore";
    public function init(){
        parent::init($this->tablename);
    }

    public function addRecord($courseid, $form, $question, $value, $status, $version, $currentuser) {
        $data = array('coursecode'=>$courseid,'question'=>$question,'value'=>$value, 'status'=>$status, 'version'=>$version, 'currentuser'=>$currentuser, 'formnumber'=>$form, 'datemodified'=>strftime('%Y-%m-%d %H:%M:%S', mktime()));
        $this->insert($data);
    }

    public function getRecord(){
        $data = $this->getAll();
        return $data;
    }

    public function getValues($courseid, $form){
        $sql = "select * from $this->tablename where coursecode = '$courseid' and formnumber='$form' and version = (
        select max(version) from $this->tablename where coursecode='$courseid');";
        $rows = $this->getArray($sql);
        //remove slashes
        return $this->removeSlashes($rows);
    }

    public function removeSlashes($twoDarray) {
        foreach ($twoDarray as $two=>$array) {
            foreach ($array as $key=>$value) {
                $twoDarray[$two][$key] = stripslashes($value);
            }
        }
        return $twoDarray;
    }

    public function updateRecord($courseid, $form , $question, $field, $currentuser){
        $sql = "update $this->tablename set value = '$field', datemodified = '".strftime('%Y-%m-%d %H:%M:%S', mktime())."' where coursecode = '$courseid' and question = '$question' and formnumber='$form' and currentuser='$currentuser';";
        $count = $this->_execute($sql)->fetchRow();
        return true;
    }

    public function getVersion($courseid, $userid) {
        $courseid = addslashes($courseid);
        $userid = addslashes($userid);
        $sql = "select version, status, currentuser from $this->tablename where coursecode = '$courseid' order by version desc;";

        $rows = $this->getArray($sql);
        if (count($rows) == 0) {
            return array('version'=>'0', 'status'=>'Editable', 'currentuser'=>$userid);
        }
        else {
            return array('version'=>$rows[0]['version'], 'status'=>$rows[0]['status'], 'currentuser'=>$rows[0]['currentuser']);
        }
    }

    public function increaseVersion($courseid, $currentuser, $newversion) {
        $courseid = addslashes($courseid);
        $currentuser = addslashes($currentuser);
        $oldversion = $newversion - 1;
        $sql = "Select coursecode, question, value, 'unsubmitted' as status, '$newversion' as version, '$currentuser' as currentuser, formnumber from $this->tablename where coursecode = '$courseid' and version = '$oldversion';";
        $rows = $this->getArray($sql);
        foreach ($rows as $row) {
            $this->insert($row);
        }
    }

    public function submitProposal($courseid, $version) {
        $sql = "update $this->tablename set status = 'submitted', datemodified = '".strftime('%Y-%m-%d %H:%M:%S', mktime())."' where coursecode = '$courseid' and version = '$version';";
        $count = $this->_execute($sql);
    }

    public function getProposal($courseid, $version) {
        $sql = "select * from $this->tablename where coursecode = '$courseid' and version='$version';";
        return $this->removeSlashes($this->getArray($sql));
    }

    public function updateComment($courseid, $comment){
        $sql = "update $this->tablename set value='$comment', datemodified = '".strftime('%Y-%m-%d %H:%M:%S', mktime())."' where formnumber= 'Comment' and coursecode='$courseid';";
        $this->_execute($sql);
    }

    public function getHistory($courseid) {
        $courseid = addslashes($courseid);
        $sql = "select distinct A.version, B.currentuser, C.deleteStatus
                from $this->tablename as A
                        join (select currentuser, version from $this->tablename) as B on A.version = B.version
                        join tbl_ads_course_proposals as C on A.coursecode = C.id where coursecode = '$courseid'
                and A.version > 0
                and C.deleteStatus = 0
                order by A.version desc";
        $data = $this->getArray($sql);

        return $data;
    }

    public function getUserId($email) {
        // check users table first
        $sql = "select userid from tbl_users where emailAddress = '".trim($email)."'";
        $info = $this->getArray($sql);
        if($info[0]['userid'] == null) {
            // check document users table
            $sql = "select id from tbl_ads_documentusers where email = '".trim($email)."'";
            $info = $this->getArray($sql);
            return $info[0]['id'];
        }
        else {
            return $info[0]['userid'];
        }
    }

    public function getFullName($email, $courseid) {
        $sql = "select fName, lName from tbl_ads_documentusers where email = '$email' and courseid = '$courseid'";
        $data = $this->getArray($sql);
        $fullName = $data[0]['lName'].$data[0]['fName'];

        return $fullName;
    }

    public function sendProposal($lname, $fname, $email, $phone, $courseid) {
        $status = true;

        // user data for proposal
        $data = array("lname"=>$lname, "fname"=>$fname, "email"=>$email, "phone"=>$phone, "courseid"=>$courseid);
        $status = $this->insert($data, "tbl_ads_documentusers");

        // owner of document now changes
        $sql = "select max(version) maxversion from tbl_ads_documentstore where coursecode = '$courseid'";
        $data = $this->getArray($sql);
        $maxversion = $data[0]['maxversion'];
        $sql = "update $this->tablename set currentuser = '$email', set datemodified = '".strftime('%Y-%m-%d %H:%M:%S', mktime())."' where coursecode = '$courseid' and version = '$maxversion'";
        $status = $this->_execute($sql);

        return $status;
    }

    public function getLastModified($courseid, $version, $currentuser) {
        $sql = "select datemodified from $this->tablename where coursecode = '$courseid' and version = '$version' and currentuser = '$currentuser'";
        $data = $this->getArray($sql);
        $lastModified = $data[0]['datemodified'];

        return $lastModified;
    }

    public function getMaxVersion($thiscourseid) {
        $sql = "select max(version) version from $this->tablename where coursecode = '$thiscourseid'";
        $data = $this->getArray($sql);
        $maxVersion = $data[0]['version'];

        return $maxVersion;
    }

    public function isCurrentEditor($email){
        $objUser = $this->getObject ( 'user', 'security' );
        $myemail=$objUser->email();
        return $myemail == $email;
    }
    function getCurrentEditor($courseid){
        $sql="select distinct currentuser from $this->tablename where coursecode='$courseid' and version = (select max(version) from $this->tablename where coursecode='$courseid')";
        $data = $this->getArray($sql);

        return $data[0]['currentuser'];
    }
    function getLastEditDate($courseid){
        $sql="select distinct datemodified from $this->tablename where coursecode='$courseid' and version = (select max(version) from $this->tablename where coursecode='$courseid')";
        $data = $this->getArray($sql);
        return $data[0]['datemodified'];
    }
    function getStatus($courseid){
        $sql="select distinct status from $this->tablename where coursecode='$courseid' and version = (select max(version) from $this->tablename where coursecode='$courseid')";
        $data = $this->getArray($sql);

        return $data[0]['status'];
    }
    public function getUsers($value,$start,$limit){
        if($value == ''){
            $value='a';
        }
        $sql="select * from tbl_users where (username like '".$value."%' or surname like '".$value."%' or firstname like '".$value."%')  limit ".$start.",".$limit;
        $rows=$this->getArray($sql);
        
        $buff=
        '{"success":true,"totalCount":'.count($rows).',"rows":[';
        $c=0;
        $total=count($rows);
        foreach($rows as $row){
            $buff.='{"userID":"'.$row['userid'].'","username":"'.$row['username'].'","firstname":"'.$row['surname'].'","lastname":"'.$row['firstname'].'","email":"'.$row['emailaddress'].'"}';
            $c++;
            if($c < $total){
                $buff.=",";
            }
        }
        $buff.=']}';
        $contentType = "application/json; charset=utf-8";
        header("Content-Type: {$contentType}");
        header("Content-Size: " . strlen($buff));
        echo $buff;
    }
}

?>
