<?
/*
* Class for NextGen Internal Email file operations
* @author James Scoble
* @version $Id$
* @copyright 2005
* @license GNU GPL
*/

class emailfiles extends dbtable
{
    var $objUser;

    function init()
    {
         parent::init('tbl_email_files');
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
    * @param string $size
    */
    function addFile($fileId,$email_id,$userId,$filename,$filetype,$size)
    {
        $sql=compact('fileId','email_id','userId','filename','filetype','size');
        $this->insert($sql);
    }

    /**
    * This is a method to add a list of attachments to a given email
    * @param string message_id - the email handle
    * @param array $attachments - the data on the files
    *
    */
    function useArray($message_id,$attachments)
    {
        if (count($attachments)>0){
            foreach ($attachments as $line)
            { 
                extract($line);
                $this->addFile($fileId,$message_id,$userId,$filename,$filetype,$size);
            }
        }
    }

    /**
    * This is a method to remove all attachments from a given email
    * @param string $handle - the email handle
    * @param array $email_id - the specific email being deleted
    *
    */
    function deleteAll($handle,$email_id)
    {
        $attachments=$this->listFiles($handle);
        $emailList=$this->getArray("select email_id,email_attach from tbl_email where email_attach='$handle' and email_id<>'$email_id'");
        if ((count($attachments)>0)&&(count($emailList)==0)){
            foreach ($attachments as $line)
            { 
                extract($line);
                $this->removeFile($fileId,$email_id);
                $this->indexDelete('tbl_email_blob',"where fileId='$fileId'");
            }
        }
    }


    /**
    * This is a method to remove a file
    * @param string $fileId
    * @param string $email_id
    */
    function removeFile($fileId,$email_id)
    {
        $this->indexDelete('tbl_email_files'," where fileId='$fileId' and email_id='$email_id'");
    }


    /**
    * This utility method's purpose is to find the primary key 'id' and delete by it, using a filter.
    * @param string $tablename - the name of the table to use
    * @param string $filder an SQL 'WHERE' clause
    */
    function indexDelete($tablename,$filter)
    {
        $sql='select id from '.$tablename.' '.$filter;
        $idlist=$this->getArray($sql);
        $this->beginTransaction();
        foreach ($idlist as $line)
        {
            $this->delete('id',$line['id'],$tablename);
        }
        $this->commitTransaction();
    }

}
?>
