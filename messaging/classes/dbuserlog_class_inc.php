<?php
/* ----------- data class extends dbTable for tbl_messaging_userlog ----------*/

// security check - must be included in all scripts
if(!$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}

/**
* Model class for the table tbl_messaging_rooms
* @author Kevin Cyster
*/

class dbuserlog extends dbTable
{
    /**
    * @var string $table The name of the database table to be affected
    * @access private
    */
    private $table;

    /**
    * Method to construct the class
    *
    * @access public
    * @return
    */
    public function init()
    {
        parent::init('tbl_messaging_userlog');
        $this->table = 'tbl_messaging_userlog';
    }

    /**
    * Method to add a user to the user log
    *
    * @access public
    * @param string $roomId The id of the chat room the user is entering
    * @param string $userId The id of the user entering the chat room 
    * @return string $userLogId The id of the user log that was added
    **/
    public function addUser($roomId, $userId)
    {
        $data = $this->getUser($userId);
        if($data == FALSE){
            $fields['room_id'] = $roomId;
            $fields['user_id'] = $userId;        
            $userLogId = $this->insert($fields);
            return $userLogId;
        }else{
            $fields['room_id'] = $roomId;
            $fields['user_id'] = $userId;
            $this->update('id', $data['id'], $fields);        
        }
    }

    /**
    * Method to return a user
    *
    * @access public
    * @param string $userId The id of the user
    * @return array $data The user data
    **/
    public function getUser($userId)
    {
        $sql = "SELECT * FROM ".$this->table;
        $sql .= " WHERE user_id = '".$userId."'";
        $data = $this->getArray($sql);
        if(!empty($data)){
            return $data[0];
        }
        return FALSE;
    }

    /**
    * Method for deleting a chat room user
    *
    * @access public
    * @param string $chatUserId  The id of the chat room user to be deleted
    * @return
    */
    public function deleteUser($userId)
    {
        $this->delete('user_id', $userId);
    }
    
    /**
    * Method for deleting all chat room users
    *
    * @access public
    * @param string $roomId  The id of the chat room to be deleted
    * @return
    */
    public function deleteUsers($roomId)
    {
        $this->delete('room_id', $roomId);
    }
    
    /** 
    * Method to list the users in the chat room
    *
    * @access public
    * @param string $roomId  The id of the chat room to list users for
    * @return array $data The chat room user list
    */
    public function listUsers($roomId)
    {
        $sql = "SELECT * FROM ".$this->table;
        $sql .= " WHERE room_id = '".$roomId."'";
        $data = $this->getArray($sql);
        if(!empty($data)){
            return $data;
        }
        return FALSE;
    }
}
?>