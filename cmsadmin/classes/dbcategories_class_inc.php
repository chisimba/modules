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

class dbcategories extends dbTable
{

	/**
	* Constructor
	*/
	public function init()
	{
		parent::init('tbl_cms_categories');
	}
	
	/**
	 * Methode to get the list of categories
	 * @access public
	 * @return array
	 */
	public function getCategories()
	{
		
		return $this->getAll();
	}
	
	/**
	 * Method to get the number of categories for a certain section
	 * @param string $sectionId The id of the category
	 * @access public
	 * @return int
	 */
	public  function getCatCount($sectionId = NULL)
	{
		if($sectionId == NULL)
		{
			return $this->getRecordCount();
		}
		else 
		{
			return $this->getRecordCount('WHERE parent_id = "'. $sectionId.'"');
		}
		
	}
	
	
	/**
	 * Method to add a section to the database
	 * @access public
	 * @return bool
	 */
	public function add()
	{
		try{
			$title = $this->getParam('title');
			$menuText = $this->getParam('menutext');
			$section = $this->getParam('section');
			$image = $this->getParam('image');
			$imagePostion = $this->getParam('imagepostion');
			$access = $this->getParam('access');
			$desciption = $this->getParam('description');
			$published = $this->getParam('published');
			
			return $this->insert(array(
							'title' => $title,
							'menutext' => $menuText,
							'sectionid' => $section,
							'image' => $image,
							'image_position' => $imagePostion,
							'access' => $access,
							'ordering' => 0,
							'description' => $desciption,
							'published' => $published
													));
													
		}catch (Exception $e){
       		echo 'Caught exception: ',  $e->getMessage();
        	exit();
        }
	}
	
	/**
	 * Method to add a section to the database
	 * @access public
	 * @return bool
	 */	
	
	public function edit()
	{
		try{
			
			$id = $this->getParam('id');
			$section = $this->getParam('section');
			$title = $this->getParam('title');
			$menuText = $this->getParam('menutext');
			$image = $this->getParam('image');
			$imagePostion = $this->getParam('imagepostion');
			$access = $this->getParam('access');
			$desciption = $this->getParam('description');
			$published = $this->getParam('published');
			$ordering = $this->getParam('ordering');
			$arrFields = array(
							'title' => $title,
							'menutext' => $menuText,
							'sectionid' => $section,
							'image' => $image,
							'image_position' => $imagePostion,
							'access' => $access,
							'ordering' => $ordering,
							'description' => $desciption,
							'published' => $published);
			return $this->update('id', $id, $arrFields);
													
		}catch (Exception $e){
       		echo 'Caught exception: ',  $e->getMessage();
        	exit();
        }
	}
	/**
	 * Method to get the menutext for a section
	 * @return string
	 * @access public
	 * @param string id 
	 */
	
	public function getMenuText($id)
	{
		$line = $this->getCategory($id);
		
		return $line['menutext'];
	}
	
	/**
	 * Method to get a Section
	 * @var string id The section id
	 * @return array
	 * @access public
	 */
	public function getCategory($id)
	{
		
		return $this->getRow('id', $id);
		
		
	}
	
}
?>