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
 * @package   dms (document management system)
 * @author    Nguni Phakela, david wafula
 * @copyright 2010

 */

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
class dbdocuments extends dbtable {
    var $tablename = "tbl_dms_documents";
    var $userid;

    public function init() {
        parent::init($this->tablename);
        $this->objUser=$this->getObject('user','security');
        $this->objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
        $this->objUploadTable = $this->getObject('dbfileuploads');
    }
    public function getdocuments() {
        $sql="select * from tbl_dms_documents where active = 'N'";
        if(!$this->objUser->isadmin()) {
               $sql.=" and userid = '".$this->objUser->userid()."'";
        }
        $sql.=' order by date_created DESC';
        $owner="1";//$this->objUser->fullname($row['userid']);
        $rows=$this->getArray($sql);
        $docs=array();

        foreach ($rows as $row) {
            $docs[]=array(
                    'userid'=> $row['userid'],
                    'owner'=>$owner,
                    'refno'=> $row['refno'],
                    'title'=> $row['docname'],
                    'docid'=> $row['id'],
                    'topic'=> $row['topic'],
                    'department'=> $row['department'],
                    'telephone'=> $row['telephone'],
                    'date'=> $row['date_created'],
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
            $path
    ) {


        $userid=$this->objUser->userid();
       // $userid='1';
        $data=array(
                'docname'=>$title,
                'date_created'=>$date,
                'userid'=>$userid,
                'refno'=>$refno,
                'department'=>$department,
                'telephone'=>$telephone,
                'topic'=>$path,
                'active'=>'N'
        );
        $id=$this->insert($data);
        echo $refno.','.$id;
    }

    /**
     * sets active to Y to docs with supplied id
     * @param <type> $docids
     */
    function approveDocs($docids) {
        $data=array('active'=>'Y');
        $ids=explode(",", $docids);
        $userid=$this->objUser->userId();
        //$userid="1";
        $ext='.na';
        $dir = $this->objSysConfig->getValue('FILES_DIR', 'dms');
        foreach ($ids as $id) {
            $this->update('id',$id,$data);
            $doc=$this->getDocument($id);
            $filename=$dir.'/'.$doc['topic'].'/'. $doc['docname'].$ext;
            $newname=$dir.'/'.$doc['topic'].'/'. $doc['docname'].'.'.$doc['ext'];
            if(!file_exists($filename)) {
                $filename= str_replace("//", "/", $filename);
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
                        'refno'=>'1234',
                        'filepath'=>$doc['topic']);
                $result = $this->objUploadTable->saveFileInfo($data);
            }else{
                rename($filename,$newname);
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

    function updateDocument($id,$data){
        $this->update('id',$id,$data);
    }
}
?>