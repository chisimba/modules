<?php

/* -------------------- dbTable class ----------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check
/**
* This object hold all the utility method that the cms modules might need
* @package cms
* @category cmsadmin
* @copyright 2004, University of the Western Cape & AVOIR Project
* @license GNU GPL
* @version
* @author Wesley  Nitsckie
* @author Warren Windvogel
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
			$this->_objContent = & $this->newObject('dbcontent', 'cmsadmin');
			$this->_objFrontPage = & $this->newObject('dbcontentfrontpage', 'cmsadmin');
			$this->_objUser = & $this->newObject('user', 'security');
      $this->objLanguage =& $this->newObject('language', 'language');

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
	public function  getImageList($name, $formName, $selected = null)
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
			$objDropDown->extra = 'onchange=" return changeImage(this, this.form) "';
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
			$firstOneChecked = 'checked="checked"';
			foreach ($arrLayouts as $layout)
			{

				if($arrSection['layout'] == $layout['id'])
				{
					$checked = 'checked="checked"';
				} else {
					$checked = '';

				}
				$checked = $firstOneChecked;
				$firstOneChecked = '';
				$str .= '<td align="center">
				            <input type="radio" name="'.$name.'" value="'.$layout['id'].'" class="transparentbgnb" id="input_layout0" '.$checked.' />
				                <p/>
				                <label for ="input_layout0">
				                    <img src ="'.$this->getResourceUri($layout['imagename'],'cmsadmin').'"/>
				                </label>
				         </td>';

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
	public  function getSectionMenu($modulename = null)
	{
		try {
			if(empty($modulename))
			{
			    $modulename = 'cms';
			}
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
		        	$arrPages = $this->_objContent->getAll('WHERE sectionid = "'.$section['id'].'" AND published=1 and trash=0 ORDER BY ordering');

		        	foreach( $arrPages as $page)
		        	{
		        		$pagenodes[] = array('text' => $page['menutext'] , 'uri' =>$this->uri(array('action' => 'showfulltext', 'id' => $page['id'], 'sectionid' => $section['id']), $modulename));

		        	}

		        	$nodes[] = array('text' =>$section['menutext'], 'uri' => $this->uri(array('action' => 'showsection', 'id' => $section['id']), $modulename), 'sectionid' => $section['id'], 'haschildren' => $pagenodes);
		        	$pagenodes = null;

		        } else {
		        	$nodes[] = array('text' =>$section['menutext'], 'uri' => $this->uri(array('action' => 'showsection', 'id' => $section['id']), $modulename), 'sectionid' => $section['id']);
		        }

			}
			//add the admin link
			$nodes[] = array('text' => 'Administration', 'uri' =>$this->uri(array(NULL), 'cmsadmin'));

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
			$str = '';
			//set a counter for the records .. display on the first 2  the rest will be dsiplayed as links
			$cnt  = 0 ;

			if(count($arrFrontPages))
			{
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
					$table->addCell('Written by '.$objUser->fullname($page['created_by']));
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
				$moreLink = $this->uri(array('action' => 'showfulltext', 'sectionid' => $page['sectionid'], 'id' => $page['id']), 'cms');
				$content = '<span class="date">'.$this->formatDate($page['created']).'</span> <p>'.$page['introtext'].'<a href="'.$moreLink.'" class="morelink" title="'.$page['title'].'">More <span>about: '.$page['title'].'</span></a></p>';
				$str .= $objFeatureBox->show($page['title'], $content);
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
	public function showSection($module = "cms")
	{
		try {

			$sectionId = $this->getParam('id');

			//get the section record
			$arrSection = $this->_objSections->getSection($sectionId);

			//get the layout for this section

			$objLayouts = & $this->newObject('dblayouts', 'cmsadmin');

			$arrLayout = $objLayouts->getLayout($arrSection['layout']);
			$arrLayout['name'] = ($arrLayout['name']=='') ? 'List' : $arrLayout['name'];
			$functionVariable = '_layout'.trim($arrLayout['name']);

			//call the right function according to the layout of the section
			return call_user_func(array('cmsutils',$functionVariable),$arrSection,$module);


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
	 function _layoutPrevious(&$arrSection, $module)
	{

		try {
		    if(!empty($arrSection['image']))
		    {
		        $image = $this->generateImageTag($arrSection['image']);
		    } else {
		        $image = '';
		    }


			$arrPages = $this->_objContent->getAll('WHERE sectionid = "'.$arrSection['id'].'" AND published=1 ORDER BY ordering');

			$cnt = 0;
			$strBody = '';
			$str = '';
			foreach ($arrPages as $page)
			{
				$cnt++;
				if($cnt > 1)
				{
					$link = & $this->newObject('link', 'htmlelements');
					$link->link = $page['menutext'];
					$link->href = $this->uri(array('action' => 'showcontent', 'id' => $page['id']), $module);

					$str .= '<li>'. $this->formatDate($page['created']).' - '.$link->show() .'</li> ';
				} else {
					$strBody = '<h3>'.$page['title'].'</h3>';
					$strBody .= $page['body'].'<p/>';
				}
			}

			return $strBody.'<p/>'.$str;

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
	 function _layoutSummaries(&$arrSection, $module)
	{
		try{
		    if(!empty($arrSection['image']))
		    {
		        $image = $this->generateImageTag($arrSection['image']);
		    } else {
		        $image = '';
		    }

			$objUser = & $this->newObject('user', 'security');
			$objConfig = & $this->newObject('altconfig', 'config');

			$str = '';

			$arrPages = $this->_objContent->getAll('WHERE sectionid = "'.$arrSection['id'].'" AND published=1 and trash=0  ORDER BY ordering');
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
				if(!isset($page['creator_by']))
				{
					$page['creator_by'] = $objUser->fullname();
				}
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

/*
				if($page['body'])
				{
					//read more link .. link to the full text
					$link = & $this->newObject('link', 'htmlelements');
					$link->link = 'Read more ..<p/><p/>';
					$link->href = $this->uri(array('action' => 'showfulltext', 'id' => $page['id']), $module);

					$table->startRow();
					$table->addCell($link->show());
					$table->endRow();

				}
				*/
				//$str .= $table->show();
				$str .= '<h4><span class="date">'.$this->formatDate($page['created']).'</span> '.$page['title'].'</h4>';
				$uri = $this->uri(array('action' => 'showfulltext', 'sectionid' => $arrSection['id'], 'id' => $page['id']), $module);
				$str .= '<p>'.$page['introtext'].'<br /><a href="'.$uri.'" class="morelink" title="'.$page['title'].'">Read more...</a></p>';

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
	 function _layoutPage(&$arrSection, $module)
	{
		try {
		    if(!empty($arrSection['image']))
		    {
		        $image = $this->generateImageTag($arrSection['image']);
		    } else {
		        $image = '';
		    }


			$arrPages = $this->_objContent->getAll('WHERE sectionid = "'.$arrSection['id'].'" AND published=1 and trash=0  ORDER BY ordering');

			$cnt = 0;
			$strBody = '';
			$str = '';
			foreach ($arrPages as $page)
			{
				$cnt++;
				if($cnt > 1)
				{
					$link = & $this->newObject('link', 'htmlelements');
					$link->link = $page['menutext'];
					$link->href = $this->uri(array('action' => 'showcontent', 'id' => $page['id'],'sectionid' => $page['sectionid']), $module);

					$str .= $link->show() .' | ';
				} else {
					$strBody = '<h3>'.$page['title'].'</h3>';
					$strBody .= $page['introtext'].'<p/>';
					$strBody .= $page['body'].'<p/>';
				}
			}



			return $strBody.'<p/>'.$str;
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
	 function _layoutList(&$arrSection, $module)
	{
		try {
		     if(!empty($arrSection['image']))
		    {
		        $image = $this->generateImageTag($arrSection['image']);
		    } else {
		        $image = '';
		    }

			$str = '';

			$arrPages = $this->_objContent->getAll('WHERE sectionid = "'.$arrSection['id'].'" AND published=1 and trash=0 ORDER BY ordering');
			foreach ($arrPages as $page)
			{
				$link = & $this->newObject('link', 'htmlelements');
				$link->link = $page['title'];
				$link->href = $this->uri(array('action' => 'showcontent', 'id' => $page['id'],'sectionid' => $page['sectionid']), $module);

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

			$strBody = '<h3>'.$page['title'].'</h3><p/>';
			$strBody .= '<span class="warning">'.$this->_objUser->fullname($page['created_by']).'</span><br />';
			$strBody .= '<span class="warning">'.$page['created'].'</span><p/>';
			$strBody .= $page['introtext'].'<p/>';
			$strBody .= $page['body'].'<p/>';

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
		      /*if(!checkdate($date))
		      {
		        $gm =  gmmktime($date);
				return  date("l, d F o",$gm);
		      } else {
*/
		      return $date;
	//	      }
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
		      return $date;
				//return  date("m/d/o",gmmktime($date) );
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
				$objIcon->setIcon('ok','png');
			} else {
				if($returnFalse)
				{
					$objIcon->setIcon('failed', 'png');
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
    $objCmsTree =& $this->newObject('cmstree', 'cmsadmin');
		$link = & $this->newObject('link', 'htmlelements');
		$str = '';

		//content link
		$link->link = $this->objLanguage->languageText('word_content');
		$link->href = $this->uri(array('action' => 'content'));
		$str.= '<p>'.$link->show();
		//sections link

		$link->link = $this->objLanguage->languageText('word_section');
		$link->href = $this->uri(array('action' => 'sections'));
		$str .= '<p>'.$link->show();

		$button =& $this->newObject('navbuttons', 'navigation');
		$viewCmsLink = $button->pseudoButton($this->uri(array(NULL), 'cms'), $this->objLanguage->languageText('mod_cmsadmin_viewcms', 'cmsadmin'));

		//media link
		$link->link = $this->objLanguage->languageText('word_media');
		$link->href = $this->uri(null, 'mediamanager');
		$str .= '<p>'.$link->show();

		$nodes = array();
		$nodes[] = array('text' => $this->objLanguage->languageText('phrase_frontpage'), 'uri' => $this->uri(array('action' => 'frontpages')));
		$nodes[] = array('text' => $this->objLanguage->languageText('word_section'), 'uri' => $this->uri(array('action' => 'sections')));
		
		$objNav = $this->newObject('sidebar', 'navigation');

		$nav = $objNav->show($nodes);
		//$nav .= $objCmsTree->show(NULL, TRUE);
		$nav .= $this->getSectionLinks();
    $nav .= '<br/>';
		$nav .= $viewCmsLink;
		
		return $nav;

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

	/**
	* Method to generate the bread crumbs
	* @param void
	* @return string
	* @access public
	*/
	public function getBreadCrumbs($module = 'cms')
	{
		if ($this->getParam('action') == '')
		{
			return '';
		}

		$link = &  $this->newObject('link', 'htmlelements');
		$link->href = $this->uri(null , $module);
		$link->link = 'Home';
		$str = $link->show() .' / ';

		if ($this->getParam('action') == 'showsection')
		{

			$str .= $this->_objSections->getMenuText($this->getParam('id'));
		}

		if($this->getParam('action') == 'showfulltext')
		{
			$link->href = $this->uri(array('action' => 'showsection', 'id' => $this->getParam('sectionid')) , $module);
			$link->link = $this->_objSections->getMenuText($this->getParam('sectionid'));
			$str .= $link->show() .' / ';
			$page = $this->_objContent->getContentPage($this->getParam('id'));
			$str .= $page['menutext'];

		}
		return '<div id="breadcrumb">'. $str .'</div>';
	}


	/**
	 * Method to generate the img tag for the section
	 * thumbnail
	 *
	 * @param string src The path the image
	 * @return string
	 * @access public
	 * @author Wesley Nitsckie
	 */
	public function generateImageTag($src)
	{
	    $objSkin = $this->newObject('skin', 'skin');

	    return '<span class="thumbnail"><center><img src="'.$objSkin->getSkinUrl().$src.'" /></center></span>';
	}

	/**
	 * Method to generate the dropdown with tree indentations for selecting parent category
	 *
	 * @return string Generated HTML for the dropdown
	 * @access public
	 * @author Warren Windvogel
	 */
	public function getTreeDropdown($setSelected = NULL, $noRoot = FALSE)
	{
	    //Create dropdown
      $treeDrop =& $this->newObject('dropdown', 'htmlelements');
	    $treeDrop->name = 'parent';
	    if(!$noRoot){
	      $treeDrop->addOption('0', ' ... Root Level ... ');
      }
	    //Create instance of geticon object
	    $objIcon =& $this->newObject('geticon', 'htmlelements');
	    //Get all root sections
	    $availsections = $this->_objSections->getRootNodes();
	    if(!empty($availsections)){
	      //initiate sequential tree structured array to be inserted into dropdown
	      $treeArray = array();
	      //Get icon for root nodes
        $objIcon->setIcon('tree/treebase');	      
	      //add nodes for each section
	      foreach($availsections as $section){
	         //initiate prefix for nodes
	         $prefix = '';
	         //add root(secion) to dropdown
 	         $treeArray[] = array('title' => $objIcon->show().$section['menutext'], 'id' => $section['id']);
	         //get number of node levels
           $numLevels = $this->getNumNodeLevels($section['id']);
           //check if section has sub sections
           if($numLevels > '0'){
	           //Get icon for parent child nodes
             $objIcon->setIcon('tree/treefolder_orange');	      
             //loop through each level and add all sub sections in level
             for($i = '2'; $i <= $numLevels; $i++){
                $prefix .= '- ';
                //get all sub secs in section on level
                $subSecs = $this->_objSections->getSubSectionsForLevel($section['id'], $i, 'DESC');
                foreach($subSecs as $sec){
                  //if its the 1st node just add it under the section
                  if($i == '2'){
                    $treeArray[] = array('title' => $prefix.$objIcon->show().$sec['menutext'], 'id' => $sec['id']);
                  //else find the parent node and include it after this node
                  } else {
                      $parentId = $sec['parentid'];
                      $subSecTitle = $this->_objSections->getMenuText($parentId);
                      $count = $this->_objSections->getLevel($parentId);
                      $searchPrefix = "";
                      for($num = '2'; $num <= $count; $num++){
                         $searchPrefix .= '- ';
                      }
                      $needle = array('title' => $searchPrefix.$objIcon->show().$subSecTitle, 'id' => $parentId);
                      $entNum = array_search($needle, $treeArray);
                      $newEnt = array('title' => $prefix.$objIcon->show().$sec['menutext'], 'id' => $sec['id']);
                      $treeArray = $this->addToTreeArray($treeArray, $entNum, $newEnt);
                  }
                }
             }
           }
         }
         //Add array to dropdown
         foreach($treeArray as $node){
            $treeDrop->addOption($node['id'], $node['title']);
         }
         if(!empty($setSelected)){
           $treeDrop->setSelected($setSelected);
         }
      }
      return $treeDrop->show();
	}

	/**
	 * Method to return the number of node levels attached to a root section
	 *
	 * @param string $rootId The id(pk) of the section root
	 * @return int $numLevels The number of node levels in the root section
	 * @access public
	 * @author Warren Windvogel
	 */
	public function getNumNodeLevels($rootId)
  {
      //get all sub secs in section
      $subSecs = $this->_objSections->getSubSectionsInRoot($rootId);
      //se number of levels
      $numLevels = '0';
      if(!empty($subSecs)){
        foreach($subSecs as $sec){
           if($sec['count'] > $numLevels){
             $numLevels = $sec['count'];
           }
        }
      }
      return $numLevels;
  }
	/**
	 * Method to insert data into an array at a specific entry pushing entries below
	 * this down
	 *
	 * @param array $dataArray The array to add the data to
	 * @param int $entryNumber The place to add the data
	 * @param mixed $newEntry The new data to be added
	 * @return array $newArray The array with the new entry
	 * @access public
	 * @author Warren Windvogel
	 */
	public function addToTreeArray($dataArray, $entryNumber, $newEntry)
  {
      //create new array
      $newArray = array();
      $counter = '0';
      //loop thru array adding entries before $entryNumber entry as usual
      foreach($dataArray as $ar){
         if($counter < $entryNumber){
           $newArray[$counter] = $ar;
         } else if($counter == $entryNumber){
              $newArray[$counter] = $ar;
              $num = $counter + '1';
              $newArray[$num] = $newEntry;
         } else {
              $num = $counter + '1';
              $newArray[$num] = $ar;
         }
         $counter++;
      }
      return $newArray;
  }
	/**
	 * Method to generate the indented section links for the side menu
	 *
	 * @return string Generated section links
	 * @access public
	 * @author Warren Windvogel
	 */
	public function getSectionLinks()
	{
       
	    //Create instance of geticon object
	    $objIcon =& $this->newObject('geticon', 'htmlelements');
	    //Get all root sections
	    $availsections = $this->_objSections->getRootNodes();
	    if(!empty($availsections)){
	      //initiate sequential tree structured array to be inserted into dropdown
	      $treeArray = array();
	      //add nodes for each section
	      foreach($availsections as $section){
	         //Get icon for root nodes
           $objIcon->setIcon('tree/treebase');	      
	         //initiate prefix for nodes
	         $prefix = '';
	         //add root(secion) to dropdown
 	         $treeArray[] = array('title' => $objIcon->show().$section['menutext'], 'id' => $section['id']);
	         //get number of node levels
           $numLevels = $this->getNumNodeLevels($section['id']);
           //check if section has sub sections
           if($numLevels > '0'){
             //loop through each level and add all sub sections in level
             for($i = '2'; $i <= $numLevels; $i++){
                $prefix .= '- ';
                //get all sub secs in section on level
                $subSecs = $this->_objSections->getSubSectionsForLevel($section['id'], $i, 'DESC');
                foreach($subSecs as $sec){
      	          //Get icon for parent child nodes
                  $objIcon->setIcon('tree/treefolder_orange');	      
                  //if its the 1st node just add it under the section
                  if($i == '2'){
                    $treeArray[] = array('title' => $prefix.$objIcon->show().$sec['menutext'], 'id' => $sec['id']);
                  //else find the parent node and include it after this node
                  } else {
                      $parentId = $sec['parentid'];
                      $subSecTitle = $this->_objSections->getMenuText($parentId);
                      $count = $this->_objSections->getLevel($parentId);
                      $searchPrefix = "";
                      for($num = '2'; $num <= $count; $num++){
                         $searchPrefix .= '- ';
                      }
                      $needle = array('title' => $searchPrefix.$objIcon->show().$subSecTitle, 'id' => $parentId);
                      $entNum = array_search($needle, $treeArray);
                      $newEnt = array('title' => $prefix.$objIcon->show().$sec['menutext'], 'id' => $sec['id']);
                      $treeArray = $this->addToTreeArray($treeArray, $entNum, $newEnt);
                  }
                }
             }
           }
         }
         $links = "";
         $objLink =& $this->newObject('link', 'htmlelements');
         
         //Add array to dropdown
         foreach($treeArray as $node){
            $matches = split('<', $node['title']);

            $img = split('>', $matches[1]);
            $image = '<'.$img[0].'>';
            $linkText = $img[1];
            $noSpaces = strlen($matches[0]);
            for($i = 1; $i < $noSpaces; $i++){
               $links .= '&nbsp;&nbsp;'; 
            }
            $links .= $image;
            $objLink->link($this->uri(array('action'=>'viewsection', 'id'=>$node['id'])));
            $objLink->link = $linkText;
            $links .= $objLink->show();
            $links .= '<br/>';
         }
      }
      return $links;
	}
  
}
?>
