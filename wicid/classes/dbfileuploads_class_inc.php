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
class dbfileuploads extends dbtable {
    var $tablename = "tbl_dms_fileuploads";
    var $userid;

    public function init() {
        parent::init($this->tablename);
        $this->objUser=$this->getObject('user','security');
        $this->objConfig = $this->getObject('altconfig', 'config');
        $this->objAltConfig = $this->getObject('altconfig','config');
        $this->resourcePath=$this->objAltConfig->getModulePath();
        $replacewith="";
        $docRoot=$_SERVER['DOCUMENT_ROOT'];
        $location = "http://" . $_SERVER['HTTP_HOST'];
        $this->sitePath=$location.'/'. str_replace($docRoot,$replacewith,$this->resourcePath);
    }

    public function setUserId($userid) {
        $this->userid = $userid;
    }
    public function saveFileInfo($data) {
        $result = $this->insert($data);
        return $result;
    }

    public function getFileTypes() {
        $sql = "select distinct filetype from ".$this->tablename. " where userid = '".$this->userid."'";
        $res = $this->getArray($sql);

        return $res;
    }

    public function getDocs($filetype) {
        $sql = "select * from ".$this->tablename." where filetype = '".$filetype."'". " and userid = '".$this->userid."'";
        $res = $this->getArray($sql);

        return $res;
    }
    /**
     * gets the instances of this file, to avoid duplication, overwriting
     * @param <type> $filename
     * @param <type> $path
     * @return <type>
     */
    public function getFileInstances($filename,$path) {
        $sql = "select  id from ".$this->tablename." where parent = '".$filename."' and filepath = '".$path."'";
        $res = $this->getArray($sql);
        return $res;
    }
    public function getAllFiles() {
        $sql = "select * from ".$this->tablename." where userid = '".$this->userid."' order by date_uploaded desc, filename limit 10";
        $res = $this->getArray($sql);

        return $res;
    }

    public function deleteFileRecord($id) {
        $this->delete('id', $id);
    }

    public function getFileName($id) {
        $data = $this->getRow('id', $id);
        return $data;
    }

    /**
     *  gets all the details of the file that was uploaded
     * @param <type> $filename
     * @param <type> $filepath
     * @return <type>
     */
    public function getFileInfo($filename, $filepath) {
        $filepath=  str_replace("//", "/", $filepath);
        $sql="select * from $this->tablename  fls,tbl_dms_documents docs
                where fls.filename = '$filename' and fls.filepath = '$filepath'
                and fls.docid=docs.id and docs.active='Y'";

        $data = $this->getArray($sql);
        return $data;
    }

    function deleteNAFile($filepath,$filename){
        $sql=
        "delete from tbl_dms_fileuploads where filename ='$filename' and filepath='$filepath'";
        $this->getArray($sql);

    }
    function searchfiles($filter) {
        $objUserutils=$this->getObject('userutils');
        $sql="select * from tbl_dms_fileuploads where filename like '%$filter%'";

        $sql.=' order by date_uploaded DESC';

        $owner=$objUserutils->getUserId();
        $rows=$this->getArray($sql);
        $files=array();

        foreach ($rows as $row) {
            $size = $this->formatBytes(filesize($dir.$node.'/'.$f), 2);
            $isowner=$this->objUser->userid() == $file['userid']?"true":"false";
            $files[] = array(
                    'text'=>$row['filename'],
                    'id'=>$row['filepath'],
                    'docid'=>$row['docid'],
                    'refno'=>$row['id'],
                    'owner'=>$this->objUser->fullname($row['userid']),
                    'lastmod'=>$lastmod,
                    'filesize'=>$size,
                    'thumbnailpath'=>$this->sitePath.'/wicid/resources/images/ext/'.$this->findexts($row['filename']).'.png'
            );

        }
        echo json_encode(array("files"=>$files));

        die();
    }
    // from php manual page
    function formatBytes($val, $digits = 3, $mode = "SI", $bB = "B") { //$mode == "SI"|"IEC", $bB == "b"|"B"
        $si = array("", "K", "M", "G", "T", "P", "E", "Z", "Y");
        $iec = array("", "Ki", "Mi", "Gi", "Ti", "Pi", "Ei", "Zi", "Yi");
        switch(strtoupper($mode)) {
            case "SI" : $factor = 1000;
                $symbols = $si;
                break;
            case "IEC" : $factor = 1024;
                $symbols = $iec;
                break;
            default : $factor = 1000;
                $symbols = $si;
                break;
        }
        switch($bB) {
            case "b" : $val *= 8;
                break;
            default : $bB = "B";
                break;
        }
        for($i=0;$i<count($symbols)-1 && $val>=$factor;$i++)
            $val /= $factor;
        $p = strpos($val, ".");
        if($p !== false && $p > $digits) $val = round($val);
        elseif($p !== false) $val = round($val, $digits-$p);
        return round($val, $digits) . " " . $symbols[$i] . $bB;
    }

    /**
     *  used to get ext to a file
     * @param <type> $filename
     * @return <type>
     */
    function findexts ($filename) {
        $filename = strtolower($filename) ;
        $exts = split("[/\\.]", $filename) ;
        $n = count($exts)-1;
        $ext = $exts[$n];

        //check if icon for this exists, else return unknown
        $filePath=$this->objConfig->getModulePath().'/wicid/resources/images/ext/'.$ext.'.png';
        if(file_exists($filePath) ) {
            return $ext;
        }else {
            return "unknown";
        }
    }
}
?>