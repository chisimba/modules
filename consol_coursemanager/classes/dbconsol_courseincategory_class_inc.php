<?php
/* ----------- data class extends dbTable for tbl_blog------------*/// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
    {
        die("You cannot view this page directly");
    }


/**
* Model class for the table tbl_users
* @author Dean Van Niekerk
* @copyright 2004 University of the Western Cape
*/
class dbconsol_courseincategory extends dbTable
{
    /**
    * Constructor method to define the table
    */
    public function init() 
    {
        parent::init('tbl_consol_coursecatergories');
    }

    /**
    * Return all records
	 * @param string $tableId The table ID
	 * @return array The assessments for a table
    */
	 public function listAll($tableId)
	 {
		$sql = "SELECT * FROM tbl_consol_coursecatergories
		ORDER BY category_order";
		return $this->getArray($sql);
		//return $this->getAll();
	 }
	
	/**
	* Insert a record
	* @param string $tableId The table ID
	* @param integer $row The row
	* @param string $objective The objective
	*/
	function insertSingle($contextcode, $category, $course_order)
	{
		$this->insert(array(
        	'contextcode' => $contextcode,
        	'category' => $category,
        	'course_order' => $course_order
		));
		return;
	}
	
	/**
	* Delete a record
	* @param string $id ID
	*/
	function deleteSingle($id)
	{
		$this->delete("id", $id);
	}

}
?>