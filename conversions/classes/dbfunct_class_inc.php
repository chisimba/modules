<?php
	/**
    * provides methods for accessing database table tbl_conversions.
    *
    * @author Nonhlanhla Gangeni <2539399@uwc.ac.za>
    * @package convertions
    * @copyright UWC 2007
    * @filesource
    */

class dbconv extends dbTable
{
 
    
    public function init() 
    {
        // initialize the table
        parent::init('tbl_conversions');
    }

        
	public function getConversion($id)
	{
		$sql = "WHERE id = '$id'";
		return $this->getAll($sql);
	}
}
?>
