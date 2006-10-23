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
			
			if(!$this->valueExists('content_id',$contentId)){
				
				return $this->insert(array(
							'content_id' => $contentId,
							'ordering' => $this->getOrdering()			
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
		try{			
	   $page = $this->getRow('id', $id);
	   $pageOrderNo = $page['ordering'];
		 $allPages = $this->getFrontPages();
		 foreach($allPages as $pg){
       if($pg['ordering'] > $pageOrderNo){
         $newOrder = $pg['ordering'] - '1';
         $this->update('id', $pg['id'], array('content_id' => $pg['content_id'], 'ordering' => $newOrder));
       }
     }
     return $this->delete('id', $id);
		}catch (Exception $e){
       		echo 'Caught exception: ',  $e->getMessage();
        	exit();
        }
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
	/**
	 * Method to update the order of the frontpage
	 * 
	 * @param string $id The id of the entry to move
	 * @param int $ordering How to update the order(up or down).
	 * @access public
	 * @return bool
	 */
	public function changeOrder($id, $ordering)
	{
	   //Get array of all front page entries
	   $fpContent = $this->getAll('ORDER BY ordering');
	   //Search for entry to be reordered and update order
	   foreach($fpContent as $content){
	     if($content['id'] == $id){
         if($ordering == 'up'){
           $changeTo = $content['ordering'];
           $toChange = $content['ordering'] + 1;
           $updateArray = array(
							'content_id' => $content['content_id'],
							'ordering' => $toChange			
							);
           $this->update('id', $id, $updateArray);
         } else {
             $changeTo = $content['ordering'];
             $toChange = $content['ordering'] - 1;
             $updateArray = array(
						  	'content_id' => $content['content_id'],
							  'ordering' => $toChange	
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
					   'content_id' => $entry['content_id'],
					   'ordering' => $changeTo	
             );
          $this->update('id', $entry['id'], $upArr);		
        }
     }
	}
	/** 
	 * Method to return the ordering value of new content (gets added last)
	 *
	 * @return int $ordering The value to insert into the ordering field
	 * @access public
   */
	public function getOrdering()
	{
     //get last order value 
     $lastOrder = $this->getAll('ORDER BY ordering DESC LIMIT 1');
     //add after this value
     $ordering = $lastOrder['0']['ordering'] + 1;
     return $ordering;
  }
	/**
	 * Method to return the links to be displayed in the order column on the table
	 * 
	 * @param string $id The id of the entry 
	 * @return string $links The html for the links
	 * @access public
	 * @return bool
	 */
	public function getOrderingLink($id)
	{
	   //Get the number of pages on the front page
	   $lastOrd = $this->getAll('ORDER BY ordering DESC LIMIT 1');
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
	       $downLink->href = $this->uri(array('action' => 'changefporder', 'id' => $id, 'ordering' => 'up'));
	       $downLink->link = $this->objIcon->show();
	       $links .= $downLink->show();
       } else if($entry['ordering'] == $topOrder){
            //return up arrow
	          //icon
	          $this->objIcon->setIcon('upend');
	          //link
	          $upLink =& $this->newObject('link', 'htmlelements');
	          $upLink->href = $this->uri(array('action' => 'changefporder', 'id' => $id, 'ordering' => 'down'));
	          $upLink->link = $this->objIcon->show();
	          $links .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $upLink->show();
       } else {
          //return both arrows
          //icon
	        $this->objIcon->setIcon('down');
	        //link
	        $downLink =& $this->newObject('link', 'htmlelements');
	        $downLink->href = $this->uri(array('action' => 'changefporder', 'id' => $id, 'ordering' => 'up'));
	        $downLink->link = $this->objIcon->show();
	        //icon
	        $this->objIcon->setIcon('up');
	        //link
	        $upLink =& $this->newObject('link', 'htmlelements');
	        $upLink->href = $this->uri(array('action' => 'changefporder', 'id' => $id, 'ordering' => 'down'));
	        $upLink->link = $this->objIcon->show();
          $links .= $downLink->show() . '&nbsp;' . $upLink->show();
       }
     }
     return $links;  
	}
}
?>
