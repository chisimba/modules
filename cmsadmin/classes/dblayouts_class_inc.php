<?php

/* -------------------- dbTable class ----------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check
/**
* Class to access the ContextCore Tables
* @package cms
* @category cmsadmin
* @copyright 2004, University of the Western Cape & AVOIR Project
* @license GNU GPL
* @version
* @author Wesley  Nitsckie
* @example :
*/

class dblayouts extends dbTable
{

	/**
	 * @var object $_objDBCatergory
	 * @access protected
	 */
	protected $_objDBCategory;
	
	
	/**
	* Constructor
	*/
	public function init()
	{
		try {
			parent::init('tbl_cms_layouts');
			$this->_objDBCategory = $this->getObject('dbcategories', 'cmsadmin');
		}catch (Exception $e){
       		echo 'Caught exception: ',  $e->getMessage();
        	exit();
        }
	}
	
	/**
	 * Method to get the layouts
	 * @access public
	 * @return array
	 */
	public function getLayouts()
	{
		try {
		
			return $this->getAll();
		}catch (Exception $e){
       		echo 'Caught exception: ',  $e->getMessage();
        	exit();
        }
	}
	
	/**
	 * Method to get the layout record
	 * 
	 * @access public
	 * @param string $id The id of the layout
	 * @return array
	 */
	public function getLayout($id)
	{
		
		try {
			return $this->getRow('id', $id);
		}catch (Exception $e){
       		echo 'Caught exception: ',  $e->getMessage();
        	exit();
        }
	}
	
}
