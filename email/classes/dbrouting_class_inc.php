<?
/* ----------- data class extends dbTable for tbl_email_routing ----------*/

// security check - must be included in all scripts
if(!$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}

/**
* Model class for the table tbl_email_new
* @author Kevin Cyster
*/

class dbrouting extends dbTable
{
    function init()
    {
        parent::init('tbl_email_routing');
        $this->table='tbl_email_routing';
        $this->tblEmail='tbl_email';
        $this->tblUsers='tbl_users';

        $this->objUser=&$this->getObject('user','security');
        $this->userId=$this->objUser->userId();
    }

    /**
    * Method for adding a row to the database.
    *
    * @param string $emailId The email id
    * @param string $senderId The id of the sender
    * @param string $recipientId The id of the recipient
    * @param string $folderId The id of the folder
    * @param string $sentEmail An indicator id the email has been sent
    * @param string $emailRead An indicator id the email has been read
    * @return string $routingId The id of the routing item
    */
    function sendMail($emailId,$senderId,$recipientId,$folderId,$sentEmail,$emailRead)
    {
        $fields=array();
        $fields['email_id']=$emailId;
        $fields['sender_id']=$senderId;
        $fields['recipient_id']=$recipientId;
        $fields['folder_id']=$folderId;
        $fields['sent_email']=$sentEmail;
        $fields['read_email']=$emailRead;
        $fields['updated']=date("Y-m-d H:i:s");
        if($emailRead==1){
            $fields['date_read']=date("Y-m-d H:i:s");
        }
        $routingId=$this->insert($fields);

    }

    /**
    * Method to retrieve read email for a user
    *
    * @param string $folderId The id of the folder to retrieve mail for
    * @return array $data the email data
    */
    function getReadMail($folderId)
    {
        $sql="SELECT * FROM ".$this->table;
        $sql.=" WHERE recipient_id='".$this->userId."'";
        $sql.=" AND folder_id='".$folderId."'";
        $sql.=" AND read_email=1";
        $data=$this->getArray($sql);
        if(!empty($data)){
            return $data;
        }
        return FALSE;
    }

    /**
    * Method to retrieve unread email for a user per folder
    *
    * @param string $folderId The id of the folder to retrieve mail for
    * @return array $data the email data
    */
    function getUnreadMail($folderId)
    {
        $sql="SELECT * FROM ".$this->table;
        $sql.=" WHERE recipient_id='".$this->userId."'";
        $sql.=" AND folder_id='".$folderId."'";
        $sql.=" AND read_email=0";
        $data=$this->getArray($sql);
        if(!empty($data)){
            return $data;
        }
        return FALSE;
    }

    /**
    * Method to retrieve unread email for a user per folder
    *
    * @param string $folderId The id of the folder to retrieve mail for
    * @return array $data the email data
    */
    function getAllUnreadMail()
    {
        $sql="SELECT * FROM ".$this->table;
        $sql.=" WHERE recipient_id='".$this->userId."'";
        $sql.=" AND read_email=0";
        $data=$this->getArray($sql);
        if(!empty($data)){
            return $data;
        }
        return FALSE;
    }

    /**
    * Method to retrieve all email for a user
    *
    * @param string $folderId The id of the folder to retrieve mail for
    * @param array $sortOrder The sort order array fieldname/direction
    * @param string $filter The filter if any
    * @return array $data the email data
    */
    function getAllMail($folderId,$sortOrder,$filter)
    {
        $sql="SELECT *, routing.id AS routing_id, email.id AS emailid ";
        $sql.=" FROM ".$this->table." AS routing,";
        $sql.=$this->tblEmail." AS email, ";
        $sql.=$this->tblUsers." AS users";
        $sql.=" WHERE routing.email_id=email.id";
        $sql.=" AND routing.sender_id=users.userid";
        $sql.=" AND routing.folder_id='".$folderId."'";
        $sql.=" AND routing.recipient_id='".$this->userId."'";
        if($filter!=NULL){
            if($filter=='1'){
                $sql.=" AND read_email='1'";
            }elseif($filter=='2'){
                $sql.=" AND read_email!='1'";
            }elseif($filter=='3'){
                $sql.=" AND attachments>='1'";
            }
        }
        if($sortOrder!=NULL){
            if($sortOrder[1]==1){
                $sql.=" ORDER BY fullName ".$sortOrder[2];
            }elseif($sortOrder[1]==2){
                $sql.=" ORDER BY subject ".$sortOrder[2];
            }else{
                $sql.=" ORDER BY date_sent ".$sortOrder[2];
            }
        }
        $data=$this->getArray($sql);
        if(!empty($data)){
            foreach($data as $key=>$line){
                $data[$key]['fullName']=$line['firstname'].' '.$line['surname'];
            }
            return $data;
        }
        return FALSE;
    }

    /**
    * Method to retrieve email for a user
    *
    * @param string $emailId The id of the email to retrieve
    * @param string $folderId The id of the folder the email is in
    * @return array $data The email data
    */
    function getMail($routingId)
    {
        $sql="SELECT * FROM ".$this->table;
        $sql.=" WHERE id='".$routingId."'";
        $data=$this->getArray($sql);
        if(!empty($data)){
            $data=$data[0];
            return $data;
        }
        return FALSE;
    }

    /**
    * Method to update an email as read
    *
    * @param string $routingId The id of the record
    * @return NULL
    */
    function markAsRead($routingId)
    {
        $data=$this->getMail($routingId);
        if($data['read_email']!=1){
            $fields=array();
            $fields['read_email']=1;
            $fields['date_read']=date("Y-m-d H:i:s");
            $fields['updated']=date("Y-m-d H:i:s");
            $this->update('id',$routingId,$fields);
        }
    }

    /**
    * Method to update an email as deleted
    *
    * @param string $routingId The id of the record
    * @return NULL
    */
    function deleteEmail($routingId)
    {
        $data=$this->getMail($routingId);
        if(!empty($data)){
            if($data['folder_id']=='init_4'){
                $this->delete('id',$routingId);
            }else{
                $fields=array();
                $fields['folder_id']='init_4';
                $this->update('id',$routingId,$fields);
            }
        }
    }

    /**
    * Method to restore an email from the trash folder
    *
    * @param string $routingId The id of the record
    * @return NULL
    */
    function restoreEmail($routingId)
    {
        $fields=array();
        $fields['folder_id']='init_1';
        $fields['updated']=date("Y-m-d H:i:s");
        $this->update('id',$routingId,$fields);
    }

    /**
    * Method to move an email to a new folder
    *
    * @param array $arrMsgId An array of the ids to be moved
    * @param string $newFolderId The id of the folder the message have to be moved to
    * @return NULL
    */
    function moveEmail($arrMsgId,$newFolderId)
    {
        foreach($arrMsgId as $routingId){
            $fields=array();
            $fields['folder_id']=$newFolderId;
            $fields['updated']=date("Y-m-d H:i:s");
            $this->update('id',$routingId,$fields);
        }
    }
}
?>