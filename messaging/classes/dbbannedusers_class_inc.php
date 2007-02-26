<?php
/* ----------- data class extends dbTable for tbl_messaging_banned ----------*/

// security check - must be included in all scripts
if(!$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}

/**
* Model class for the table tbl_messaging_rooms
* @author Kevin Cyster
*/

class dbbannedusers extends dbTable
{
    /**
    * @var string $table The name of the database table to be affected
    * @access private
    */
    private $table;

    /**
    * @var object $objUser The user class of the security module
    * @access private
    */
    private $objUser;
     
    /**
    * @var string $userId The user id of the current logged in user
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
        parent::init('tbl_messaging_banned');
        $this->table = 'tbl_messaging_banned';

        $this->objUser = $this->getObject('user', 'security');
        $this->userId = $this->objUser->userId(); 
    }

    /**
    * Method to add a user to the banned user list
    *
    * @access public
    * @param array $userData An array containing the banned user data
    * @return string $bannedId The id of the banned user record
    **/
    public function addUser($userData)
    {
        $fields['room_id'] = $userData['room_id'];
        $fields['user_id'] = $userData['user_id'];        
        $fields['ban_type'] = $userData['ban_type'];
        if($userData['ban_type'] != 1){
            $fields['ban_start'] = date('Y-m-d H:i:s');        
            $fields['ban_stop'] = date('Y-m-d H:i:s', strtotime('+ '.$userData['ban_length'].' min'));        
        }        
        $fields['creator_id'] = $this->userId;
        $fields['updated'] = date('Y-m-d H:i:s');        
        $bannedId = $this->insert($fields);
        return $bannedId;
    }

    /**
    * Method to return a user
    *
    * @access public
    * @param string $roomId The id of the chat room
    * @param string $userId The id of the user
    * @return array $data The user data
    **/
    public function getUser($roomId, $userId)
    {
        $sql = "SELECT * FROM ".$this->table;
        $sql .= " WHERE room_id = '".$roomId."'";
        $sql .= " AND user_id = '".$userId."'";
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
    * @param string $bannedId  The id of the banned user record to be deleted
    * @return
    */
    public function deleteUser($bannedId)
    {
        $this->delete('id', $bannedId);
    }
    
    /**
    * Method for deleting all banned chat room users
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
    * Method to list the users in the chat room the current user is in
    *
    * @access public
    * @param array $roomId The id of the room to list banned users for
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