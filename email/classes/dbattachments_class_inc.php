<?
/* ----------- data class extends dbTable for tbl_email_attachments ----------*/

// security check - must be included in all scripts
if(!$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}

/**
* Model class for the table tbl_email_new
* @author Kevin Cyster
*/

class dbattachments extends dbTable
{
    function init()
    {
        parent::init('tbl_email_attachments');
        $this->table='tbl_email_attachments';

        $this->objUser=&$this->getObject('user','security');
        $this->userId=$this->objUser->userId();

        $this->objConfig=&$this->getObject('altconfig','config');
        $this->fileLocation=$this->objConfig->getcontentBasePath().'attachments/';
        $this->attachLocation=$this->fileLocation.$this->userId."/";
    }

    /**
    * Method for adding attachments to the database.
    *
    * @param string $emailId The id of the email the attachments are for
    * @return NULL
    */
    function addAttachments($emailId)
    {
        ini_set('memory_limit',-1);
        $files=$this->attachLocation.'*';
        if(glob($files)!=FALSE){
            foreach(glob($files) as $filename){
                $fields=array();
                $fields['email_id']=$emailId;
                $fields['file_name']=basename($filename);
                $fields['file_type']=filetype($filename);
                $fields['file_size']=filesize($filename);
                $handle=fopen($filename, "rb");
                $contents=fread($handle,filesize($filename));
                fclose($handle);
                $fields['file_data']=$contents;
                $fields['updated']=date("Y-m-d H:i:s");
                $fileId=$this->insert($fields);
            }
        }
        ini_restore('memory_limit');
    }

    /**
    * Method to retrieve attachments from the data base
    *
    * @param string $emailId The id of the email the attachments are for
    * @return array $data The attachment data
    */
    function getAttachments($emailId)
    {
        $sql="SELECT * FROM ".$this->table;
        $sql.=" WHERE email_id='".$emailId."'";
        $sql.=" ORDER BY file_name";
        $data=$this->getArray($sql);
        if(!empty($data)){
            return $data;
        }
        return FALSE;
    }

    /**
    * Method to download the attachment
    *
    * @access private
    * @param string $attachId The id of the file to download
    * @return data $fileData The file data
    */
    function outputFile($attachId)
    {
        $sql="SELECT * FROM ".$this->table;
        $sql .= " WHERE id = '".$attachId."'";

        $data = $this->getArray($sql);
        $name=$data[0]['file_name'];
        $size=$data[0]['file_size'];
        $type=$data[0]['file_type'];
        $fileData=$data[0]['file_data'];
        header("Content-type: $type");
        header("Content-length: $size");
        header("Content-Disposition: filename=$name");
        header("Content-Description: PHP Generated Data");

        echo $fileData;
    }

    /**
    *  Method to resend an attachment
    *
    * @param string $emailId The id of the original email
    * @param string $newEmailId The id of the resent email
    */
    function resendAttachments($emailId,$newEmailId)
    {
        $arrAttachments=$this->getAttachments($emailId);
        foreach($arrAttachments as $attachment){
            $fields=array();
            $fields['email_id']=$newEmailId;
            $fields['file_name']=$attachment['file_name'];
            $fields['file_type']=$attachment['file_type'];
            $fields['file_size']=$attachment['file_size'];
            $fields['file_data']=$attachment['file_data'];
            $fileId=$this->insert($fields);
        }
    }
}
?>