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
	* Constructor
	*/
	public function init()
	{
		try {
			parent::init('tbl_cms_layouts');
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
	 * @param string $name The name of the layout
	 * @return array
	 */
	public function getLayout($name)
	{
		
		try {
			return $this->getRow('name', $name);
		}catch (Exception $e){
       		echo 'Caught exception: ',  $e->getMessage();
        	exit();
        }
	}
	/**
	 * Method to get the description of a layout by referencing its name
	 * 
	 * @access public
	 * @param string $name The name of the layout
	 * @return string $description The layout description
	 */
	public function getLayoutDescription($name)
	{
		
		try {
			$layout = $this->getRow('name', $name);
			$description = $layout['description'];
			return $description;
		}catch (Exception $e){
       		echo 'Caught exception: ',  $e->getMessage();
        	exit();
        }
	}
	
	
}
