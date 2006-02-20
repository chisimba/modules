<?
/*
* Class for NextGen Internal Email operations
* @author James Scoble
* @version $Id$
* @copyright 2004
* @license GNU GPL
*/

class kngmail extends dbtable
{

    var $objUser;

    function init()
    {
         parent::init('tbl_email');
         $this->objUser=&$this->getObject('user','security');
    }

    /**
    * This method pulls a specific email message from the database, so that it can be displayed for the user.
    * @author James Scoble
    * @param string $messageId - the identifier of the message to read
    * @returns $email an assoc array
    */
    function readMail($messageId)
    {
        $line=$this->getRow('email_id',$messageId);
        return $line;
    }

    /**
    * This method sets an email as having been read.
    * @author James Scoble
    * @param string $emailId
    * @param string $read_date
    */
    function setReadStatus($emailId,$readDate=0)
    {
        $sqlarray=array();
        $sqlarray['date_read']=$readDate;
        $this->update('email_id',$emailId,$sqlarray); 
    }
    
    /**
    * This method moves an email to another folder.
    * @author James Scoble
    * @param string $emailId
    * @param string $folder
    */
    function moveEmail($emailId,$folder='old')
    {
        $sqlarray=array();
        $sqlarray['folder']=$folder;
        $this->update('email_id',$emailId,$sqlarray); 
    }

    /**
    * This method pulls a list of email headers from the database, so that it can be displayed for the user.
    * @author James Scoble
    * @param string $userId - the identifier of the message to read
    * @param string $filter - filters email
    * @returns $email an assoc array
    */
    function listMail($userId,$filter=Null,$order='date')
    {
        $sql1=" where user_id='".$userId."'";
        switch ($filter)
        {
        case 'new':
            $sql2=" and folder='new'";
            break;
        case 'old':
            $sql2=" and folder='old'";
            break;
        case 'trash':
            $sql2=" and folder='trash'";
            break;
        case 'mail':
            $sql2=" and (folder='new' or folder='old' or folder='main') ";
            break;
        case 'userfolder':
            $userfolder=$this->getParam('userfolder');
            $sql2=" and folder='$userfolder'";
            break;
        case 'daterange': // not supported yet
            $start=$this->getParam('startdate');
            $end=$this->getParam('enddate');
            $sql2=" and date_sent>='$start' and date_sent<='$end'";
            break;
        case 'date': // also might not work
            $when=$this->getParam('startdate');
            $sql2=" and date_sent='$when'";
        default:
            $sql2='';
        }

        switch($order)
        {
        case 'date':
            $sql3=' order by date_sent desc';
            break;
        case 'reversedate':
            $sql3=' order by date_sent';
            break;
        case 'subject':
            $sql3=' order by subject';
            break;
        case 'reversesubject':
            $sql3=' order by subject desc';
            break;
        case 'sender':
            $sql1="INNER JOIN tbl_users ON ( tbl_email.sender_id = tbl_users.userId ) ".$sql1;
            $sql3='order by firstName';
            break;
        case 'reversesender':
            $sql1="INNER JOIN tbl_users ON ( tbl_email.sender_id = tbl_users.userId ) ".$sql1;
            $sql3='order by firstName desc';
            break;
        default:
            $sql3=' order by date_sent desc';
        }
        
        $list=$this->getAll($sql1.$sql2.$sql3);
        $rlist=array();
        foreach ($list as $line)
        {
            $dataline=array();
            $dataline['email_id']=$line['email_id'];
            $dataline['sender_id']=$line['sender_id'];
            $dataline['username']=$this->lookupUser($line['sender_id'],'username');
            $dataline['fullname']=$this->lookupUser($line['sender_id'],'fullname');
            $dataline['subject']=$line['subject'];
            $dataline['date']=$line['date_sent'];
            $dataline['date_read']=$line['date_read'];
            $dataline['folder']=$line['folder'];
            $rlist[]=$dataline;
        }
        return $rlist;
    }

    /**
    * method to determine username or name and surname of a user, given the userId
    * @author James Scoble
    * @param string $id
    * @param string $field
    * @returns string $rvalue
    */
    function lookupUser($id,$field='username')
    {
        switch($field)
        {
            case 'fullname':
              $rvalue=$this->objUser->fullname($id);
              break;
            case 'username':
            default:
              $rvalue=$this->objUser->userName($id);
         }
         return $rvalue;
    }

    /**
    * This method deletes a specific email from the database.
    * @author James Scoble
    * @param string $emailId - the identifier of the message to delete
    * 
    */
    function deleteMail($emailId)
    {
        $this->delete('email_id',$emailId);
    }

    /**
    * This method adds a new email to the database.
    * @author James Scoble
    * @param string $from - the userId of the sender 
    * @param string $to - the userId of the recipient
    * @param string $subject - the title of the email
    * @param string $text - the text of the message
    * @param string $attach - handle for attachments
    * 
    */
    function sendMail($from,$to,$subject,$text,$attach=Null)
    {
        $sql=array();
        $emailId=$from.$to.time();
        $sql['email_id']=$emailId;
        $sql['sender_id']=$from;
        $sql['user_id']=$to;
        $sql['subject']=$subject;
        $sql['message']=$text;
        $sql['date_sent']=date('Y-m-d H:i:s');
        $sql['date_read']='0';
        $sql['email_attach']=$attach;
        $sql['folder']='new';

        $this->insert($sql);
        return $emailId;
    }

    /**
    * This method returns the number of new emails for the specified user
    * @param string $userId
    * @param string $folder
    * @returns string $countnew;
    */
    function countNew($userId,$folder='new')
    {
        $sql1 = "select count(sender_id) as countnew from tbl_email where user_id='".$userId."'";
        $sql2 = " and folder=$folder";
        // We might want to count the email in all the folders
        if ($folder=='ALL'){
            $sql2='';
        }
        // If we specify old and new
        if ($folder=='mail'){
            $sql2=" and folder<>'trash'";
        }
	$count=$this->getArray($sql1.$sql2);
        return $count[0]['countnew'];
    }

    /**
    * This function will determine which email is "previous", and return its emailId
    * @param string $emailId
    * @param string $userId
    * @param string $dt
    * @returns string $emailId
    */
    function getPrevious($emailId,$userId,$dt,$folder='mail')
    {
        $sqlfolder="and folder='$folder'";
        if ($folder=='mail'){
            $sqlfolder="and folder<>'trash'";
        }
        $list=$this->getArray("select email_id from tbl_email where user_id='".$userId."' ".$sqlfolder." and date_sent>'".$dt."' order by date_sent");
        $list=array_reverse($list);
        if (isset($list[0])){
            return $list[0]['email_id'];
        } else {
            return $emailId;
        }
    }
    
    /**
    * This function will determine which email is "next", and return its emailId
    * @param string $emailId
    * @param string $userId
    * @param string $dt
    * @returns string $emailId
    */
    function getNext($emailId,$userId,$dt,$folder='mail')
    {
        $sqlfolder="and folder='$folder'";
        if ($folder=='mail'){
            $sqlfolder="and folder<>'trash'";
        }
        $list=$this->getArray("select email_id from tbl_email where user_id='".$userId."' ".$sqlfolder." and date_sent<'".$dt."' order by date_sent desc");
        if (isset($list[0])){
            return $list[0]['email_id'];
        } else {
            return $emailId;
        }
    }

    /**
    * This method attempts to send an email to an external address
    * @author James Scoble
    * @param string $userId - the userId of the sender 
    * @param string $to - the email address of the recipient
    * @param string $subject - the title of the email
    * @param string $text - the text of the message
    * @param array $attachments - list of attachments
    * 
    */
    function sendtoEmail($userId,$to,$subject,$text,$attachments)
    {
        // init the email class from utils
        $this->objEmail=&$this->getObject('kngemail','utilities');
        $this->objEmail->setup($userId.'@'.$_SERVER['SERVER_NAME'],$this->objUser->fullname($userId));
        $filedata=NULL;
        if (is_array($attachments)){
            $filedata=array();
            $this->objTempFiles=&$this->getObject('fileupload','email');
            foreach ($attachments as $line)
            {
                if ($line['size']<5242880){
                    $filedata[]=$this->objTempFiles->getFile($line['fileId']);
                }
            }
            if (count($filedata)==0){
                $filedata=NULL;
            }
        }
        // Send email
        $this->objEmail->sendMail('', $subject, $to, TRUE, $text,$filedata);
    }

}
?>
