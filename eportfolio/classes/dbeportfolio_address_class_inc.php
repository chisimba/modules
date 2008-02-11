<?php
/* ----------- data class extends dbTable for tbl_blog------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}

/**
* Model class for the table tbl_eportfolio_address
* @author Paul Mungai
* @copyright 2005 University of the Western Cape
*/
class dbEportfolio_Address extends dbTable
{
    /**
    * Constructor method to define the table
    */
    function init() 
    {
        parent::init('tbl_eportfolio_address');
        $this->objUser = & $this->getObject('user', 'security');        
    }

	/**
	* Return all records
	* @param string $userid The User ID
	* @return array The entries
	*/
	function listAll($userid)
	{
		return $this->getAll("WHERE userid='" . $userid . "'");
		
	}

	/**
	* Return a single record
	* @param string $id ID
	* @return array The values
	*/	
	function listSingle($id)
	{
		//$sql = "SELECT * FROM tbl_eportfolio_address WHERE id = '" . $id . "'";
		//return $this->getArray($sql);
		return $this->getAll("WHERE id='" . $id . "'");
		
	}
	
	
	function getByItem($userId)
	{
        $sql = "SELECT * FROM tbl_eportfolio_address WHERE userid = '" .$userId . "'";	
 	$data = $this->getArray($sql);
        if(!empty($data)){
            return $data;
        }else{
            return FALSE;
        }	  
	//return $this->getAll("WHERE userid='" . $userid . "'");
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
	function insertSingle($type, $street_no, $street_name, $locality, $city,$postcode,$postal_address)
	{
		$userid = $this->objUser->userId();
		$id = $this->insert(array(
			'userid'=>$userid, 
        		'type' => $type,
        		'street_no' => $street_no,
			'street_name' => $street_name,
			'locality' => $locality,
			'city' => $city,			
			'postcode' => $postcode,
			'postal_address' => $postal_address
		));
		return $id;	
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
	function updateSingle($id, $type, $street_no, $street_name, $locality, $city,$postcode,$postal_address)
	{
		$this->update("id", $id, 
			array(
        		'type' => $type,
        		'street_no' => $street_no,
			'street_name' => $street_name,
			'locality' => $locality,
			'city' => $city,
			'postcode' => $postcode,
			'postal_address' => $postal_address
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
	
	/*
	public function saveRecord()
    {
        $searchTerm = $this->getParam('searchterm', NULL);
        if ($searchTerm == NULL) {
            $searchTerm = $this->getParam('q', NULL);
        }
        if ($searchTerm == NULL) {
            $searchTerm = $this->getParam('search', NULL);
        }
        $userId = $this->objUser->userId();
        $params = urldecode($this->getParam('params', NULL));
        $searchengine = $this->getParam('searchengine', NULL);
        $callingModule = $this->getParam('callingModule', "_default");
        // Get the context
        $this->objDbContext = &$this->getObject('dbcontext','context');
        $context = $this->objDbContext->getContextCode();
        // Are we in a context ?
        if ($context == NULL) {
            $context = "lobby";
        }
        $this->insert(array(
            'userid' => $userId,
            'searchterm' => $searchTerm,       
            'module' => $callingModule,
            'context' => $context,
            'params' => $params,
            'searchengine' => $searchengine,
            'datecreated' => date("Y/m/d H:i:s")));
    }
    */
	
}
?>
