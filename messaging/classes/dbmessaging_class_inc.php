<?php
/* ----------- data class extends dbTable for tbl_messaging ----------*/

// security check - must be included in all scripts
if(!$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}

/**
* Model class for the table tbl_messaging_rooms
* @author Kevin Cyster
*/

class dbmessaging extends dbTable
{
    /**
    * @var string $table The name of the database table to be affected
    * @access private
    */
    private $table;

    /**
    * @var object $objUser The user class in the security module
    * @access private
    */
    private $objUser;

    /**
    * @var string $userId The user id of the current user
    * @access private
    */
    private $userId;

    /**
    * Method to construct the class
    *
    * @access public
    * @return
    */
    public function init()
    {
        parent::init('tbl_messaging');
        $this->table = 'tbl_messaging';
        
        $this->objUser = $this->getObject('user', 'security');
        $this->userId = $this->objUser->userId();
    }

    /**
    * Method to add a user to a chat room
    *
    * @access public
    * @param string $message The chat message that is being sent
    * @return string $messageId The id of the chat mesasge that was added
    **/
    public function addChatMessage($message, $system = FALSE)
    {   
        $roomId = $this->getSession('chat_room_id');
        $date = date('Y-m-d H:i:s');
        if($system){
            $fields['sender_id'] = 'system';
        }else{
            $fields['sender_id'] = $this->userId;            
        }
        $fields['message'] = $message;
        $fields['recipient_id'] = $roomId;
        $count = $this->getMessageCount($roomId);
        $fields['message_counter'] = $count + 1;
        $fields['date_created'] = $date;
        $fields['updated'] = $date;
        $messageId = $this->insert($fields);
        return $messageId;
    }

    /**
    * Method to return a chat messages that have not been displayed
    *
    * @access public
    * @param string $roomId The id of the room to get the data of
    * @return array $data The chat room user data
    **/
    public function getChatMessages($roomId, $counter)
    {
        $sql = "SELECT * FROM ".$this->table;
        $sql .= " WHERE recipient_id = '".$roomId."'";
        $sql .= " AND message_counter > '".$counter."'";
        $sql .= " ORDER BY message_counter";
        $data = $this->getArray($sql);
        if(!empty($data)){
            return $data;
        }
        return FALSE;
    }
    
    /**
    * Method to return the number of chat messages for the room
    *
    * @access public
    * @param string $roomId The id of the room to get the data of
    * @return string $messageCount
    **/
    public function getMessageCount($roomId)
    {
        $sql = "SELECT * FROM ".$this->table;
        $data = $this->getArray($sql);
        if(!empty($data)){
            return count($data);
        }
        return 0;
    }
}
?>