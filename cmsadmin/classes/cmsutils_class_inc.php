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
	 * @var  string $name The name of the field
	 * @return string
	 */
	public function  getImageList($name)
	{
		try {
			$objDropDown = & $this->newObject('dropdown', 'htmlelements');
			$objDropDown->name = $name;
			//fill the drop down with the list of images
			//TODO
			$objDropDown->addOption('0',' - Select Image - ');
			return $objDropDown->show();		
		}catch (Exception $e){
       		echo 'Caught exception: ',  $e->getMessage();
        	exit();
        }
	}
	
	/**
	 * Method to get the image position dropdown
	 * @access public
	 * @var  string $name The name of the field
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
	 * @var string $name The name of the radio box
	 * @access public
	 * @return string
	 */
	public function  getYesNoRadion($name)
	{
		try {
			$objRadio = & $this->newObject('radio', 'htmlelements');
			$objRadio->name = $name;
			$objRadio->addOption('0','No');		
			$objRadio->addOption('1','Yes');		
			$objRadio->setSelected('1');
			
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
		
		$str ='<table><tr>';
		for ($i = 0; $i < 4 ; $i++)
		{
			if($id == $i)
			{
				$checked = 'checked';
			} else {
				$checked = '';
				
			}
			$str .= '<td align="center"><input type="radio" name="'.$name.'" value="'.$i.'" class="transparentbgnb" id="input_layout0" '.$checked.' /><p><label for ="input_layout0"><img src ="'.$this->getResourceUri('section_'.$i.'.gif','cmsadmin').'"></label></td>';
			
		}
		
		$str .='</tr></table>';
		return $str;
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
			$link = & $this->newObject('link', 'htmlelements');
			$table = & $this->newObject('htmltable', 'htmlelements');
			
			$table->width='100%';
	        $table->cellspacing='0';
	        $table->cellpadding='0';
			
	        //add the home link
	        $link->link = 'Home';
	        $link->href = $this->uri(null, 'cms');
	        $link->cssClass = 'mainmenu1';
	        $table->startRow();
			$table->addCell($link->show() );
			$table->endRow();
			
			$arrSections = $this->_objSections->getSections();
			foreach ($arrSections as $section)
			{
				
				//add the sections
		        $link->link = $section['menutext'];
		        $link->href = $this->uri(array('action' => 'showsection', 'id' => $section['id']), 'cms');
		        $link->cssClass = 'mainmenu1';
		        $table->startRow();
				$table->addCell($link->show() );
				$table->endRow();
				
			}
			
			//add the admin link
			$link->link = 'Administration';
	        $link->href = $this->uri(null, 'cmsadmin');
	        $link->cssClass = 'mainmenu1';
	        $table->startRow();
			$table->addCell($link->show() );
			$table->endRow();
			
			return $table->show();
				
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
					$table->addCell($page['created']);
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
					
					$str .= $table->show();
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
					
					$str .= $table->show();
					
				}
				
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
		
			switch ($arrSection['layout'])
			{
				case null:
				case 'previous':
					return $this->_layoutPrevious($arrSection);
				case 'summaries':
					return $this->_layoutSummaries($arrSection);
				case 'page':
					return $this->_layoutPage($arrSection);
				case 'list':
					return $this->_layoutList($arrSection);
			}
			
			return 'this is the section '.	$sectionId;
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
	private function _layoutPrevious(&$arrSection)
	{
		
		try {
			$str = '<h3>'. $arrSection['title']."</h3>";
			
			$arrPages = $this->_objContent->getAll('WHERE sectionid = "'.$arrSection['id'].'" ORDER BY ordering');
			
			$cnt = 0;
			foreach ($arrPages as $page)
			{			
				$cnt++;
				if($cnt > 1)
				{
					$link = & $this->newObject('link', 'htmlelements');
					$link->link = $page['menutext'];
					$link->href = $this->uri(array('action' => 'showcontent', 'id' => $page['id']), 'cms');
					
					$str .= '<li>'. $page['created'].' - '.$link->show() .'</li> ';
				} else {
					$strBody = '<h3>'.$page['title'].'</h3>';
					$strBody .= $page['body'].'<p>';
				}
			}
	
			return $str.'<p>'.$strBody;
				
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
	private function _layoutSummaries(&$arrSection)
	{
		$str = '<h3>'. $arrSection['title']."</h3>";
		$objUser = & $this->newObject('user', 'security');
		
		$arrPages = $this->_objContent->getAll('WHERE sectionid = "'.$arrSection['id'].'" ORDER BY ordering');
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
			$table->addCell($page['created']);
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
			$str .= $table->show();
		}
		
		return $str;
		
	}
	
	/**
	 * Method to generate the layout for a section
	 * in 'Previous Layout'
	 * 
	 * @param array $arrSection The Section record
	 * @access private
	 * @return string
	 */
	private function _layoutPage(&$arrSection)
	{
		$heading = '<h3>'. $arrSection['title']."</h3>";
		
		$arrPages = $this->_objContent->getAll('WHERE sectionid = "'.$arrSection['id'].'" ORDER BY ordering');
		
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
		
	}
	
	/**
	 * Method to generate the layout for a section
	 * in 'Previous Layout'
	 * 
	 * @param array $arrSection The Section record
	 * @access private
	 * @return string
	 */
	private function _layoutList(&$arrSection)
	{
		
		$str = '<h3>'. $arrSection['title']."</h3>";
		
		$arrPages = $this->_objContent->getAll('WHERE sectionid = "'.$arrSection['id'].'" ORDER BY ordering');
		foreach ($arrPages as $page)
		{
			$link = & $this->newObject('link', 'htmlelements');
			$link->link = $page['title'];
			$link->href = $this->uri(array('action' => 'showcontent', 'id' => $page['id']),'cms');
			
			$str .= '<li>'.$page['created'].' - '. $link->show() .'</li>';
		}
		
		return $str;	
	}
	
	
	/**
	 * Method to show  the body of a pages
	 * 
	 * @access public
	 * @return string
	 */
	public function showBody()
	{
		$contentId = $this->getParam('id');
		$page = $this->_objContent->getContentPage($contentId);
		
		$strBody = '<h3>'.$page['title'].'</h3><p>';
		$strBody .= '<span class="warning">'.$this->_objUser->fullname($page['created_by']).'</span><p>';
		$strBody .= '<span class="warning">'.$page['created'].'</span><p>';
		$strBody .= $page['body'].'<p>';
		
		return $strBody;
	}
}