<?php
/* ----------- data class extends dbTable for ------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
    {
        die("You cannot view this page directly");
    }


/**
* Model class for the table tbl_phonebook
* @author 
*/

class dbcontacts extends dbTable
{
    /**
    * Constructor method to define the table
    */
    public function init() 
    {
        // initialize the table
        parent::init('tbl_phonebook');
    }

    /**
    * Returns an array that contains contactId and Fullname 
    *
    * ordered by first name where the person is a phonec contact, and 
    * nothing else in the array.
    *
    * @param string $userId The user ID
    * @return array The phone details for a user
    */
	public function getContact($userId)
	{
		$sql = "WHERE userid = '$userId'";
		return $this->getAll($sql);
	}
}
?>
