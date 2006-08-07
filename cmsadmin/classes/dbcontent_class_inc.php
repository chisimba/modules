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

class dbcontent extends dbTable
{

	/**
	 * @var object $_objUser;
	 */
	protected $_objUser;
	
	
	/**
	 * @var object $_objFrontPage t
	 * 
	 * @access protected
	 */
	protected $_objFrontPage;
	
	
	/**
	* Constructor
	*/
	public function init()
	{
		parent::init('tbl_cms_content');
		$this->_objUser = & $this->getObject('user', 'security');
		$this->_objFrontPage = & $this->newObject('dbcontentfrontpage', 'cmsadmin');
	}
	
	/**
	 * MEthod to save a record to the database
	 * @access public
	 * @return bool
	 */
	public function add()
	{
		try{
			$title = $this->getParam('title');
			$menuText = $this->getParam('menutext');
			$sectionid = $this->getParam('section');			
			$published = ($this->getParam('published') == 'on') ? 1 : 0;
			$creatorid = $this->_objUser->userId();
			$access = $this->getParam('access');
			$catid = $this->getParam('catid');
			$introText = $this->getParam('intro');
			$fullText = $this->getParam('body');
			
			
			
			$newArr = array(
							'title' => $title ,
							'menutext' => $menuText,
							'sectionid' => $sectionid,
							'catid' => $catid,
							'introtext' => $introText,
							'body' => $fullText,							
							'access' => $access,
							'ordering' => $this->getNewOrder(),
							'published' => $published,
							'created' => $this->now(),
							'modified' => $this->now(),
							'created_by' => $creatorid
							);
			
			$newId = 	$this->insert($newArr);
			
			//process the forntpage
			$isFrontPage = $this->getParam('frontpage');
			
			if($isFrontPage == 'on')
			{				
				$this->_objFrontPage->add($newId);
			}
		
			return $newId;
			
		}catch (Exception $e){
       		echo 'Caught exception: ',  $e->getMessage();
        	exit();
        }
		
		
	}
	
	/**
	 * Method to edit a record
	 * @access public
	 * @return bool
	 */
	public function edit()
	{
			
		try{
			$id = $this->getParam('id');
			$title = $this->getParam('title');
			$menuText = $this->getParam('menutext');
			$sectionid = $this->getParam('section');
			$published = ($this->getParam('published') == 'on') ? '1':'0';
			$creatorid = $this->_objUser->userId();
			$access = $this->getParam('access');
			$catid = $this->getParam('catid');
			$introText = $this->getParam('intro');
			$fullText = $this->getParam('body');
		
			$newArr = array(
							'title' => $title ,
							'menutext' => $menuText,
							'sectionid' => $sectionid,
							'catid' => $catid,							
							'access' => $access,
							'introtext' => $introText,
							'body' => $fullText,		
							'modified' => $this->now(),					
							'ordering' => 0,							
							'published' => $published,
							'created_by' => $creatorid
							);
		
			//process the forntpage
			$isFrontPage = $this->getParam('frontpage');
			
			if($isFrontPage == 'on')
			{				
				$this->_objFrontPage->add($id);
			} else {
				$this->_objFrontPage->remove($id);
			}
		
			
			return $this->update('id', $id, $newArr);
		
			//print 'Saving new record';
		}catch (Exception $e){
       		echo 'Caught exception: ',  $e->getMessage();
        	exit();
        }
		
		
	}
	
	/**
	 * Method delete a record
	 * @param string $id The id of the record that needs to be deleted
	 * @access public
	 * @return bool
	 */
	public function trash()
	{
		
		
	}
	
	/**
	 * Method to get the content
	 * @return  array
	 * @access public
	 */
	public function getContentPages()
	{
		try{
			return $this->getAll('ORDER BY sectionid,catid');
		}catch (Exception $e){
       		echo 'Caught exception: ',  $e->getMessage();
        	exit();
        }
		
	}
	
	
	/**
	 * Method to get a page content record
	 * @param string $id The id of the page content
	 * @access public
	 * @return array
	 */
	public function getContentPage($id)
	{
		
		return $this->getRow('id', $id );
	}
	
	/**
	 * Method to the order number for the page
	 * @param 
	 * @access public
	 * @return int
	 */
	public function getNewOrder()
	{
		$arr = $this->getArray('Select max("ordering") as neworder from tbl_cms_content');
		
		return 0;	
	}
	
	/**
	 * Method to toggle the publish field 
	 * 
	 * @param string id The id if the content
	 * @access public
	 * @return boolean
	 * @author Wesley Nitsckie
	 */
	public function togglePublish($id)
	{
	   $row = $this->getContentPage($id);
	   if($row['published'] == 1)
	   {    
	       return $this->update('id', $id , array('published' => 0) );    
	   } else {
	       return $this->update('id', $id , array('published' => 1) );    
	   }
	    
	}
	
	
	
	/**
	* Method to update all the content with the 
	* sections that will be deleted
	* @param string $sectionId The section Id
	* @return boolean
	* @access public
	*/
	public  function resetSection($sectionId)
	{
		
		$arrContent = $this->getAll('WHERE sectionid = "'.$id.'"');
		foreach ($arrContent as $page)
		{
			$this->update('id', $page['id'], array('sectionid' => 'no-id'));
		}
		return ;
	}
	
}
?>