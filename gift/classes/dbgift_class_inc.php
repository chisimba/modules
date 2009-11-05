<?php
class dbgift extends dbtable {
/**
 * Assign the table name in dbtable to be the table specified below
 */
    public function init() {
        parent::init("tbl_gifttable");
        $this->objUser     = $this->getObject("user","security");
    }

    /**
     * Submitted information from the Add Gift form is saved as a new record
     * in the database.
     * @param string $donor
     * @param string $recipient
     * @param string $giftname
     * @param blob $description
     * @param int $value
     * @param boolean $listed
     * @return boolean
     */
    public function addInfo($donor,$recipient,$giftname,$description,$value,$listed) {
        $data=array("donor"=>$donor,"recipient"=>$recipient,"giftname"=>$giftname,"description"=>$description,"value"=>$value,"listed"=>$listed);
        $result = $this->insert($data);
        return $result;
    }

    /**
     * Edited, submitted information from the Edit Gift form is updated
     * in the database under the correct row.
     * @param string $donor
     * @param string $recipient
     * @param string $giftname
     * @param blob $description
     * @param int $value
     * @param boolean $listed
     * @param string $id
     * @return boolean
     */
    public function updateInfo($donor,$recipient,$giftname,$description,$value,$listed,$id) {
        $data=array("donor"=>$donor,"recipient"=>$recipient,"giftname"=>$giftname,"description"=>$description,"value"=>$value,"listed"=>$listed);
		
        $result = $this->update('id',$id,$data);
        return $result;
    }

    /**
     * Get the array associated with a specific query.
     * @param string $qry
     * @return array
     */
    public function getInfo($qry) {
        $data = $this->getArray($qry);
        return $data;
    }

    /**
     * Replaces the archived status value with the opposite value.
     * (i.e. if listed is 0, then listed becomes 1 and vice versa)
     * Used to archive non-archived gifts and unarchive archived gifts.
     * @param string $id
     * @return boolean
     */
    public function archive($id) {
        $listed = !$this->_getListedValue($id);
        $data['listed'] = $listed;
        $result = $this->update('id',$id,$data);
        return $result;
    }


    public function checkDuplicates($data) {
        $qry = "SELECT * FROM tbl_gifttable WHERE
                donor = '{$data['donor']}',
                recipient = '{$data['recipient']}',
                giftname = '{$data['giftname']}',
                description = '{$data['description']}'
                value = '{$data['value']}'";
        $info = $this->getArray(qry);
        return count($info);
    }

    /**
     * Used in conjunction with archive to get the value of the field "listed"
     * so that the value can be altered.
     * @param string $id
     * @return int;
     */
    private function _getListedValue($id) {
        $result = $this->getRow('id',$id);
        return $result['listed'];
    }

    public function getGifts() {
        $sql="select * from tbl_gifttable";//." where userid = '".$userid."'";
        $rows=$this->getArray($sql);
        return $rows;
    }

    public function getNumberOfGifts() {
        return $this->getRecordCount();
    }

    function getMyGifts() {
        $recipient = $this->objUser->fullname();     // Recipient name
		$query = $_POST['query'];
        $qry = "SELECT * FROM tbl_gifttable WHERE recipient = '$recipient'";
    	if (isset($query)){
   			 $qry .= " AND (giftname LIKE '%".addslashes($query)."%' )";
   			 echo "<h1>".$qry."</h1";
  		}
        $data = $this->getInfo($qry);

        return $data;
    }


}
?>
