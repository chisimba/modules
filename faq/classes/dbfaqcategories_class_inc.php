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
            "WHERE contextId='" . $contextId . "'
            ORDER BY categoryId");
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
		AND id='" . $categoryId . "' LIMIT 1";
        
        $results = $this->getArray($sql);
        
        if (count($results) == 0) {
            return NULL;
        } else {
            return $results[0];
        }
    }
    
    /**
	* Return a single record
	* @param string $contextId The context ID
	* @param string $categoryId The category ID
	* @return array The category
	*/	
	function getNotCategorisedId($contextId)
	{
		$sql = 'SELECT * FROM tbl_faq_categories WHERE categoryId = "Not Categorised" AND userId = "admin" AND contextId = "'. $contextId.'"';
        $results = $this->getArray($sql);
        
        if (count($results) == 0) {
            return NULL;
        } else {
            return $results[0]['id'];
        }
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
			'contextId' => $contextId, 
			'categoryId' => $categoryId,
			'userId' => $userId,
			'dateLastUpdated' => strftime('%Y-%m-%d %H:%M:%S', $dateLastUpdated)
			
		));
		return;	
	}
}
?>