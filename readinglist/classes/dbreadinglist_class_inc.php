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
class dbReadingList extends dbTable
{
    /**
    * Constructor method to define the table
    */
    function init() 
    {
        parent::init('tbl_readinglist');
        $this->dbLinks = $this->newObject('dbreadinglist_links');
        //$this->USE_PREPARED_STATEMENTS=True;
    }

	/**
	* Return all records
	* @param string $contextId The context ID
	* @return array The entries
	*/
	function listAll($contextId)
	{
		//$sql = "SELECT FROM";
		//return $this->getArray($sql);
		return $this->getAll("WHERE contextCode='" . $contextId . "'");
		//return $this->getAll();
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
	* @param string $contextId The context ID
	* @param string $author The author
	* @param string $title The title
	* @param string $publisher The publisher
	* @param string $year The year
	* @param string $link The Link
	* -- @param string $userId The user ID
	* -- @param string $dateLastUpdated Date last updated
	*/
	function insertSingle($contextId, $author, $title, $publisher, $publishingYear, $link, $publication,$country,$language)
	{
		$this->insert(array(
			'contextcode'=>$contextId, 
        		'author' => $author,
        		'title' => $title,
			'publisher' => $publisher,
			'publishingyear' => $publishingYear,
			'link' => $link,
			'publication' => $publication,
			'country' => $country,
			'language' => $language
			//'userId' => $userId
			//'dateLastUpdated' => strftime('%Y-%m-%d %H:%M:%S', $dateLastUpdated)
		));
		return;	
	}

	/**
	* Update a record
	* @param string $id ID
	* @param string $author The author
	* @param string $title The title
	* @param string $publisher The publisher
	* @param string $year The year
	* @param string $link The Link
	* -- @param string $userId The user ID
	* -- @param string $dateLastUpdated Date last updated
	*/
	function updateSingle($id, $author, $title, $publisher, $year, $link, $publication,$country,$language)
	{
		$this->update("id", $id, 
			array(
        		'author' => $author,
        		'title' => $title,
			'publisher' => $publisher,
			'publishingYear' => $year,
			'link' => $link,
			'publication' => $publication,
			'country' => $country,
			'language' => $language
			//'userId' => $userId,
			//'dateLastUpdated' => strftime('%Y-%m-%d %H:%M:%S', $dateLastUpdated)
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
		$this->dbLinks->delete('itemid', $id);
	}
}
?>
