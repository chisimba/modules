<?php
/* ----------- data class extends dbTable for tbl_messaging_userlog ----------*/

// security check - must be included in all scripts
if(!$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}

/**
* Model class for the table tbl_messaging_userlog
* @author Kevin Cyster
*/

class dbuserlog extends dbTable
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
    * @var string $tblBanned: The name of an additional database table to be affected
    * @access private
    */
    private $tblBanned;

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
        $this->tblUsers = 'tbl_users';
        $this->tblBanned = 'tbl_messaging_banned';
    }

    /**
    * Method to get a user in a chat room
    *
    * @access public
    * @param string $roomId: The id of the chat room to get the user from
    * @param string $userId: The id of the user to get from the chat room
    * @return array $data: The chat room user data
    */
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
    * Method to add a user to the list of users using a chat room
    *
    * @access public
    * @param string $roomId: The id of the chat room the user is entering
    * @param string $userId: The id of the user entering the chat room 
    * @return string $userLogId: The id of the user log that was added
    **/
    public function addUser($roomId, $userId)
    {
        $data = $this->getUser($roomId, $userId);
        if($data == FALSE){
            $fields['room_id'] = $roomId;
            $fields['user_id'] = $userId;        
            $userLogId = $this->insert($fields);
            return $userLogId;
        }else{
            $userLogId = $data['id'];
            $fields['room_id'] = $roomId;
            $fields['user_id'] = $userId;
            $this->update('id', $userLogId, $fields);
            return $userLogId;      
        }
    }

    /**
    * Method for deleting a user from all chat rooms
    *
    * @access public
    * @param string $userId: The id of the user to be deleted from all chat rooms
    * @return
    */
    public function deleteUser($userId)
    {
        $this->delete('user_id', $userId);
    }
    
    /**
    * Method for deleting all users in a chat room
    *
    * @access public
    * @param string $roomId: The id of the chat room to delete users from
    * @return
    */
    public function deleteUsers($roomId)
    {
        $this->delete('room_id', $roomId);
    }
    
    /** 
    * Method to list the users in a chat room
    *
    * @access public
    * @param string $roomId:  The id of the chat room to list users for
    * @return array $data: The chat room user list
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
    * Method to search for users in a chat room
    *
    * @access public
    * @param string $roomId: The id of the chat room to list users for
    * @param string $option: The field to search - firstname|surname
    * @param string $value: The field value to search for
    * @return array $data: The chat room user list
    */
    public function searchUsers($roomId, $option, $value)
    {
        $sql = "SELECT * FROM ".$this->table;
        $sql .= " WHERE room_id = '".$roomId."'";
        $logData = $this->getArray($sql);
        if(!empty($logData)){
            $data = array();
            foreach($logData as $key=>$line){
                $sql = " SELECT * FROM ".$this->tblUsers;
                $sql .= " WHERE userid = '".$line['user_id']."'";
                $sql .= " AND ".$option." LIKE '".$value."%'";
                $usrData = $this->getArray($sql);
                if(!empty($usrData)){
                    $array = array_merge($line, $usrData[0]);
                    $sql = " SELECT * FROM ".$this->tblBanned;
                    $sql .= " WHERE user_id = '".$line['user_id']."'";
                    $sql .= " AND room_id = '".$roomId."'";
                    $bndData = $this->getArray($sql);
                    if(!empty($bndData)){
                        $array = array_merge($array, $bndData[0]);
                    }
                    $data[] = $array;
                }
            }
            return $data;
        }
        return array();
    }
}
?>