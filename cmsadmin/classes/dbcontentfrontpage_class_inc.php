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

class dbcontentfrontpage extends dbTable
{

	/**
	 * @var object $_objUser;
	 */
	protected $_objUser;
	
	
	
	/**
	* Constructor
	*/
	public function init()
	{
		parent::init('tbl_cms_content_frontpage');
		$this->_objUser = & $this->getObject('user', 'security');
		
	}
	
	/**
	 * MEthod to save a record to the database
	 * 
	 * @param string $contentId The neContent Id
	 * @param int $ordering
	 * @access public
	 * @return bool
	 */
	public function add($contentId, $ordering = 0)
	{
		try{		
			
			if(!$this->valueExists('content_id',$contentId))
			{
				
				return $this->insert(array(
							'content_id' => $contentId,
							'ordering' => $ordering			
							));
			}
		}catch (Exception $e){
       		echo 'Caught exception: ',  $e->getMessage();
        	exit();
        }
	}
	
	
	/**
	 * Method to remove a record
	 * 
	 * @param string $id The content Id that must be removed
	 * @access public
	 * @return bool
	 */
	public function remove($id)
	{
		$this->delete('content_id', $id);
		
	}
	
	
	/**
	 * Method to get all the front page id's
	 * 
	 * @return array
	 * @access public
	 */
	public function getFrontPages()
	{
		
		return $this->getAll('ORDER BY ordering');
	}
	
	
	/**
	 * Method to check if a page is a front page
	 * 
	 * @param string $id The id to be checked
	 * @access public
	 * @return bool
	 * 
	 */
	public function isFrontPage($id)
	{
		
		return $this->valueExists('content_id',$id);
		
	}
}