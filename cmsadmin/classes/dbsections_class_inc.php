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
				return $this->getAll('WHERE published = 1 ORDER BY ordering');
			} else {
				return $this->getAll('ORDER BY ordering');
			}
		}catch (Exception $e){
       		echo 'Caught exception: ',  $e->getMessage();
        	exit();
        }
	}

	/**
	 * Methode to get the list of root nodes
	 * @access public
	 * @param bool $isPublished TRUE | FALSE To get published sections
	 * @return array
	 */
	public function getRootNodes($isPublished = FALSE)
	{
		try {
			if($isPublished)
			{
				return $this->getAll('WHERE published = 1 AND count = 1 ORDER BY ordering');
			} else {
				return $this->getAll('WHERE count = 1 ORDER BY ordering');
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
		try {
		  return $this->getRow('id', $id);
    }catch (Exception $e){
       		echo 'Caught exception: ',  $e->getMessage();
        	exit();
    }		
	}
	
	/**
	 * Method to the first sections id(pk)
	 * @return string First sections id
	 * @access public
	 */
	public function getFirstSectionId()
	{
		try {
		  $firstSection = $this->getAll('WHERE count = 1 AND ordering = 1');
		  $firstSectionId = $firstSection['0']['id'];
		  return $firstSectionId;
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
	public function add()
	{
		try{
	   //get param from dropdown
     $parentSelected = $this->getParam('parent');
	   //get parent type "subsection", "root" or "param is null"(new section will be root level) and its id
     $id = $parentSelected;
     $parentid = $id;
     if($this->getLevel($parentid) == '1'){
       $rootid = $parentid;
     } else {
         $rootid = $this->getRootNodeId($id);
     }    
  	 $title = $this->getParam('title');
		 $menuText = $this->getParam('menutext');
		 $image = $this->getParam('image');
		 $imagePostion = $this->getParam('imageposition');
		 $access = $this->getParam('access');
		 $desciption = $this->getParam('description');
		 $published = $this->getParam('published');
		 $layout = $this->getParam('sectionlayout');
		 $ordering = $this->getOrdering($parentid);
     return $this->insert(array(
			        'rootid' => $rootid,
			        'parentid' => $parentid,
							'title' => $title,
							'menutext' => $menuText,
							'image' => $image,
							'image_position' => $imagePostion,
							'access' => $access,
							'layout' => $layout,
							'ordering' => $ordering,
							'description' => $desciption,
							'published' => $published,
							'count' => $this->getLevel($parentid) + '1'
													));
													
		}catch (Exception $e){
       		echo 'Caught exception: ',  $e->getMessage();
        	exit();
        }
	}
	
	/**
	 * Method to edit a section in the database
	 * @access public
	 * @return bool
	 */
	public function edit()
	{
		try{
			
			$id = $this->getParam('id');
			$parentid = $this->getParam('parentid');
			$rootid = $this->getParam('rootid');
			$title = $this->getParam('title');
			$menuText = $this->getParam('menutext');
			$image = $this->getParam('image');
			$imagePostion = $this->getParam('imageposition');
			$access = $this->getParam('access');
			$desciption = $this->getParam('description');
			$published = $this->getParam('published');
			$ordering = $this->getParam('ordering');
			$layout = $this->getParam('sectionlayout');
			if(isset($parentid)){
			  $count = $this->getLevel($parentid) + '1';
			} else {
          $count = '1';
      } 
			$arrFields = array(
			        'parentid' => $parentid,
							'title' => $title,
							'menutext' => $menuText,
							'image' => $image,
							'image_position' => $imagePostion,
							'access' => $access,
							'layout' => $layout,
							'ordering' => $ordering,
							'description' => $desciption,
							'count' => $count,
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
//	public function deleteSection($id)
//	{
//		$this->_objDBContent->resetSection($id);
//		return $this->delete('id', $id);
//	}
	/** 
	 * Method to check if a section has child/leaf node(s)
	 *
	 * @param string $id The id(pk) of the section
	 * @return bool True if has nodes else False
	 * @access public
   */
	public function hasNodes($id)
	{
     $nodes = $this->getAll('WHERE parentid = "'.$id.'"');
     if(count($nodes) > '0'){
       $hasNodes = True;
     } else {
         $hasNodes = False;
     }
     return $hasNodes;
  }
	/** 
	 * Method to return the count value of a section
	 *
	 * @param string $id The id(pk) of the section
	 * @return int $count The value of the count field
	 * @access public
   */
	public function getLevel($id)
	{
	   $count = 0;
     //get entry 
     $section = $this->getRow('id', $id);
     if(!empty($section)){
       //get and return value of count field
       $count = $section['count'];
     }
     return $count;
  }
	/** 
	 * Method to return a sections root node id
	 *
	 * @param string $id The id(pk) of the section
	 * @return string $rootId The id(pk) of the sections root node
	 * @access public
   */
	public function getRootNodeId($id)
	{
     //get entry 
     $section = $this->getRow('id', $id);
     //get and return value of count field
     $rootId = $section['rootid'];
     return $rootId;
  }
	/** 
	 * Method to get all subsections in a specific section
	 *
	 * @param string $sectionId The id(pk) of the section
	 * @param int $level The node level in question	 
	 * @param string $order Either DESC or ASC
	 * @param bool $isPublished TRUE | FALSE To get published sections
	 * @return array $subsections An array of associative arrays for all categories in the section
	 * @access public
   */
	public function getSubSectionsInSection($sectionId, $order = 'ASC', $isPublished = FALSE)
	{
		try {
			if($isPublished)
			{
	    //return all subsections
      return $this->getAll('WHERE published = 1 AND parentid = "'.$sectionId.'" ORDER BY ordering '.$order);
			} else {
				return $this->getAll('WHERE parentid = "'.$sectionId.'" ORDER BY ordering '.$order);
			}
		}catch (Exception $e){
       		echo 'Caught exception: ',  $e->getMessage();
        	exit();
    }
  }

	/** 
	 * Method to get all subsections in a specific root
	 *
	 * @param string $rootId The id(pk) of the section
	 * @param bool $isPublished TRUE | FALSE To get published sections
	 * @return array $subsections An array of associative arrays for all categories in the section
	 * @access public
   */
	public function getSubSectionsInRoot($rootId, $isPublished = FALSE)
	{
		try {
			if($isPublished)
			{
	    //return all subsections
      return $this->getAll('WHERE published = 1 AND rootid = "'.$rootId.'" ORDER BY ordering');
			} else {
				return $this->getAll('WHERE rootid = "'.$rootId.'" ORDER BY ordering');
			}
		}catch (Exception $e){
       		echo 'Caught exception: ',  $e->getMessage();
        	exit();
    }
  }

	/** 
	 * Method to get all subsections in a specific level
	 *
	 * @param string $rootId The id(pk) of the sections root node
	 * @param int $level The node level in question	 
	 * @param int $order Either DESC or ASC 
	 * @param bool $isPublished TRUE | FALSE To get published sections
	 * @return array $subsections An array of associative arrays for all sub sections in the section
	 * @access public
   */
	public function getSubSectionsForLevel($rootId, $level, $order = 'ASC', $isPublished = FALSE)
	{
		try {
			if($isPublished)
			{
	    //return all subsections
      return $this->getAll('WHERE published = 1 AND count = "'.$level.'" AND rootid = "'.$rootId.'" ORDER BY ordering '.$order);
			} else {
				return $this->getAll('WHERE count = "'.$level.'" AND rootid = "'.$rootId.'" ORDER BY ordering '.$order);
			}
		}catch (Exception $e){
       		echo 'Caught exception: ',  $e->getMessage();
        	exit();
    }
  }

	/** 
	 * Method to get the number of sub sections in a section
	 *
	 * @param string $sectionId The id(pk) of the section
	 * @return int $noSubSecs The number of subsections in the section
	 * @access public
   */
	public function getNumSubSections($sectionId)
	{
		try {
				$subSecs = $this->getAll('WHERE parentid = "'.$sectionId.'"');
				$noSubSecs = count($subSecs);
				return $noSubSecs;
		}catch (Exception $e){
       		echo 'Caught exception: ',  $e->getMessage();
        	exit();
    }
  }

	/** 
	 * Method to delete a section
	 *
	 * @param string $id The id(pk) of the section
	 * @return NULL
	 * @access public
   */
	public function deleteSection($id)
	{
     //if cat has nodes delete nodes as well
     if($this->hasNodes($id)){
       //get cat details 
       $category = $this->getSection($id);
       //get number of levels in section
       $this->objCmsUtils =& $this->newObject('cmsutils', 'cmsadmin');
       $numLevels = $this->objCmsUtils->getNumNodeLevels($category['id']);
       $parentId = $id;
       $nodeIdArray = array();
       $level = $category['count'] + '1';
       //get an array of all the cats nodes
       for($i = $level; $i <= $numLevels; $i++){
          $nodes = $this->getAll('WHERE parentid = "'.$parentId.'" AND count = "'.$i.'"');
          foreach($nodes as $node){
             $nodeIdArray[] = $node['id'];
          }
       }
       //delete each node in array
       foreach($nodeIdArray as $data){
          $this->_objDBContent->resetSection($data);
          $this->delete('id', $data);
       }   
       //delete original category
       $this->_objDBContent->resetSection($id);
       $this->delete('id', $id);
     } else {
         $this->_objDBContent->resetSection($id);
         $this->delete('id', $id);
     }
  }
	/** 
	 * Method to return the ordering value of new section (gets added last)
	 *
	 * @param string $parentid The id(pk) of the parent. Uses root node order if NULL
	 * @return int $ordering The value to insert into the ordering field
	 * @access public
	 * @author Warren Windvogel
   */
	public function getOrdering($parentid = NULL)
	{
		try {	
	   $ordering = 1;
     //get last order value 
     $lastOrder = $this->getAll('WHERE parentid = "'.$parentid.'" ORDER BY ordering DESC LIMIT 1');
     //add after this value
     if(!empty($lastOrder)){
       $ordering = $lastOrder['0']['ordering'] + 1;
     }  
     return $ordering;
		}catch (Exception $e){
       		echo 'Caught exception: ',  $e->getMessage();
        	exit();
    }
  }
	/**
	 * Method to return the links to be displayed in the order column on the table
	 * 
	 * @param string $id The id of the entry 
	 * @return string $links The html for the links
	 * @access public
	 * @return bool
	 * @author Warren Windvogel
	 */
	public function getOrderingLink($id)
	{
     //Get the parent id
     $entry = $this->getRow('id', $id);
     $parentId = $entry['parentid'];
     if(empty($parentId)){
	     //Get the number of root sections
	     $lastOrd = $this->getAll('WHERE count = 1 ORDER BY ordering DESC LIMIT 1');
	   } else {
         //Get the number of sub sections in section
         $lastOrd = $this->getAll('WHERE parentid = "'.$parentId.'" ORDER BY ordering DESC LIMIT 1');
     }  
	   $topOrder = $lastOrd['0']['ordering'];
	   $links = " ";
	   if($topOrder > '1'){
	     //Create geticon obj
	     $this->objIcon =& $this->newObject('geticon', 'htmlelements');
	     if($entry['ordering'] == '1'){
	       //return down arrow link
	       //icon
	       $this->objIcon->setIcon('downend');
	       //link
	       $downLink =& $this->newObject('link', 'htmlelements');
	       $downLink->href = $this->uri(array('action' => 'changesectionorder', 'id' => $id, 'ordering' => 'up', 'parent'=>$entry['parentid']));
	       $downLink->link = $this->objIcon->show();
	       $links .= $downLink->show();
       } else if($entry['ordering'] == $topOrder){
            //return up arrow
	          //icon
	          $this->objIcon->setIcon('upend');
	          //link
	          $upLink =& $this->newObject('link', 'htmlelements');
	          $upLink->href = $this->uri(array('action' => 'changesectionorder', 'id' => $id, 'ordering' => 'down', 'parent'=>$entry['parentid']));
	          $upLink->link = $this->objIcon->show();
	          $links .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $upLink->show();
       } else {
          //return both arrows
          //icon
	        $this->objIcon->setIcon('down');
	        //link
	        $downLink =& $this->newObject('link', 'htmlelements');
	        $downLink->href = $this->uri(array('action' => 'changesectionorder', 'id' => $id, 'ordering' => 'up', 'parent'=>$entry['parentid']));
	        $downLink->link = $this->objIcon->show();
	        //icon
	        $this->objIcon->setIcon('up');
	        //link
	        $upLink =& $this->newObject('link', 'htmlelements');
	        $upLink->href = $this->uri(array('action' => 'changesectionorder', 'id' => $id, 'ordering' => 'down', 'parent'=>$entry['parentid']));
	        $upLink->link = $this->objIcon->show();
          $links .= $downLink->show() . '&nbsp;' . $upLink->show();
       }
     }
     return $links;  
	}
	/**
	 * Method to update the order of the frontpage
	 * 
	 * @param string $id The id of the entry to move
	 * @param int $ordering How to update the order(up or down).
	 * @access public
	 * @return bool
	 * @author Warren Windvogel
	 */
	public function changeOrder($id, $ordering, $parentid)
	{
		try {	
	   //Get array of all sections in level
	   $fpContent = $this->getAll('WHERE parentid = "'.$parentid.'" ORDER BY ordering ');
	   //Search for entry to be reordered and update order
	   foreach($fpContent as $content){
	     if($content['id'] == $id){
         if($ordering == 'up'){
           $changeTo = $content['ordering'];
           $toChange = $content['ordering'] + 1;
           $updateArray = array(
			        'rootid' => $content['rootid'],
			        'parentid' => $content['parentid'],
							'title' => $content['title'],
							'menutext' => $content['menutext'],
							'image' => $content['image'],
							'image_position' => $content['image_position'],
							'access' => $content['access'],
							'layout' => $content['layout'],
							'description' => $content['description'],
							'published' => $content['published'],
							'ordering' => $toChange	
							);
           $this->update('id', $id, $updateArray);
         } else {
             $changeTo = $content['ordering'];
             $toChange = $content['ordering'] - 1;
             $updateArray = array(
			        'rootid' => $content['rootid'],
			        'parentid' => $content['parentid'],
							'title' => $content['title'],
							'menutext' => $content['menutext'],
							'image' => $content['image'],
							'image_position' => $content['image_position'],
							'access' => $content['access'],
							'layout' => $content['layout'],
							'description' => $content['description'],
							'published' => $content['published'],
							'ordering' => $toChange	
                );
             $this->update('id', $id, $updateArray);		
         }
       }
     }
     //Get other entry to change
     $entries = $this->getAll('WHERE parentid = "'.$parentid.'" AND ordering = "'.$toChange.'"');
     foreach($entries as $entry){
        if($entry['id'] != $id){
          $upArr = array(
			        'rootid' => $entry['rootid'],
			        'parentid' => $entry['parentid'],
							'title' => $entry['title'],
							'menutext' => $entry['menutext'],
							'image' => $entry['image'],
							'image_position' => $entry['image_position'],
							'access' => $entry['access'],
							'layout' => $entry['layout'],
							'description' => $entry['description'],
							'published' => $entry['published'],
							'ordering' => $changeTo	
             );
          $this->update('id', $entry['id'], $upArr);		
        }
     }
		}catch (Exception $e){
       		echo 'Caught exception: ',  $e->getMessage();
        	exit();
    }
  }
}
?>
