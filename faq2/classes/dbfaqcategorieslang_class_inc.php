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
class dbFaqCategoriesLang extends dbTable
{
    /**
    * Constructor method to define the table
    */
  public function init() 
    {
        parent::init('tbl_faq2_categories_lang');
        //$this->USE_PREPARED_STATEMENTS=True;
    } 

    /**
    * Get category id. (called in getContextLinks() function in modulelinks class)
    * @author Nonhlanhla Gangeni <noegang@gmail.com>
    */
  public function getCatId($contextId)
    {
        return $this->getRow("contextid", $contextId);
    }

    /**
    * Return all records
	* @param string $contextId The context ID

	* @return array The categories
    */
   public function listAll()
	{
		//$sql = "SELECT id, question, answer FROM tbl_faq";
		//return $this->getArray($sql);
		return $this->getAll("ORDER BY categoryname");
	}

	/**
	* Return a single record
	* @param string $contextId The context ID
	* @param string $categoryId The category ID
	* @return array The category
	*/	
   public function listSingle($categoryId)
	{
		$sql = "SELECT * FROM tbl_faq2_categories_lang 
		WHERE categoryid='" . $categoryId . "'";
		return $this->getArray($sql);
		//return $this->getRow("id", $id);
	}

	/**
	* Return a single record from the id
	* @param string $id The ID
	* @return array The category
	*/	
   public function listSingleId($id)
	{
		$sql = "SELECT * FROM tbl_faq2_categories_lang 
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
 public	function insertSingle($categoryId,$categoryname,$language,$isdefaultlang,$userId)
	{
		$id = $this->insert(array(
			'categoryname' => $categoryname, 
			'language' => $language,
                        'isdefaultlang'=>$isdefaultlang,
			'userid' => $userId,
			'datelastupdated' => now()
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
  public function updateSingle($id,$categoryname,$language,$isdefaultlang)
	{
		$this->update("id", $id, 
			array(
        		'categoryname' => $categoryname,
				'languange' => $language,
                                'isdefaultlang' => $isdefaultlang,
				'datelastupdated' => now()
			)
		);
	}
	
	/**
	* Delete a record
	* @param string $id ID
	*/
 public	function deleteSingle($id)
	{
		$this->delete("id", $id);
	}



///from FAQ

  /**
	* Return a single record
	* @param string $contextId The context ID
	* @param string $categoryId The category ID
	* @return array The category
	*/	
 public	function getNotCategorisedId($contextId)
	{
		$sql = 'SELECT * FROM tbl_faq_categories WHERE categoryname = "Not Categorised" AND userid = "admin" AND contextid = "'. $contextId.'"';
        $results = $this->getArray($sql);
        
        if (count($results) == 0) {
            return NULL;
        } else {
            return $results[0]['id'];
        }
	}
	
  /**
	* Return the latest Category created or updated
	* @param string $contextId The context ID
	* @return array The category id
	*/		
 public function getLastestCategory($contextId)
 {
 	$sql = 'SELECT id FROM tbl_faq_categories WHERE contextid = "'. $contextId.'" AND datelastupdated = (SELECT MAX(datelastupdated) FROM tbl_faq_categories)';
 	$results = $this->getArray($sql);
 	$latest = current($results);
 	return $latest['id'];
 }


}

?>
