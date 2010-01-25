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
class dbfileuploads extends dbtable {
    var $tablename = "tbl_dms_fileuploads";
    var $userid;

    public function init() {
        parent::init($this->tablename);
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

}
?>