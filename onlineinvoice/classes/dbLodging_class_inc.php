<?php
class dbLodging extends dbTable{
	/**
	* Constructor
	*/
	function init()
	{
		parent::init('tbl_lodging');
	}

	function add($data)
	{
    
   $results = $this->insert($data); 
   return $results;
   
   
                
  }
	
	function getTev()
	{}
}

?>
