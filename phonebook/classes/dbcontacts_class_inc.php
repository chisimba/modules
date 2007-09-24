<?php
/* ----------- data class extends dbTable for tbl_blog------------*/// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
    {
        die("You cannot view this page directly");
    }


/**
* Model class for the table tbl_phonebook
* @author 

*/
class dbPhoneContact extends dbTable
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
    * Returns an array that contains contactId and Fullname 
    * ordered by first name where the person is a phonec contact, and 
    * nothing else in the array.
	* @param string $userId The user ID
	* @return array The phone details for a user
    */
	public function getContact($userId)
	{
		$sql = "SELECT 
            tbl_phonebook.contactId, 
            CONCAT(tbl_users.firstName, ' ', tbl_users.surname) AS Fullname
        FROM tbl_phoneboo, tbl_users
		WHERE 
		(tbl_buddies.buddyId = tbl_users.userId)
        AND (tbl_phonebook.userId = '" . $userId . "')k
        AND (tbl_phonebook.isContact = '1')
        ORDER BY Fullname";
		return $this->getArray($sql);
	}