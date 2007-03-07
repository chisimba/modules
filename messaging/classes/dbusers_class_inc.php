<?php
/* ----------- data class extends dbTable for tbl_messaging_users ----------*/

// security check - must be included in all scripts
if(!$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}

/**
* Model class for the table tbl_messaging_rooms
* @author Kevin Cyster
*/

class dbusers extends dbTable
{
    /**
    * @var string $table: The name of the main database table to be affected
    * @access private
    */
    private $table;

    /**
    * @var string $tblUsers: The name of an additional database table to be affected
    * @access private
    */
    private $tblUsers;

    /**
    * @var object $objUser: The user class in the security module
    * @access private
    */
    private $objUser;

    /**
    * @var string $userId: The user id of the current user
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
        parent::init('tbl_messaging_users');
        $this->table = 'tbl_messaging_users';
        $this->tblUsers = 'tbl_users';
        
        // system classes
        $this->objUser = $this->getObject('user', 'security');
        $this->userId = $this->objUser->userId();
    }

    /**
    * Method to add a user to a private chat room
    *
    * @access public
    * @param string $roomId: The id of the chat room
    * @param string $userId: The id of the user
    * @return string $chatUserId: The id of the chat room user that was added
    **/
    public function addUser($roomId, $userId)
    {
        $fields['room_id'] = $roomId;
        $fields['user_id'] = $userId;
        $fields['creator_id'] = $this->userId;
        $fields['updated'] = date('Y-m-d H:i:s');
        $chatUserId = $this->insert($fields);
        return $chatUserId;
    }

    /**
    * Method to return a list of users for a private chat room
    *
    * @access public
    * @param string $roomId: The id of the room to get the data of
    * @return array $data: The chat room user data
    **/
    public function listRoomUsers($roomId)
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
    * Method to return a list of private chat rooms that the user is a member of
    *
    * @access public
    * @param string $userId: The id of the user to get the data of
    * @return array $data: The user chat roomdata
    **/
    public function listUserRooms($userId)
    {
        $sql = "SELECT * FROM ".$this->table;
        $sql .= " WHERE user_id = '".$userId."'";
        $data = $this->getArray($sql);
        if(!empty($data)){
            return $data;
        }
        return FALSE;
    }

    /**
    * Method for deleting a private chat room user
    *
    * @access public
    * @param string $userId: The id of the private chat room user to be deleted
    * @return
    */
    public function deleteUser($chatUserId)
    {
        $this->delete('id', $chatUserId);
    }

    /**
    * Method for deleting all users in a private chat room
    *
    * @access public
    * @param string $roomId: The id of the private chat room to delete users from
    * @return
    */
    public function deleteUsers($roomId)
    {
        $this->delete('room_id', $roomId);
    }

    /** 
    * Method to search for users in a chat room
    *
    * @access public
    * @param string $option: The field to search - firstname|surname
    * @param string $value: The field value to search for
    * @return array $array: The chat room user list
    */
    public function searchUsers($option, $value)
    {
        $sql = " SELECT * FROM ".$this->tblUsers;
        $sql .= " WHERE ".$option." LIKE '".$value."%'";
        $data = $this->getArray($sql);
        if(!empty($data)){
            if(count($data) > 20){
                $array = array_slice($data, 0, 20);   
            }else{
                $array = $data;
            }
            return $array;
        }
        return array();
    }
    
    /** 
    * Method to check if a user is a user of the chat room
    *
    * @access public
    * @param array $userId: The id of the user to check
    * @param array $roomId: The id of the chat room to check
    * @return array $data: The banned user data
    */
    public function getRoomUser($roomId, $userId)
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