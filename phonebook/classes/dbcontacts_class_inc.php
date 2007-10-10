<?php
/* ----------- data class extends dbTable for tbl_blog------------*/// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
    {
        die("You cannot view this page directly");
    }


/**
* Model class for the table tbl_phonebook
* @author Jacques Cilliers <2618315@uwc.ac.za>  
* @copyright 2007 University of the Western Cape
*/
class dbContacts extends dbTable
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
        * Return all records in the tbl_phonebook.
      */ 

	public function listAll($userId)
	{
        //$this->_changeTable('tbl_users');
        $userrec = $this->getAll("WHERE userid = '$userId'");
        return $userrec;
	}

/*       private function _changeTable($tablename)
*        {
*        parent::init($tablename);
*        }
*/
	/**
	* Insert a record
	*Where do the variables come from? 
  */
   public function insertRecord($userid, $firstname, $lastname, $emailaddress, $cellnumber, $landlinenumber)
   {

      $this->objUser = $this -> getObject('user', 'security');	

      $arrayOfRecords = array('userid' =>$this ->objUser ->userId(), 'firstname' => $firstname, 'lastname' => $lastname, 'emailaddress' => $emailaddress, 'cellnumber' => $cellnumber, 'landlinenumber' => $landlinenumber, 'created_by' =>$this ->now());
      return $this->insert($arrayOfRecords, 'tbl_phonebook');
    }


	/**
	* Deletes records
	*/
	public function deleteRec($id)
	{
        return $this->delete('id', $id, 'tbl_phonebook');
	}

        /**
        * Update record
        */
        public function updateRec($id)
        {
        return $this->update('id', $id, $fields, 'tbl_phonebook');
        }
}
?>
