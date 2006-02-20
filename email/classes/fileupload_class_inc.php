<?
/*
* Class for upload and storage of files as BLOBs.
* @author James Scoble
* @version $Id$
* @copyright 2005
* @license GNU GPL
*/

class fileupload extends dbtable
{
    var $objUser;

    function init()
    {
         parent::init('tbl_email_tempfiles');
         $this->objUser=&$this->getObject('user','security');
    }

    /**
    * This is a method to list the files attached to a specific email
    * @author James Scoble
    * @param string email_id
    * @returns array $result
    */
    function listFiles($email_id)
    {
        $sql="where email_id='$email_id'";
        $result=$this->getAll($sql);
        return $result;
    }

    /**
    * This is a method to attach a file
    * @param string $fileId
    * @param string $email_id
    * @param string $userId
    * @param string $filename
    * @param string $filetype
    * @param string$size
    * @param string $uploadtime
    */
    function addFile($fileId,$email_id,$userId,$filename,$filetype,$size,$uploadtime)
    {
        $sql=compact('fileId','email_id','userId','filename','filetype','size','uploadtime');
        $this->insert($sql);
    }


    /**
    * This is a method to remove a file
    * @param string $fileId
    * @param string $email_id
    */
    function removeFile($fileId,$email_id)
    {
        $this->indexDelete('tbl_email_tempfiles'," where file_id='$fileId' and email_id='$email_id'");
    }

    /**
    * This is a method to erase a file
    * @param string $fileId
    * @param string $email_id
    */
    function eraseFile($fileId,$email_id)
    {
        $this->beginTransaction();
        $this->indexDelete('tbl_email_blob'," where fileId='$fileId'");
        $this->indexDelete('tbl_email_tempfiles'," where fileId='$fileId' and email_id='$email_id'");
        $this->commitTransaction();
    }


    /**
    * This is a method to remove all the file entries - closing the temp entry
    * @param string $email_id
    */
    function dropEmail($email_id)
    {
        $this->beginTransaction();
        $this->indexDelete('tbl_email_tempfiles',"where  email_id='$email_id'");
        $this->commitTransaction();
    }

    /**
    * This is a method to load a file into the database
    * @param array $filedata
    * @param string $email_id
    */
    function uploadFile($filedata,$email_id)
    {
        $filename=$filedata['name'];
        $filetype=$filedata['type'];
        $tempfile=$filedata['tmp_name'];
        if (file_exists($tempfile)) { // if the file isn't there, we don't try to anything with it.
            $filesize = filesize($tempfile);
            $userId=$this->objUser->userId();
            $fileId=$userId.time();
            $fp = fopen($tempfile, "rb");
            $this->beginTransaction();
            $count=0;
            while (!feof($fp)) 
            {
                $binarydata = fread($fp, 65535); // now we load the file into 64k BLOBs
                $this->insert(array('fileId'=>$fileId,'segment'=>$count,'filedata'=>$binarydata),'tbl_email_blob');
                $count=$count+1;
            }
            fclose($fp);
            $this->addFile($fileId,$email_id,$userId,$filename,$filetype,$filesize,time()); 
            $this->commitTransaction();   
        }
    }


    /**
    * This is a method to get a file from the database and return it as an array
    * THIS WILL CRASH IF THE FILE IS TOO LARGE
    * @param $fileId
    * @returns array $file the file contents
    */
    function getFile($fileId)
    {
        $data=$this->getArray("select * from tbl_email_tempfiles where fileId='$fileId'");
        if (count($data)==0){ // if the file has been deleted
            return FALSE;
        } else {
            $name=$data[0]['filename'];
            $size=$data[0]['size'];
            // Imposing a file-size limit of 5megs
            if ($size>5242880){
                return FALSE;
            }
            $type=$data[0]['filetype'];
            $fileId2=$data[0]['fileId'];
            $file['name']=$name;
            $list=$this->getArray("select id from tbl_email_blob where fileId='$fileId2' order by segment");
            $file['data']='';
            foreach ($list as $line)
            {
                $id=$line['id'];
                $filedata=$this->getArray("select * from tbl_email_blob where id='$id'");
                $file['data'].=$filedata[0]['filedata'];
            }
            return $file;
        }
    }
    /**
    * utility method to find the primary key 'id' and delete by it, using a filter
    * @param string $tablename - the name of the table to use
    * @param string $filder an SQL 'WHERE' clause
    */                
    function indexDelete($tablename,$filter)
    {
        $sql='select id from '.$tablename.' '.$filter;
        $idlist=$this->getArray($sql);
        foreach ($idlist as $line)
        {
            $this->delete('id',$line['id'],$tablename);
        }
    }

}
?>
