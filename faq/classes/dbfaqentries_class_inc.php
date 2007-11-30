<?php
/* ----------- data class extends dbTable for tbl_blog------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}

/**
* Model class for the table tbl_faq_entries
* @author Jeremy O'Connor
* @copyright 2004 University of the Western Cape
*/
class dbFaqEntries extends dbTable
{
    /**
    * Constructor method to define the table
    */
    function init() 
    {
        parent::init('tbl_faq_entries');
        //$this->USE_PREPARED_STATEMENTS=True;
    }
 
    /**
	* Insert a record
	* @param string $contextId The context ID
	* @param string $categoryId The category ID
	* @param string $question The question
	* @param string $answer The answer
	* @param string $userId The user ID
	* @param string $dateLastUpdated Date last updated
	*/
	function insertSingle($contextId, $categoryId, $index, $question, $answer, $userId, $dateLastUpdated)
	{
		//$array = $this->getArray("SELECT MAX(_index) AS _max FROM {$this->_tableName}");
	  $ins = $this->insert(array(
			'contextId'=>$contextId, 
			'categoryId'=>$categoryId, 
			'_index' => $index,
        	        'question' => $question,
        	        'answer' => $answer,
			'userId' => $userId,
			'dateLastUpdated' => strftime('%Y-%m-%d %H:%M:%S', $dateLastUpdated)
		));
		return $ins;	
	}
    
    function getEntries($contextId, $categoryId)
    {
        $sql = "SELECT id, question, answer FROM tbl_faq_entries WHERE contextId='" . $contextId . "'";
        return $this->getArray($sql);
    }

    /**
    * Return all records
	* @param string $contextId The context ID
	* @param string $categoryId The category ID
	* @return array The FAQ entries
    */
	function listAll($contextId, $categoryId)
	{
		//$sql = "SELECT id, question, answer FROM tbl_faq";
		//return $this->getArray($sql);
		if ($categoryId == "All Categories") {
			return $this->getAll("WHERE contextId='" . $contextId . "' ORDER BY _index");
		}
		else {
			return $this->getAll("WHERE contextId='" . $contextId . "' AND categoryId='" . $categoryId ."' ORDER BY _index");
		}
	}

	/**
	* Return a single record
	* @param string $id ID
	* @return array
	* @return array The FAQ entrry
	*/	
	function listSingle($id)
	{
		$sql = "SELECT * FROM tbl_faq_entries WHERE id = '" . $id . "'";
		return $this->getArray($sql);
		//return $this->getRow("id", $id);
	}

	/** 
	* Get the next index
	* @param string $contextId The context ID
	* @param string $categoryId The category ID
	* @return int The next index
	*/
	function getNextIndex($contextId, $categoryId)
	{
		$array = $this->getArray("SELECT MAX(_index) AS _max FROM {$this->_tableName} WHERE contextId='$contextId' AND categoryId='$categoryId'");
		return $array[0]['_max'] + 1;
	}
	

	/**
	* Update a record
	* @param string $id ID
	* @param string $question The question
	* @param string $answer The answer
	* @param string $userId The user ID
	* @param string $dateLastUpdated Date last updated
	*/
	function updateSingle($id, $index, $question, $answer, $category, $userId, $dateLastUpdated)
	{
		$this->update("id", $id, 
			array(
				'_index' => $index,
        		'question' => $question,
        		'answer' => $answer,
                'categoryId' => $category,
				'userId' => $userId,
				'dateLastUpdated' => strftime('%Y-%m-%d %H:%M:%S', $dateLastUpdated)
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
	}//
}
?>
