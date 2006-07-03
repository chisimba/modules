<?php

/* -------------------- dbTable class ----------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check
/**
* This object hold all the utility method that the cms modules might need
* @package cmsutils
* @category cmsutils
* @copyright 2004, University of the Western Cape & AVOIR Project
* @license GNU GPL
* @version
* @author Wesley  Nitsckie
* @example :
*/

class cmsutils extends object 
{
	
	/**
     * The sections  object 
     *
     * @access private
     * @var object
    */
    protected $_objSections;
    
    /**
     * The categories  object 
     *
     * @access public
     * @var object
    */
    protected $_objCategories;
    
     /**
     * The Content object 
     *
     * @access private
     * @var object
    */
    protected $_objContent;
    
     /**
     * The Content Front Page object 
     *
     * @access private
     * @var object
    */
    protected $_objFrontPage;
    
     /**
     * The User object
     *
     * @access private
     * @var object
    */
    protected $_objUser;
    
    

	/**
	 * Constructor
	 */
	public function init()
	{
		try {
			$this->_objSections = & $this->newObject('dbsections', 'cmsadmin');
			$this->_objCategories = & $this->newObject('dbcategories', 'cmsadmin');
			$this->_objContent = & $this->newObject('dbcontent', 'cmsadmin');
			$this->_objFrontPage = & $this->newObject('dbcontentfrontpage', 'cmsadmin');
			$this->_objUser = & $this->newObject('user', 'security');
			
			
		}catch (Exception $e){
       		echo 'Caught exception: ',  $e->getMessage();
        	exit();
        }
		
	}
	
	/**
	 * Method to reoder records
	 */
	public function reOrder()
	{
		try {
			
		}catch (Exception $e){
       		echo 'Caught exception: ',  $e->getMessage();
        	exit();
        }
		
		
	}
	
	
	/**
	 * Method to detemine the access 
	 * @param int $access The access 
	 * @return string
	 * @access public
	 */
	public function getAccess($access)
	{
		try {
			if($access == 1)
			{
				return 'Registered';
			} else {
				return 'Public';
			}
		}catch (Exception $e){
       		echo 'Caught exception: ',  $e->getMessage();
        	exit();
        }
	}
	
	
	/**
	 * Method to get the images dropdown
	 * @access public
	 * @param   string $name The name of the field
	 * @param  string $selected the selected value
	 * @return string
	 */
	public function  getImageList($name, $selected)
	{
		try {
			$objDropDown = & $this->newObject('dropdown', 'htmlelements');
			$objConfig = & $this->newObject('altconfig' , 'config');
			
			$objMedia = & $this->newObject('mmutils', 'mediamanager');
			$objMedia->getImages();
			$objDropDown->name = $name;
			//fill the drop down with the list of images
			$path = $objConfig->getsiteRoot().'usrfiles/media';
			
			$objDropDown->addOption('0',' - Select Image - ');
			$objDropDown->addFromDB($objMedia->getImages(),'title','folder',$selected);
			$objDropDown->extra = 'onchange="javascript:if (document.forms[0].'.$name.'.options[selectedIndex].value!=\'\') {document.imagelib.src=\''. $path.'\' + document.forms[0].image.options[selectedIndex].value} else {document.imagelib.src=\'http://localhost/5ive/app/skins/_common/blank.png\'}"';
			return $objDropDown->show();		
		}catch (Exception $e){
       		echo 'Caught exception: ',  $e->getMessage();
        	exit();
        }
	}	
	
	
	
	
	/**
	 * Method to get the image position dropdown
	 * @access public
	 * @param   string $name The name of the field
	 * @return string
	 */
	public function  getImagePostionList($name)
	{
		try {
			$objDropDown = & $this->newObject('dropdown', 'htmlelements');
			$objDropDown->name = $name;
			//fill the drop down with the list of images
			//TODO
			$objDropDown->addOption('0','Centre');
			$objDropDown->addOption('1','Left');
			$objDropDown->addOption('2','Right');
			$objDropDown->setSelected('1');
			$objDropDown->extra = 'size="3"';
			return $objDropDown->show();		
		}catch (Exception $e){
       		echo 'Caught exception: ',  $e->getMessage();
        	exit();
        }
	}
	
	
	/**
	 * Method to get the Yes/No radio  box
	 * 
	 * @param  string $name The name of the radio box
	 * @access public
	 * @return string
	 */
	public function  getYesNoRadion($name, $selected = 'Yes')
	{
		try {
			$objRadio = & $this->newObject('radio', 'htmlelements');
			$objRadio->name = $name;
			$objRadio->addOption('0','No');		
			$objRadio->addOption('1','Yes');		
			$selected = ($selected == 'No') ? '0' : '1';
			$objRadio->setSelected($selected);
			
			return $objRadio->show();		
		}catch (Exception $e){
       		echo 'Caught exception: ',  $e->getMessage();
        	exit();
        }
	}
	
	/**
	 * Method to get the Access List dropdown
	 * 
	 * @var string $name The name of the radio box
	 * @access public
	 * @param string $name The name of the field
	 * @return string
	 */
	public function  getAccessList($name)
	{
		try {
			$objDropDown = & $this->newObject('dropdown', 'htmlelements');
			$objDropDown->name = $name;
			//fill the drop down with the list of images
			//TODO
			$objDropDown->addOption('0', 'Public');
			$objDropDown->addOption('1', 'Registered');
			$objDropDown->setSelected('0');
			$objDropDown->extra = 'size="2"';
			return $objDropDown->show();
		}catch (Exception $e){
       		echo 'Caught exception: ',  $e->getMessage();
        	exit();
        }
		
	}
	
	
	/**
	 * 
	 * Method to get the layout options for a section
	 * At the moment there are 4 types of layouts
	 * The layouts will be diplayed as images for selection
	 * The layouts templates will be displayed as images
	 * 
	 * @param string $name The of the of the field
	 * @return string 
	 * @access public
	 */
	public function getLayoutOptions($name, $id)
	{
		try {
			$objLayouts = & $this->newObject('dblayouts', 'cmsadmin');
			$arrLayouts = $objLayouts->getLayouts();
			$arrSection = $this->_objSections->getSection($id);
			$str ='<table><tr>';
			foreach ($arrLayouts as $layout)
			{
			
				if($arrSection['layout'] == $layout['id'])
				{
					$checked = 'checked';
				} else {
					$checked = '';
					
				}
				$str .= '<td align="center"><input type="radio" name="'.$name.'" value="'.$layout['id'].'" class="transparentbgnb" id="input_layout0" '.$checked.' /><p><label for ="input_layout0"><img src ="'.$this->getResourceUri($layout['imagename'],'cmsadmin').'"></label></td>';
				
			}
			
			$str .='</tr></table>';
			return $str;
		}catch (Exception $e){
       		echo 'Caught exception: ',  $e->getMessage();
        	exit();
        }
	}
	
	
	/**
	 * Method to generate the Sections Menu 
	 * that will appear on the left side of the menu
	 * 
	 * @access public
	 * @return string
	 * 
	 */
	public  function getSectionMenu()
	{
		try {
			
			//initiate the objects
			$objSideBar = $this->newObject('sidebar', 'navigation');
			
			//create the nodes array
			$nodes = array();
			
			//get the section id
			$section = $this->getParam('id');
			
			//create the home like first
			//$nodes[] = array('text' => 'Home', 'uri' => $this->uri(null, 'cms'));
						
			//get the all the sections from the database
			$arrSections = $this->_objSections->getSections(TRUE);
			
			
			//start looping through the sections
			foreach ($arrSections as $section)
			{
				
				//add the sections
		        if(($this->getParam('action') ==  'showsection') && ($this->getParam('id') == $section['id']) || $this->getParam('sectionid') == $section['id'])
		        {
		        	
		        	$pagenodes = array();
		        	$arrPages = $this->_objContent->getAll('WHERE sectionid = "'.$section['id'].'" AND published=1 ORDER BY ordering');
		        	
		        	foreach( $arrPages as $page)
		        	{
		        		$pagenodes[] = array('text' => $page['menutext'] , 'uri' =>$this->uri(array('action' => 'showfulltext', 'id' => $page['id'], 'sectionid' => $section['id']), 'cms'));
		        		
		        	}
		        	
		        	$nodes[] = array('text' =>$section['menutext'], 'uri' => $this->uri(array('action' => 'showsection', 'id' => $section['id']), 'cms'), 'sectionid' => $section['id'], 'haschildren' => $pagenodes);
		        	$pagenodes = null;
		        	
		        } else {
		        	$nodes[] = array('text' =>$section['menutext'], 'uri' => $this->uri(array('action' => 'showsection', 'id' => $section['id']), 'cms'), 'sectionid' => $section['id']);	
		        }
				
			}
			//add the admin link
			$nodes[] = array('text' => 'Administration', 'uri' =>$this->uri(null, 'cmsadmin'));
						
			return $objSideBar->show($nodes, $this->getParam('id'));
		}catch (Exception $e){
       		echo 'Caught exception: ',  $e->getMessage();
        	exit();
        }
	}
	
	
	
	/**
	 * Method to get the Front Page Content
	 * in a ordered way. It should also conform to the 
	 * section template for the section that this page is in
	 * 
	 * @return string
	 * @access public
	 */
	public function getFrontPageContent()
	{
		try {
			$objUser = & $this->newObject('user', 'security');
			$arrFrontPages = $this->_objFrontPage->getFrontPages();
			$objFeatureBox = $this->newObject('featurebox', 'navigation');
			
			//set a counter for the records .. display on the first 2  the rest will be dsiplayed as links
			$cnt  = 0 ;
			
			foreach ($arrFrontPages as $frontPage) 
			{
				
				//get the page
				$page = $this->_objContent->getContentPage($frontPage['content_id'])	;
			
				$cnt++;
				if($cnt < 5)
				{
					//display the intro text
					$table = & $this->newObject('htmltable', 'htmlelements');
					
					//title
					$table->startRow();
					$table->addHeader(array($page['title']));
					$table->endRow();
					
					//author
					$table->startRow();
					$table->addCell('Written by '.$objUser->fullname($page['creator_by']));
					$table->endRow();
					
					//date
					$table->startRow();
					$table->addCell($this->formatDate($page['created']));
					$table->endRow();
					
					//intor text
					$table->startRow();
					$table->addCell('<p>'.$page['introtext']);
					$table->endRow();
					
					if(!$page['fulltext'] == '')
					{
						//read more link .. link to the full text
						$link = & $this->newObject('link', 'htmlelements');
						$link->link = 'Read more ..';
						$link->href = $this->uri(array('action' => 'showfulltext', 'id' => $page['id']), 'cms');
						
						$table->startRow();
						$table->addCell($link->show());
						$table->endRow();
						
					}
					
					$str .= '';//$table->show();
				} else {
					//display as links
					
					$table = & $this->newObject('htmltable', 'htmlelements');
					$link = & $this->newObject('link', 'htmlelements');
					
					$link->link = $page['title'];
					$link->href = $this->uri(array('action' => 'showfulltext', 'id' => $page['id']), 'cms');
						
					//title
					$table->startRow();
					$table->addCell($link->show());
					$table->endRow();
					
					//$str .= $table->show();
					
				}
				
				//make feature boxes of the front page post
				
				//$str .= '<h4><span class="date">'.$this->formatDate($page['created']).'</span> '.$page['title'].'</h4>';
				//$str .= '<p>'.$page['introtext'].'<a href="devtodo" class="morelink" title="'.$page['title'].'">More <span>about: '.$page['title'].'</span></a></p>';
				$content = '<span class="date">'.$this->formatDate($page['created']).'</span> <p>'.$page['introtext'].'<a href="devtodo" class="morelink" title="'.$page['title'].'">More <span>about: '.$page['title'].'</span></a></p>';
				$str .= $objFeatureBox->show($page['title'], $content);
			}
			return $str;
				
		}catch (Exception $e){
       		echo 'Caught exception: ',  $e->getMessage();
        	exit();
        }
	}
	
	
	
	/**
	 * Method to generate the content for a section
	 * 
	 * @access public
	 * @return string
	 */
	public function showSection()
	{
		try {
			
			$sectionId = $this->getParam('id');
			
			//get the section record
			$arrSection = $this->_objSections->getSection($sectionId);
			
			//get the layout for this section
		
			$objLayouts = & $this->newObject('dblayouts', 'cmsadmin');
		
			$arrLayout = $objLayouts->getLayout($arrSection['layout']);	
			$functionVariable = '_layout'.trim($arrLayout['name']);
		
			//call the right function according to the layout of the section
			return call_user_func(array('cmsutils',$functionVariable),$arrSection);
			
			
		}catch (Exception $e){
       		echo 'Caught exception: ',  $e->getMessage();
        	exit();
        }
	}
	
	
	/**
	 * Method to generate the layout for a section
	 * in 'Previous Layout'
	 * 
	 * @param array $arrSection The Section record
	 * @access private
	 * @return string
	 */
	 function _layoutPrevious(&$arrSection)
	{
		
		try {
			$heading = '<h3>'. $arrSection['title']."</h3>";
			
			$arrPages = $this->_objContent->getAll('WHERE sectionid = "'.$arrSection['id'].'" AND published=1 ORDER BY ordering');
			
			$cnt = 0;
			foreach ($arrPages as $page)
			{			
				$cnt++;
				if($cnt > 1)
				{
					$link = & $this->newObject('link', 'htmlelements');
					$link->link = $page['menutext'];
					$link->href = $this->uri(array('action' => 'showcontent', 'id' => $page['id']), 'cms');
					
					$str .= '<li>'. $this->formatDate($page['created']).' - '.$link->show() .'</li> ';
				} else {
					$strBody = '<h3>'.$page['title'].'</h3>';
					$strBody .= $page['body'].'<p>';
				}
			}
	
			return $heading.$strBody.'<p>'.$str;
				
		}catch (Exception $e){
       		echo 'Caught exception: ',  $e->getMessage();
        	exit();
        }
		
	}
	
	
	
	/**
	 * Method to generate the layout for a section
	 * in 'Previous Layout'
	 * 
	 * @param array $arrSection The Section record
	 * @access private
	 * @return string
	 */
	 function _layoutSummaries(&$arrSection)
	{
		try{
			
			$objUser = & $this->newObject('user', 'security');
			$objConfig = & $this->newObject('altconfig', 'config');
			
			$str = '<h3>'. $arrSection['title'].'</h3><img src="'.$objConfig->getSiteRoot().'usrfiles/media'.$arrSection['image'].'" >';
			
			$arrPages = $this->_objContent->getAll('WHERE sectionid = "'.$arrSection['id'].'" AND published=1 ORDER BY ordering');
			foreach ($arrPages as $page)
			{
				
				
				//display the intro text
				$table = & $this->newObject('htmltable', 'htmlelements');
				
				//title
				$table->startRow();
				$table->addHeader(array($page['title']));
				$table->endRow();
				
				//author
				$table->startRow();
				$table->addCell('Written by '.$objUser->fullname($page['creator_by']));
				$table->endRow();
				
				//date
				$table->startRow();
				$table->addCell($this->formatDate($page['created']));
				$table->endRow();
				
				//intor text
				$table->startRow();
				$table->addCell('<p>'.$page['introtext']);
				$table->endRow();
				
				if(!$page['body'] == '')
				{
					//read more link .. link to the full text
					$link = & $this->newObject('link', 'htmlelements');
					$link->link = 'Read more ..<p><p>';
					$link->href = $this->uri(array('action' => 'showfulltext', 'id' => $page['id']), 'cms');
					
					$table->startRow();
					$table->addCell($link->show());
					$table->endRow();
					
				}
				//$str .= $table->show();
				$str .= '<h4><span class="date">'.$this->formatDate($page['created']).'</span> '.$page['title'].'</h4>';
				$uri = $this->uri(array('action' => 'showfulltext', 'sectionid' => $arrSection['id'], 'id' => $page['id']), 'cms');
				$str .= '<p>'.$page['introtext'].'<a href="'.$uri.'" class="morelink" title="'.$page['title'].'">More <span>about: '.$page['title'].'</span></a></p>';

			}
			
			return $str;
		}catch (Exception $e){
       		echo 'Caught exception: ',  $e->getMessage();
        	exit();
        }
	}
	
	/**
	 * Method to generate the layout for a section
	 * in 'Previous Layout'
	 * 
	 * @param array $arrSection The Section record
	 * @access private
	 * @return string
	 */
	 function _layoutPage(&$arrSection)
	{
		try {
			$heading = '<h3>'. $arrSection['title']."</h3>";
			
			$arrPages = $this->_objContent->getAll('WHERE sectionid = "'.$arrSection['id'].'" AND published=1 ORDER BY ordering');
			
			$cnt = 0;
			foreach ($arrPages as $page)
			{			
				$cnt++;
				if($cnt > 1)
				{
					$link = & $this->newObject('link', 'htmlelements');
					$link->link = $page['menutext'];
					$link->href = $this->uri(array('action' => 'showcontent', 'id' => $page['id']), 'cms');
					
					$str .= $link->show() .' | ';
				} else {
					$strBody = '<h3>'.$page['title'].'</h3>';
					$strBody .= $page['body'].'<p>';
				}
			}
			
			return $heading.$strBody.'<p>'.$str;
		}catch (Exception $e){
       		echo 'Caught exception: ',  $e->getMessage();
        	exit();
        }
		
	}
	
	/**
	 * Method to generate the layout for a section
	 * in 'Previous Layout'
	 * 
	 * @param array $arrSection The Section record
	 * @access private
	 * @return string
	 */
	 function _layoutList(&$arrSection)
	{
		try {
			$str = '<h3>'. $arrSection['title']."</h3>";
			
			$arrPages = $this->_objContent->getAll('WHERE sectionid = "'.$arrSection['id'].'" AND published=1 ORDER BY ordering');
			foreach ($arrPages as $page)
			{
				$link = & $this->newObject('link', 'htmlelements');
				$link->link = $page['title'];
				$link->href = $this->uri(array('action' => 'showcontent', 'id' => $page['id']),'cms');
				
				$str .= '<li>'.$this->formatDate($page['created']).' - '. $link->show() .'</li>';
			}
			
			return $str;	
		}catch (Exception $e){
       		echo 'Caught exception: ',  $e->getMessage();
        	exit();
        }
	}
	
	
	/**
	 * Method to show  the body of a pages
	 * 
	 * @access public
	 * @return string
	 */
	public function showBody()
	{
		try {
			$contentId = $this->getParam('id');
			$page = $this->_objContent->getContentPage($contentId);
			
			$strBody = '<h3>'.$page['title'].'</h3><p>';
			$strBody .= '<span class="warning">'.$this->_objUser->fullname($page['created_by']).'</span><p>';
			$strBody .= '<span class="warning">'.$page['created'].'</span><p>';
			$strBody .= $page['body'].'<p>';
			
			return $strBody;
		}catch (Exception $e){
       		echo 'Caught exception: ',  $e->getMessage();
        	exit();
        }
	}
	
	/**
	 * Method to format the date
	 * 
	 * @example Thursday, 12 November 2006
	 * @param  date $date The unformatted date
	 * @return formatted date string
	 * @access public
	 * @version 0.1
	 * @author Wesley Nitsckie
	 * @copyright 2004, University of the Western Cape & AVOIR Project
	 * @license GNU GPL
	 */
	public function formatDate($date)
	{
		try {
				return  date("l, d F o", gmmktime($date));
			}
			catch (Exception $e){
       			echo 'Caught exception: ',  $e->getMessage();
        		exit();
        	}
		
	}
	
	
	/**
	 * Method to format the date
	 * 
	 * @example 01/12/2006
	 * @param  date $date The unformatted date
	 * @return formatted date string
	 * @access public
	 * @version 0.1
	 * @author Wesley Nitsckie
	 * @copyright 2004, University of the Western Cape & AVOIR Project
	 * @license GNU GPL
	 */
	public function formatShortDate($date)
	{
		try {
				return  date("m/d/o",gmmktime($date) );
			}
			catch (Exception $e){
       			echo 'Caught exception: ',  $e->getMessage();
        		exit();
        	}
		
	}
	
	/**
	 * Method resolve reordering of pages
	 * 
	 * @param  
	 * @return
	 * @access public
	 * @version 0.1
	 * @author Wesley Nitsckie
	 * @copyright 2004, University of the Western Cape & AVOIR Project
	 * @license GNU GPL
	 */
	public function _reOrder()
	{
		try {
		
		}catch (Exception $e){
       		echo 'Caught exception: ',  $e->getMessage();
        	exit();
        }
		
		
	}
	
	/**
	 * Method to the true/false tick
	 * 
	 * @param  $isCheck Booleans value with either TRUE|FALSE
	 * @return string icon
	 * @access public
	 * @version 0.1
	 * @author Wesley Nitsckie
	 * @copyright 2004, University of the Western Cape & AVOIR Project
	 * @license GNU GPL
	 */
	public function getCheckIcon($isCheck, $returnFalse = TRUE)
	{
		try {
			$objIcon = & $this->newObject('geticon', 'htmlelements');
			
			if($isCheck)
			{
				$objIcon->setIcon('greentick');
			} else {
				if($returnFalse)
				{
					$objIcon->setIcon('redcross');
				}
			}
			
			
			return $objIcon->show();
		
		}catch (Exception $e){
       		echo 'Caught exception: ',  $e->getMessage();
        	exit();
        }
		
		
	}
	
	
	/**
	 * Method to generate the navigation
	 * 
	 * @access public
	 * @return string
	 */
	public function getNav()
	{
		
		$link = & $this->newObject('link', 'htmlelements');
		
		
		//content link
		$link->link = 'Content';
		$link->href = $this->uri(array('action' => 'content'));
		$str.= '<p>'.$link->show();
		//sections link
		
		$link->link = 'Sections';
		$link->href = $this->uri(array('action' => 'sections'));
		$str .= '<p>'.$link->show();
		
		//categories link
		$link->link = 'Categories';
		$link->href = $this->uri(array('action' => 'categories'));
		$str .= '<p>'.$link->show();
		
		//categories link
		$link->link = 'Media';
		$link->href = $this->uri(null, 'mediamanager');
		$str .= '<p>'.$link->show();
		
		$nodes = array();
		$nodes[] = array('text' => 'Content', 'uri' => $this->uri(array('action' => 'content')));
		$nodes[] = array('text' => 'Sections', 'uri' => $this->uri(array('action' => 'sections')));
		$nodes[] = array('text' => 'Categories', 'uri' => $this->uri(array('action' => 'categories')));
		$nodes[] = array('text' => 'Media', 'uri' => $this->uri(null,'mediamanager'))	;	
		
		$objNav = $this->newObject('sidebar', 'navigation');
		
		return $objNav->show($nodes);

	}
	
	/**
	 * Method to show the full content of a page
	 * 
	 * @access public 
	 * @return string
	 * @param $string contentId The id of the content
	 * @param string sectionId The section Id
	 * 
	 */
	public function getFullContent($contentId, $sectionId)
	{
		
		
		
	}
	
}