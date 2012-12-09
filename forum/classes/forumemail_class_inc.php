<?php

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}

/**
* Forum Email Class
*
* This class controls all functionality for sending emails from forum posts
*
* NB! At the moment, it only works for context forums, not lobby
*
* @author Tohir Solomons
* @copyright (c) 2004 University of the Western Cape
* @package forum
* @version 1
*/
class forumemail extends object
{

    /**
    * @var Array $emailList List of Email Addresses
    */
    var $emailList;
    
    /**
    * @var string $contextCode Context Code
    */
    var $contextCode;
    
    /**
    * Constructor
    */
    function init()
    {
        $this->contextGroups =& $this->getObject('managegroups', 'contextgroups');
        $this->objUser =& $this->getObject('user', 'security');
        $this->objLanguage =& $this->getObject('language', 'language'); 
    }
    
    /**
    * Method to set the Context Code to use
    * @param String $context Context Code
    */
    function setContextCode($context)
    {
        $this->contextCode = $context;
        $this->objMailer = $this->getObject('mailer', 'mail');
    }
    
    /**
    * Method to prepare the list of email addresses to use
    * At the moment, it takes the list of all users in a context.
    */
    function prepareListEmail($topic_id)
    {
        
        // Create an empty array for the email addresses
        $this->emailList = array();
        
        // Get Users Subscribed to Topic
        $objTopicSubscribers =& $this->getObject('dbtopicsubscriptions');
        $topicSubscribers = $objTopicSubscribers->getUsersSubscribedTopic($topic_id);
        
        // Add the Email to the array
        foreach ($topicSubscribers as $user)
        {
            array_push($this->emailList, $user['emailaddress']);
        }
        
        $objTopic =& $this->getObject('dbtopic');
        $topicDetails = $objTopic->listSingle($topic_id);
        
        // Get Users Subscribed to Forum
        $objForumSubscribers =& $this->getObject('dbforumsubscriptions');
        $forumSubscribers = $objForumSubscribers->getUsersSubscribedForum($topicDetails['forum_id']);
        
        // Add the Email to the array
        foreach ($forumSubscribers as $user)
        {
            array_push($this->emailList, $user['emailaddress']);
        }
        
        // Remove duplicate emails
        $this->emailList = array_unique($this->emailList); 
    }
    
    /**
    * Method to send an email to the users
    * @param String $topic_id Record Id of the Topic
    * @param String $title Title of the Post
    * @param String $text Text of the Post
    * @param String $forum Name of the Forum
    * @param String $senderId Record Id of the Sender
    * @param String $replyUrl Url for the User to Reply to
    */
    function sendEmail($topic_id, $title, $text, $forum, $senderId, $replyUrl)
    {
        $this->prepareListEmail($topic_id);
        
        // Only bother to send emails if there are more than one user.
        if (count($this->emailList) > 0) {
            $this->loadClass('link', 'htmlelements');
            
            $subject = '['.$forum.'] '.$title;
            $name = 'Not Needed';
            
            $line1 = $this->objLanguage->languageText('mod_forum_emailtextline1', 'forum', '{NAME} has posted the following message to the {FORUM} discussion forum').':';
            $line1 = str_replace('{NAME}', $this->objUser->fullname($senderId), $line1); 
            $line1 = str_replace('{FORUM}', $forum, $line1);
            
            //Convert '&' to '&amp;'
            $replyUrl = str_replace('&', '&amp;', $replyUrl);
            
            // Create a link
            $replyLink = new link($replyUrl);
            $replyLink->link = $replyUrl;
            
            $line2 = $this->objLanguage->languageText('mod_forum_emailtextline2', 'forum', 'To reply to this message, go to: {URL}');
            $line2 = str_replace('{URL}', $replyLink->show(), $line2); 
            
            $message = '------------------------------------------------<br />'."\r\n";
            $message .= $title."<br />\r\n";
            $message .= ucfirst($this->objLanguage->languageText('word_by', 'forum', 'By')).' '.$this->objUser->fullname($senderId)."<br />\r\n";
            $message .= '------------------------------------------------<br />'."\r\n";
            //$message .= '<p>'.$line1.'</p>'."\r\n\r\n";
            $message .= str_replace('&nbsp;', ' ', $text)."\r\n\r\n";
            $message .= '<hr />'."\r\n\r\n";
            $message .= '<p>'.$line2.'</p>'."\r\n\r\n";
            
            $body = '<html><head></head><body>'.$message.'</body></html>';
            
            $from = $senderId.'@'.$_SERVER['SERVER_NAME'];
            $fromName = $this->objUser->fullname($senderId);
            
            // Setup Alternate Message - Convert '&amp;' back to '&'
            $altMessage = str_replace('&amp;', '&', $message); 
            
            // Add alternative message - same version minus html tags
            $messagePlain = strip_tags($altMessage);
            $this->objMailer->setValue('to', $from);
            $this->objMailer->setValue('bcc', $this->emailList);
            $this->objMailer->setValue('from', $from);
            $this->objMailer->setValue('fromName', $fromName);
            $this->objMailer->setValue('subject', $subject);
            $this->objMailer->setValue('useHTMLMail', TRUE);
            $this->objMailer->setValue('body', $messagePlain);
            $this->objMailer->setValue('htmlbody', $message);
            return $this->objMailer->send();
        } else {
            return NULL;
        }
   
    }

}
?>