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
			$introText = $this->getParam('intro');
			$fullText = $this->getParam('body');
			
			$newArr = array(
							'title' => $title ,
							'menutext' => $menuText,
							'sectionid' => $sectionid,
							'introtext' => $introText,
							'body' => $fullText,							
							'access' => $access,
							'ordering' => $this->getOrdering($sectionid),
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
			
		} 
		catch (customException $e)
        {
        	echo customException::cleanUp($e);
        	die();
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
							'access' => $access,
							'introtext' => $introText,
							'body' => $fullText,		
							'modified' => $this->now(),					
							'ordering' => $this->getPageOrder($id),							
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
		}
		 catch (customException $e)
        {
        	echo customException::cleanUp($e);
        	die();
        }
		
		
	}
	
	
	
	
	/**
	 * Method move a record to trash
	 * @param string $id The id of the record that needs to be deleted
	 * @access public
	 * @return bool
	 */
	public function trashContent($id)
	{
		
		return $this->update('id', $id, array('trash' => 1));
	}
	
	
	
	
	/**
	 * Method to undelete content
	 * @param string $id The id of the record that needs to be deleted
	 * @access public
	 * @return bool
	 */
	public function undelete($id)
	{
		
		return $this->update('id', $id, array('trash' => 0));
	}
	
	
	
	/**
	* Method to delete a content page
	* @param string $id
	* @return boolean
	* @access public
	*/
	public function deleteContent($id)
	{
		try{			
		 //Re-order other pages in section accordingly
	   $page = $this->getRow('id', $id);
	   $pageOrderNo = $page['ordering'];
	   $sectionId = $page['secionid'];
		 $allPagesInSection = $this->getPagesInSection($sectionId);
		 foreach($allPagesInSection as $pg){
       if($pg['ordering'] > $pageOrderNo){
         $newOrder = $pg['ordering'] - '1';
         $this->update('id', $pg['id'], array('title' => $pg['title'],
							'menutext' => $pg['menutext'],
							'sectionid' => $pg['sectionid'],
							'introtext' => $pg['introtext'],
							'body' => $pg['body'],							
							'access' => $pg['access'],
							'ordering' => $newOrder,
							'published' => $pg['published'],
							'created' => $pg['created'],
							'modified' => $this->now(),
							'created_by' => $pg['created_by']
              ));
       }
     }
     //First remove from front page
     if($this->_objFrontPage->isFrontPage($id)){
       $fpEntry = $this->_objFrontPage->getRow('content_id', $id);
       $fpEntryId = $fpEntry['id'];
       $this->_objFrontPage->remove($fpEntryId);
     }
     //Delete page
     return $this->delete('id', $id);
		}catch (Exception $e){
       		echo 'Caught exception: ',  $e->getMessage();
        	exit();
        }
	}
	
	/**
	 * Method to get the content
	 * @return  array
	 * @access public
	 * @param string filter The Filter 
	 */
	public function getContentPages($filter = '')
	{
		try{
			if($filter == 'trash')
			{
				$filter = ' WHERE trash=1 ';
			} else {
				$filter = ' WHERE trash=0 ';
			}
			return $this->getAll($filter.' ORDER BY ordering');
		}
		 catch (customException $e)
        {
        	echo customException::cleanUp($e);
        	die();
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
		try{
		   $row = $this->getContentPage($id);
		   if($row['published'] == 1)
		   {    
		       return $this->update('id', $id , array('published' => 0) );    
		   } else {
		       return $this->update('id', $id , array('published' => 1) );    
		   }
		} 
		catch (customException $e)
        {
        	echo customException::cleanUp($e);
        	die();
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
		try{	
			$arrContent = $this->getAll('WHERE sectionid = "'.$id.'"');
			foreach ($arrContent as $page)
			{
				$this->update('id', $page['id'], array('sectionid' => 'no-id'));
			}
			return ;
		}
		 catch (customException $e)
        {
        	echo customException::cleanUp($e);
        	die();
        }
	}
	/**
	 * Method to get all pages in a specific section
	 * 
	 * @param string $sectionId The id of the section 
	 * @return array $pages An array of all pages in the section
	 * @access public
	 * @author Warren Windvogel
	 */
	public function getPagesInSection($sectionId)
	{
     try{		
	     $pages = $this->getAll('WHERE sectionid = "'.$sectionId.'" ORDER BY ordering');
	     return $pages;
	   }  
		 catch (customException $e)
        {
        	echo customException::cleanUp($e);
        	die();
        }

	}
	/**
	 * Method to get the number of pages in a specific section
	 * 
	 * @param string $sectionId The id of the section 
	 * @return int $noPages The number of pages in the section
	 * @access public
	 * @author Warren Windvogel
	 */
	public function getNumberOfPagesInSection($sectionId)
	{
	  try{		
	   $noPages = '0';
	   $pages = $this->getAll('WHERE sectionid = "'.$sectionId.'" ORDER BY ordering');
	   $noPages = count($pages);
	   return $noPages;
	   }  
		 catch (customException $e)
        {
        	echo customException::cleanUp($e);
        	die();
        }
	}
	/** 
	 * Method to return the ordering value of new content (gets added last)
	 *
	 * @param string $sectionId The id(pk) of the section the content is attached to
	 * @return int $ordering The value to insert into the ordering field
	 * @access public
   */
	public function getPageOrder($pageId)
	{
    try{		
     //get last order value 
     $lastOrder = $this->getRow('id', $pageId);
     //add after this value
     $ordering = $lastOrder['ordering'];
     return $ordering;
	   }  
		 catch (customException $e)
        {
        	echo customException::cleanUp($e);
        	die();
        }
  }
	/** 
	 * Method to return the ordering value of new content (gets added last)
	 *
	 * @param string $sectionId The id(pk) of the section the content is attached to
	 * @return int $ordering The value to insert into the ordering field
	 * @access public
   */
	public function getOrdering($sectionId)
	{
	  try{		
     //get last order value 
     $lastOrder = $this->getAll('WHERE sectionid = "'.$sectionId.'" ORDER BY ordering DESC LIMIT 1');
     //add after this value
     $ordering = $lastOrder['0']['ordering'] + 1;
     return $ordering;
	   }  
		 catch (customException $e)
        {
        	echo customException::cleanUp($e);
        	die();
        }
  }

	/**
	 * Method to return the links to be displayed in the order column on the table
	 * 
	 * @param string $id The id of the entry 
	 * @return string $links The html for the links
	 * @access public
	 * @return bool
	 */
	public function getOrderingLink($sectionid, $id)
	{
    try{			
	   //Get the number of pages in the section
	   $lastOrd = $this->getAll('WHERE sectionid = "'.$sectionid.'" ORDER BY ordering DESC LIMIT 1');
	   $topOrder = $lastOrd['0']['ordering'];
	   $links = " ";
	   if($topOrder > '1'){
	     //Get the order position
	     $entry = $this->getRow('id', $id);
	     //Create geticon obj
	     $this->objIcon =& $this->newObject('geticon', 'htmlelements');
	     if($entry['ordering'] == '1'){
	       //return down arrow link
	       //icon
	       $this->objIcon->setIcon('downend');
	       //link
	       $downLink =& $this->newObject('link', 'htmlelements');
	       $downLink->href = $this->uri(array('action' => 'changecontentorder', 'id' => $id, 'ordering' => 'up', 'sectionid' => $sectionid));
	       $downLink->link = $this->objIcon->show();
	       $links .= $downLink->show();
       } else if($entry['ordering'] == $topOrder){
            //return up arrow
	          //icon
	          $this->objIcon->setIcon('upend');
	          //link
	          $upLink =& $this->newObject('link', 'htmlelements');
	          $upLink->href = $this->uri(array('action' => 'changecontentorder', 'id' => $id, 'ordering' => 'down', 'sectionid' => $sectionid));
	          $upLink->link = $this->objIcon->show();
	          $links .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $upLink->show();
       } else {
          //return both arrows
          //icon
	        $this->objIcon->setIcon('down');
	        //link
	        $downLink =& $this->newObject('link', 'htmlelements');
	        $downLink->href = $this->uri(array('action' => 'changecontentorder', 'id' => $id, 'ordering' => 'up', 'sectionid' => $sectionid));
	        $downLink->link = $this->objIcon->show();
	        //icon
	        $this->objIcon->setIcon('up');
	        //link
	        $upLink =& $this->newObject('link', 'htmlelements');
	        $upLink->href = $this->uri(array('action' => 'changecontentorder', 'id' => $id, 'ordering' => 'down', 'sectionid' => $sectionid));
	        $upLink->link = $this->objIcon->show();
          $links .= $downLink->show() . '&nbsp;' . $upLink->show();
       }
     }
     return $links;  
	   }  
		 catch (customException $e)
        {
        	echo customException::cleanUp($e);
        	die();
        }
	}
	/**
	 * Method to update the order of the frontpage
	 * 
	 * @param string $id The id of the entry 	 
	 * @param string $id The id of the entry to move
	 * @param int $ordering How to update the order(up or down).
	 * @access public
	 * @return bool
	 */
	public function changeOrder($sectionid, $id, $ordering)
	{
	  try{		
	   //Get array of all page entries
	   $fpContent = $this->getAll('WHERE sectionid = "'.$sectionid.'" ORDER BY ordering');
	   //Search for entry to be reordered and update order
	   foreach($fpContent as $content){
	     if($content['id'] == $id){
         if($ordering == 'up'){
           $changeTo = $content['ordering'];
           $toChange = $content['ordering'] + 1;
           $updateArray = array(
							'title' => $content['title'] ,
							'menutext' => $content['menutext'],
							'sectionid' => $content['sectionid'],
							'introtext' => $content['introtext'],
							'body' => $content['body'],							
							'access' => $content['access'],
							'ordering' => $toChange,
							'published' => $content['published'],
							'created' => $content['created'],
							'modified' => $this->now(),
							'created_by' => $content['created_by']
							);
           $this->update('id', $id, $updateArray);
         } else {
             $changeTo = $content['ordering'];
             $toChange = $content['ordering'] - 1;
             $updateArray = array(
							'title' => $content['title'] ,
							'menutext' => $content['menutext'],
							'sectionid' => $content['sectionid'],
							'introtext' => $content['introtext'],
							'body' => $content['body'],							
							'access' => $content['access'],
							'ordering' => $toChange,
							'published' => $content['published'],
							'created' => $content['created'],
							'modified' => $this->now(),
							'created_by' => $content['created_by']
                );
             $this->update('id', $id, $updateArray);		
         }
       }
     }
     //Get other entry to change
     $entries = $this->getAll('WHERE ordering = "'.$toChange.'"');
     foreach($entries as $entry){
        if($entry['id'] != $id){
          $upArr = array(
							'title' => $entry['title'] ,
							'menutext' => $entry['menutext'],
							'sectionid' => $entry['sectionid'],
							'introtext' => $entry['introtext'],
							'body' => $entry['body'],							
							'access' => $entry['access'],
							'ordering' => $changeTo,
							'published' => $entry['published'],
							'created' => $entry['created'],
							'modified' => $this->now(),
							'created_by' => $entry['created_by']
             );
          $this->update('id', $entry['id'], $upArr);		
        }
     }
	   }  
		 catch (customException $e)
        {
        	echo customException::cleanUp($e);
        	die();
        }
	}

}
?>
