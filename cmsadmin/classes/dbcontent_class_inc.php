<?php

/* -------------------- dbTable class ----------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check
/**
* Class to access the ContextCore Tables
* @package dbcategories
* @category dbcategories
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
	* Constructor
	*/
	public function init()
	{
		parent::init('tbl_cms_content');
		$this->_objUser = & $this->getObject('user', 'security');
		
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
			$published = $this->getParam('published');
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
							'ordering' => 0,
							'published' => $published,
							'created_by' => $creatorid
							);
			//var_dump($newArr);
			//				die;
			$this->insert($newArr);
		
			//print 'Saving new record';
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
			$published = $this->getParam('published');
			$creatorid = $this->_objUser->userId();
			$access = $this->getParam('access');
			$catid = $this->getParam('catid');
			$introText = $this->getParam('intro');
			$fullText = $this->getParam('body');
		
			$newArr = array(
							'title' => $title ,
							'menutext' => $menuText,
							'sectionid' => intval($sectionid),
							'catid' => intval($catid),							
							'access' => $access,
							'introtext' => $introText,
							'fulltext' => $fullText,							
							'ordering' => 0,
							'published' => $published,
							'created_by' => $creatorid
							);
		
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
			return $this->getAll();
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
}
?>