<?php
/* ----------- data class extends dbTable for tbl_blog------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}

/**
* Model class for the table tbl_readinglist
* @author John Abakpa, Juliet Mulindwa
* @author Luis Domingos, Juliet Mulindwa
* @copyright 2005 University of the Western Cape
*/
class dbReadingList_comment extends dbTable
{
    /**
    * Constructor method to define the table
    */
    function init() 
    {
        parent::init('tbl_readinglist_comment');
        //$this->USE_PREPARED_STATEMENTS=True;
    }

	function getByItem($itemId)
	{
	  $sql = "SELECT * FROM tbl_readinglist_comment WHERE itemid = '" .$itemId . "'";
	  return $this->getArray($sql);
	  
	}

	
	/**
	* Return a single record
	* @param string $id ID
	* @return array The values
	*/	
	function listSingle($id)
	{
		$sql = "SELECT * FROM tbl_readinglist WHERE id = '" . $id . "'";
		return $this->getArray($sql);
		//return $this->getRow("id", $id);
	}

	/**
	* Insert a record
	* @param string $Id The ID
	* @param string $comment The comment
	*
	*/
	function insertIntoDB($id, $comment)
	{
		$this->insert(array( 
        		'id' => $id,
        		'comment' => $comment
			//'description' => $description
			
			//'userId' => $userId
			//'dateLastUpdated' => strftime('%Y-%m-%d %H:%M:%S', $dateLastUpdated)
		));
		return;	
	}

}
?>