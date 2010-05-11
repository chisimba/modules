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
 * @package   wicid (document management system)
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
        $this->userutils=$this->getObject('userutils');
    }
    public function getdocuments($mode="default") {
        $sql="select A.*, B.docid from tbl_wicid_documents as A
                  left outer join tbl_wicid_fileuploads as B on A.id = B.docid
              where A.active = 'N'";// and mode ='$mode'";
        if(!$this->objUser->isadmin()) {
            //$sql.=" and userid = '".$this->objUser->userid()."'";
        }
        $sql.=' order by date_created DESC';

        $rows=$this->getArray($sql);
        $docs=array();

        foreach ($rows as $row) {
            //$owner=$this->userutils->getUserId();
            $owner=$this->objUser->fullname($row['userid']);
            if(trim(strlen($row['docid'])) == 0) {
                $attachmentStatus = "No";
            }
            else {
                $attachmentStatus = "Yes";
            }
            $docs[]=array(
                    'userid'=> $row['userid'],
                    'owner'=>$owner,
                    'refno'=> $row['refno'],
                    'title'=> $row['docname'],
                    'group'=> $row['groupid'],
                    'docid'=> $row['id'],
                    'topic'=> $row['topic'],
                    'department'=> $row['department'],
                    'telephone'=> $row['telephone'],
                    'date'=> $row['date_created'],
                    'attachmentstatus'=> $attachmentStatus
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
            $telephone,
            $title,
            $groupid,
            $path,
            $mode="default",
            $approved="N"

    ) {


        $userid=$this->userutils->getUserId();
        $data=array(
                'docname'=>$title,
                'date_created'=>$date,
                'userid'=>$userid,
                'refno'=>$refno,
                'groupid'=>$groupid,
                'department'=>$department,
                'telephone'=>$telephone,
                'topic'=>$path,
                'mode'=>$mode,
                'active'=>$approved
        );
        $id=$this->insert($data);
        echo $refno.'|'.$id;
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
    function deleteDocs($docids) {

        $ids=explode(",", $docids);
        $ext='.na';
        $dir = $this->objSysConfig->getValue('FILES_DIR', 'wicid');
        foreach ($ids as $id) {

            $doc=$this->getDocument($id);

            //$filename=$dir.'/'.$doc['topic'].'/'. $doc['docname'].$ext;
            //$filename= str_replace("//", "/", $filename);
            $filename=$dir.'/'.$doc['topic'].'/'. $doc['docname'].'.'.$doc['ext'];
            $filename= str_replace("//", "/", $newname);
            //unlink($filename);
            $this->delete('id',$id);
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

    function updateDocument($id,$data) {
        $this->update('id',$id,$data);
    }

    function checkRefNo() {
        $sql = "select max(SUBSTRING(refno, length(refno), 1)) as myrefno from .".$this->tablename;
        $sql .= " where refno like '%".date("Y")."%'";
        $res = $this->getArray($sql);

        $refno=date("Y")."-".((int)$res[0]['myrefno']+1);

        return $refno;
    }
}
?>