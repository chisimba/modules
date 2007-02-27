<?php

/**
* Database Class to Store Chat Messages
*/
class dbchatmessages extends dbtable
{

    /**
    * Constructor
    */
    public function init()
    {
        parent::init('tbl_ajaxchatjs_messages');
    }
    
    /**
    * Method to add a message
    * @param string $message Chat Message
    * @param string $userid User Posting Message
    */
    public function addMessage($message, $userid='1')
    {
        $chatTime = strftime('%Y-%m-%d %H:%M:%S', mktime());
        $chatMicroTime = microtime(true)*100;
        $this->insert(array(
                'userid'   => $userid, 
                'message'  => $message, 
                'chattime' => $chatTime,
                'chatmicrotime' => $chatMicroTime
            ));
            
        return $chatMicroTime;
    }
    
    /**
    * Method to get the latest chat messages
    * @param int $microtime Microtime of last message displayed
    * @return array
    */
    public function getChatMessages($microtime)
    {
        return $this->getAll(' WHERE chatmicrotime > \''.$microtime.'\' ORDER BY chattime');
    }

}

?>