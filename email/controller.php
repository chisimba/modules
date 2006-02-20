<?
/* -------------------- email class extends controller ----------------*/                                                                    
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}
// end security check
                                                                                                                                             
/**
* Controller class for the module that handles internal email
* @copyright (c) 2004 KEWL.NextGen GPL
* @package email
* @version 0.99
* @author James Scoble
*
* $Id: controller.php
*/

class email extends controller
{
    var $objUser; // the handle for the user class
    var $objMail; // the handle for the main email class
    var $userId; // this class variable will store the user's userId
    var $objLanguage; // the language object
    var $userList; // class variable
    var $userfolder; // this variable is used to determine which emails should be shown
    var $objAttach; //handle for BLOB attachments

    /**
    * init method
    */
    function init()
    {
        // User Object
        $this->objUser=& $this->getObject('user', 'security');
        // Main email class
        $this->objMail=& $this->getObject('kngmail','email');
        // Get userId from the user object
        $this->userId=$this->objUser->userId();
        // Language Object
        $this->objLanguage= & $this->getObject('language','language');
        //Get the folder
        $this->userfolder=$this->getParam('userfolder','mail');
        // Email attachment class
        $this->objAttach=$this->getobject('emailfiles');
        
        //Get the activity logger class 
        $this->objLog=$this->newObject('logactivity', 'logger'); 
        //Log this module call 
        $this->objLog->log();
                                        
    }

    /**
    * dispatch method
    * This is basically a switch statement, which the engine class class to run this module
    * @param string $action 
    * @returns string the template to use
    */
    function dispatch($action)
    {
        switch($action)
        {
            case 'new': 
                $this->setLayoutTemplate('user_layout_tpl.php');
                return 'edit_email_tpl.php';
            case 'read':
                $this->emailRead();
                $this->setLayoutTemplate('user_layout_tpl.php');
                return 'read_email_tpl.php';
            case 'readprevious':
                $prev=$this->objMail->getPrevious($this->getParam('emailId'),$this->userId,$this->getParam('dateInfo'),$this->userfolder);
                return $this->nextaction('read',array('emailId'=>$prev));
            case 'readnext':
                $next=$this->objMail->getNext($this->getParam('emailId'),$this->userId,$this->getParam('dateInfo'),$this->userfolder);
                return $this->nextaction('read',array('emailId'=>$next));
            case 'reply':
                $this->emailRead();
                $this->setLayoutTemplate('user_layout_tpl.php');
                return 'edit_email_tpl.php';
            case 'delete':
                $nextEmail=$this->emailDelete();
                $this->setLayoutTemplate('email_layout_tpl.php');
                if ($nextEmail!=NULL){
                    return $this->nextaction('read',array('emailId'=>$nextEmail,'userfolder'=>$this->userfolder));
                } else {
                    return $this->nextaction($this->getParam('nextaction'),array('userfolder'=>$this->userfolder));
                }
            case 'deleteselected':
                $delete=$this->getParam('delete',array());
                if (is_array($delete)&&(count($delete)>0)){
                    foreach ($delete as $line)
                    {
                        $this->emailDelete($line);
                    }
                }
                return($this->nextaction($this->getParam('nextaction'),array('userfolder'=>$this->getParam('nextfolder'))));
            case 'fileupload':
                $this->setPageTemplate('upload_page_tpl.php');
                return 'upload_tpl.php';
            case 'filedownload':
                $this->setPageTemplate('filedownload_page_tpl.php');
                return 'filedownload_tpl.php';
            case 'usermenu':
                $this->userList=$this->emailUserMenu();
                $this->setPageTemplate('userlist_page_tpl.php');
                return 'userlist_tpl.php';
            case 'userlist':
                $field=$this->getParam('userfield','listall');
		$this->userList= $this->emailUserList($field);
                $this->setVar('userList',$this->userList);
                $this->setPageTemplate('userlist_page_tpl.php');
                return 'userlist_tpl.php';
            case 'list':
                $this->emailList($this->userId,'userfolder');
                $this->setLayoutTemplate('user_layout_tpl.php');
                return 'list_email_tpl.php';
            case 'showmail':
                $this->setLayoutTemplate('user_layout_tpl.php');
                $this->emailList($this->userId,'mail');
                return 'list_email_tpl.php';
            case 'listall':
                $this->setLayoutTemplate('user_layout_tpl.php');
                $this->emailList($this->userId,NULL);
                return 'list_email_tpl.php';
            case 'index':
                $emailsFound=$this->emailList();
                $count=$this->emailCount($emailsFound);
                $this->setVar('email_count',$count);
                $this->setLayoutTemplate('user_layout_tpl.php');
                return 'index_tpl.php';
            case 'sendnew':
                $this->emailSend();
                $this->emailsendtext='mod_email_message_sent';
                return $this->nextaction('showmail',array('userfolder'=>'mail','status'=>'emailsent'));
            case 'showmail':
            default:
                $this->setLayoutTemplate('user_layout_tpl.php');
                $this->emailList($this->userId,'mail');
                return 'list_email_tpl.php';
        }
    }

    /**
    * This is a method to display a list of incoming emails.
    * It calls the function listmail in the objMail class
    * and then calls setVar() to make the array available to the templates.
    * @author James Scoble
    * @param string $userId
    * @param string $filter
    */
    function emailList($userId=NULL,$filter=NULL)
    {
        if (!$userId)
        {
            $userId=$this->userId;
        }
        $order=$this->getParam('messageorder');
        $emailsFound=$this->objMail->listMail($userId,$filter,$order);
        $this->setVar('emailList',$emailsFound);
        return $emailsFound;
    }
    
    /**
    * This is a method to count up the email in different folders.
    * @author James Scoble
    * @param array $emailsFound - the list returned by $this->emailList
    * @returns array $count
    */
    function emailCount($emailsFound)
    {
        $count=array('new'=>0,'old'=>0,'trash'=>'0','mail'=>'0');
        $totalNotTrash=0;
        foreach ($emailsFound as $line)
        {
            $folder=$line['folder'];
            if (isset($count[$folder])){
                $count[$folder]=$count[$folder]+1;
            } else {
                $count[$folder]=1;
            }
            if (($folder!='trash')&&($folder!='sent')){
                $totalNotTrash++;
            }
        }
        $count['all']=$totalNotTrash;
        return $count;
    }
    
    /**
    * This is a method to get email contents for displaying on the template
    * calls the function readMail() in class objMail
    * @author James Scoble
    */
    function emailRead($messageId=NULL)
    {
        // first we get the emailId
        if ($messageId==NULL){
                $messageId=$this->getParam('emailId');
        }
        
        // then we get all the info from the database
        $data=$this->objMail->readMail($messageId);
        $email['from']=$this->objMail->lookupUser($data['sender_id'],'username');
        $email['name']=$this->objMail->lookupUser($data['sender_id'],'fullname');
        $email['subject']=$data['subject'];
        $email['message']=str_replace("\n","<br />\n",stripslashes($data['message']));
        $email['email_id']=$data['email_id'];
        $email['date_sent']=$data['date_sent'];
        $email['folder']=$data['folder'];

        // now we check for attachments
        $handle=$data['email_attach'];
        $attach=array();
        $fileList=$this->objAttach->listFiles($handle);
        if (count($fileList)>0){
            foreach ($fileList as $line)
            {
                $temp['name']=$line['filename'];
                $temp['fileId']=$line['fileId'];
                $temp['size']=$line['size'];
                $attach[]=$temp;
            }
        }
        $email['attach']=$attach;

        // and we send all this info to the template by an array
        $this->setVar('email',$email);
        
        // next we mark a new email as read.
        if ($data['date_read']==0) {
            $this->objMail->setReadStatus($messageId,date('Y-m-d H:i:s'));
        }
        if ($data['folder']=='new'){
            $this->objMail->moveEmail($messageId,'old');
        }
    }


    /**
    * This is wrapper method to delete an email
    * It calls the function readMail() in class objMail to check if the logged-in user
    * actually owns the email to be deleted, then if so calls the deleteMail() function.
    * no parameters - it gets its info from the getParam() function call.
    * @author James Scoble
    * @returns string $nextEmail the next email message to view, if there is one.
    * @param strong $emailId  the unique id of the message to be deleted
    */
    function emailDelete($emailId=NULL)
    {
        if ($emailId==NULL){
            $emailId=$this->getParam('emailId',NULL);
        }
        if ($emailId)
        {
            $email=$this->objMail->readMail($emailId);
            $nextEmail=$this->objMail->getPrevious($email['email_id'],$this->userId,$email['date_sent'],$this->userfolder);
            if ($email['user_id']==$this->userId)
            {
                switch ($email['folder'])
                {
                    case 'trash':
                        $this->objMail->deleteMail($emailId);
                        $handle=$email['email_attach'];
                        if ($handle!=NULL){
                           $this->objAttach->deleteAll($handle,$emailId); 
                        }
                        break;
                    default:
                        $this->objMail->moveEmail($emailId,'trash');
                }
            }
            if ($nextEmail==$email['email_id']){
                return NULL;
            } else {
                return $nextEmail;
            }
        }
    } // end of function


    /**
    * This is a method to prepare the message to send.
    * To actually send, it calls the function sendMail() in class objMail
    * @author James Scoble
    */
    function emailSend($emailArray=NULL,$emailRecipients=NULL)
    {
        // First we get the data for the message.
        if ($emailArray==NULL){
            $rlist=$this->getParam('to');
            $subject=$this->getParam('subject');
            $message=$this->getParam('messagetext');
            $emailId=$this->getParam('emailId');
            $from=$this->userId;
        } else {
            $rlist=$emailArray['to'];
            $subject=$emailArray['subject'];
            $message=$emailArray['messagetext'];
            $emailId=$emailArray['emailId'];
            $from=$emailArray['userId'];
        }

        // Next we get an array of the addresses.
        $recipients=$this->lookup($rlist);
        
        // File attachments are handled here, if there are any.
        $this->objTempFiles=&$this->getObject('fileupload');
        $this->objAttach=&$this->getObject('emailfiles');
        $attachments=$this->objTempFiles->listFiles($emailId);
        $countAttach=count($attachments);
        if ($countAttach>0){
            $handle=$emailId;
        } else {
            $handle=NULL;
        }
        
        // Now we loop through all the recipients, sending a copy of the message to each one
        $copiesSent=0;
        foreach ($recipients as $username=>$sendToUserId)
        {
            if ($sendToUserId) {
                // This is the function call to the objMail class that actually sends the message
                $messageId=$this->objMail->sendMail($from,$sendToUserId,$subject,$message,$handle);
                $copiesSent=$copiesSent+1;
            } else if (strstr($username,"@")){
                // External email address
                $this->objMail->sendtoEmail($from,$username,$subject,$message,$attachments);
            } else { 
                // If the address used was invalid, we send a message back to the sending user:
                $errorSubject=$this->objLanguage->languageText('mod_email_invalid_user','Mail Delivery Error - invalid user');
                $errorMessage=$this->objLanguage->languageText('mod_email_not_sent','This email could not be delivered to user ');
                $errorMessage.=" '".$username."' ";
                $errorMessage.=$this->objLanguage->languageText('mod_email_no_such_user',' as there is no such user on the system.');
                $errorMessage.="\n".$subject."\n".$message;
                $this->objMail->sendMail('KNG',$from,$errorSubject,$errorMessage);
            }
        }
        if ($countAttach>0){  // if there are attachments
            if ($copiesSent>0){  // and if any emails actually got sent
                $this->objAttach->useArray($emailId,$attachments); // then we add those attachments
            } else {
                // Delete the attachments, since we no longer need them
                foreach($attachments as $line)
                {
                    $this->objTempFiles->eraseFile($line['fileId'],$emailId);
                }

            }
            // Discard the entries from the temporary file table
            $this->objTempFiles->dropEmail($emailId); 
        }
    }

    /**
    * This function will determine which email is "previous", and return its emailId
    * @param string $emailId
    * @returns string $emailId
    */
    function getPrevious($emailId)
    {
        $dt=$this->getParam('dateInfo');    
        $list=$this->objMail->getArray("select email_id from tbl_email where user_id='".$this->userId."' and date_sent<'".$dt."' order by date_sent");
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
    * @returns string $emailId
    */
    function getNext($emailId)
    {
        $dt=$this->getParam('dateInfo');    
        $list=$this->objMail->getArray("select email_id from tbl_email where user_id='".$this->userId."' and date_sent>'".$dt."' order by date_sent");
        if (isset($list[0])){
            return $list[0]['email_id'];
        } else {
            return $emailId;
        }
    }


    /**
    * This is a method to sort the username from the name
    * added 10 March 2005
    * @param string $line
    * @returns string $username
    */
    function getUserName($line)
    {
         $breakup=split("<|>",$line);
         $substrings=count($breakup);
         if ($substrings==1)
         {
             return $breakup[0];
         } else {
             return $breakup[1];
         }
    }
    
    /**
    * This is a method to get a userId from a username
    * calls the function getuserId() in class users
    * @author James Scoble
    * @param array $addrs
    * @returns array $return_list
    */
    function lookup($addrs)
    {
        $list=explode(',',$addrs);
        $return_list=array();
        foreach ($list as $line)
        {
            if (trim($line)!=''){
                $recipId=$this->objUser->getuserId($this->getUserName(trim($line)));
                $return_list[$line]=$recipId;
            }
        }
        return $return_list;
    }


    /**
    * This is a method to display email recipient's usernames, firstnames, and surnames.
    * It calls the function getAll() in class users.
    * @author James Scoble
    * @param string $field the filter to use
    */
    function emailUserList($field='listall')
    {
        // Here we detect if the special filters are being applied
        $objFilters=&$this->getObject('emailfilters','email');
        switch ($field)
        {
            // The filter for users in this context
            case 'contextusers':
                $contextword=$this->objLanguage->languageText('mod_context_context','Context');
                $str="<b>".$this->objLanguage->code2Txt('mod_email_filter1',array('CONTEXT'=>$contextword))."</b><br />\n";
                $str.=$objFilters->showUserLinks($objFilters->getContextUsers());
                return $str;
            // The filter to show "buddies"
            case 'buddies':
                $str="<b>".$this->objLanguage->languageText('mod_email_filter2')."<b><br />\n";
                $str.=$objFilters->showUserLinks($objFilters->getBuddies($this->userId));
                return $str;
            // The filter to show users recently emailed to or from
            case 'recent':
                $str="<b>".$this->objLanguage->languageText('mod_email_filter3')."</b><br />\n";
                $str.=$objFilters->showUserLinks($objFilters->getRecentContacts($this->userId));
                return $str;
            default:
        }
        
        // If the userfield is "listall" it means show ALL users, so we don't apply a filter at all
        if ($field!='listall')
        {
            $where=" where firstname like '".$field."%' ";
        } else {
            $where='';
        }
        $str=''; // Here we start with a blank string
        if ($field!='listall'){
            $str.="<b>".str_replace('{LETTER}',$field,$this->objLanguage->languageText('mod_email_letter'))."</b>\n";
        }
        //$str=$this->emailUserMenu();
        $str.="<hr \>\n";
        
        $ulist=$this->objUser->getAll($where.' order by firstname');
        $str.= "<b>".$this->objLanguage->languageText('word_select','Select Recipients').":</b><br />\n";
        $count=0;
        // Here we build up the HTML/JavaScript links for the template
        foreach ($ulist as $line)
        {   
            $username=$line['username'];
            $fullname=str_replace('\'','',$line['firstName']." ".$line['surname']);
            $fullname=str_replace('\\','',$fullname);
            $str.= "<a name='".$username."' href='javascript:sendEmailTo(\"".$fullname." <".$username.">\");' onMouseOver=\"window.status='".addslashes($fullname)."';\"; onMouseOut=\"window.status=''; \";>".$fullname."</a><br>\n";
            $count=$count+1;
        }
        if ($count==0)
        {
            $str.= "<b>".$this->objLanguage->languageText('mod_email_found_no_users','No users match search pattern').":</b><br />\n";
        }
        return $str;
    }

    /**
    * This is a method to display a list of alphabetic letters
    * which are links to show the users with surnames
    * beginning with that letter
    */
    function emailUserMenu()
    {
        $objAlpha=$this->newObject('alphabet','display');
        $link=$this->uri(array('action'=>'userlist','userfield'=>'LETTER'));
        $row=$objAlpha->putAlpha($link,TRUE,NULL,'userlist');
        $objFilters=&$this->getObject('emailfilters','email');
        $row.=$objFilters->showFilterLinks();
        return($row."\n");
    }
    
    /**
    * This is display-generating code to make the side-bar
    */
    function templatePanel()
    {
        $objTblclass=$this->newObject('htmltable','htmlelements');
        $objTblclass->width='80';
        $objTblclass->attributes=" border=0";
        $objTblclass->cellspacing='1';
        $objTblclass->cellpadding='1';
       
        $objIcons=$this->newObject('geticon','htmlelements');
        
        $objIcons->setIcon('email1');
        $sendtext=$this->objLanguage->languageText('mod_email_compose');
        $objIcons->alt=$sendtext;
        $objIcons->title=$objIcons->alt;
        $newIcon=$objIcons->show();
        $new="<a href='".$this->uri(array('action'=>'new'))."'>".$newIcon."</a>";
        $new2="<a href='".$this->uri(array('action'=>'new'))."'>".$sendtext."</a>";


        $objIcons->setIcon('mail_closed');
        $readnewtext=$this->objLanguage->languageText('mod_email_read_new');
        $objIcons->alt=$readnewtext;
        $objIcons->title=$objIcons->alt;
        $readNewIcon=$objIcons->show();

        $readnew="<a href='".$this->uri(array('action'=>'list','userfolder'=>'new'))."'>".$readNewIcon."</a>";
        $readnew2="<a href='".$this->uri(array('action'=>'list','userfolder'=>'new'))."'>".$readnewtext."</a>";

        $objIcons->setIcon('mail_open');
        $readoldtext=$this->objLanguage->languageText('mod_email_read_old');
        $objIcons->alt=$readoldtext;
        $objIcons->title=$objIcons->alt;
        $readOldIcon=$objIcons->show();

        $readold="<a href='".$this->uri(array('action'=>'list','userfolder'=>'old'))."'>".$readOldIcon."</a>";
        $readold2="<a href='".$this->uri(array('action'=>'list','userfolder'=>'old'))."'>".$readoldtext."</a>";

        $objIcons->setIcon('bigtrash');
        $readTrashText=$this->objLanguage->languageText('mod_email_read_trash');
        $objIcons->alt=$readTrashText;
        $objIcons->title=$objIcons->alt;
        $readTrashIcon=$objIcons->show();

        $readtrash="<a href='".$this->uri(array('action'=>'list','userfolder'=>'trash'))."'>".$readTrashIcon."</a>";
        $readtrash2="<a href='".$this->uri(array('action'=>'list','userfolder'=>'trash'))."'>".$readTrashText."</a>";
        
        
        $objIcons->setIcon('toolbar_mail');
        $objIcons->alt=$this->objLanguage->languageText('word_inbox');
        $objIcons->title=$objIcons->alt;
        $indexIcon=$objIcons->show();

        $index="<a href='".$this->uri(array('action'=>'index'))."'>".$indexIcon."</a>";
        $index2="<a href='".$this->uri(array('action'=>'index'))."'>".$objIcons->title."</a>";

        $objTblclass->addRow(array($index));
        $objTblclass->addRow(array($index2));
        $objTblclass->addRow(array($new));
        $objTblclass->addRow(array($new2));
        $objTblclass->addRow(array($readnew));
        $objTblclass->addRow(array($readnew2));
        $objTblclass->addRow(array($readold));
        $objTblclass->addRow(array($readold2));
        $objTblclass->addRow(array($readtrash));
        $objTblclass->addRow(array($readtrash2));

        return $objTblclass->show();
    }

    /**
    * This is a method to put the row of link icons on the page
    */
    function linkLine()
    {
        $objTblclass=$this->newObject('htmltable','htmlelements');
        $objTblclass->width='70%';
        $objTblclass->attributes=" border=0 align='center'";
        $objTblclass->cellspacing='1';
        $objTblclass->cellpadding='1'; 
        $objIcons=$this->newObject('geticon','htmlelements');
        

        $objIcons->setIcon('inbox');
        $readnewtext=$this->objLanguage->languageText('word_inbox');
        $objIcons->alt=$readnewtext;
        $objIcons->title=$objIcons->alt;
        $readNewIcon=$objIcons->show();
        $readnew="<a href='".$this->uri(array('userfolder'=>'mail'))."'>".$readNewIcon."</a>";
        $readnew2="<a href='".$this->uri(array('userfolder'=>'mail'))."'>".$readnewtext."</a>";

        $objIcons->setIcon('notes');
        $sendtext=$this->objLanguage->languageText('mod_email_compose');
        $objIcons->alt=$sendtext;
        $objIcons->title=$objIcons->alt;
        $newIcon=$objIcons->show();
        $new="<a href='".$this->uri(array('action'=>'new'))."'>".$newIcon."</a>";
        $new2="<a href='".$this->uri(array('action'=>'new'))."'>".$sendtext."</a>";

        $objIcons->setIcon('bigtrash');
        $readTrashText=$this->objLanguage->languageText('mod_email_read_trash');
        $objIcons->alt=$readTrashText;
        $objIcons->title=$objIcons->alt;
        $readTrashIcon=$objIcons->show();
        $readtrash="<a href='".$this->uri(array('action'=>'list','userfolder'=>'trash'))."'>".$readTrashIcon."</a>";
        $readtrash2="<a href='".$this->uri(array('action'=>'list','userfolder'=>'trash'))."'>".$readTrashText."</a>";
        
        $objTblclass->startRow();
        $objTblclass->addCell($readnew."&nbsp".$readnew2,"33%");
        $objTblclass->addCell($new."&nbsp".$new2 ,"33%");
        $objTblclass->addCell($readtrash."&nbsp".$readtrash2 ,"33%");
        $objTblclass->endRow();

        return '<br />'.$objTblclass->show();
    }
    
    /**
    * This is a method to put the module's title in the templates
    * @param $text the text to display
    */
    function pagetitle($text='mod_email_title')
    {
        if ($text==NULL){
            $text='mod_email_title';
        }
        $objTblclass=$this->newObject('htmltable','htmlelements');
        $objTblclass->width='99%';
        $objTblclass->attributes=" border=0 align='center'";
        $objTblclass->cellspacing='2';
        $objTblclass->cellpadding='2';
                                                                                                                                             
        $objTblclass->startRow();
        $objTblclass->addCell("<h1>".str_replace('{USER}',$this->objUser->fullname(),$this->objLanguage->languageText($text))."</h1>","", 
NULL, NULL, '',"align='left'");
        $objTblclass->endRow();
        return $objTblclass->show();
    }
    
    /**
    * This is a method to put the module's title in the templates
    * @depreciated  use pagetitle and pass the text you want used
    */
    function composetitle()
    {
        $objTblclass=$this->newObject('htmltable','htmlelements');
        $objTblclass->width='99%';
        $objTblclass->attributes=" border=0 align='center' class='odd'";
        $objTblclass->cellspacing='2';
        $objTblclass->cellpadding='2';
                                                                                                                                             
        $objTblclass->startRow();
        $objTblclass->addCell("<h1>".$this->objLanguage->languageText('mod_email_compose')."</h1>","", 
NULL, NULL, 'odd',"align='center'");
        $objTblclass->endRow();
        return $objTblclass->show();
    }
    
}// end of class
