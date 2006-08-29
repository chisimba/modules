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

class dbsections extends dbTable
{

	/**
	 * @var object $_objDBCatergory
	 * @access protected
	 */
	protected $_objDBCategory;
	
	
	/**
	 * @var object $_objDBContent
	 * @access protected
	 */
	protected $_objDBContent;
	
	/**
	* Constructor
	*/
	public function init()
	{
		try {
			parent::init('tbl_cms_sections');
			$this->_objDBCategory = $this->getObject('dbcategories', 'cmsadmin');
			$this->_objDBContent = $this->getObject('dbcontent', 'cmsadmin');
		}catch (Exception $e){
       		echo 'Caught exception: ',  $e->getMessage();
        	exit();
        }
	}
	
	/**
	 * Methode to get the list of sections
	 * @access public
	 * @param bool $isPublished TRUE | FALSE To get published sections
	 * @return array
	 */
	public function getSections($isPublished = FALSE)
	{
		try {
			if($isPublished)
			{
				return $this->getAll('WHERE published = 1');
			} else {
				return $this->getAll();
			}
		}catch (Exception $e){
       		echo 'Caught exception: ',  $e->getMessage();
        	exit();
        }
	}
	
	/**
	 * Method to get a Section
	 * @param  string $id The section id
	 * @return array
	 * @access public
	 */
	public function getSection($id)
	{
		
		return $this->getRow('id', $id);
		
		
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
			$image = $this->getParam('image');
			$imagePostion = $this->getParam('imageposition');
			$access = $this->getParam('access');
			$desciption = $this->getParam('description');
			$published = $this->getParam('published');
			$layout = $this->getParam('layout');
			
			return $this->insert(array(
							'title' => $title,
							'menutext' => $menuText,
							'image' => $image,
							'image_position' => $imagePostion,
							'access' => $access,
							'layout' => $layout,
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
			$title = $this->getParam('title');
			$menuText = $this->getParam('menutext');
			$image = $this->getParam('image');
			$imagePostion = $this->getParam('imageposition');
			$access = $this->getParam('access');
			$desciption = $this->getParam('description');
			$published = $this->getParam('published');
			$ordering = $this->getParam('ordering');
			$layout = $this->getParam('sectionlayout');
			
			$arrFields = array(
							'title' => $title,
							'menutext' => $menuText,
							'image' => $image,
							'image_position' => $imagePostion,
							'access' => $access,
							'layout' => $layout,
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
	 * Method to get the Order List List dropdown
	 * 
	 * @param  string $name The name of the radio box
	 * @access public
	 * @return string
	 */
	public function  getOrderList($name)
	{
		try {
			$objDropDown = & $this->newObject('dropdown', 'htmlelements');
			$objDropDown->name = $name;
			//fill the drop down with the list of images
			$arrSections = $this->getAll('ORDER BY ordering');
			
			$cnt = 1;
			$objDropDown->addOption('0', ' 0 first');	
			foreach ($arrSections as $section)
			{
				$objDropDown->addOption($cnt++, ' '.$cnt.'  '.$section['menutext']);	
				if($section['ordering'] == $cnt)
				{
					$objDropDown->setSelected($cnt);
				}
				
			}
			$objDropDown->addOption($cnt++ ,' '.$cnt. ' last');	
			
			
			return $objDropDown->show();
		}catch (Exception $e){
       		echo 'Caught exception: ',  $e->getMessage();
        	exit();
        }
		
	}
	
	/**
	 * Method to check if there is sections
	 * @access public
	 * @return boolean
	 * 
	 */
	public function isSections()
	{
	    $list = $this->getAll();
	    if(count($list) > 0)
	    {
	        return TRUE;
	    } else {
	        return FALSE;
	    }
	}
	
	
	/**
	 * Method to get the menutext for a section
	 * @return string
	 * @access public
	 * @param string $id 
	 */
	public function getMenuText($id)
	{
		$line = $this->getSection($id);
		
		return $line['menutext'];
	}
	

	/**
	 * Method to toggle the publish field 
	 * 
	 * @param string id The id if the section
	 * @access public
	 * @return boolean
	 * @author Wesley Nitsckie
	 */
	public function togglePublish($id)
	{
	   $row = $this->getSection($id);
	   if($row['published'] == 1)
	   {    
	       return $this->update('id', $id , array('published' => 0) );    
	   } else {
	       return $this->update('id', $id , array('published' => 1) );    
	   }
	    
	}
	
	
	
	/**
	* Method to delete a section 
	* 
	*
	* 
	* @param $string $id The section id
	* @access public
	* @return boolean
	*/
	public function deleteSection($id)
	{
		$this->_objDBContent->resetSection($id);
		return $this->delete('id', $id);
			
	}
}