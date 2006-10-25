<?php
/* ----------- data class extends dbTable for tbl_blog------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}


/**
* Model class for the table tbl_faq_categories
* @author Jeremy O'Connor
* @copyright 2004 University of the Western Cape
*/
class dbFaqCategories extends dbTable
{
    /**
    * Constructor method to define the table
    */
    function init() 
    {
        parent::init('tbl_faq_categories');
        //$this->USE_PREPARED_STATEMENTS=True;
    }

    /**
    * Return all records
	* @param string $contextId The context ID
	* @return array The categories
    */
	function listAll($contextId)
	{
		//$sql = "SELECT id, question, answer FROM tbl_faq";
		//return $this->getArray($sql);
		return $this->getAll(
            "WHERE contextid='" . $contextId . "'
            ORDER BY categoryid");
	}

	/**
	* Return a single record
	* @param string $contextId The context ID
	* @param string $categoryId The category ID
	* @return array The category
	*/	
	function listSingle($contextId, $categoryId)
	{
		$sql = "SELECT * FROM tbl_faq_categories 
		WHERE contextId = '" . $contextId . "' 
		AND categoryId='" . $categoryId . "'";
		return $this->getArray($sql);
		//return $this->getRow("id", $id);
	}

	/**
	* Return a single record from the id
	* @param string $id The ID
	* @return array The category
	*/	
	function listSingleId($id)
	{
		$sql = "SELECT * FROM tbl_faq_categories 
		WHERE id = '" . $id . "'";
		return $this->getArray($sql);
		//return $this->getRow("id", $id);
	}

	/**
	* Insert a record
	* @param string $contextId The context ID
	* @param string $categoryId The category ID
	* @param string $userId The user ID
	* @param string $dateLastUpdated Date last updated
	*/
	function insertSingle($contextId, $categoryId, $userId, $dateLastUpdated)
	{
		$this->insert(array(
			'contextid' => $contextId, 
			'categoryid' => $categoryId,
			'userid' => $userId,
			'datelastupdated' => strftime('%Y-%m-%d %H:%M:%S', $dateLastUpdated)
			));
		return;	
	}
	/**
	* Update a record
	* @param string $id ID
	* @param string $categoryId The category
	* @param string $userId The user ID
	* @param string $dateLastUpdated Date last updated
	*/
	function updateSingle($id, $categoryId, $userId, $dateLastUpdated)
	{
		$this->update("id", $id, 
			array(
        		'categoryid' => $categoryId,
				'userid' => $userId,
				'datelastupdated' => strftime('%Y-%m-%d %H:%M:%S', $dateLastUpdated)
			)
		);
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
