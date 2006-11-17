<?php
/* -------------------- dbTable class ----------------*/
// security check - must be included in all scripts

if (!$GLOBALS['kewl_entry_point_run'])
{
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
        * The Skin object
        *
        * @access private
        * @var object
        */
        protected $objSkin;
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
        * The config object
        *
        * @access private
        * @var object
        */
        protected $_objConfig;
        /**
         * Constructor
         */
        public function init()
        {
            try {
                $this->_objSections = & $this->newObject('dbsections', 'cmsadmin');
                $this->_objContent = & $this->newObject('dbcontent', 'cmsadmin');
                $this->_objConfig = & $this->newObject('altconfig', 'config');
                $this->objSkin = & $this->newObject('skin', 'skin');
                $this->_objFrontPage = & $this->newObject('dbcontentfrontpage', 'cmsadmin');
                $this->_objUser = & $this->newObject('user', 'security');
                $this->objLanguage = & $this->newObject('language', 'language');
            } catch (Exception $e) {
                echo 'Caught exception: ', $e->getMessage();
                exit();
            }
        }

        /**
         * Method to reoder records
         */
        public function reOrder()
        {
            try {}
            catch (Exception $e) {
                echo 'Caught exception: ', $e->getMessage();
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
                if ($access == 1) {
                    return 'Registered';
                } else {
                    return 'Public';
                }
            } catch (Exception $e) {
                echo 'Caught exception: ', $e->getMessage();
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
        public function getImageList($name, $formName, $selected = null)
        {
            try {
                $objDropDown = & $this->newObject('dropdown', 'htmlelements');
                $objConfig = & $this->newObject('altconfig' , 'config');
                $objMedia = & $this->newObject('mmutils', 'mediamanager');
                $objMedia->getImages();
                $objDropDown->name = $name;
                //fill the drop down with the list of images
                $path = $objConfig->getsiteRoot().'usrfiles/media';
                $objDropDown->addOption('0', ' - Select Image - ');
                $objDropDown->addFromDB($objMedia->getImages(), 'title', 'folder', $selected);
                $objDropDown->extra = 'onchange=" return changeImage(this, this.form) "';
                return $objDropDown->show();
            } catch (Exception $e) {
                echo 'Caught exception: ', $e->getMessage();
                exit();
            }
        }

        /**
         * Method to get the image position dropdown
         * @access public
         * @param   string $name The name of the field
         * @return string
         */
        public function getImagePostionList($name)
        {
            try {
                $objDropDown = & $this->newObject('dropdown', 'htmlelements');
                $objDropDown->name = $name;
                //fill the drop down with the list of images
                //TODO
                $objDropDown->addOption('0', 'Centre');
                $objDropDown->addOption('1', 'Left');
                $objDropDown->addOption('2', 'Right');
                $objDropDown->setSelected('1');
                $objDropDown->extra = 'size="3"';
                return $objDropDown->show();
            } catch (Exception $e) {
                echo 'Caught exception: ', $e->getMessage();
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
        public function getYesNoRadion($name, $selected = '1')
        {
            try {
                //Get visible not visible icons
                $objIcon =& $this->newObject('geticon', 'htmlelements');
                //Not visible
                $objIcon->setIcon('not_visible');
                $notVisibleIcon = $objIcon->show();
                //Visible
                $objIcon->setIcon('visible');
                $visibleIcon = $objIcon->show();

                $objRadio = & $this->newObject('radio', 'htmlelements');
                $objRadio->name = $name;
                $objRadio->addOption('0', $notVisibleIcon.$this->objLanguage->languageText('word_no').'&nbsp;'.'&nbsp;');
                $objRadio->addOption('1', $visibleIcon.$this->objLanguage->languageText('word_yes'));
                $objRadio->setSelected($selected);
                return $objRadio->show();
            } catch (Exception $e) {
                echo 'Caught exception: ', $e->getMessage();
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
        public function getAccessList($name)
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
            } catch (Exception $e) {
                echo 'Caught exception: ', $e->getMessage();
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
                $str = '<table><tr>';

                $firstOneChecked = 'checked="checked"';
                foreach ($arrLayouts as $layout) {
                    if ($arrSection['layout'] == $layout['id']) {
                        $firstOneChecked = '';
                        break;
                    }
                }

                $i = 0;
                foreach ($arrLayouts as $layout) {
                    if ($firstOneChecked != '') {
                        if ($i == 0) {
                            $checked = $firstOneChecked;
                        } else {
                            $checked = '';
                        }
                    } else {
                        if ($arrSection['layout'] == $layout['id']) {
                            $checked = 'checked="checked"';
                        } else {
                            $checked = '';
                        }
                    }

                    $str .= '<td align="center">
                            <input type="radio" name="'.$name.'" value="'.$layout['id'].'" class="transparentbgnb" id="input_layout0" '.$checked.' />&nbsp;'.$layout['description'].'
                            <p/>
                            <label for ="input_layout0">
                            <img src ="'.$this->getResourceUri($layout['imagename'], 'cmsadmin').'"/>
                            </label>
                            </td>';
                    $i++;
                }

                $str .= '</tr></table>';
                return $str;
            } catch (Exception $e) {
                echo 'Caught exception: ', $e->getMessage();
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
        public function getSectionMenu($modulename = null)
        {
            try {
                if (empty($modulename)) {
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
                foreach ($arrSections as $section) {
                    //add the sections

                    if (($this->getParam('action') == 'showsection') && ($this->getParam('id') == $section['id']) || $this->getParam('sectionid') == $section['id']) {
                        $pagenodes = array();
                        $arrPages = $this->_objContent->getAll('WHERE sectionid = "'.$section['id'].'" AND published=1 and trash=0 ORDER BY ordering');
                        foreach( $arrPages as $page) {
                            $pagenodes[] = array('text' => $page['menutext'] , 'uri' => $this->uri(array('action' => 'showfulltext', 'id' => $page['id'], 'sectionid' => $section['id']), $modulename));
                        }

                        $nodes[] = array('text' => $section['menutext'], 'uri' => $this->uri(array('action' => 'showsection', 'id' => $section['id']), $modulename), 'sectionid' => $section['id'], 'haschildren' => $pagenodes);
                        $pagenodes = null;
                    } else {
                        $nodes[] = array('text' => $section['menutext'], 'uri' => $this->uri(array('action' => 'showsection', 'id' => $section['id']), $modulename), 'sectionid' => $section['id']);
                    }
                }

                //add the admin link
                $nodes[] = array('text' => 'Administration', 'uri' => $this->uri(array(NULL), 'cmsadmin'));

                return $objSideBar->show($nodes, $this->getParam('id'));
            } catch (Exception $e) {
                echo 'Caught exception: ', $e->getMessage();
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
                //$objFeatureBox = $this->newObject('featurebox', 'navigation');
                $str = '';
                //set a counter for the records .. display on the first 2  the rest will be dsiplayed as links
                $cnt = 0 ;

                if (count($arrFrontPages)) {
                    foreach ($arrFrontPages as $frontPage) {
                        //get the page
                        $page = $this->_objContent->getContentPage($frontPage['content_id']) ;
                        $cnt++;

                        if ($cnt < 5) {
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

                            if (!$page['body'] == '') {
                                //read more link .. link to the full text
                                $link = & $this->newObject('link', 'htmlelements');
                                $link->link = 'Read more ..';
                                $link->href = $this->uri(array('action' => 'showfulltext', 'id' => $page['id']), 'cms');
                                $table->startRow();
                                $table->addCell($link->show());
                                $table->endRow();
                            }

                            $str .= ''; //$table->show();
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

                        $content = '<span class="date">'.$this->formatDate($page['created']).'</span> <p>'.$page['introtext'].'<br /><a href="'.$moreLink.'" class="morelink" title="'.$page['title'].'">Read more...</a></p>';

                        $str .= '<h3>'.$page['title'].'</h3>'.$content;
                    }
                }

                return $str;
            } catch (Exception $e) {
                echo 'Caught exception: ', $e->getMessage();
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
                $arrLayout['name'] = ($arrLayout['name'] == '') ? 'List' : $arrLayout['name'];
                $functionVariable = '_layout'.trim($arrLayout['name']);
                //call the right function according to the layout of the section
                return call_user_func(array('cmsutils', $functionVariable), $arrSection, $module);
            } catch (Exception $e) {
                echo 'Caught exception: ', $e->getMessage();
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
                $pageId = $this->getParam('pageid', '');

                $orderType = $arrSection['ordertype'];
                $showIntro = $arrSection['showintroduction'];
                $showDate = $arrSection['showdate'];
                $description = $arrSection['description'];
                
                switch ($orderType) {
                    case null:
                    case 'pageorder':
                        $arrPages = $this->_objContent->getAll('WHERE sectionid = "'.$arrSection['id'].'" AND published=1 ORDER BY ordering');
                        break;
                    case 'pagedate_asc':
                        $arrPages = $this->_objContent->getAll('WHERE sectionid = "'.$arrSection['id'].'" AND published=1 ORDER BY created');
                        break;
                    case 'pagedate_desc':
                        $arrPages = $this->_objContent->getAll('WHERE sectionid = "'.$arrSection['id'].'" AND published=1 ORDER BY created DESC');
                        break;
                    case 'pagetitle_asc':
                        $arrPages = $this->_objContent->getAll('WHERE sectionid = "'.$arrSection['id'].'" AND published=1 ORDER BY title');
                        break;
                    case 'pagetitle_desc':
                        $arrPages = $this->_objContent->getAll('WHERE sectionid = "'.$arrSection['id'].'" AND published=1 ORDER BY title DESC');
                        break;
                }        
 
                $cnt = 0;
                $strBody = '';
                $str = '';

                if ($pageId == '') {
                    $pageId = $arrPages[0]['id'];
                }

                $foundPage = FALSE;

                foreach ($arrPages as $page) {
                    if ($foundPage == TRUE) {
                        $link = & $this->newObject('link', 'htmlelements');
                        $link->link = $page['title'];
                        $link->href = $this->uri(array('action' => 'showsection', 'id' => $arrSection['id'], 'pageid' => $page['id'], 'sectionid' => $page['sectionid']), $module);
                        if($showDate){
                          $str .= '<li>'. $this->formatDate($page['created']).' - '.$link->show() .'</li> ';
                        } else {
                            $str .= '<li>'. $link->show() .'</li> ';
                        }
                    }

                    if ($pageId == $page['id']) {
                        $strBody = '<h3>'.$page['title'].'</h3>';
                        $strBody .= $page['body'].'<p/>';
                        $foundPage = TRUE;
                    }
                }
                if($showIntro){
                  return '<p>'.$description.'</p>'.$strBody.'<p/>'.$str;
                } else {
                    return $strBody.'<p/>'.$str;
                }
            } catch (Exception $e) {
                echo 'Caught exception: ', $e->getMessage();
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
            try {

                $objUser = & $this->newObject('user', 'security');
                $objConfig = & $this->newObject('altconfig', 'config');
                $str = '';
                
                $orderType = $arrSection['ordertype'];
                $showIntro = $arrSection['showintroduction'];
                $showDate = $arrSection['showdate'];
                $description = $arrSection['description'];
                
                switch ($orderType) {
                    case null:
                    case 'pageorder':
                        $arrPages = $this->_objContent->getAll('WHERE sectionid = "'.$arrSection['id'].'" AND published=1 ORDER BY ordering');
                        break;
                    case 'pagedate_asc':
                        $arrPages = $this->_objContent->getAll('WHERE sectionid = "'.$arrSection['id'].'" AND published=1 ORDER BY created');
                        break;
                    case 'pagedate_desc':
                        $arrPages = $this->_objContent->getAll('WHERE sectionid = "'.$arrSection['id'].'" AND published=1 ORDER BY created DESC');
                        break;
                    case 'pagetitle_asc':
                        $arrPages = $this->_objContent->getAll('WHERE sectionid = "'.$arrSection['id'].'" AND published=1 ORDER BY title');
                        break;
                    case 'pagetitle_desc':
                        $arrPages = $this->_objContent->getAll('WHERE sectionid = "'.$arrSection['id'].'" AND published=1 ORDER BY title DESC');
                        break;
                }        

                foreach ($arrPages as $page) {
                    //display the intro text
                    $table = & $this->newObject('htmltable', 'htmlelements');
                    //title
                    $table->startRow();
                    $table->addHeader(array($page['title']));
                    $table->endRow();
                    //author
                    $table->startRow();

                    if (!isset($page['creator_by'])) {
                        $page['creator_by'] = $objUser->fullname();
                    }

                    $table->addCell('Written by '.$objUser->fullname($page['creator_by']));
                    $table->endRow();
                    //date
                    if($showDate){
                      $table->startRow();
                      $table->addCell($this->formatDate($page['created']));
                      $table->endRow();
                    }  
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
                    if($showIntro){
                      $str .= '<p>'.$description.'</p>';
                    }
                    if($showDate){
                      $str .= '<h4><span class="date">'.$this->formatDate($page['created']).'</span> '.$page['title'].'</h4>';
                    } else {
                        $str .= '<h4>'.$page['title'].'</h4>';
                    }
                    $uri = $this->uri(array('action' => 'showfulltext', 'sectionid' => $arrSection['id'], 'id' => $page['id']), $module);
                    $str .= '<p>'.$page['introtext'].'<br /><a href="'.$uri.'" class="morelink" title="'.$page['title'].'">Read more...</a></p>';
                }

                return $str;
            } catch (Exception $e) {
                echo 'Caught exception: ', $e->getMessage();
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
                $pageId = $this->getParam('pageid', '');

                $orderType = $arrSection['ordertype'];
                $showDate = $arrSection['showdate'];
                
                switch ($orderType) {
                    case null:
                    case 'pageorder':
                        $arrPages = $this->_objContent->getAll('WHERE sectionid = "'.$arrSection['id'].'" AND published=1 ORDER BY ordering');
                        break;
                    case 'pagedate_asc':
                        $arrPages = $this->_objContent->getAll('WHERE sectionid = "'.$arrSection['id'].'" AND published=1 ORDER BY created');
                        break;
                    case 'pagedate_desc':
                        $arrPages = $this->_objContent->getAll('WHERE sectionid = "'.$arrSection['id'].'" AND published=1 ORDER BY created DESC');
                        break;
                    case 'pagetitle_asc':
                        $arrPages = $this->_objContent->getAll('WHERE sectionid = "'.$arrSection['id'].'" AND published=1 ORDER BY title');
                        break;
                    case 'pagetitle_desc':
                        $arrPages = $this->_objContent->getAll('WHERE sectionid = "'.$arrSection['id'].'" AND published=1 ORDER BY title DESC');
                        break;
                }        

                $cnt = 0;
                $strBody = '';
                $str = '';

                if ($pageId == '') {
                    $pageId = $arrPages[0]['id'];
                }

                foreach ($arrPages as $page) {
                    if ($pageId == $page['id']) {
                        $strBody = '<h3>'.$page['title'].'</h3>';
                        $strBody .= $page['body'].'<p/>';
                        $str .= $page['title'].' | ';
                    } else {
                        $link = & $this->newObject('link', 'htmlelements');
                        $link->link = $page['title'];
                        $link->href = $this->uri(array('action' => 'showsection', 'pageid' => $page['id'], 'id' => $page['sectionid'], 'sectionid' => $page['sectionid']), $module);
                        $str .= $link->show() .' | ';
                    }
                }

                if (strlen($str) > 1) {
                    $str = substr($str, 0, strlen($str) - 3);
                }

                return $strBody.'<p/>'.$str;
            } catch (Exception $e) {
                echo 'Caught exception: ', $e->getMessage();
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

                $str = '';
                
                $orderType = $arrSection['ordertype'];
                $showIntro = $arrSection['showintroduction'];
                $showDate = $arrSection['showdate'];
                $description = $arrSection['description'];
                
                switch ($orderType) {
                    case null:
                    case 'pageorder':
                        $arrPages = $this->_objContent->getAll('WHERE sectionid = "'.$arrSection['id'].'" AND published=1 ORDER BY ordering');
                        break;
                    case 'pagedate_asc':
                        $arrPages = $this->_objContent->getAll('WHERE sectionid = "'.$arrSection['id'].'" AND published=1 ORDER BY created');
                        break;
                    case 'pagedate_desc':
                        $arrPages = $this->_objContent->getAll('WHERE sectionid = "'.$arrSection['id'].'" AND published=1 ORDER BY created DESC');
                        break;
                    case 'pagetitle_asc':
                        $arrPages = $this->_objContent->getAll('WHERE sectionid = "'.$arrSection['id'].'" AND published=1 ORDER BY title');
                        break;
                    case 'pagetitle_desc':
                        $arrPages = $this->_objContent->getAll('WHERE sectionid = "'.$arrSection['id'].'" AND published=1 ORDER BY title DESC');
                        break;
                }        

                foreach ($arrPages as $page) {
                    $link = & $this->newObject('link', 'htmlelements');
                    $link->link = $page['title'];
                    $link->href = $this->uri(array('action' => 'showcontent', 'id' => $page['id'], 'sectionid' => $page['sectionid']), $module);
                    $str .= '<li>'.$this->formatDate($page['created']).' - '. $link->show() .'</li>';
                }
                if($showIntro){
                  return '<p>'.$description.'</p>'.$str;
                } else {
                    return $str;
                }
            } catch (Exception $e) {
                echo 'Caught exception: ', $e->getMessage();
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
                $sectionId = $page['sectionid'];
                $section = $this->_objSections->getSection($sectionId);
                $strBody = '<h3>'.$page['title'].'</h3><p/>';
                $strBody .= '<span class="warning">'.$this->_objUser->fullname($page['created_by']).'</span><br />';
                if($section['showdate']){
                  $strBody .= '<span class="warning">'.$page['created'].'</span><p/>';
                }  
                $strBody .= $page['body'].'<p/>';
                return $strBody;
            } catch (Exception $e) {
                echo 'Caught exception: ', $e->getMessage();
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
                //       }
            } catch (Exception $e) {
                echo 'Caught exception: ', $e->getMessage();
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
            } catch (Exception $e) {
                echo 'Caught exception: ', $e->getMessage();
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
            try {}
            catch (Exception $e) {
                echo 'Caught exception: ', $e->getMessage();
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

                if ($isCheck) {
                    $objIcon->setIcon('visible', 'gif');
                } else {
                    if ($returnFalse) {
                        $objIcon->setIcon('not_visible', 'gif');
                    }
                }

                return $objIcon->show();
            } catch (Exception $e) {
                echo 'Caught exception: ', $e->getMessage();
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
            //Instantiate cms tree object
            $objCmsTree = & $this->newObject('cmstree', 'cmsadmin');
            //Instantiate link object
            $link = & $this->newObject('link', 'htmlelements');
            //Create heading
            $objH = & $this->newObject('htmlheading', 'htmlelements');
            $objH->type = '2';
            $objH->str = $this->objLanguage->languageText('word_sections');

            //Create cms admin link
            $link->link = $this->objLanguage->languageText('mod_cmsadmin_cmsadmin', 'cmsadmin');
            $link->href = $this->uri(NULL, 'cmsadmin');
            $cmsAdminLink = $link->show();
            //Create new section link
            $link->link = $this->objLanguage->languageText('mod_cmsadmin_createnewsection', 'cmsadmin');
            $link->href = $this->uri(array('action' => 'addsection'), 'cmsadmin');
            $createSectionLink = $link->show();
            //Create new section link
            $link->link = $this->objLanguage->languageText('mod_cmsadmin_viewcms', 'cmsadmin');
            $link->href = $this->uri(NULL, 'cms');
            $viewCmsLink = $link->show();
            //Create front page manager link
            $link->link = $this->objLanguage->languageText('mod_cmsadmin_frontpagemanager', 'cmsadmin');
            $link->href = $this->uri(array('action' => 'frontpages'), 'cmsadmin');
            $frontpageManagerLink = $link->show();

            //Add links to the output layer
            $nav = $objH->show();
            $nav .= '<br/>';
            $nav .= $cmsAdminLink;
            $nav .= '<br/>'.'&nbsp;'.'<br/>';
            $nav .= $this->getSectionLinks();
            $nav .= '<br/>'.'&nbsp;'.'<br/>';
            $nav .= $createSectionLink;
            $nav .= '<br/>'.'&nbsp;'.'<br/>';
            $nav .= $frontpageManagerLink;
            $nav .= '<br/>'.'&nbsp;'.'<br/>';
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
        {}

        /**
        * Method to generate the bread crumbs
        * @param void
        * @return string
        * @access public
        */
        public function getBreadCrumbs($module = 'cms')
        {
            if ($this->getParam('action') == '') {
                return '';
            }

            $link = & $this->newObject('link', 'htmlelements');
            $link->href = $this->uri(null , $module);
            $link->link = 'Home';
            $str = $link->show() .' / ';
            $link->href = $this->uri(array('action' => 'showsection', 'id' => $this->getParam('sectionid'), 'sectionid' => $this->getParam('sectionid')) , $module);
            $link->link = $this->_objSections->getMenuText($this->getParam('sectionid'));
            $str .= $link->show() .' / ';
            $page = $this->_objContent->getContentPage($this->getParam('id'));
            $str .= $page['menutext'];
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
         * @param string $setSelected The dropdown option to select
         * @param bool $noRoot True Root Level option will not be displayed
         * @return string Generated HTML for the dropdown
         * @access public
         * @author Warren Windvogel
         */
        public function getTreeDropdown($setSelected = NULL, $noRoot = FALSE)
        {
            //Create dropdown
            $treeDrop = & $this->newObject('dropdown', 'htmlelements');
            $treeDrop->name = 'parent';

            if(!$noRoot) {
                $treeDrop->addOption('0', '...'.$this->objLanguage->languageText('mod_cmsadmin_rootlevelmenu', 'cmsadmin').'...');
            }

            //Create instance of geticon object
            $objIcon = & $this->newObject('geticon', 'htmlelements');

            //Get all root sections
            $availsections = $this->_objSections->getRootNodes();

            if (!empty($availsections)) {
                //initiate sequential tree structured array to be inserted into dropdown
                $treeArray = array();
                //Get icon for root nodes
                $objIcon->setIcon('tree/treebase');
                //add nodes for each section
                foreach($availsections as $section) {
                    //initiate prefix for nodes
                    $prefix = '';
                    //add root(secion) to dropdown
                    $treeArray[] = array('title' => $objIcon->show().$section['menutext'], 'id' => $section['id']);
                    //get number of node levels
                    $numLevels = $this->getNumNodeLevels($section['id']);
                    //check if section has sub sections

                    if ($numLevels > '0') {
                        //Get icon for parent child nodes
                        $objIcon->setIcon('tree/treefolder_orange');
                        //loop through each level and add all sub sections in level

                        for ($i = '2'; $i <= $numLevels; $i++) {
                            $prefix .= '- ';
                            //get all sub secs in section on level
                            $subSecs = $this->_objSections->getSubSectionsForLevel($section['id'], $i, 'DESC');
                            foreach($subSecs as $sec) {
                                //if its the 1st node just add it under the section

                                if ($i == '2') {
                                    $treeArray[] = array('title' => $prefix.$objIcon->show().$sec['menutext'], 'id' => $sec['id']);
                                    //else find the parent node and include it after this node
                                } else {
                                    $parentId = $sec['parentid'];
                                    $subSecTitle = $this->_objSections->getMenuText($parentId);
                                    $count = $this->_objSections->getLevel($parentId);
                                    $searchPrefix = "";

                                    for ($num = '2'; $num <= $count; $num++) {
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
                foreach($treeArray as $node) {
                    $treeDrop->addOption($node['id'], $node['title']);
                }

                if(isset($setSelected)) {
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

            if (!empty($subSecs)) {
                foreach($subSecs as $sec) {
                    if ($sec['count'] > $numLevels) {
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
            foreach($dataArray as $ar) {
                if ($counter < $entryNumber) {
                    $newArray[$counter] = $ar;
                } else if ($counter == $entryNumber) {
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
         * @param bool $forTable If false returns links for side menu if true returns array of text with indentation         
         * @return string Generated section links or array $treeArray section names indented and ids
         * @access public
         * @author Warren Windvogel
         */
        public function getSectionLinks($forTable = FALSE)
        {
            //Create instance of geticon object
            $objIcon = & $this->newObject('geticon', 'htmlelements');
            //Get all root sections
            $availsections = $this->_objSections->getRootNodes();

            if (!empty($availsections)) {
                //initiate sequential tree structured array to be inserted into dropdown
                $treeArray = array();
                //add nodes for each section
                foreach($availsections as $section) {
                    //Get icon for root nodes
                    $objIcon->setIcon('tree/treebase');
                    //initiate prefix for nodes
                    $prefix = '';
                    //add root(secion) to dropdown
                    $treeArray[] = array('title' => $objIcon->show().$section['menutext'], 'id' => $section['id']);
                    //get number of node levels
                    $numLevels = $this->getNumNodeLevels($section['id']);
                    //check if section has sub sections

                    if ($numLevels > '0') {
                        //loop through each level and add all sub sections in level

                        for ($i = '2'; $i <= $numLevels; $i++) {
                            $prefix .= '- ';
                            //get all sub secs in section on level
                            $subSecs = $this->_objSections->getSubSectionsForLevel($section['id'], $i);
                            foreach($subSecs as $sec) {
                                //Get icon for parent child nodes
                                $objIcon->setIcon('tree/treefolder_orange');
                                //if its the 1st node just add it under the section

                                if ($i == '2') {
                                    $treeArray[] = array('title' => $prefix.$objIcon->show().$sec['menutext'], 'id' => $sec['id']);
                                    //else find the parent node and include it after this node
                                } else {
                                    $parentId = $sec['parentid'];
                                    $subSecTitle = $this->_objSections->getMenuText($parentId);
                                    $count = $this->_objSections->getLevel($parentId);
                                    $searchPrefix = "";

                                    for ($num = '2'; $num <= $count; $num++) {
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
                if($forTable){
                  return $treeArray;
                } else {
                $links = "";
                $objLink = & $this->newObject('link', 'htmlelements');
                //Add array to dropdown
                foreach($treeArray as $node) {
                    $matches = split('<', $node['title']);
                    $img = split('>', $matches[1]);
                    $image = '<'.$img[0].'>';
                    $linkText = $img[1];
                    $noSpaces = strlen($matches[0]);

                    for ($i = 1; $i < $noSpaces; $i++) {
                        $links .= '&nbsp;&nbsp;';
                    }

                    $links .= $image;
                    $objLink->link($this->uri(array('action' => 'viewsection', 'id' => $node['id'])));
                    $objLink->link = $linkText;
                    $links .= $objLink->show();
                    $links .= '<br/>';
                }
           
             return $links;
            } 
          } 
        }

        /**
         * Method to check if a section will be displayed on the tree menu
         *
         * @param string $sectionId The id of the section
         * @return bool $isVisible True if it will be displayed, else False
         * @access public
         * @author Warren Windvogel
         */
        public function sectionIsVisibleOnMenu($sectionId)
        {
            //Set $isVisible to true
            $isVisible = true;
            //Get count value of section to use in for loop
            $sectionLevel = $this->_objSections->getLevel($sectionId);

            for ($i = 1; $i <= $sectionLevel; $i++) {
                //If section has no content set $isVisible to false

                if ($this->_objContent->getNumberOfPagesInSection($sectionId) == 0) {
                    $isVisible = false;
                } else {
                    $section = $this->_objSections->getSection($sectionId);
                    $sectionId = $section['parentid'];
                }
            }

            return $isVisible;
        }

        /**
         * Method to return the add edit section form 
         *
         * @param string $sectionId The id of the section to be edited. Default NULL for adding new section
         * @param string $parentid The id of the section it is found in. Default NULL for adding root node         
         * @return string $middleColumnContent The form used to create and edit a section
         * @access public
         * @author Warren Windvogel
         */
        public function getAddEditSectionForm($sectionId = NULL, $parentid = NULL)
        {

            $initRadioDisplay = "
                                <script type='text/javascript' language='javascript'>
                                <!--
                                function initRadioDisplay()
                                {
                                var len;
                                var index;
                                len = document.addsection.display.length;
                                for (index=0;index<len;index++) {
                                if (document.addsection.display[index].checked) {
                                xajax_processSection(document.addsection.display[index].value);
                                break;
                            }
                            }
                            }
                                //-->
                                </script>
                                ";
            $this->appendArrayVar('headerParams',$initRadioDisplay);
            $this->appendArrayVar('bodyOnLoad','initRadioDisplay()');

            //initiate objects
            $table = & $this->newObject('htmltable', 'htmlelements');
            $titleInput = & $this->newObject('textinput', 'htmlelements');
            $menuTextInput = & $this->newObject('textinput', 'htmlelements');
            $h3 = &$this->newObject('htmlheading', 'htmlelements');
            $sections = & $this->newObject('dropdown', 'htmlelements');
            $parent = & $this->newObject('dropdown', 'htmlelements');
            $button = & $this->newObject('button', 'htmlelements');
            $objRootId = & $this->newObject('textinput', 'htmlelements');
            $objParentId = & $this->newObject('textinput', 'htmlelements');
            $objCount = & $this->newObject('textinput', 'htmlelements');
            $objOrdering = & $this->newObject('textinput', 'htmlelements');
            //Load radio class
            $this->loadClass('radio', 'htmlelements');
            $this->loadClass('label', 'htmlelements');
            $this->loadClass('textinput', 'htmlelements');

            if ($sectionId == NULL) {
                $action = 'createsection';
                $editmode = FALSE;
                $sectionId = '';
            } else {
                $action = 'editsection';
                $sectionId = $sectionId;
                $editmode = TRUE;
                $section = $this->_objSections->getSection($sectionId);
            }

            //Layout radio
            $radio = new radio ('display');
            $radio->addOption('page', $this->objLanguage->languageText('mod_cmsadmin_layout_pagebypage', 'cmsadmin').'<br /><img src="modules/cmsadmin/resources/section_page.gif" />');
            $radio->addOption('previous', $this->objLanguage->languageText('mod_cmsadmin_layout_previouspagebelow', 'cmsadmin').'<br /><img src="modules/cmsadmin/resources/section_previous.gif" />');
            $radio->addOption('list', $this->objLanguage->languageText('mod_cmsadmin_layout_listofpages', 'cmsadmin').'<br /><img src="modules/cmsadmin/resources/section_list.gif" />');
            $radio->addOption('summaries', $this->objLanguage->languageText('mod_cmsadmin_layout_summaries', 'cmsadmin').'<br /><img src="modules/cmsadmin/resources/section_summaries.gif" />'); // Deactivated so long - will activate once done
            $radio->extra = 'onchange="xajax_processSection(this.value);"';
            $radio->setBreakSpace('table');
            $radio->tableColumns = 4;
            if ($editmode) {
                $radio->setSelected($section['layout']);
            } else {
                $radio->setSelected('page');
            }


            $objForm =& $this->newObject('form', 'htmlelements');
            //setup form
            $objForm->name = 'addsection';
            $objForm->id = 'addsection';

            if (isset($parentid) && !empty($parentid)) {
                $objForm->setAction($this->uri(array('action' => $action, 'id' => $sectionId, 'parentid' => $parentid), 'cmsadmin'));
            } else {
                $objForm->setAction($this->uri(array('action' => $action, 'id' => $sectionId), 'cmsadmin'));
            }

           // $objForm->setDisplayType(3);
            $table->cellpadding = 5;            //the title
            $titleInput->name = 'title';
            $titleInput->id = 'title';
            $titleInput->size = 50;
            $objForm->addRule('title', $this->objLanguage->languageText('mod_cmsadmin_pleaseaddtitle', 'cmsadmin'), 'required');
            $menuTextInput->name = 'menutext';
            $menuTextInput->size = 50;
            $objForm->addRule('menutext', $this->objLanguage->languageText('mod_cmsadmin_pleaseaddmenutext', 'cmsadmin'), 'required');
            //$button->setToSubmit();
            $button->name = 'submit';
            $button->id = 'submit';
            $button->value = 'Save';
            $button->setToSubmit(); //onclick = 'return validate_addsectionfrm_form(this.form) ';

            if ($editmode) {
                $titleInput->value = $section['title'];
                $menuTextInput->value = $section['menutext'];
                $layout = $section['layout'];
                $isPublished = $section['published'];
                //Set rootid as hidden field
                $objRootId->name = 'rootid';
                $objRootId->id = 'rootid';
                $objRootId->fldType = 'hidden';
                $objRootId->value = $section['rootid'];
                //Set parentid as hidden field
                $objParentId->name = 'parent';
                $objParentId->id = 'parent';
                $objParentId->fldType = 'hidden';
                $objParentId->value = $section['parentid'];
                //Set parentid as hidden field
                $objCount->name = 'count';
                $objCount->fldType = 'hidden';
                $objCount->value = $section['count'];
                //Set parentid as hidden field
                $objOrdering->name = 'ordering';
                $objOrdering->fldType = 'hidden';
                $objOrdering->value = $section['ordering'];
            } else {
                $titleInput->value = '';
                $menuTextInput->value = '';
                $bodyInput->value = '';
                $layout = 0;
                $isPublished = '1';
            }

            //Add form elements to the table
            if (!$editmode) {
                $table->startRow();
                $table->addCell($this->objLanguage->languageText('mod_cmsadmin_parentfolder', 'cmsadmin'));

                if (isset($parentid)) {
                    $table->addCell($this->getTreeDropdown($parentid).'&nbsp;'.'-'.'&nbsp;'.$this->objLanguage->languageText('mod_cmsadmin_parentsectiondesc', 'cmsadmin'));
                } else {
                    $table->addCell($this->getTreeDropdown().'&nbsp;'.'-'.'&nbsp;'.$this->objLanguage->languageText('mod_cmsadmin_parentsectiondesc', 'cmsadmin'));
                }

                $table->endRow();
            } else {
                $table->startRow();
                $table->addCell($objParentId->show().$objRootId->show().$objCount->show().$objOrdering->show());
                $table->endRow();
            }


            $table->startRow();
            $table->addCell('&nbsp;');
            $table->addCell('&nbsp;');  
            $table->endRow();

            //title name
            $table->startRow();
            $table->addCell($this->objLanguage->languageText('word_title'));
            $table->addCell($titleInput->show().'&nbsp;'.'-'.'&nbsp;'.$this->objLanguage->languageText('mod_cmsadmin_sectionnamedescription', 'cmsadmin'));
            $table->endRow();

            $table->startRow();
            $table->addCell('&nbsp;');
            $table->addCell('&nbsp;');  
            $table->endRow();

            //menu text name
            $table->startRow();
            $table->addCell($this->objLanguage->languageText('mod_cmsadmin_menuname', 'cmsadmin'));
            $table->addCell($menuTextInput->show().'&nbsp;'.'-'.'&nbsp;'.$this->objLanguage->languageText('mod_cmsadmin_menutextdescription', 'cmsadmin'));
            $table->endRow();

            $table->startRow();
            $table->addCell('&nbsp;');
            $table->addCell('&nbsp;');  
            $table->endRow();

            //published
            $table->startRow();
            $table->addCell($this->objLanguage->languageText('word_section').'&nbsp;'.$this->objLanguage->languageText('word_visible'));
            $table->addCell($this->getYesNoRadion('published', $isPublished));
            $table->endRow();

            $table->startRow();
            $table->addCell('&nbsp;');
            $table->addCell('&nbsp;');  
            $table->endRow();
            
            //layout
            $table->startRow();
            $table->addCell($this->objLanguage->languageText('mod_cmsadmin_layoutofpages', 'cmsadmin'));
            $table->addCell($radio->show());
            $table->endRow();

            $table->startRow();
            $table->addCell('&nbsp;');
            $table->addCell('&nbsp;');  
            $table->endRow();

            //Order type
            $label = new label ($this->objLanguage->languageText('mod_cmsadmin_orderpagesby', 'cmsadmin'), 'input_pageorder');
            $pageOrder = new radio ('pageorder');
            $pageOrder->addOption('pageorder', $this->objLanguage->languageText('mod_cmsadmin_order_pageorder', 'cmsadmin'));
            $pageOrder->addOption('pagedate_asc', $this->objLanguage->languageText('mod_cmsadmin_order_pagedate_asc', 'cmsadmin'));
            $pageOrder->addOption('pagedate_desc', $this->objLanguage->languageText('mod_cmsadmin_order_pagedate_desc', 'cmsadmin'));
            $pageOrder->addOption('pagetitle_asc', $this->objLanguage->languageText('mod_cmsadmin_order_pagetitle_asc', 'cmsadmin'));
            $pageOrder->addOption('pagetitle_desc', $this->objLanguage->languageText('mod_cmsadmin_order_pagetitle_desc', 'cmsadmin'));
            if ($editmode) {
              $pageOrder->setSelected($section['ordertype']);
            } else {
                $pageOrder->setSelected('pageorder');
            }
            $pageOrder->setBreakSpace(' &nbsp; ');
            
            $table->startRow();
            $table->addCell($label->show());
            $table->addCell($pageOrder->show());
            $table->endRow();

            $table->startRow();
            $table->addCell('&nbsp;');
            $table->addCell('&nbsp;');  
            $table->endRow();

/*
            //layout
            $table->startRow();

            $table->addCell($this->objLanguage->languageText('mod_cmsadmin_layoutofpages', 'cmsadmin'));

            $table->addCell($this->getLayoutOptions('sectionlayout', $this->getParam('id')).'<p/>');

            $table->endRow();


                        //access level
                        $table->startRow();
                        $table->addCell($this->objLanguage->languageText('phrase_accesslevel'));
                        $table->addCell($this->getAccessList('access').'<p/>');
                        $table->endRow();
             
                        //description
                        $table->startRow();
                        $table->addCell($this->objLanguage->languageText('word_description'));
                        $table->addCell($bodyInput->show());
                        $table->endRow();
            */
            //Show intro or not
            $label = new label ('Show Introduction', 'input_showintro');
            $showdate = new radio ('showintro');
            $showdate->addOption('1', $this->objLanguage->languageText('word_yes'));
            $showdate->addOption('0', $this->objLanguage->languageText('word_no'));
            if ($editmode) {
                $showdate->setSelected($section['showdate']);
            } else {
                $showdate->setSelected('1');
            }
            $showdate->setBreakSpace(' &nbsp; ');
            
            //Intro text
            $introText =& $this->newObject('htmlarea', 'htmlelements');
            $introText->name = 'introtext';
            $introText->height = '500px';
            if ($editmode) {
                $introText->value = $section['description'];
            }
            
            $table->startRow();
            $table->addCell('<div id="showintrolabel">'.$label->show().'</div>');
            $table->addCell('<div id="showintrocol">'.'Should the Section Introduction text display above the list of pages'.' '.$showdate->show().'<br /><br />'.$introText->show().'</div>');
            $table->endRow();

            $table->startRow();
            $table->addCell('&nbsp;');
            $table->addCell('&nbsp;');  
            $table->endRow();
            
            //No. pages to display
            $label = new label ($this->objLanguage->languageText('phrase_numberofpages'), 'input_pagenum');
            $pagenum = new radio ('pagenum');
            $pagenum->addOption('0', 'Show All');
            $pagenum->addOption('3', '3');
            $pagenum->addOption('5', '5');
            $pagenum->addOption('10', '10');
            $pagenum->addOption('20', '20');
            $pagenum->addOption('30', '30');
            $pagenum->addOption('50', '50');
            $pagenum->addOption('100', '100');
            //Input custom no.
            $customInput = new textinput('customnumber');
            if ($editmode && $section['numpagedisplay'] != '0') {
                $customInput->value = $section['numpagedisplay'];
            }
            $pagenum->addOption('custom', $this->objLanguage->languageText('phrase_othernumber').': '.$customInput->show());
            if ($editmode) {
                $num = $section['numpagedisplay'];

                if ($num == '0' || $num == '3' || $num == '5' || $num == '10' || $num == '20' || $num == '30' || $num == '50' || $num == '100') {
                    $pagenum->setSelected($section['numpagedisplay']);
                } else {
                    $pagenum->setSelected('custom');
                }
            } else {
                $pagenum->setSelected('0');
            }
            $pagenum->setBreakSpace(' &nbsp; ');
            
            $table->startRow();
            $table->addCell('<div id="pagenumlabel">'.$label->show().'</div>');
            $table->addCell('<div id="pagenumcol">'.$this->objLanguage->languageText('mod_cmsadmin_numpagesdisplaypersection', 'cmsadmin').'<p>'.$pagenum->show().'</p><p class="warning">* '.$this->objLanguage->languageText('mod_cmsadmin_numpagesonlyrequiredwhen', 'cmsadmin').'</p>'.'</div>');
            $table->endRow();

            $table->startRow();
            $table->addCell('&nbsp;');
            $table->addCell('&nbsp;');  
            $table->endRow();

            //Show date or not
            $label = new label ($this->objLanguage->languageText('phrase_showdate'), 'input_showdate');
            $showdate = new radio ('showdate');
            $showdate->addOption('1', $this->objLanguage->languageText('word_yes'));
            $showdate->addOption('0', $this->objLanguage->languageText('word_no'));
            if ($editmode) {
                $showdate->setSelected($section['showdate']);
            } else {
                $showdate->setSelected('1');
            }
            $showdate->setBreakSpace(' &nbsp; ');

            $table->startRow();
            $table->addCell('<div id="dateshowlabel">'.$label->show().'</div>');            
            $table->addCell('<div id="dateshowcol">'.$this->objLanguage->languageText('mod_cmsadmin_shoulddatebedisplayed', 'cmsadmin').' '.$showdate->show().'</div>');
            $table->endRow();

            //button
            $table->startRow();
            $table->addCell('&nbsp;');
            $table->addCell($button->show());
            $table->endRow();
            $objForm->addToForm($table->show());
            //create heading

            if ($editmode) {
                $h3->str = $this->objLanguage->languageText('mod_cmsadmin_editsection', 'cmsadmin');
            } else {
                $h3->str = $this->objLanguage->languageText('mod_cmsadmin_addnewsection', 'cmsadmin');
            }

            //Add content to the output layer
            $middleColumnContent = "";

            $middleColumnContent .= $h3->show();

            $middleColumnContent .= $objForm->show();

            return $middleColumnContent;
        }

        /**
         * Method to return the add edit content form 
         *
         * @param string $contentId The id of the content to be edited. Default NULL for adding new section
         * @access public
         * @author Warren Windvogel
         */
        public function getAddEditContentForm($contentId = NULL, $section = NULL)
        {
            //initiate objects
            $table = & $this->newObject('htmltable', 'htmlelements');
            $titleInput = & $this->newObject('textinput', 'htmlelements');
            $bodyInput =  $this->newObject('htmlarea', 'htmlelements');
            $introInput =  $this->newObject('htmlarea', 'htmlelements');
            $h3 = &$this->newObject('htmlheading', 'htmlelements');
            $button = & $this->newObject('button', 'htmlelements');
            $table2 = & $this->newObject('htmltable', 'htmlelements');
            $frontPage = & $this->newObject('checkbox', 'htmlelements');
            $published = & $this->newObject('checkbox', 'htmlelements');
            $objOrdering = & $this->newObject('textinput', 'htmlelements');
            $objForm =& $this->newObject('form', 'htmlelements');

            if ($contentId == NULL) {
                $action = 'createcontent';
                $editmode = FALSE;
                $titleInput->value = '';
                $introInput->value = '';
                $published->setChecked(TRUE);
                $contentId = '';
                $arrContent = null;

                if ( $this->getParam('frontpage') == 'true') {
                    $frontPage->setChecked(TRUE);
                }
            } else {
                $action = 'editcontent';
                $editmode = TRUE;
                $arrContent = $this->_objContent->getContentPage($contentId);
                $titleInput->value = $arrContent['title'];
                $introInput->setContent($arrContent['introtext']);
                $bodyInput->setContent($arrContent['body']);
                $frontPage->setChecked($this->_objFrontPage->isFrontPage($arrContent['id']));
                $published->setChecked($arrContent['published']);
            }

            if ($editmode) {
                $sections = & $this->newObject('textinput', 'htmlelements');
                $sections->name = 'parent';
                $sections->id = 'parent';
                $sections->fldType = 'hidden';
                $sections->value = $arrContent['sectionid'];
                //Set ordering as hidden field
                $objOrdering->name = 'ordering';
                $objOrdering->id = 'ordering';
                $objOrdering->fldType = 'hidden';
                $objOrdering->value = $arrContent['ordering'];
            } else {
                if (isset($section) && !empty($section)) {
                    $sections = $this->getTreeDropdown($section, TRUE);
                } else {
                    $sections = $this->getTreeDropdown(NULL, TRUE);
                }
            }

            //setup form
            $objForm->name = 'addfrm';
            $objForm->id = 'addfrm';
            $objForm->setAction($this->uri(array('action' => $action, 'id' => $contentId, 'frontpage' => $this->getParam('frontpage')), 'cmsadmin'));
            $objForm->setDisplayType(3);

            $table->width = '80%';
            $table->border = '0';

            $table2->width = '80%';
            $table2->border = '0';

            //create heading
            $h3->str = $this->objLanguage->languageText('mod_cmsadmin_contentitem', 'cmsadmin').':'.'&nbsp;'.$this->objLanguage->languageText('word_new');

            $titleInput->name = 'title';
            $titleInput->id = 'title';

            $objForm->addRule('title', $this->objLanguage->languageText('mod_cmsadmin_pleaseaddtitle', 'cmsadmin'), 'required');

            $bodyInput->name = 'body';
            $bodyInput->id = 'body';
            $bodyInput->height = '400';
            $bodyInput->width = '100%';

            $introInput->name = 'intro';
            $introInput->id = 'intro';
            $introInput->setBasicToolBar();
            $introInput->height = '200';
            $introInput->width = '100%';

            $objForm->addRule('menutext', $this->objLanguage->languageText('mod_cmsadmin_pleaseaddmenutext', 'cmsadmin'), 'required');

            $button->setToSubmit();
            $button->value = $this->objLanguage->languageText('word_save');
            $button->id = 'submit';
            $button->name = 'submit';

            $published->name = 'published';
            $published->id = 'published';

            $frontPage->name = 'frontpage';
            $frontPage->id = 'frontpage';

            $table->startRow();
            $table->addCell($this->objLanguage->languageText('word_title'));
            $table->addCell($titleInput->show());
            $table->endRow();

            if (!$editmode) {
                $table->startRow();
                $table->addCell($this->objLanguage->languageText('word_section'));
                $table->addCell($sections);
                $table->endRow();
            } else {
                $table->startRow();
                $table->addCell($sections->show());
                $table->endRow();
            }

            $table->startRow();
            $table->addCell($this->objLanguage->languageText('mod_cmsadmin_showonfrontpage', 'cmsadmin'));
            $table->addCell($frontPage->show());
            $table->endRow();
            $table->startRow();
            $table->addCell($this->objLanguage->languageText('word_published'));
            $table->addCell($published->show());
            $table->endRow();
            $table2->startRow();
            $table2->addCell($table->show());
            $table2->endRow();
            //body
            $table2->startRow();
            $table2->addCell($this->objLanguage->languageText('mod_cmsadmin_maintext', 'cmsadmin'));
            $table2->endRow();
            $table2->startRow();
            $table2->addCell($bodyInput->show());
            $table2->endRow();
            //intro input
            $table2->startRow();
            $table2->addCell($this->objLanguage->languageText('mod_cmsadmin_summary', 'cmsadmin').'/'.
                             $this->objLanguage->languageText('mod_cmsadmin_introduction', 'cmsadmin').' ('.$this->objLanguage->languageText('word_required').')');
            $table2->endRow();
            $table2->startRow();
            $table2->addCell($introInput->show());
            $table2->endRow();
            $table2->startRow();
            $table2->addCell($button->show());
            $table2->endRow();
            $objForm->addToForm($table2);
            //Add content to the output layer
            $middleColumnContent = "";
            $middleColumnContent .= $h3->show();
            $middleColumnContent .= $objForm->show();
            return $middleColumnContent;
        }
}

?>
