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
    * @var string $table: The name of the main database table to be affected
    * @access private
    */
    private $table;

    /**
    * @var object $objUser: The user class of the security module
    * @access private
    */
    private $objUser;
     
    /**
    * @var string $userId: The user id of the current logged in user
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

        // system classes
        $this->objUser = $this->getObject('user', 'security');
        $this->userId = $this->objUser->userId(); 
    }

    /**
    * Method to add a user to the banned user list
    *
    * @access public
    * @param array $banData: An array containing the banned user data
    * @return string $bannedId: The id of the banned user record
    **/
    public function addUser($banData)
    {
        $fields['room_id'] = $banData['room_id'];
        $fields['user_id'] = $banData['user_id'];        
        $fields['ban_reason'] = $banData['ban_reason'];        
        $fields['ban_type'] = $banData['ban_type'];
        if($banData['ban_type'] != 1){
            $fields['ban_start'] = date('Y-m-d H:i:s');        
            $fields['ban_stop'] = date('Y-m-d H:i:s', strtotime('+ '.$banData['ban_length'].' min'));        
        }        
        $fields['creator_id'] = $this->userId;
        $fields['updated'] = date('Y-m-d H:i:s');
                
        $isBanned = $this->isBanned($banData['user_id'], $banData['room_id']);
        if($isBanned == FALSE){
            $bannedId = $this->insert($fields);
            return $bannedId;
        }
    }

    /**
    * Method to return a banned user
    *
    * @access public
    * @param string $bannedId: The id of the banned user record
    * @return array $data: The user data
    **/
    public function getUser($bannedId)
    {
        $sql = "SELECT * FROM ".$this->table;
        $sql .= " WHERE id = '".$bannedId."'";
        $data = $this->getArray($sql);
        if(!empty($data)){
            return $data[0];
        }
        return FALSE;
    }

    /**
    * Method for deleting a banned user record
    *
    * @access public
    * @param string $bannedId: The id of the banned user record to be deleted
    * @return
    */
    public function deleteUser($bannedId)
    {
        $this->delete('id', $bannedId);
    }
    
    /**
    * Method for deleting all banned records for chat room
    *
    * @access public
    * @param string $roomId: The id of the chat room to delete records from
    * @return
    */
    public function deleteUsers($roomId)
    {
        $this->delete('room_id', $roomId);
    }
    
    /** 
    * Method to list banned users of a chat room
    *
    * @access public
    * @param array $roomId: The id of the room to list banned users for
    * @return array $data: The banned user list
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

    /** 
    * Method to check if a user is banned from a chat room
    *
    * @access public
    * @param array $userId: The id of the user to check
    * @param array $roomId: The id of the chat room to check
    * @return array $data: The banned user data
    */
    public function isBanned($userId, $roomId)
    {
        $sql = "SELECT * FROM ".$this->table;
        $sql .= " WHERE user_id = '".$userId."'";
        $sql .= " AND room_id = '".$roomId."'";
        $data = $this->getArray($sql);
        if(!empty($data)){
            return $data[0];
        }
        return FALSE;
    }
}
?>