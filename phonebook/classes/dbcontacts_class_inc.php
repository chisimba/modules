<?php
/* ----------- data class extends dbTable for tbl_blog------------*/// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
    {
        die("You cannot view this page directly");
    }


/**
* Model class for the table tbl_phonebook
* @author 
* @copyright 2007 University of the Western Cape
*/
class dbPhonebook extends dbTable
{
    /**
    * Constructor method to define the table
    */
    public function init() 
    {
        parent::init('tbl_phonebook');
        //$this->USE_PREPARED_STATEMENTS=True;
    }


    /**
        * Return all records
	* @param string $userId The user ID
	* @return array The contacts for a user
    */
	public function listAll($userId)
	{
		$sql = "SELECT 
            tbl_phonebook.contactid AS contactid, 
            tbl_phonebook.isContact, 
            tbl_phonebook.firstName, 
            tbl_phonebook.surname, 
            tbl_phonebook.emailAddress 
            tbl_phonebook.cellnumber
            tbl_phonebook.landlinenumber
        FROM tbl_phonebook, tbl_users
		WHERE (tbl_phonebook.userid = '" . $userId . "')
		AND (tbl_phonebook.contactid = tbl_users.userid)
        ORDER BY tbl_users.firstName, tbl_users.surname";
		return $this->getArray($sql);
	}


	/**
	* Insert a record
	* @param string $userId The user ID
	* @param string $contactId The contact ID
	*/
	public function insertSingle($userId, $contactId)
	{
        $list = $this->listSingle($userId, $contactId);
        if (empty($list)) {
    		$this->insert(array(
    			'userid' => $userId,
    			'contactid' => $contactId,
                'isContact' => '1',
    		));
        }
        else {
            $this->update(
                'id',
                $list[0]['id'],
                array(
                    'isContact' => '1'
                )
            );
        };
        $list = $this->listSingle($contactId, $userId);
        if (empty($list)) {
    		$this->insert(array(
    			'userid' => $buddyId,
    			'contactid' => $userId,
                'isContact' => '0',
    		));
        }
        else {
            $this->update(
                'id',
                $list[0]['id'],
                array(
                    'isContact' => '1'
                )
            );
        };
		return;	
	}

	/**
	* Deletes records
	* @param string $userId The user ID
	* @param string $contactId The contact ID
	*/
	public function deleteSingle($userId, $contactId)
	{
        $list = $this->listSingle($buddyId, $userId);
        $this->update(
            'id',
            $list[0]['id'],
            array(
                'isContact' => '0'
            )
        );
        if ($list[0]['isContact']=='0') {
            $this->delete(
                'id',
                $list[0]['id']
            );
        }
        return;
	}
}
?>
