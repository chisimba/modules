<?php
/**
 * This class interfaces with db to store a list of files uploaded
 *  PHP version 5
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the
 * Free Software Foundation, Inc.,
 * 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 * @category  Chisimba
 * @package   wicid (docume3nt management system)
 * @author    Nguni Phakela, david wafula
 * @copyright 2010

 */

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
class dbdocuments extends dbtable {
    var $tablename = "tbl_wicid_documents";
    var $userid;

    public function init() {
        parent::init($this->tablename);
        $this->objUser=$this->getObject('user','security');
        $this->objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
        $this->objUploadTable = $this->getObject('dbfileuploads');
        $this->objConfig = $this->getObject('altconfig', 'config');
        $this->userutils=$this->getObject('userutils');
        $this->resourcePath=$this->objConfig->getModulePath();
        $docRoot=$_SERVER['DOCUMENT_ROOT'];
        $location = "http://" . $_SERVER['HTTP_HOST'];
        $this->sitePath=$location.'/'. str_replace($docRoot,$replacewith,$this->resourcePath);
    }
    public function getdocuments($mode="default",$userid, $rejected = "N") {
        if(strcmp($rejected,'Y') == 0) {
            $sql="select A.*, B.docid, B.filename from tbl_wicid_documents as A
                      left outer join tbl_wicid_fileuploads as B on A.id = B.docid
                  where A.active = 'N'
                  and A.deleteDoc = 'N'
                  and A.rejectDoc = 'Y'";// and mode ='$mode'";
        }
        else {
            $sql="select A.*, B.docid, B.filename from tbl_wicid_documents as A
                  left outer join tbl_wicid_fileuploads as B on A.id = B.docid
              where A.active = 'N'
              and A.deleteDoc = 'N'
              and A.rejectDoc = 'N'";
        }
        if(!$this->objUser->isadmin()) {

        //    $sql.=" and A.userid = '".$this->objUser->userid()."'";
        }
        $sql.=' order by date_created DESC';

       // echo $sql;
       // die();

        $rows=$this->getArray($sql);
        $docs=array();
        //print_r($rows);

        foreach ($rows as $row) {
            //$owner=$this->userutils->getUserId();
            if(strlen(trim($row['contact_person'])) == 0) {
                $owner=$this->objUser->fullname($row['userid']);
            }else {
                $owner = $row['contact_person'];
            }

            if(trim(strlen($row['docid'])) == 0) {
                $attachmentStatus = "No";
            }
            else {
                $f = $row['filename'];
                $attachmentStatus ='Yes&nbsp;<img  src="'.$this->sitePath.'/wicid/resources/images/ext/'.$this->findexts($f).'-16x16.png">';
            }
            $docs[]=array(
                    'userid'=> $row['userid'],
                    'owner'=>$owner,
                    'refno'=> $row['refno']."-".$row['ref_version'],
                    'title'=> $row['docname'],
                    'group'=> $row['groupid'],
                    'docid'=> $row['id'],
                    'topic'=> $row['topic'],
                    'department'=> $row['department'],
                    'telephone'=> $row['telephone'],
                    'date'=> $row['date_created'],
                    'attachmentstatus'=> $attachmentStatus,
                    'status'=> $row['status'],
                    'currentuserid'=>$row['currentuserid']
            );
        }
        echo json_encode(array("documents"=>$docs));
        die();
    }

    /**
     * adds new document record
     * @param <type> $date
     * @param <type> $refno
     * @param <type> $department
     * @param <type> $telephone
     * @param <type> $title
     * @param <type> $path
     */
    public function addDocument(
            $date,
            $refno,
            $department,
            $contact,
            $telephone,
            $title,
            $groupid,
            $path,
            $mode="default",
            $approved="N",
            $status="0",
            $currentuserid,
            $ref_version
    ) {


        $userid=$this->userutils->getUserId();
        $currentuserid=$userid;

        // using this user id, get the full name and compare it with contact person!
        $fullname = $this->objUser->fullname($userid);
        if(strcmp($fullname, $contact) == 0) {
            $contact = "";
        }
        
        $data=array(
                'docname'=>$title,
                'date_created'=>$date,
                'userid'=>$userid,
                'refno'=>$refno,
                'groupid'=>$groupid,
                'department'=>$department,
                'contact_person'=>$contact,
                'telephone'=>$telephone,
                'topic'=>$path,
                'mode'=>$mode,
                'active'=>$approved,
                'status'=>$status,
                'currentuserid'=>$currentuserid,
                'ref_version' => $ref_version,
                'version' => $ref_version
        );
        $id=$this->insert($data);
        echo $refno."-".$ref_version.','.$id;
        //echo "success|$id";
        return $id;
    }


    /**
     * sets active to Y to docs with supplied id
     * @param <type> $docids
     */
    function approveDocs($docids) {
        $data=array('active'=>'Y');
        $ids=explode(",", $docids);
        $userid=$this->userutils->getUserId();
        $ext='.na';
        $dir = $this->objSysConfig->getValue('FILES_DIR', 'wicid');
        foreach ($ids as $id) {
            $this->update('id',$id,$data);
            $doc=$this->getDocument($id);
            //print_r($doc);

            $filename=$dir.'/'.$doc['topic'].'/'. $doc['docname'].$ext;
            $filename= str_replace("//", "/", $filename);
            $newname=$dir.'/'.$doc['topic'].'/'. $doc['docname'].'.'.$doc['ext'];
            $newname= str_replace("//", "/", $newname);

            /*  $fh = fopen("/dwaf/wicidtest/log.txt", 'w') or die("can't open file ".$doc['docname']);
            $stringData = "renaming on approve $filename\n$newname\n===================";
            fwrite($fh, $stringData);
            fclose($fh);
            */
            if(!file_exists($filename)) {

                $fh = fopen($filename, 'w') or die("can't open file ".$doc['docname']);
                $stringData = "\n";
                fwrite($fh, $stringData);
                fclose($fh);
                $data = array(
                        'filename'=>$doc['docname'].$ext,
                        'filetype'=>'txt',
                        'date_uploaded'=>strftime('%Y-%m-%d %H:%M:%S',mktime()),
                        'userid'=>$userid,
                        'parent'=>$doc['topic'],
                        'docid'=>$id,
                        'refno'=>$this->userutils->getRefNo(),
                        'filepath'=>$doc['topic'].'/'.$doc['docname'].$ext);
                $result = $this->objUploadTable->saveFileInfo($data);
            }else {
                rename($filename,$newname);
            }
        }
    }

    /**
     * sets active to Y to docs with supplied id
     * @param <type> $docids
     */
    function rejectDocs($docids) {

        $ids=explode(",", $docids);
        $ext='.na';
        $dir = $this->objSysConfig->getValue('FILES_DIR', 'wicid');
        foreach ($ids as $id) {
            if(strlen($id) > 0) {
                $data = array('rejectDoc'=>'Y');
                $res = $this->update('id', $id, $data);
            }
        }
    }

    /**
     * sets active to Y to docs with supplied id
     * @param <type> $docids
     */
    function deleteDocs($docids) {

        $ids=explode(",", $docids);
        $ext='.na';
        $dir = $this->objSysConfig->getValue('FILES_DIR', 'wicid');
        foreach ($ids as $id) {
            if(strlen($id) > 0) {
                $doc=$this->getDocument($id);

                //$filename=$dir.'/'.$doc['topic'].'/'. $doc['docname'].$ext;
                //$filename= str_replace("//", "/", $filename);
                $filename=$dir.'/'.$doc['topic'].'/'. $doc['docname'].'.'.$doc['ext'];
                $filename= str_replace("//", "/", $newname);
                //unlink($filename);
                // instead of deleting, set the delete field to Y
                //$this->delete('id',$id);
                $data = array('deleteDoc'=>'Y');
                $res = $this->update('id', $id, $data);
            }
        }
    }
    /**
     * get a documet with specified id
     * @param <type> $id
     * @return <type>
     */
    function getDocument($id) {
        return $this->getRow('id',$id);
    }

    /*function updateDocument($id,$data) {
        $this->update('id',$id,$data);
    }*/

    function checkRefNo($number) {
        $sql = "select max(ref_version) as myrefno from .".$this->tablename;
        $sql .= " where refno like '%".date("Y")."%'";
        $sql .= " and SUBSTRING(refno, 1, 1) = '$number'";
        $res = $this->getArray($sql);

        return (int)$res[0]['myrefno']+1;
    }

    function getRefNo($id) {
        $res = $this->getRow("id", $id);
        
        return $res['refno'];
    }

    function findexts ($filename) {
        $filename = strtolower($filename) ;
        $exts = split("[/\\.]", $filename) ;
        $n = count($exts)-1;
        $ext = $exts[$n];

        //check if icon for this exists, else return unknown
        $filePath=$this->objConfig->getModulePath().'/wicid/resources/images/ext/'.$ext.'-16x16.png';
        if(file_exists($filePath) ) {
            return $ext;
        }else {
            return "unknown";
        }
    }

    function updateInfo($id, $data) {
        $this->update("id", $id, $data);
    }

     
    function changeCurrentUser($userid, $docid) {
        $sql= "update tbl_wicid_documents set currentuserid = '$userid' where id = '$docid'";
        $this->sendEmailAlert($userid);
        return $this->getArray($sql);
    }

    function sendEmailAlert($useridto) {
        $toNames=$this->objUser->fullname($useridto);
        $toEmail=$this->objUser->email($useridto);

        $linkUrl = $this->uri(array('action'=>'home'));
        $linkUrl->link="Link";
        $objMailer = $this->getObject('email', 'mail');
        $body="xyz has forwarded you document titled xrf. To access it, click on link below
        ".$linkUrl->href;
        $subject="hi";
       
        $objMailer->setValue('to',array($toEmail));
        $objMailer->setValue('from', $this->objUser->email());
        $objMailer->setValue('fromName', $this->objUser->fullname());
        $objMailer->setValue('subject', $subject);
        $objMailer->setValue('body', strip_tags($body));
        $objMailer->setValue('AltBody', strip_tags($body));

        $objMailer->send();
    }

    function retrieveDocument($userid, $docid){
        $sql= "update tbl_wicid_documents set currentuserid = '$userid' where id = '$docid'";
        $this->sendEmailAlert($userid);
        return $this->getArray($sql);

    }

    function checkUsers($docid) {
        $sql="select userid, currentuserid from tbl_wicid_documents where id='$docid'";
        $userid="";
        $currentuserid="";
        $rows=$this->getArray($sql);

        foreach($rows as $row) {
            $userid=$row['userid'];
            $currentuserid=$row['currentuserid'];
        }
        echo $userid.' '.$currentuserid;
        return $userid;$currentuserid;
    }

 /*   function getStatus($docid){
        $sql = "select status from tbl_wicid_documents where id='$docid'";
        $rows=$this->getArray($sql);

        foreach($rows as $row) {
            $status=$row['status'];
        }
        echo $status;
        return $status;
    }
*/
    function setStatus($docid, $status){
        $sql = "update tbl_wicid_documents set status ='$status' where id = '$docid'";
        return $this->getArray($sql);
    }
}
?>
