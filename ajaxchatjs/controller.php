<?php

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}
// end security check
                                                                                                                                             
/**
* Demo of Ajax within KEWL.NextGen
* @copyright 2005 KEWL.NextGen
* @author Tohir Solomons
*/
class ajaxchatjs extends controller
{

    /**
    * Constructor
    */
    public function init()
    {
        $this->objChatMessages =& $this->getObject('dbchatmessages');
        $this->objUser =& $this->getObject('user', 'security');
        $this->objSmileyParser =& $this->getObject('parse4smileys', 'filters');
    }
    
    /**
    * Dispatch Method
    */
    function dispatch($action)
    {
        switch ($action)
        {
            case 'getlatestchat' : return $this->getLatestChat();
            case 'sendmessage' : return $this->saveNewMessage();
            default: return $this->chatHome();
        }
        
    }
    
    /**
    * Method to show the Chat Interface Page
    */
    private function chatHome()
    {
        // Return Process should be turned off when user enters the room
        $this->setSession('sendingPosts', FALSE);
        // Set Current Micro Time as start for posts
        $this->setSession('chattime', microtime(true)*100);
        
        // Notify the room that user has been added
        $message = '<strong><em>'.$this->objUser->fullname().' has entered room</em></strong>';
        $result = $this->objChatMessages->addMessage($message, 1);
        
        // Send Message to Template as Start
        $this->setVar('message', $message.'<br />');
        
        return 'page.php';
    }
    
    /**
    * Method to get the latest chat messages
    */
    private function getLatestChat()
    {
        // Check that another return process is not in progress
        if ($this->getSession('sendingPosts') == FALSE) {
        
            // Get Latest Messages
            $messages = $this->objChatMessages->getChatMessages($this->getSession('chattime'));
            // Check if there are any latest messages
            if (count($messages) > 0) {
                
                // Start Return Process
                $this->setSession('sendingPosts', TRUE);
                
                // Loop through messages
                foreach ($messages as $message)
                {
                    // Parse for Smileys
                    echo $this->objSmileyParser->parse($message['message']).'<br />';
                    
                    // Set Time of Last Post as Flag for next round of latest messages
                    $this->setSession('chattime', $message['chatmicrotime']);
                }
                
                // Turn off Return Process
                $this->setSession('sendingPosts', FALSE);
            }
        }
    }
    
    /**
    * Method to save a new post
    */
    private function saveNewMessage()
    {
        // Get Message, and clean up
        $message = stripslashes($this->getParam('message'));
        
        // Only add Message if not empty
        if (trim($message) != '') {
            $message = '<span style="font-family:'.$this->getParam('font').'; font-size:'.$this->getParam('fontsize').'pt; color:'.$this->getParam('fontcolor').'">'.htmlentities($message).'</span>';
        
            // Add User Details to Chat
            $message = '<strong>'.$this->objUser->fullname().'</strong>: '.$message;
            
            // Save Message
            $postTime = $this->objChatMessages->addMessage($message, $this->objUser->userId());
            
            // Return Updated Posts
            return $this->getLatestChat();
        }
    }
    
    

}

?>