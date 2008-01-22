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
			'contextid'=>$contextId, 
			'categoryid'=>$categoryId, 
			'_index' => $index,
        	        'question' => $question,
        	        'answer' => $answer,
			'userid' => $userId,
			'dateLastUpdated' => strftime('%Y-%m-%d %H:%M:%S', $dateLastUpdated)
		));
		
		$this->objDbFaqCategories =& $this->getObject('dbfaqcategories');
		$categoryRow = $this->objDbFaqCategories->getRow('id', $categoryId);
		
		// Add to Search
        $objIndexData = $this->getObject('indexdata', 'lucene');
        
        // Prep Data
        $docId = 'faq_entry_'.$ins;
        $docDate = strftime('%Y-%m-%d %H:%M:%S', $dateLastUpdated);
        $url = $this->uri(array('action'=>'view', 'category'=>$categoryId), 'faq');
        $title = $categoryRow['categoryname'];
        $contents = $question.': '.$answer;
        $teaser = $question;
        $module = 'faq';
        $userId = $userId;
        
        // Add to Index
        $objIndexData->luceneIndex($docId, $docDate, $url, $title, $contents, $teaser, $module, $userId);
		return $ins;	
	}
    
    /**
    * Get FAQ entries
    * @author Nonhlanhla Gangeni <noegang@gmail.com>
    */
    function getEntries($contextId, $categoryId)
    {
        $sql = "SELECT fc.categoryname as categoryname, fe.question as qn, fe.answer FROM tbl_faq_entries fe,tbl_faq_categories fc WHERE fe.contextid='" . $contextId . "' and fc.id= fe.categoryid";

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
			return $this->getAll("WHERE contextid='" . $contextId . "' ORDER BY _index");
		}
		else {
			return $this->getAll("WHERE contextid='" . $contextId . "' AND categoryid='" . $categoryId ."' ORDER BY _index");
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
		$array = $this->getArray("SELECT MAX(_index) AS _max FROM {$this->_tableName} WHERE contextid='$contextId' AND categoryid='$categoryId'");
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
                'categoryid' => $category,
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
		$objIndexData = $this->getObject('indexdata', 'lucene');
        $objIndexData->removeIndex('faq_entry_'.$id);
	}//
}
?>
