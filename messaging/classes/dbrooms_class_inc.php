<?php
/* ----------- data class extends dbTable for tbl_messaging_rooms ----------*/

// security check - must be included in all scripts
if(!$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}

/**
* Model class for the table tbl_messaging_rooms
* @author Kevin Cyster
*/

class dbrooms extends dbTable
{
    /**
    * @var string $table: The name of the main database table to be affected
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
        parent::init('tbl_messaging_rooms');
        $this->table = 'tbl_messaging_rooms';
    }

    /**
    * Method to add a chat room
    *
    * @access public
    * @param array $roomData: An array containing the chat room data  
    * @return string $roomId: The id of the chat room that was added
    **/
    public function addRoom($roomData)
    {
        $fields['room_type'] = $roomData['room_type'];
        $fields['room_name'] = $roomData['room_name'];
        $fields['room_desc'] = $roomData['room_desc'];
        $fields['text_only'] = $roomData['text_only'];
        $fields['disabled'] = $roomData['disabled'];
        $fields['owner_id'] = $roomData['owner_id'];
        $fields['date_created'] = date('Y-m-d H:i:s');
        $fields['updated'] = date('Y-m-d H:i:s');
        $roomId = $this->insert($fields);
        return $roomId;
    }

    /**
    * Method for editing a room
    *
    * @access public
    * @param array $roomData: An array containing the chat room data  
    * @param string $roomId: The id of the chat room to edit
    * @return
    */
    public function editRoom($roomId, $roomData)
    {
        $fields['room_name'] = $roomData['room_name'];
        $fields['room_desc'] = $roomData['room_desc'];
        $fields['text_only'] = $roomData['text_only'];
        $fields['disabled'] = $roomData['disabled'];
        $fields['updated'] = date('Y-m-d H:i:s');
        $this->update('id', $roomId, $fields);
    }

    /**
    * Method to get a chat room
    *
    * @access public
    * @param string $roomId: The id of the room to get the data for
    * @return array $data: The chat room data
    **/
    public function getRoom($roomId)
    {
        $sql = "SELECT * FROM ".$this->table;
        $sql .= " WHERE id = '".$roomId."'";
        $data = $this->getArray($sql);
        if(!empty($data)){
            return $data[0];
        }
        return FALSE;
    }

    /**
    * Method to return all chat room records
    *
    * @access public
    * @param string $contextCode: The context code if the user is in context
    * @return array $data: The chat room data
    **/
    public function listRooms($contextCode)
    {
        $sql = "SELECT * FROM ".$this->table;
        if($contextCode != NULL){
            $sql .= " WHERE owner_id = '".$contextCode."'";
            $sql .= " OR room_type < 2";
        }else{
            $sql .= " WHERE room_type < 2";
        }
        $sql .= " ORDER BY 'date_created' ";
        $data = $this->getArray($sql);
        if(!empty($data)){
            return $data;
        }
        return FALSE;
    }

    /**
    * Method for deleting a chat room
    *
    * @access public
    * @param string $roomId:  The id of the chat room to be deleted
    * @return
    */
    public function deleteRoom($roomId)
    {
        $this->delete('id', $roomId);
    }
    
    /**
    * Method to get a context chat room
    * 
    * @access public
    * @param string $contextCode: The context code | Current contextCode if NULL
    * @return string $data: The context chat room data
    */
    public function getContextRoom($contextCode = NULL)
    {
        if($contextCode == NULL){
            $contextCode = $this->getSession('contextCode');
        }
        if($contextCode != NULL){
            $sql = "SELECT * FROM ".$this->table;
            $sql .= " WHERE owner_id = '".$contextCode."'";
            $data = $this->getArray($sql);
            if(!empty($data)){
                return $data[0];
            }
        }
        return FALSE;
    }    
}
?>