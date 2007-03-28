<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check

/**
* This object hold all the utility method that the cms modules might need
*
* @package cmsadmin
* @category chisimba
* @copyright AVOIR
* @license GNU GPL
* @author Wesley  Nitsckie
* @author Warren Windvogel
*/

class cmsutils extends object
{
		/**
        * The context  object
        *
        * @access private
        * @var object
        */
        protected $_objContext;	
        /**
        * The inContextMode  object
        *
        * @access private
        * @var object
        */
        protected $inContextMode;	


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
         * The user model
         *
         * @access private
         * @var object
         */
        protected $_objUserModel;

        /**
        * The config object
        *
        * @access private
        * @var object
        */
        protected $_objConfig;

        /**
        * The blocks object
        *
        * @access private
        * @var object
        */
        protected $_objBlocks;
        
        /**
         * Feature box object
         *
         * @var object
         */
        public $objFeatureBox;

	   /**
	    * Class Constructor
	    *
	    * @access public
	    * @return void
	    */
        public function init()
        {
        	try {
                $this->_objSections =$this->newObject('dbsections', 'cmsadmin');
                $this->_objContent =$this->newObject('dbcontent', 'cmsadmin');
                $this->_objConfig =$this->newObject('altconfig', 'config');
                $this->_objBlocks =$this->newObject('dbblocks', 'cmsadmin');
                $this->objRss = $this->newObject('dblayouts');
                $this->objSkin =$this->newObject('skin', 'skin');
                $this->_objFrontPage =$this->newObject('dbcontentfrontpage', 'cmsadmin');
                $this->_objUser =$this->newObject('user', 'security');
                $this->_objUserModel =$this->newObject('useradmin_model','security');
                $this->objLanguage =$this->newObject('language', 'language');
                $this->_objContext =$this->newObject('dbcontext', 'context');
				$this->objFeatureBox = $this->newObject('featurebox', 'navigation');
				
                $objModule =$this->newObject('modules', 'modulecatalogue');
                if ($objModule->checkIfRegistered('context')) {
                    $this->inContextMode = $this->_objContext->getContextCode();
                    $this->contextCode = $this->_objContext->getContextCode();
                } else {
                    $this->inContextMode = FALSE;
                }

                $this->objDateTime = $this->getObject('datetime', 'utilities');

                $this->loadClass('textinput', 'htmlelements');
                $this->loadClass('checkbox', 'htmlelements');
                $this->loadClass('radio', 'htmlelements');
                $this->loadClass('dropdown', 'htmlelements');
                $this->loadClass('form', 'htmlelements');
                $this->loadClass('button', 'htmlelements');
                $this->loadClass('link', 'htmlelements');
                $this->loadClass('label', 'htmlelements');
                $this->loadClass('hiddeninput', 'htmlelements');
                $this->loadClass('textarea','htmlelements');
                $this->loadClass('htmltable','htmlelements');
                

		   } catch (Exception $e){
       		    throw customException($e->getMessage());
        	    exit();
     	   }
        }

        /**
         * Method to detemine the access
         *
         * @param int $access The access
         * @return string Registered if 1 else Public
         * @access public
         */
        public function getAccess($access)
        {
            if ($access == 1) {
                return $this->objLanguage->languageText('word_registered');
            } else {
                return $this->objLanguage->languageText('word_public');
            }
        }

        /**
         * Method to get the Yes/No radio  box
         *
         * @param  string $name The name of the radio box
         * @param string $selected The option to set as selected
         * @access public
         * @return string Html for radio buttons
         */
        public function getYesNoRadion($name, $selected = '1')
        {
            //Get visible not visible icons
            $objIcon =& $this->newObject('geticon', 'htmlelements');
            //Not visible
            $objIcon->setIcon('not_visible');
            $notVisibleIcon = $objIcon->show();
            //Visible
            $objIcon->setIcon('visible');
            $visibleIcon = $objIcon->show();

            $objRadio = new radio ($name);

            $objRadio->addOption('1', $visibleIcon.$this->objLanguage->languageText('word_yes'));
            $objRadio->addOption('0', $notVisibleIcon.$this->objLanguage->languageText('word_no').'&nbsp;'.'&nbsp;');

            $objRadio->setSelected($selected);

            $objRadio->setBreakSpace(' &nbsp; ');

            return $objRadio->show();
        }

        /**
         * Method to get the Access List dropdown
         *
         * @access public
         * @param string $name The name of the field
         * @return string Html for access dropdown
         */
        public function getAccessList($name)
        {
            $objDropDown = new dropdown($name);
            //fill the drop down with the list of images
            //TODO
            $objDropDown->addOption('0', $this->objLanguage->languageText('word_public'));
            $objDropDown->addOption('1', $this->objLanguage->languageText('word_registered'));
            $objDropDown->setSelected('0');
            $objDropDown->extra = 'size="2"';
            return $objDropDown->show();
        }

        /**
         * Method to get the layout options for a section
         * At the moment there are 4 types of layouts
         * The layouts will be diplayed as images for selection
         * The layouts templates will be displayed as images
         *
         * @param string $name The of the of the field
         * @return string Html for selecting layout type
         * @access public
         */
        public function getLayoutOptions($name, $id)
        {
            $objLayouts =$this->newObject('dblayouts', 'cmsadmin');
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
        }
        
        /**
         * Method to get the Control Panel
         * The control panel provides the first navigation screen to the CMS admin module
         * 
         * @return string The control Panel display data
         *@access public
         */
        public function getControlPanel()
        {
            $objLayer = $this->newObject('layer', 'htmlelements');
            $this->loadClass('htmltable', 'htmlelements');
            
        			$tbl = new htmltable();
                    $tbl->cellspacing = '5';
                    $tbl->cellpadding = '5';
                    $tbl->width = "45%";
                    $tbl->align = "left";
                          
                    $link =$this->newObject('link', 'htmlelements');
                    $objIcon = $this->newObject('geticon', 'htmlelements');


					//content link
					$link = $this->objLanguage->languageText('mod_cmsadmin_contentitem', 'cmsadmin');
					$url = $this->uri(array('action' => 'addcontent'));
					$icnContent = $objIcon->getBlockIcon($url, 'add_article', $link, 'png', 'icons/cms/');
                      
					//sections link
					$link = $this->objLanguage->languageText('mod_cmsadmin_sectionmanager', 'cmsadmin'); 
					$url = $this->uri(array('action' => 'sections'));
					$icnSection = $objIcon->getBlockIcon($url, 'section', $link, 'png', 'icons/cms/');
					
					 //Create front page manager link
		            $link = $this->objLanguage->languageText('mod_cmsadmin_frontpagemanager', 'cmsadmin');
		            $url = $this->uri(array('action' => 'frontpages'), 'cmsadmin');
		            $icnFront = $objIcon->getBlockIcon($url, 'frontpage', $link, 'png', 'icons/cms/');
		            
		            // Create archive / trash manager link
		            $url = $this->uri(array('action' => 'trashmanager'));
		            $link = $this->objLanguage->languageText('mod_cmsadmin_archive', 'cmsadmin');
					$icnArchive = $objIcon->getBlockIcon($url, 'trash', $link, 'png', 'icons/cms/');
					
					// RSS feeds manager link
		            $url = $this->uri(array('action' => 'createfeed'));
		            $link = $this->objLanguage->languageText('mod_cmsadmin_rss', 'cmsadmin');
					$icnRss = $objIcon->getBlockIcon($url, 'rss', $link, 'png', 'icons/cms/');
					
					// Menu manager link
		            //$url = $this->uri(array('action' => 'managemenus'));
		            $url = $this->uri(array('action' => 'menustyle'));
		            $link = $this->objLanguage->languageText('mod_cmsadmin_menu', 'cmsadmin');
					$icnMenu = $objIcon->getBlockIcon($url, 'menu2', $link, 'png', 'icons/cms/');
					
					// File manager link
		            $url = $this->uri('', 'filemanager');
		            $link = $this->objLanguage->languageText('phrase_uploadfiles');
					$icnFiles = $objIcon->getBlockIcon($url, 'media', $link, 'png', 'icons/cms/');
					
					$tbl->startRow();
					$tbl->addCell($icnContent);
					$tbl->addCell($icnSection);
		            $tbl->addCell($icnFront);
					$tbl->addCell($icnArchive);
					$tbl->endRow();
					
					$tbl->startRow();
					$tbl->addCell($icnRss);
					$tbl->addCell($icnMenu);
		            $tbl->addCell($icnFiles);
					$tbl->addCell('');
					$tbl->endRow();
					
					$tbl->startRow();
		            $tbl->addCell('&nbsp;');
					$tbl->endRow();
					
					$tbl->startRow();
					$tbl->endRow();
					
                    $objLayer->str = $tbl->show();
                    $objLayer->id = 'cpanel';
                    $fboxcontent = $objLayer->show();

                    return $this->objFeatureBox->showContent('',$fboxcontent);
                
        }
        /**
         * Method to get the Top navigation menus for CMS admin
         * The method renders the top navigation based on pages being rendered
         * @param string action which is the page or action being called
         * @param string params which is any params to be passed to top navigation
         * @return str the string top navigation
         * @access public
         */
        public function topNav($action='home',$params=NULL){
        	
        	//Declare objects
        	$tbl = $this->newObject('htmltable', 'htmlelements');
        	$link =$this->newObject('link', 'htmlelements');
        	$link2 =$this->newObject('link', 'htmlelements');
        	$cancel=$this->newObject('link','htmlelements');
        	$objIcon = $this->newObject('geticon', 'htmlelements');
        	$objIconSave = $this->newObject('geticon', 'htmlelements');
        	$objIconCancel = $this->newObject('geticon', 'htmlelements');
        	$icon_publish = $this->newObject('geticon', 'htmlelements');
        	$lnk_publish =$this->newObject('link', 'htmlelements');
        	$icon_unpublish = $this->newObject('geticon', 'htmlelements');
        	$lnk_unpublish =$this->newObject('link', 'htmlelements');
        	$icon_copy = $this->newObject('geticon', 'htmlelements');
        	$lnk_copy =$this->newObject('link', 'htmlelements');
        	$icon_delete = $this->newObject('geticon', 'htmlelements');
        	$lnk_delete =$this->newObject('link', 'htmlelements');
        	$icon_edit = $this->newObject('geticon', 'htmlelements');
        	$lnk_edit =$this->newObject('link', 'htmlelements');
        	$icon_new = $this->newObject('geticon', 'htmlelements');
        	$lnk_new =$this->newObject('link', 'htmlelements');
        	$icon_copy = $this->newObject('geticon', 'htmlelements');
        	$lnk_copy =$this->newObject('link', 'htmlelements');
        	$icon_upload = $this->newObject('geticon', 'htmlelements');
        	$lnk_upload = $this->newObject('link', 'htmlelements');
        	$icon_apply = $this->newObject('geticon', 'htmlelements');
        	$lnk_apply = $this->newObject('link', 'htmlelements');
        	$icon_sections = $this->newObject('geticon', 'htmlelements');
        	$lnk_sections = $this->newObject('link', 'htmlelements');
        	$icon_frontpage = $this->newObject('geticon', 'htmlelements');
        	$lnk_frontpage = $this->newObject('link', 'htmlelements');
		    $iconList = '';
						 
			 switch ($action) {
			 	case 'createcontent':
			 				 		
                    // Apply
			 		$url = $this->uri(array('action' => 'releaselock'), 'cms');
			 		$linkText = $this->objLanguage->languageText('word_apply');
			 		$iconList = $icon_publish->getTextIcon($url, 'apply', $linkText, 'gif', 'icons/cms/');

                    // Save
			 		$url = "javascript:if(validate_addfrm_form(document.getElementById('form_addfrm')) == true){ document.getElementById('form_addfrm').submit(); }";
			 		$linkText = $this->objLanguage->languageText('word_save');
			 		$iconList .= $icon_publish->getTextIcon($url, 'save', $linkText, 'gif', 'icons/cms/');
			 		
			 	    // Cancel	 		
			 		$url = "javascript:history.back();";
			 		$linkText = ucwords($this->objLanguage->languageText('word_cancel'));
			 		$iconList .= $icon_publish->getTextIcon($url, 'cancel', $linkText, 'gif', 'icons/cms/');
			 		
			 		// Preview
			 		$url1 = $this->uri('', 'cms');
			 		$url = '#';
			 		$extra = "onclick=\"window.open('{$url1}', 'preview_cms', ' width=700, height=500, resizable=yes, toolbar=yes, scrollbars=yes')\"";
			 		$linkText = $this->objLanguage->languageText('mod_cmsadmin_viewcms', 'cmsadmin');
			 		$iconList .= $icon_publish->getTextIcon($url, 'preview', $linkText, 'png', 'icons/cms/');
			 		
			 		return '<p style="align:right;">'.$iconList.'</p>';
			 		break;
			 		
			 	case 'sections':
			 			 		
			 		// Publish
			 		$url = "javascript:if(checkSelect('select','input_arrayList[]')==false){alert('Please make a selection from the list to Publish');}else{submitbutton('select','publish');}";
			 		$linkText = $this->objLanguage->languageText('word_publish');
			 		$iconList = $icon_publish->getTextIcon($url, 'publish', $linkText, 'png', 'icons/cms/');
			 		
			 		// Unpublish
			 		$url = "javascript:if(checkSelect('select','input_arrayList[]')==false){alert('Please make a selection from the list to Unpublish');}else{submitbutton('select','unpublish');}";
			 		$linkText = $this->objLanguage->languageText('word_unpublish');
			 		$iconList .= $icon_publish->getTextIcon($url, 'unpublish', $linkText, 'png', 'icons/cms/');
			 		/*
			 		// Copy
			 		$url = $this->uri('', 'cmsadmin');
			 		$linkText = $this->objLanguage->languageText('word_copy');
			 		$iconList .= $icon_publish->getTextIcon($url, 'copy', $linkText, 'gif', 'icons/cms/');
			 		*/
			 		
			 		// New - add
			 		$url = $this->uri(array('action' => 'addsection'), 'cmsadmin');
			 		$linkText = $this->objLanguage->languageText('word_new');
			 		$iconList .= $icon_publish->getTextIcon($url, 'new', $linkText, 'gif', 'icons/cms/');
			 		
			 		// Cancel	 		
			 		$url = "javascript:history.back();";
			 		$linkText = ucwords($this->objLanguage->languageText('word_cancel'));
			 		$iconList .= $icon_publish->getTextIcon($url, 'cancel', $linkText, 'gif', 'icons/cms/');
			 		
			 		// Preview
			 		$url1 = $this->uri('', 'cms');
			 		$url = '#';
			 		$extra = "onclick=\"window.open('{$url1}', 'preview_cms', ' width=700, height=500, resizable=yes, toolbar=yes, scrollbars=yes')\"";
			 		$linkText = $this->objLanguage->languageText('mod_cmsadmin_viewcms', 'cmsadmin');
			 		$iconList .= $icon_publish->getTextIcon($url, 'preview', $linkText, 'png', 'icons/cms/', $extra);
			 		
			 		return '<p style="align:right;">'.$iconList.'</p>';
			 		break;
			 		
			    case 'frontpage':
			        // Publish
			 		$url = "javascript: if(checkSelect('select','input_arrayList[]')==false){alert('Please make a selection from the list to Publish');}else{submitbutton('select','publish');}";
			 		$linkText = $this->objLanguage->languageText('word_publish');
			 		$iconList = $icon_publish->getTextIcon($url, 'publish', $linkText, 'png', 'icons/cms/');
			 		
			 		// Unpublish
			 		$url = "javascript:if(checkSelect('select','input_arrayList[]')==false){alert('Please make a selection from the list to Unpublish');}else{submitbutton('select','unpublish');}";
			 		$linkText = $this->objLanguage->languageText('word_unpublish');
			 		$iconList .= $icon_publish->getTextIcon($url, 'unpublish', $linkText, 'png', 'icons/cms/');
			 		
			 		// Cancel	 		
			 		$url = "javascript:history.back();";
			 		$linkText = ucwords($this->objLanguage->languageText('word_cancel'));
			 		$iconList .= $icon_publish->getTextIcon($url, 'cancel', $linkText, 'gif', 'icons/cms/');
			 		
			 		// Preview
			 		$url1 = $this->uri('', 'cms');
			 		$url = '#';
			 		$extra = "onclick=\"window.open('{$url1}', 'preview_cms', ' width=700, height=500, resizable=yes, toolbar=yes, scrollbars=yes')\"";
			 		$linkText = $this->objLanguage->languageText('mod_cmsadmin_viewcms', 'cmsadmin');
			 		$iconList .= $icon_publish->getTextIcon($url, 'preview', $linkText, 'png', 'icons/cms/', $extra);
			 		
			 		return '<p style="align:right;">'.$iconList.'</p>';
			 		break;
			 		
			 	case 'addsection':
			 				 		
			 		// Upload
			 		$url = $this->uri(array('action' => 'uploadimage'), 'cmsadmin');
			 		$linkText = $this->objLanguage->languageText('word_upload');
			 		$iconList = $icon_publish->getTextIcon($url, 'upload', $linkText, 'gif', 'icons/cms/');
			 				
			 		// Save
			 		$url = "javascript: if(validate_addsection_form(document.getElementById('addsection')) == true){ document.getElementById('addsection').submit(); }";
			 		$linkText = $this->objLanguage->languageText('word_save');
			 		$iconList .= $icon_publish->getTextIcon($url, 'save', $linkText, 'gif', 'icons/cms/');
			 		
			 		// Cancel
			 		$url = "javascript:history.back();";
			 		$linkText = ucwords($this->objLanguage->languageText('word_cancel'));
			 		$iconList .= $icon_publish->getTextIcon($url, 'cancel', $linkText, 'gif', 'icons/cms/');
			 		
			 		// Preview
			 		$url1 = $this->uri(array('action' => null), 'cms');
			 		$url = '#';
			 		$extra = "onclick=\"window.open('{$url1}', 'preview_cms', ' width=700, height=500, resizable=yes, toolbar=yes, scrollbars=yes')\"";
			 		$linkText = $this->objLanguage->languageText('mod_cmsadmin_viewcms', 'cmsadmin');
			 		$iconList .= $icon_publish->getTextIcon($url, 'preview', $linkText, 'png', 'icons/cms/', $extra);

			 		return '<p style="align:right;">'.$iconList.'</p>';
			 		break;
			 		
			 	case 'viewsection':
			 				 		
			 		// Section manager
			 		$url = $this->uri(array('action' => 'sections'), 'cmsadmin');
			 		$linkText = $this->objLanguage->languageText('mod_cmsadmin_sectionmanager', 'cmsadmin');
			 		$iconList = $icon_publish->getTextIcon($url, 'section', $linkText, 'png', 'icons/cms/');
			 		
			 		// front page manager
			 		$url = $this->uri(array('action' => 'frontpages'), 'cmsadmin');
			 		$linkText = $this->objLanguage->languageText('mod_cmsadmin_frontpagemanager', 'cmsadmin');
			 		$iconList .= $icon_publish->getTextIcon($url, 'frontpage', $linkText, 'png', 'icons/cms/');
			 		
			 		// Cancel	 		
			 		$url = "javascript:history.back();";
			 		$linkText = ucwords($this->objLanguage->languageText('word_cancel'));
			 		$iconList .= $icon_publish->getTextIcon($url, 'cancel', $linkText, 'gif', 'icons/cms/');
			 		
			 		// Preview
			 		$url1 = $this->uri('', 'cms');
			 		$url = '#';
			 		$extra = "onclick=\"window.open('{$url1}', 'preview_cms', ' width=700, height=500, resizable=yes, toolbar=yes, scrollbars=yes')\"";
			 		$linkText = $this->objLanguage->languageText('mod_cmsadmin_viewcms', 'cmsadmin');
			 		$iconList .= $icon_publish->getTextIcon($url, 'preview', $linkText, 'png', 'icons/cms/', $extra);			 		
			 		
			 		return '<p style="align:right;">'.$iconList.'</p>';
			 	    break;
			 	    
			 	case 'trash':

                    // Reload	 
                    $url = "javascript: if(checkSelect('select','input_arrayList[]')==false){alert('Please make a selection from the list of articles to Restore');}else{submitbutton('select','restore');}";
			 		$linkText = $this->objLanguage->languageText('mod_cmsadmin_restore', 'cmsadmin');
			 		$iconList = $icon_publish->getTextIcon($url, 'restore', $linkText, 'png', 'icons/cms/');

			 	    // Cancel	 		
			 		$url = "javascript:history.back();";
			 		$linkText = ucwords($this->objLanguage->languageText('word_cancel'));
			 		$iconList .= $icon_publish->getTextIcon($url, 'cancel', $linkText, 'gif', 'icons/cms/');
			 		
			 		// Preview
			 		$url1 = $this->uri('', 'cms');
			 		$url = '#';
			 		$extra = "onclick=\"window.open('{$url1}', 'preview_cms', ' width=700, height=500, resizable=yes, toolbar=yes, scrollbars=yes')\"";
			 		$linkText = $this->objLanguage->languageText('mod_cmsadmin_viewcms', 'cmsadmin');
			 		$iconList .= $icon_publish->getTextIcon($url, 'preview', $linkText, 'png', 'icons/cms/', $extra);
			 					 		
			 		return '<p style="align:right;">'.$iconList.'</p>';
			 		break;
			 		
			 	case 'menu':
			 	
			 	    /* Switch menu style
					$url = $this->uri(array('action' => 'menustyle'), 'cmsadmin');
			 		$linkText = $this->objLanguage->languageText('phrase_menustyle');
			 		$iconList = $icon_publish->getTextIcon($url, 'menu2', $linkText, 'png', 'icons/cms/');
			 		*/
			 		
			 	    // New menu
					$url = $this->uri(array('action' => 'addnewmenu','pageid'=>'0','add'=>'TRUE'), 'cmsadmin');
			 		$linkText = $this->objLanguage->languageText('word_new');
			 		$iconList .= $icon_publish->getTextIcon($url, 'new', $linkText, 'gif', 'icons/cms/');
			 					 	    
			 		// Cancel	 		
			 		$url = "javascript:history.back();";
			 		$linkText = ucwords($this->objLanguage->languageText('word_cancel'));
			 		$iconList .= $icon_publish->getTextIcon($url, 'cancel', $linkText, 'gif', 'icons/cms/');
			 		
			 		// Preview
			 		$url1 = $this->uri('', 'cms');
			 		$url = '#';
			 		$extra = "onclick=\"window.open('{$url1}', 'preview_cms', ' width=700, height=500, resizable=yes, toolbar=yes, scrollbars=yes')\"";
			 		$linkText = $this->objLanguage->languageText('mod_cmsadmin_viewcms', 'cmsadmin');
			 		$iconList .= $icon_publish->getTextIcon($url, 'preview', $linkText, 'png', 'icons/cms/', $extra);
			 					 		
			 		return '<p style="align:right;">'.$iconList.'</p>';
			 		break;
			 	    
			 	default:
			 		// Cancel	 		
			 		$url = "javascript:history.back();";
			 		$linkText = ucwords($this->objLanguage->languageText('word_cancel'));
			 		$iconList = $icon_publish->getTextIcon($url, 'cancel', $linkText, 'gif', 'icons/cms/');
			 		
			 		// Preview
			 		$url1 = $this->uri('', 'cms');
			 		$url = '#';
			 		$extra = "onclick=\"window.open('{$url1}', 'preview_cms', ' width=700, height=500, resizable=yes, toolbar=yes, scrollbars=yes')\"";
			 		$linkText = $this->objLanguage->languageText('mod_cmsadmin_viewcms', 'cmsadmin');
			 		$iconList .= $icon_publish->getTextIcon($url, 'preview', $linkText, 'png', 'icons/cms/', $extra);
			 		
			 		return '<p style="align:right;">'.$iconList.'</p>';
			 		break;
			 }
			 
			 return $tbl->show();
					
				
        	
        }
        /**
         * Method provides tabboxes for page artifacts
         * such as metadata and basic cms page behaviour artifacts
         * @param array Content The content to be modified
         * @return str tabs
         *
         */
        public function getConfigTabs($arrContent=NULL){
        	
        	/**
        	 * Defining Basic items to be displayed for 
        	 * First Tab
        	 */
        	//var_dump($arrContent);
        	$tabs =$this->newObject('tabpane','htmlelements');
        	$tbl_basic = $this->newObject('htmltable','htmlelements');
        	$tbl_advanced = $this->newObject('htmltable','htmlelements');
        	$tbl_meta =  $this->newObject('htmltable','htmlelements');
        	$objdate = $this->getObject('datepickajax', 'popupcalendar');
        	$publishing = $this->getObject('datepickajax', 'popupcalendar');
        	$publishing_end = $this->getObject('datepickajax', 'popupcalendar');
        	//date controls
        	if (is_array($arrContent)) {
        		//convert to strings to datetime
        		$override = date("Y-m-d H:i:s",strtotime($arrContent['created']));
        		$start_pub = '';
        		$end_pub = '';
        		
        		if (!is_null($arrContent['start_publish'])) {
        			$start_pub = date("Y-m-d H:i:s",strtotime($arrContent['start_publish']));
        			
        		}elseif (!is_null($arrContent['end_publish'])){
        			$end_pub = date("Y-m-d H:i:s",strtotime($arrContent['end_publish']));
        			
        		}
        		        		
        		$dateField = $objdate->show('overide_date', 'yes', 'no', $override);
        		$pub = $publishing->show('publish_date', 'yes', 'no',$start_pub);
        		$end_pub = $publishing_end->show('end_date', 'yes', 'no', $end_pub);
        		//Author Alias
        		$author = new textinput('author_alias',$arrContent['created_by_alias'],null,20);
        		$lbl_author = new label( $this->objLanguage->languageText('mod_cmsadmin_author_alias','cmsadmin'),'author_alias');
        		//Pre-populated dropdown
        		$creator = new dropdown('creator');
        		$users = $this->_objUserModel->getUsers('username','listall');
        		$creator->addFromDB($users,"username","userid",$arrContent['created_by']);
        		$lbl_creator = new label($this->objLanguage->languageText('mod_cmsadmin_change_author','cmsadmin'),'creator');
        		//Change Created Date
        		$lbl_date_created = new label($this->objLanguage->languageText('mod_cmsadmin_override_creation_date','cmsadmin'),'overide_date');
        		$lbl_pub = new label($this->objLanguage->languageText('mod_cmsadmin_start_publishing','cmsadmin'),'publish_date');
        		$lbl_pub_end = new label($this->objLanguage->languageText('mod_cmsadmin_end_publishing','cmsadmin'),'end_date');
        		
        	}else{
        		$dateField = $objdate->show('overide_date', 'yes', 'no', '');
        		$pub = $publishing->show('publish_date', 'yes', 'no', '');
        		$end_pub = $publishing_end->show('end_date', 'yes', 'no', '');
        		//Author Alias
        		$author = new textinput('author_alias',null,null,20);
        		$lbl_author = new label( $this->objLanguage->languageText('mod_cmsadmin_author_alias','cmsadmin'),'author_alias');
        		//Pre-populated dropdown
        		$creator = new dropdown('creator');
        		$users = $this->_objUserModel->getUsers('username','listall');
        		$creator->addFromDB($users,"username","userid");
        		$lbl_creator = new label($this->objLanguage->languageText('mod_cmsadmin_change_author','cmsadmin'),'creator');
        		//Change Created Date
        		$lbl_date_created = new label($this->objLanguage->languageText('mod_cmsadmin_override_creation_date','cmsadmin'),'overide_date');
        		$lbl_pub = new label($this->objLanguage->languageText('mod_cmsadmin_start_publishing','cmsadmin'),'publish_date');
        		$lbl_pub_end = new label($this->objLanguage->languageText('mod_cmsadmin_end_publishing','cmsadmin'),'end_date');
        	}
        	
        	//Start with the basic table
        	
        	$tbl_basic->startRow();
        	$tbl_basic->endRow();
        	$tbl_basic->startRow();
        	$tbl_basic->addCell($lbl_author->show());
        	$tbl_basic->addCell($author->show());
        	$tbl_basic->endRow();
        	$tbl_basic->startRow();
        	$tbl_basic->addCell($lbl_creator->show());
        	$tbl_basic->addCell($creator->show());
        	$tbl_basic->endRow();
        	$tbl_basic->startRow();
        	$tbl_basic->addCell($lbl_date_created->show());
        	$tbl_basic->addCell($dateField);
        	$tbl_basic->endRow();
        	$tbl_basic->startRow();
        	$tbl_basic->addCell($lbl_pub->show());
        	$tbl_basic->addCell($pub);
        	$tbl_basic->endRow();
        	$tbl_basic->startRow();
        	$tbl_basic->addCell($lbl_pub_end->show());
        	$tbl_basic->addCell($end_pub);
        	$tbl_basic->endRow();
        	
        	/**
        	 * Defining Items to be added to Advanced Tab
        	 */
        	if (is_array($arrContent)) {
        		$opt_author = new dropdown('show_auth');
        		$opt_author->addOption('g',"-Global-Option-");
        		$opt_author->addOption('y','yes');
        		$opt_author->addOption('n','no');
        		$opt_author->setSelected('g');
        		$lbl_author_names = new label($this->objLanguage->languageText('mod_cmsadmin_show_author','cmsadmin'),'show_auth');
        		//pdf option
        		$opt_pdf = new dropdown('show_pdf');
        		$opt_pdf->addOption('g',"-Global-Option-");
        		$opt_pdf->addOption("y",'yes');
        		$opt_pdf->addOption("n",'no');
        		$opt_pdf->setSelected('g');
        		$lbl_pdf = new label($this->objLanguage->languageText('mod_cmsadmin_show_pdf','cmsadmin'),'show_pdf');
        		//email option
        		$opt_email = new dropdown('show_email');
        		$opt_email->addOption('g',"-Global-Option-");
        		$opt_email->addOption('y','yes');
        		$opt_email->addOption('n','no');
        		$opt_email->setSelected('g');
        		$lbl_email = new label($this->objLanguage->languageText('mod_cmsadmin_show_email','cmsadmin'),'show_email');
        		//Print
        		$opt_print = new dropdown('show_print');
        		$opt_print->addOption('g',"-Global-Option-");
        		$opt_print->addOption('y','yes');
        		$opt_print->addOption('n','no');
        		$opt_print->setSelected('g');
        		$lbl_print = new label($this->objLanguage->languageText('mod_cmsadmin_show_print','cmsadmin'),'show_print');
        		
        	}else{
        		$opt_author = new dropdown('show_auth');
        		$opt_author->addOption('g',"-Global-Option-");
        		$opt_author->addOption('y','yes');
        		$opt_author->addOption('n','no');
        		$opt_author->setSelected('g');
        		$lbl_author_names = new label($this->objLanguage->languageText('mod_cmsadmin_show_author','cmsadmin'),'show_auth');
        		//pdf option
        		$opt_pdf = new dropdown('show_pdf');
        		$opt_pdf->addOption('g',"-Global-Option-");
        		$opt_pdf->addOption("y",'yes');
        		$opt_pdf->addOption("n",'no');
        		$opt_pdf->setSelected('g');
        		$lbl_pdf = new label($this->objLanguage->languageText('mod_cmsadmin_show_pdf','cmsadmin'),'show_pdf');
        		//email option
        		$opt_email = new dropdown('show_email');
        		$opt_email->addOption('g',"-Global-Option-");
        		$opt_email->addOption('y','yes');
        		$opt_email->addOption('n','no');
        		$opt_email->setSelected('g');
        		$lbl_email = new label($this->objLanguage->languageText('mod_cmsadmin_show_email','cmsadmin'),'show_email');
        		//Print
        		$opt_print = new dropdown('show_print');
        		$opt_print->addOption('g',"-Global-Option-");
        		$opt_print->addOption('y','yes');
        		$opt_print->addOption('n','no');
        		$opt_print->setSelected('g');
        		$lbl_print = new label($this->objLanguage->languageText('mod_cmsadmin_show_print','cmsadmin'),'show_print');
        	}
        	//add items to tables for good layout
        	
        	$tbl_advanced->startRow();
        	$tbl_advanced->addCell($lbl_author_names->show());
        	$tbl_advanced->addCell($opt_author->show());
        	$tbl_advanced->endRow();
        	$tbl_advanced->startRow();
        	$tbl_advanced->addCell($lbl_pdf->show());
        	$tbl_advanced->addCell($opt_pdf->show());
        	$tbl_advanced->endRow();
        	$tbl_advanced->startRow();
        	$tbl_advanced->addCell($lbl_email->show());
        	$tbl_advanced->addCell($opt_email->show());
        	$tbl_advanced->endRow();
        	$tbl_advanced->startRow();
        	$tbl_advanced->addCell($lbl_print->show());
        	$tbl_advanced->addCell($opt_print->show());
        	$tbl_advanced->endRow();
        	
        	/**
        	 * Defining Items for Metadata
        	 */
        	if (is_array($arrContent)) {
        		$keyword = new textarea('keyword',$arrContent['metakey'],6);
        		$lbl_keyword = new label($this->objLanguage->languageText('mod_cmsadmin_keyword','cmsadmin'),'keyword');
        		$descr = new textarea('description',$arrContent['metadesc'],6);
        		$lbl_descr = new label($this->objLanguage->languageText('mod_cmsadmin_description','cmsadmin'),'description');
        	}else{
        		$keyword = new textarea('keyword',null,6);
        		$lbl_keyword = new label($this->objLanguage->languageText('mod_cmsadmin_keyword','cmsadmin'),'keyword');
        		$descr = new textarea('description',null,6);
        		$lbl_descr = new label($this->objLanguage->languageText('mod_cmsadmin_description','cmsadmin'),'description');
        	}
        	$tbl_meta->startRow();
        	$tbl_meta->addCell($lbl_keyword->show());
        	$tbl_meta->endRow();
        	$tbl_meta->startRow();
        	$tbl_meta->addCell($keyword->show());
        	$tbl_meta->endRow();
        	$tbl_meta->startRow();
        	$tbl_meta->addCell($lbl_descr->show());
        	$tbl_meta->endRow();
        	$tbl_meta->startRow();
        	$tbl_meta->addCell($descr->show());
        	$tbl_meta->endRow();
        	$tbl_meta->startRow();
        	if (is_array($arrContent)) {
        		$tbl_meta->addCell("<input type=\"button\" class=\"button\" value=\"{$this->objLanguage->languageText('mod_cmsadmin_add_section_button','cmsadmin')}\" onclick=\"f=document.getElementById('form_addfrm');f.keyword.value=document.getElementById('form_addfrm').parent.value+', '+f.title.value+f.keyword.value;\" />");
        	}else{
        		$tbl_meta->addCell("<input type=\"button\" class=\"button\" value=\"{$this->objLanguage->languageText('mod_cmsadmin_add_section_button','cmsadmin')}\" onclick=\"f=document.getElementById('form_addfrm');f.keyword.value=document.getElementById('form_addfrm').parent.options[document.getElementById('form_addfrm').parent.selectedIndex].text+', '+f.title.value+f.keyword.value;\" />");
        	}
        	
        	
        	$tbl_meta->endRow();
        	//Add items to tabs
        	$tabs->addTab(array('name'=>"{$this->objLanguage->languageText('mod_cmsadmin_basic','cmsadmin')}",'','content' => $tbl_basic->show()),'winclassic-tab-style-sheet');
        	$tabs->addTab(array('name'=>"{$this->objLanguage->languageText('mod_cmsadmin_advanced','cmsadmin')}",'','content' => $tbl_advanced->show()),'winclassic-tab-style-sheet');
        	$tabs->addTab(array('name'=>"{$this->objLanguage->languageText('mod_cmsadmin_meta','cmsadmin')}",'','content' => $tbl_meta->show()),'winclassic-tab-style-sheet');
        	
        	return $tabs->show();
        }

        /**
         * Method to the true/false tick
         *
         * @param  $isCheck Booleans value with either TRUE|FALSE
         * @return string icon
         * @access public
         */
        public function getCheckIcon($isCheck, $returnFalse = TRUE)
        {
            $objIcon =$this->newObject('geticon', 'htmlelements');

            if ($isCheck) {
                $objIcon->setIcon('visible', 'gif');
            } else {
                if ($returnFalse) {
                    $objIcon->setIcon('not_visible', 'gif');
                }
            }

            return $objIcon->show();
        }

        /**
         * Method to generate the navigation
         *
         * @access public
         * @return string The html for the side bar link / navigation
         */
        public function getNav()
        {
            $lbSection = $this->objLanguage->languageText('mod_cmsadmin_sectionnotvisible', 'cmsadmin');
            $lbOrange = $this->objLanguage->languageText('mod_cmsadmin_sectionsetnotvisible', 'cmsadmin');
            $lbWhite = $this->objLanguage->languageText('mod_cmsadmin_sectionnocontent', 'cmsadmin');
            $lbGreen = $this->objLanguage->languageText('mod_cmsadmin_sectionparentnotvisible', 'cmsadmin');
            
            //Instantiate cms tree object
            $objCmsTree =$this->newObject('cmstree', 'cmsadmin');
            $objFeatureBox = $this->newObject('featurebox', 'navigation');
            //Instantiate link object
            $link =$this->newObject('link', 'htmlelements');
            $objIcon = $this->newObject('geticon', 'htmlelements');
            
            //Roundconer object
            $objRound =$this->newObject('roundcorners','htmlelements');
            //Create heading

            //Create cms admin link
            $link = $this->objLanguage->languageText('mod_cmsadmin_cpanel','cmsadmin');
            $url = $this->uri('', 'cmsadmin');
            $cmsAdminLink = $objIcon->getTextIcon($url, 'control_panel', $link, 'png', 'icons/cms/');
            
            // Create RSS link
            $link = $this->objLanguage->languageText('mod_cmsadmin_rss','cmsadmin');
            $url = $this->uri(array('action' => 'createfeed'), 'cmsadmin');
            $createRss = $objIcon->getTextIcon($url, 'rss', $link, 'png', 'icons/cms/');
            
            //Create menu management
            $link = $this->objLanguage->languageText('mod_cmsadmin_menu','cmsadmin');
            //$url = $this->uri(array('action' => 'managemenus'), 'cmsadmin');
            $url = $this->uri(array('action' => 'menustyle'), 'cmsadmin');
            $menuMangement = $objIcon->getTextIcon($url, 'menu2', $link, 'png', 'icons/cms/');
            
            //Create filemanager menu
            $link = $this->objLanguage->languageText('phrase_uploadfiles');
            $url = $this->uri(array('action' => ''), 'filemanager');
            $filemanager = $objIcon->getTextIcon($url, 'media', $link, 'png', 'icons/cms/');
            
			$objCMSTree = $this->getObject('cmstree');

            //Add links to the output layer
            $nav = $objFeatureBox->show($this->objLanguage->languageText('word_sections'),$objCMSTree->getCMSAdminTree());
            //$nav .= '<br/>';
			// $nav .= $createSectionLink;
            // $nav .= '<br/>'.'&nbsp;'.'<br/>';
            //$nav .= $frontpageManagerLink;
            $nav .= '<br/>'.'&nbsp;'.'<br />';
            //$nav .= $viewCmsLink.'<br /><br />';
			$nav .= $objFeatureBox->showContent('<strong>Navigation Links</strong><hr />
					'.$cmsAdminLink.'<br />
					&nbsp;&nbsp;'.$createRss.'<br />
					&nbsp;&nbsp;'.$menuMangement.'<br />
					&nbsp;&nbsp;'.$filemanager.'<br />
					<div style="clear: both;">&nbsp;</div>
					');
            $nav .= '<br />';

            return $nav;
        }
        
        /**
     * Method to show the content info for the group list popup
     *
     * @access public
     * @return string The page content to be displayed
     */
    public function showGroupContent($groupId, $groupFieldName = 'input_publisherid')
    {
   		$this->loadClass('textinput','htmlelements');
   		$this->loadClass('hiddeninput','htmlelements');
        $objGroups = & $this->newObject('dbgroups', 'cmsadmin');
        $group = $objGroups->getNode($groupId);

        //initiate objects
        $objForm = new form('editfrm', $this->uri(array()));
        $objForm->setDisplayType(3);

        $selectedGroupInput = new textinput('selectedgroup');

        if (count($group) > 0) {
            $selectedGroupInput->value = $groupId;
            $table = & $this->newObject('htmltable', 'htmlelements');
            $table->startRow();
            $table->addCell('Name');
            $table->addCell($group[0]['name']);
            $table->endRow();
            $table->startRow();
            $table->addCell('Description');
            $table->addCell($group[0]['description']);
            $table->endRow();

            $objForm->addToForm($table->show());
        }
        $objForm->addToForm($selectedGroupInput->show());

        $strHTML = $objForm->show();
        $strHTML .= '<br /><button onclick="javascript:opener.document.getElementById(\''.$groupFieldName.'\').value=document.getElementById(\'input_selectedgroup\').value;window.close()">Link</button>';

        return $strHTML;
    }


    /**
	* Method to check if a user is in a group
    *
    * @param string $userId User ID of logd in user
    * @param string $group Group to check for
    * @return TRUE|FALSE TRUE if user is in group
    * @access public
	*/
  	public function inGroup($group)
    {
        $userId = $this->objUser->userId();
        $objGroupModel =& $this->getObject('groupadminmodel','groupadmin');
        $id = $this->objUser->PKid($userId);
        $groupId = $objGroupModel->getId($group);
        return $objGroupModel->isGroupMember($id, $groupId);
    }

    /**
	* Method to check if a user is in a group using group Id
    *
    * @param string $userId User ID of logd in user
    * @param string $group Group to check for
    * @return TRUE|FALSE TRUE if user is in group
    * @access public
	*/
  	public function inGroupById($groupId)
    {
        $userId = $this->objUser->userId();
        $objGroupModel =& $this->getObject('groupadminmodel','groupadmin');
        $id = $this->objUser->PKid($userId);
        return $objGroupModel->isGroupMember($id, $groupId);
    }



    /**
	* Method to check if a user is in a group using group Id
    *
    * @param string $userId User ID of logd in user
    * @param string $group Group to check for
    * @return TRUE|FALSE TRUE if user is in group
    * @access public
	*/
  	public function userGroups()
    {
        $userId = $this->objUser->userId();
        $objGroupModel =& $this->getObject('groupadminmodel','groupadmin');
        $id = $this->objUser->PKid($userId);
        return $objGroupModel->getUserGroups($id);
    }

        
     /**
     * Method to show  the edit page screen in Menu management
     *
     * @access public
     * @return string The page content to be displayed
     */
    public function showEditNode($menuNodeId)
    {
   		$this->loadClass('textinput','htmlelements');
   		$this->loadClass('hiddeninput','htmlelements');
   		$this->loadClass('dropdown','htmlelements');
   		$this->loadClass('checkbox','htmlelements');
   		$this->loadClass('windowPop','htmlelements');
   		$objHtmlCleaner = $this->newObject('htmlcleaner', 'utilities');
		$this->objTreeMenu = $this->newObject('buildtree', 'cmsadmin');
        $menuNode = $this->objTreeMenu->getNode($menuNodeId);
        //initiate objects
        $table =  $this->newObject('htmltable', 'htmlelements');
        $objForm = new form('editfrm', $this->uri(array('action' => 'savemenu', 'id' => $menuNodeId)));
        $objForm->setDisplayType(3);


        $nodeTypeInput = new dropdown ('nodetype');
        if ($menuNode[0]['parent_id'] == '0') {
            $nodeTypeInput->addOption('0','Menu Root'.'&nbsp;&nbsp;');
            $nodeTypeInput->selected = '0';
            $linkReferenceInput = new hiddeninput ('linkreference');
            $bannerInput = new hiddeninput ('banner');
            $cssInput = new hiddeninput ('css');
            $layoutInput = new hiddeninput ('layout');
        } else {
            $nodeTypeInput->addOption('1','CMS Content'.'&nbsp;&nbsp;');
            $nodeTypeInput->addOption('2','External Link'.'&nbsp;&nbsp;');
            $nodeTypeInput->addOption('3','News Item'.'&nbsp;&nbsp;');
            $nodeTypeInput->selected = $menuNode[0]['node_type'];
            $linkReferenceInput = new textinput ('linkreference');
            $bannerInput = new textinput ('banner');
            $cssInput = new textinput ('css');
            $layoutInput = new textinput ('layout');
        }
        $titleInput = new textinput ('title', null, null, 30);
        $publishedInput = new checkbox ('published', 'Published');

        $publisherIdInput = new textinput('publisherid', $menuNode[0]['publisher_id']);
        $parentIdInput = new hiddeninput('parentid', $menuNode[0]['parent_id']);
        $orderingInput = new hiddeninput ('ordering', $menuNode[0]['ordering']);

        // Submit Button
        $button = new button('submitform', $this->objLanguage->languageText('word_save'));
        $button->setToSubmit();


        $titleInput->value = html_entity_decode($menuNode[0]['title']);
        $linkReferenceInput->value = html_entity_decode($menuNode[0]['link_reference']);
        $bannerInput->value = $menuNode[0]['banner'];
        $cssInput->value = $menuNode[0]['css'];
        $layoutInput->value = $menuNode[0]['layout'];
        if ($menuNode[0]['published'] == 1) {
            $publishedInput->setChecked(TRUE);
        } else {
            $publishedInput->setChecked(FALSE);
        }


        $table->startRow();
        $table->addCell('Node Id');
        $table->addCell($menuNode[0]['id']);
        $table->endRow();
        $table->startRow();
        $table->addCell('Node Type');
        $table->addCell($nodeTypeInput->show());
        $table->endRow();
        $table->startRow();
        $table->addCell('Title');
        $table->addCell($titleInput->show());
        $table->endRow();

        if ($menuNode[0]['parent_id'] == '0') {
            $objForm->addToForm($linkReferenceInput->show());
            $objForm->addToForm($bannerInput->show());
            $objForm->addToForm($layoutInput->show());
            $objForm->addToForm($cssInput->show());
        } else {
            $table->startRow();
            $table->addCell('Link Reference');
            $table->addCell($linkReferenceInput->show());
            if ($menuNode[0]['node_type'] == 1) {
                $objPop= new windowPop;
                $objPop->set('location',$this->uri(array('action' => 'showcmspages', 'id' => $menuNode[0]['link_reference'], 'pageid' => $menuNodeId), 'cmsadmin'));
                $objPop->set('linktext','CMS content');
                $objPop->set('width','600');
                $objPop->set('height','600');
                $objPop->set('left','300');
                $objPop->set('top','400');
                $objPop->putJs(); // you only need to do this once per page

                $table->addCell($objPop->show());
            }
            $table->endRow();
            $table->startRow();
            $table->addCell('Banner');
            $table->addCell($bannerInput->show());
            $table->endRow();
            $table->startRow();
            $table->addCell('Layout');
            $table->addCell($layoutInput->show());
            $table->endRow();
            $table->startRow();
            $table->addCell('CSS');
            $table->addCell($cssInput->show());
            $table->endRow();
        }
        $table->startRow();
        $table->addCell('Published');
        $table->addCell($publishedInput->show());
        $table->endRow();
        $table->startRow();
        $table->addCell('Group Id');
        $table->addCell($publisherIdInput->show());

        $objPop= new windowPop;
        $objPop->set('location',$this->uri(array('action' => 'showgrouplist', 'groupid' => $menuNode[0]['publisher_id']), 'cmsadmin'));
        $objPop->set('linktext','Group list');
        $objPop->set('width','600');
        $objPop->set('height','600');
        $objPop->set('left','300');
        $objPop->set('top','400');
        $objPop->putJs(); // you only need to do this once per page

        $table->addCell($objPop->show());

        $table->endRow();
        $table->startRow();
        $table->addCell($button->show());
        $table->endRow();

        $objForm->addToForm($table->show());

        $objForm->addToForm($parentIdInput->show());
        $objForm->addToForm($orderingInput->show());
		$objH = null;
        $objH = $this->newObject('htmlheading', 'htmlelements');
        $objH->type = '3';
        $objH->str = 'Editing: '. $menuNode[0]['title'];
        $strHTML = $objH->show();
		
        $strHTML.= $objForm->show();


        return $strHTML;
    }

    /**
     * Method to show  the add node screen when managing menus
     *
     * @access public
     * @return string The page content to be displayed
     */
    public function showAddNode($parentId)
    {
   		$this->loadClass('textinput','htmlelements');
   		$this->loadClass('hiddeninput','htmlelements');
   		$this->loadClass('dropdown','htmlelements');
   		$this->loadClass('checkbox','htmlelements');
   		$this->loadClass('windowPop','htmlelements');
		 $this->objTreeMenu =  $this->newObject('buildtree', 'cmsadmin');
		 $menuNode = $this->objTreeMenu->getNode($parentId);
        if (count($menuNode) > 0) {
            $parentTitle = $menuNode[0]['title'];
        } else {
            $parentTitle = 'Root';
        }
        //initiate objects
        $table = $this->newObject('htmltable', 'htmlelements');
        $objForm = new form('editfrm', $this->uri(array('action' => 'addmenu')));
        $objForm->setDisplayType(3);


        $nodeTypeInput = new dropdown ('nodetype');

        if ($parentId == '0') {
            $nodeTypeInput->addOption('0','Menu Root'.'&nbsp;&nbsp;');
            $nodeTypeInput->selected = '0';
            $linkReferenceInput = new hiddeninput ('linkreference', '');
            $bannerInput = new hiddeninput ('banner', '');
            $cssInput = new hiddeninput ('css', '');
            $layoutInput = new hiddeninput ('layout', '');
        } else {
            $nodeTypeInput->addOption('1','CMS Content'.'&nbsp;&nbsp;');
            $nodeTypeInput->addOption('2','External Link'.'&nbsp;&nbsp;');
            $nodeTypeInput->addOption('3','News Item'.'&nbsp;&nbsp;');
            $nodeTypeInput->selected = '1';
            $linkReferenceInput = new textinput ('linkreference');
            $bannerInput = new textinput ('banner');
            $cssInput = new textinput ('css');
            $layoutInput = new textinput ('layout');
        }

        $titleInput = new textinput ('title');
        $publishedInput = new checkbox ('published', 'Published', TRUE);

        $publisherIdInput = new textinput('publisherid');

        $parentIdInput = new hiddeninput('parentid', $parentId);
        // Submit Button
        $button = new button('submitform', $this->objLanguage->languageText('word_save'));
        $button->setToSubmit();


        $table->startRow();
        $table->addCell('Node Type');
        $table->addCell($nodeTypeInput->show());
        $table->endRow();
        $table->startRow();
        $table->addCell('Title');
        $table->addCell($titleInput->show());
        $table->endRow();
        if ($parentId == '0') {
            $objForm->addToForm($linkReferenceInput->show());
            $objForm->addToForm($bannerInput->show());
            $objForm->addToForm($layoutInput->show());
            $objForm->addToForm($cssInput->show());
        } else {
            $table->startRow();
            $table->addCell('Link Reference');
            $table->addCell($linkReferenceInput->show());
            $table->endRow();
            $table->startRow();
            $table->addCell('Banner');
            $table->addCell($bannerInput->show());
            $table->endRow();
            $table->startRow();
            $table->addCell('Layout');
            $table->addCell($layoutInput->show());
            $table->endRow();
            $table->startRow();
            $table->addCell('CSS');
            $table->addCell($cssInput->show());
            $table->endRow();
        }
        $table->startRow();
        $table->addCell('Published');
        $table->addCell($publishedInput->show());
        $table->endRow();
        $table->startRow();
        $table->addCell('Group Id');
        $table->addCell($publisherIdInput->show());

        $objPop= new windowPop;
        $objPop->set('location',$this->uri(array('action' => 'showgrouplist'), 'cmsadmin'));
        $objPop->set('linktext','Group list');
        $objPop->set('width','600');
        $objPop->set('height','600');
        $objPop->set('left','300');
        $objPop->set('top','400');
        $objPop->putJs(); // you only need to do this once per page

        $table->addCell($objPop->show());
        $table->endRow();
        $table->startRow();
        $table->addCell($button->show());
        $table->endRow();

        $objForm->addToForm($table->show());

        $objForm->addToForm($parentIdInput->show());
		$objH = null;	
        $objH = $this->newObject('htmlheading', 'htmlelements');
        $objH->type = '3';
        $objH->str = 'Add node to: '. $parentTitle;
        $strHTML = $objH->show().'<p/>';

        $strHTML .= $objForm->show();


        return $strHTML;
    }

        /**
        * Method to generate the bread crumbs
        *
        * @param void
        * @return string Html for breadcrumbs
        * @access public
        */
        public function getBreadCrumbs($module = 'cms')
        {
            $str = '';
            $objTools =$this->newObject('tools', 'toolbar');
            if ($this->getParam('action') == '') {
                return '';
            }

            $home =$this->newObject('link', 'htmlelements');
            $link =$this->newObject('link', 'htmlelements');

            if (!is_null($this->getParam('sectionid', NULL))) {

                $section = $this->_objSections->getSection($this->getParam('sectionid'));
                $link->href = $this->uri(array('action' => 'showsection', 'id' => $this->getParam('sectionid'), 'sectionid' => $this->getParam('sectionid')) , $module);
                $link->link = $this->_objSections->getMenuText($this->getParam('sectionid'));
                $str = $link->show() .' / ';

                while (($section['parentid'] != '0') && (!is_null($section['parentid']))) {
                    $section = $this->_objSections->getSection($section['parentid']);
                    if (is_null($section['parentid'])) {
                        break;
                    }
                    $link->href = $this->uri(array('action' => 'showsection', 'id' => $section['id'], 'sectionid' => $section['id']) , $module);
                    $link->link = $this->_objSections->getMenuText($section['id']);
                    $str = $link->show() .' / ' .$str;

                }
            }
            if (!is_null($this->getParam('id', NULL))) {
                $page = $this->_objContent->getContentPage($this->getParam('id'));
                $str .= $page['title'];
            }
            $home->href = $this->uri(null , $module);
            $home->link = $this->objLanguage->languageText('word_home');
            $str = $home->show() .' / ' . $str;

            $objTools->replaceBreadCrumbs(split(' / ', $str));
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
        public function getTreeDropdown($setSelected = NULL, $noRoot = TRUE)
        {
            $objCMSTree = $this->getObject('cmstree');

			return $objCMSTree->getCMSAdminDropdownTree($setSelected, $noRoot);

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
                    if ($sec['nodelevel'] > $numLevels) {
                        $numLevels = $sec['nodelevel'];
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
         * @param string contextcode The context the user is in: defaults to null
         * @access public
         * @author Warren Windvogel
         */
        public function getSectionLinks($forTable = FALSE,$contextcode=null)
        {
            //Create instance of geticon object
            $objIcon =$this->newObject('geticon', 'htmlelements');
            //Get all root sections
            $availsections = $this->_objSections->getRootNodes(FALSE,$contextcode);
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
                            $subSecs = $this->_objSections->getSubSectionsForLevel($section['id'], $i, 'DESC');
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
                if($forTable) {
                    return $treeArray;
                } else {
                    $links = "";
                    $objLink =$this->newObject('link', 'htmlelements');
                    //Add array to dropdown
                    foreach($treeArray as $node) {
                        $matches = split('<', $node['title']);
                        $img = split('>', $matches[1]);
                        $image = '<'.$img[0].'>';
                        $linkText = $img[1];
                        $noSpaces = strlen($matches[0]);
                        //Add space for indentation of node levels
                        for ($i = 1; $i < $noSpaces; $i++) {
                            $links .= '&nbsp;&nbsp;';
                        }
                        //Add folder image
                        $links .= $image;
                        //Create link to section
                        $objLink->link($this->uri(array('action' => 'viewsection', 'id' => $node['id'])));
                        $objLink->link = $linkText;
                        //Add link to section
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
                    if ($section['published'] == 0) {
                        $isVisible = false;
                    }
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

            //initiate objects
            $table =$this->newObject('htmltable', 'htmlelements');
			$objRound =$this->newObject('roundcorners','htmlelements');
			$objIcon =  $this->newObject('geticon', 'htmlelements');
			$tbl = $this->newObject('htmltable', 'htmlelements');
            $titleInput =$this->newObject('textinput', 'htmlelements');
            $menuTextInput =$this->newObject('textinput', 'htmlelements');
            $h3 =$this->newObject('htmlheading', 'htmlelements');
            $sections =$this->newObject('dropdown', 'htmlelements');
            $parent =$this->newObject('dropdown', 'htmlelements');
            $button =$this->newObject('button', 'htmlelements');
            $objRootId =$this->newObject('textinput', 'htmlelements');
            $objParentId =$this->newObject('textinput', 'htmlelements');
            $objCount =$this->newObject('textinput', 'htmlelements');
            $objOrdering =$this->newObject('textinput', 'htmlelements');
			$ContextInput =$this->newObject('textinput', 'htmlelements');
			$objLayer =$this->newObject('layer', 'htmlelements');
			$this->loadClass('image','htmlelements');
			$objFiles =$this->getObject('dbfile','filemanager');
			 $objUser =$this->getObject('user', 'security');
			 $objconfig = $this->getObject('altconfig','config');
			 
			
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
            
            // Dropdown list of page layouts
            
            $objDrop = new dropdown('display');
            $objDrop->cssId = 'display';
            
            $objDrop->addOption('page', $this->objLanguage->languageText('mod_cmsadmin_layout_pagebypage', 'cmsadmin'));
            $objDrop->addOption('previous', $this->objLanguage->languageText('mod_cmsadmin_layout_previouspagebelow', 'cmsadmin'));
            $objDrop->addOption('list', $this->objLanguage->languageText('mod_cmsadmin_layout_listofpages', 'cmsadmin'));
            $objDrop->addOption('summaries', $this->objLanguage->languageText('mod_cmsadmin_layout_summaries', 'cmsadmin'));
            //$objDrop->addOption('columns', $this->objLanguage->languageText('mod_cmsadmin_layout_columns', 'cmsadmin'));
            
            if ($editmode) {
                $objDrop->setSelected($section['layout']);
                $imgPath = $this->getResourceUri('section_'.$section['layout'].'.gif', 'cmsadmin');
                $this->appendArrayVar('bodyOnLoad', 'xajax_processSection(\''.$section['layout'].'\')');
            } else {
                $objDrop->setSelected('page');
                $imgPath = $this->getResourceUri('section_page.gif', 'cmsadmin');
                $this->appendArrayVar('bodyOnLoad', 'xajax_processSection(\'page\')');
            }
            
            $objDrop->extra = "onchange=\"xajax_processSection(this.value); javascript: 
            var path = '{$this->getResourceUri('', 'cmsadmin')}'; 
            var image = path+'section_'+this.value+'.gif';
            document.getElementById('img').src = image;\"";
            
            $imgStr = "<img id='img' src='{$imgPath}' />";
            
            $layoutStr = $objDrop->show();
            
			//layout preview image place holder
			$imagesrc = $this->_objConfig->getSiteRootPath().'/skins/_common/blank.png';
			$image ="<img src='{$imagesrc}' name='imagelib' id='imagelib' border='2' height='80' width='80' />" ;
			$imageThumb = new textinput('imagesrc',$imagesrc,'hidden');
			//read for images
			$listFiles = $objFiles->getUserFiles($objUser->userId(), null);	 
		    
			$drp_image = new dropdown('image');
			$drp_image->id= 'image';
			$drp_image->extra ="onchange=\"javascript:if (this.options[selectedIndex].value!='') {document.getElementById('imagelib').src= 'usrfiles/'+this.options[selectedIndex].value,document.getElementById('input_imagesrc').value= 'usrfiles/'+this.options[selectedIndex].value} else {document.getElementById('imagelib').src='../images/blank.png'}\"";
			$drp_image->addOption('','- Select Image -');
			$drp_image->addFromDB($listFiles,'filename','path');
            $objForm =& $this->newObject('form', 'htmlelements');
            //setup form
            $objForm->name = 'addsection';
            $objForm->id = 'addsection';

            if (isset($parentid) && !empty($parentid)) {
                $objForm->setAction($this->uri(array('action' => $action, 'id' => $sectionId, 'parentid' => $parentid), 'cmsadmin'));
            } else {
                $objForm->setAction($this->uri(array('action' => $action, 'id' => $sectionId), 'cmsadmin'));
            }
            

             $objForm->setDisplayType(3);
            $table->cellpadding = '5';  
            $table->cellspacing = '2';  
            //the title
            $titleInput = new textinput('title',null,null,30);
            $menuTextInput = new textinput('menutext',null,null,30);
            $objForm->addRule('title', $this->objLanguage->languageText('mod_cmsadmin_pleaseaddtitle', 'cmsadmin'), 'required');
            $objForm->addRule('menutext', $this->objLanguage->languageText('mod_cmsadmin_pleaseaddmenutext', 'cmsadmin'), 'required');
            //$button->setToSubmit();
            $button->name = 'save';
            $button->id = 'save';
            $button->value = $this->objLanguage->languageText('word_save');
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
                $objCount->value = $section['nodelevel'];
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
                    $table->addCell($this->getTreeDropdown($parentid).'&nbsp;'.'-'.'&nbsp;'.$this->objLanguage->languageText('mod_cmsadmin_parentsectiondesc', 'cmsadmin'),'','','','',"colspan='2'");
                } else {
                    $table->addCell($this->getTreeDropdown().'&nbsp;'.'-'.'&nbsp;'.$this->objLanguage->languageText('mod_cmsadmin_parentsectiondesc', 'cmsadmin'),'','','','',"colspan='2'");
                }

                $table->endRow();
            } else {
                $table->startRow();
                $table->addCell($objParentId->show().$objRootId->show().$objCount->show().$objOrdering->show(),'','','','',"colspan='2'");
                $table->endRow();
            }
		

            $table->startRow();
            $table->addCell('&nbsp;');
            $table->addCell('&nbsp;','','','','',"colspan='2'");
            $table->endRow();

            //title name
            $table->startRow();
            $table->addCell($this->objLanguage->languageText('word_title').': ');
            $table->addCell($titleInput->show().'&nbsp;'.'-'.'&nbsp;'.$this->objLanguage->languageText('mod_cmsadmin_sectionnamedescription', 'cmsadmin'),'','','','',"colspan='2'");
            $table->endRow();

            $table->startRow();
            $table->addCell('&nbsp;');
            $table->addCell('&nbsp;','','','','',"colspan='2'");
            $table->endRow();

            //menu text name
            $table->startRow();
            $table->addCell($this->objLanguage->languageText('mod_cmsadmin_menuname', 'cmsadmin').': ');
            $table->addCell($menuTextInput->show().'&nbsp;'.'-'.'&nbsp;'.$this->objLanguage->languageText('mod_cmsadmin_menutextdescription', 'cmsadmin'),'','','','',"colspan='2'");
            $table->endRow();

            $table->startRow();
            $table->addCell('&nbsp;');
            $table->addCell('&nbsp;','','','','',"colspan='2'");
            $table->endRow();

            //published
            $table->startRow();
            $table->addCell($this->objLanguage->languageText('word_section').'&nbsp;'.$this->objLanguage->languageText('word_visible').': ');
            $table->addCell($this->getYesNoRadion('published', $isPublished),'','','','',"colspan='2'");
            $table->endRow();

            $table->startRow();
            $table->addCell($this->objLanguage->languageText('word_image').':&nbsp;');
            $table->addCell($drp_image->show(),'','','','',"colspan='2'");
            $table->endRow();

            $table->startRow();
            $table->addCell('&nbsp;');
            $table->addCell('&nbsp;','','','','',"colspan='2'");
            $table->endRow();
            
            //layout
            $table->startRow();
            $table->addCell($this->objLanguage->languageText('mod_cmsadmin_layoutofpages', 'cmsadmin').': ','','center');
            $table->addCell($layoutStr, '20%', 'center');
            $table->addCell($imgStr);
            $table->endRow();

            $table->startRow();
            $table->addCell('&nbsp;');
            $table->addCell('&nbsp;','','','','',"colspan='2'");
            $table->endRow();

            //Order type
            $label = new label ($this->objLanguage->languageText('mod_cmsadmin_orderpagesby', 'cmsadmin').': ', 'input_pageorder');
            $pageOrder = new dropdown('pageorder');
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

            $table->startRow();
            $table->addCell($label->show());
            $table->addCell($pageOrder->show(),'','','','',"colspan='2'");
            $table->endRow();

            $table->startRow();
            $table->addCell('&nbsp;');
            $table->addCell('&nbsp;','','','','',"colspan='2'");
            $table->endRow();

            //Show intro or not
            $label = new label ($this->objLanguage->languageText('mod_cmsadmin_showintro', 'cmsadmin').': ', 'input_showintro');
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
                $introText->value = nl2br($section['description']);
            }

            $table->startRow();
            $table->addCell('<div id="showintrolabel">'.$label->show().'</div>');
            $table->addCell('<div id="showintrocol">'.$this->objLanguage->languageText('mod_cmsadmin_showintrotext', 'cmsadmin').' '.$showdate->show().'<br /><br />'.$introText->show().'</div>','','','','',"colspan='2'");
            $table->endRow();

            $table->startRow();
            $table->addCell('&nbsp;');
            $table->addCell('&nbsp;','','','','',"colspan='2'");
            $table->endRow();

            //No. pages to display
            $lbOther = $this->objLanguage->languageText('phrase_othernumber');
            $label = new label ($this->objLanguage->languageText('phrase_numberofpages').': ', 'input_pagenum');
            $pagenum = new dropdown('pagenum');
            $pagenum->addOption('0', $this->objLanguage->languageText('phrase_showall'));
            $pagenum->addOption('3', '3');
            $pagenum->addOption('5', '5');
            $pagenum->addOption('10', '10');
            $pagenum->addOption('20', '20');
            $pagenum->addOption('30', '30');
            $pagenum->addOption('50', '50');
            $pagenum->addOption('100', '100');
            
            $pagenum->addOption('custom', $lbOther);
            
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
            $pagenum->extra = "onclick=\"javascript: if(this.value == 'custom'){document.getElementById('input_customnumber').disabled=false;}
            else{document.getElementById('input_customnumber').value='';
            document.getElementById('input_customnumber').disabled=true;}\"";
            
            //Input custom no.
            $customInput = new textinput('customnumber');
            if ($editmode && $section['numpagedisplay'] != '0') {
                $customInput->value = $section['numpagedisplay'];
            }else{
                $customInput->extra = "disabled='true'";
            }
            
            $numStr = $this->objLanguage->languageText('mod_cmsadmin_numpagesdisplaypersection', 'cmsadmin');
            $numStr .= '<p>'.$pagenum->show().'&nbsp;&nbsp;'.$lbOther.': '.$customInput->show().'</p>';
            $numStr .= '<p class="warning">* '.$this->objLanguage->languageText('mod_cmsadmin_numpagesonlyrequiredwhen', 'cmsadmin').'</p>';
            
            $table->startRow();
            $table->addCell('<div id="pagenumlabel">'.$label->show().'</div>');
            $table->addCell('<div id="pagenumcol">'.$numStr.'</div>','','','','',"colspan='2'");
            $table->endRow();

            $table->startRow();
            $table->addCell('&nbsp;');
            $table->addCell('&nbsp;','','','','',"colspan='2'");
            $table->endRow();

            //Show date or not
            $label = new label ($this->objLanguage->languageText('phrase_showdate').': ', 'input_showdate');
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
            $table->addCell('<div id="dateshowcol">'.$this->objLanguage->languageText('mod_cmsadmin_shoulddatebedisplayed', 'cmsadmin').' '.$showdate->show().'</div>','','','','',"colspan='2'");
            $table->endRow();
             //add context
            if ($this->inContextMode) {
            	$ContextInput->name = 'Contextcode';
            	$ContextInput->id = 'Contextcode';
            	$ContextInput->fldType = 'hidden';
            	$ContextInput->value = $this->contextCode;
            	$table->startRow();
            	$table->addCell('&nbsp;');
	            $table->addCell($ContextInput->show(),'','','','',"colspan='2'");
	            $table->endRow();
            }

            //button
            $table->startRow();
            $table->addCell('&nbsp;');
            $table->addCell($button->show(),'','','','',"colspan='2'");
            $table->endRow();
            $objForm->addToForm($table->show());
            //create heading
			$tbl->cellpadding = 3;
			$tbl->align = "left";
            if ($editmode) {
            	$objIcon->setIcon('section', 'png', 'icons/cms/');
            	$objIcon->title = $this->objLanguage->languageText('mod_cmsadmin_addnewsection', 'cmsadmin');
                $h3->str = $objIcon->show().'&nbsp;'.$this->objLanguage->languageText('mod_cmsadmin_editsection', 'cmsadmin');
            } else {
            	$objIcon->setIcon('section', 'png', 'icons/cms/');
            	$objIcon->title = $this->objLanguage->languageText('mod_cmsadmin_addnewsection', 'cmsadmin');
                $h3->str = $objIcon->show().'&nbsp;'.$this->objLanguage->languageText('mod_cmsadmin_addnewsection', 'cmsadmin');
            }
            //Heading box
            $topNav = $this->topNav('addsection');
            /*
			$tbl->startRow();
			//Get top navigation
			$tbl->addCell($h3->show());
			$tbl->addCell($topNav, '','','right');
			$tbl->endRow();
            //$objRound->show
            */

            $objLayer->str = $h3->show();
            $objLayer->border = '; float:left; align: left; margin:0px; padding:0px;';
            $header = $objLayer->show();
            
            $objLayer->str = $topNav;
            $objLayer->border = '; float:right; align:right; margin:0px; padding:0px;';
            $header .= $objLayer->show();
            
            $objLayer->str = '';
            $objLayer->border = '; clear:both; margin:0px; padding:0px;';
            $headShow = $objLayer->show();

            //Add content to the output layer
            $middleColumnContent = "";
            $middleColumnContent .= $objRound->show($header.$headShow);//$tbl->show());
            $middleColumnContent .= $objForm->show();

            return $middleColumnContent;
        }

        /**
         * Method to return the add edit content form
         *
         * @param string $contentId The id of the content to be edited. Default NULL for adding new section
         * @access public
         * @return string $middleColumnContent The form used to create and edit a page
         * @author Warren Windvogel
         */
        public function getAddEditContentForm($contentId = NULL, $section = NULL)
        {
            // Determine whether to show the toggle or not
            if ($section == NULL) {
                $toggleShowHideIntro = TRUE;
            } else {
                $sectionInfo = $this->_objSections->getSection($section);

                if ($sectionInfo == FALSE) {
                    $toggleShowHideIntro = TRUE;
                } else {
                    if ($sectionInfo['layout'] == 'summaries') {
                        $toggleShowHideIntro = FALSE;
                    } else {
                        $toggleShowHideIntro = TRUE;
                    }
                }
            }
    
            // Title Input
            $titleInput = new textinput ('title');
            $titleInput->extra = ' style="width: 50%"';

            // Content Area
            $bodyInput = $this->newObject('htmlarea', 'htmlelements');
            $bodyInput->name = 'body';
            $bodyInput->height = '400px';
            $bodyInput->width = '100%';

            // Introduction Area
            $introInput = $this->newObject('htmlarea', 'htmlelements');
            $introInput->name = 'intro';
            $introInput->setBasicToolBar();
            $introInput->height = '200px';
            $introInput->width = '100%';

            // Submit Button
            $button = new button('submitform', $this->objLanguage->languageText('word_save'));
            $button->setToSubmit();



            $published = new checkbox('published');
            $frontPage = new checkbox('frontpage');
			$frontPage->value = 1;





            if ($toggleShowHideIntro) {
                /*$this->appendArrayVar('headerParams', '
				<style type="text/css">
				div#introdiv {display: none; }
				span#introrequiredtext {display: none; }
				</style>
                ');*/

                $this->appendArrayVar('headerParams', '
				<script type="text/javascript">
				function toggleIntroRequired()
				{
					if (document.forms[\'addfrm\'].frontpage.checked == true)
					{
						document.getElementById(\'introdiv\').style.display =\'block\';
						document.getElementById(\'introrequiredtext\').style.display =\'inline\';
						adjustLayout();
					} else {
					    document.getElementById(\'introdiv\').style.display =\'none\';
					    document.getElementById(\'introrequiredtext\').style.display =\'none\';
					    adjustLayout();
					}
				
				}
				</script>');
				$this->appendArrayVar('bodyOnLoad', 'toggleIntroRequired();');
                $frontPage->extra = 'onchange="toggleIntroRequired();"';
            } else {
                $this->appendArrayVar('headerParams', '
				<style type="text/css">
				span#introrequiredtext {display: none; }
				</style>');
            }




            $objOrdering =$this->newObject('textinput', 'htmlelements');


            $objCCLicence = $this->newObject('licensechooser', 'creativecommons');

            $is_front = FALSE;
            if ($contentId == NULL) {
                $action = 'createcontent';
                $editmode = FALSE;
                $titleInput->value = '';
                $introInput->value = '';
                $published->setChecked(TRUE);
                $visible = TRUE;
                $contentId = '';
                $arrContent = null;

                if ( $this->getParam('frontpage') == 'true') {
                    $frontPage->setChecked(TRUE);
                    $is_front = TRUE;
                }
            } else {
                $action = 'editcontent';
                $editmode = TRUE;
                $arrContent = $this->_objContent->getContentPage($contentId);
                $titleInput->value = $arrContent['title'];

                $introInput->setContent(stripslashes($arrContent['introtext']));
                $bodyInput->setContent((stripslashes($arrContent['body'])));

                $is_front = $this->_objFrontPage->isFrontPage($arrContent['id']);
                $frontPage->setChecked($is_front);
                $published->setChecked($arrContent['published']);
                $visible = $arrContent['published'];
                if(isset($arrContent['post_lic'])){
                    $objCCLicence->defaultValue = $arrContent['post_lic'];
                }
            }

            //setup form
            $objForm = new form('addfrm', $this->uri(array('action' => $action, 'id' => $contentId, 'frontpage' => $is_front), 'cmsadmin'));
            $objForm->setDisplayType(3);

            if ($editmode) {
                //Set ordering as hidden field
                $sections = new hiddeninput('parent', $arrContent['sectionid']);
//                $objOrdering = new hiddeninput('ordering', $arrContent['ordering']);

            } else {
                if (isset($section) && !empty($section)) {
                    $sections = $this->getTreeDropdown($section, FALSE);
                } else {
                    $sections = $this->getTreeDropdown(NULL, FALSE);
                }
            }
           
            $table = new htmltable();

            $table->startRow();
            $table->addCell($this->objLanguage->languageText('word_title').': ', 150);
            $table->addCell($titleInput->show());
            $table->endRow();

            if (!$editmode) {
                $table->startRow();
                $table->addCell($this->objLanguage->languageText('word_section').': ');
                $table->addCell($sections);
                $table->endRow();
            } else {
                $table->startRow();
                $table->addCell($sections->show());
                $table->endRow();
            }

            $table->startRow();
            $table->addCell($this->objLanguage->languageText('word_visible').': &nbsp; ');
            $table->addCell($this->getYesNoRadion('published', $visible));
            //$table->addCell($published->show());
            $table->endRow();

            $table->startRow();
            $table->addCell($this->objLanguage->languageText('mod_cmsadmin_showonfrontpage', 'cmsadmin').': ');
            $table->addCell($frontPage->show().'<span id="introrequiredtext" class="warning">'.$this->objLanguage->languageText('mod_cmsadmin_pleaseenterintrotext', 'cmsadmin').'</span>');
            $table->endRow();

            $objForm->addToForm($table->show());
            $objForm->addRule('title', $this->objLanguage->languageText('mod_cmsadmin_pleaseaddtitle', 'cmsadmin'), 'required');


            $h3 = $this->newObject('htmlheading', 'htmlelements');
            $h3->str = $this->objLanguage->languageText('word_introduction').' ('.$this->objLanguage->languageText('word_required').')';
            $h3->type = 3;
			
            //intro input
            $objForm->addToForm('<div id="introdiv">');
            $objForm->addToForm('<br />'.$h3->show());
            $objForm->addToForm($introInput->show());
            $objForm->addToForm('</div>');

            //body
            $table2 = new htmltable();
            $table2->startRow();
            $table2->addCell($bodyInput->show(),'70%','top','left');
            if (!$editmode) {
            	$table2->addCell($this->getConfigTabs(),'30%','top','right');
            }else{
            	$table2->addCell($this->getConfigTabs($arrContent),'30%','top','right');
            }
            $table2->endRow();
            
            $h3->str = $this->objLanguage->languageText('mod_cmsadmin_maintext', 'cmsadmin');
            $h3->type = 3;
            $objForm->addToForm('<br />'.$h3->show());
            $objForm->addToForm($table2->show());

            $objModulesInfo =& $this->getObject('modules', 'modulecatalogue');
			//Pass action
			$txt_action = new textinput('action',$action,'hidden');
			$objForm->addToForm($txt_action);

            //cc licence input
            if ($objModulesInfo->checkIfRegistered('creativecommons')) {
                $h3->str = $this->objLanguage->languageText('word_licence');
                $h3->type = 3;
                $objForm->addToForm('<br />'.$h3->show());
                $objForm->addToForm($objCCLicence->show());
            } else {
                $creativecommons = new hiddeninput('creativecommons', '');
                $objForm->addToForm($creativecommons->show());
            }
            //create heading
            //$h3->str = $this->objLanguage->languageText('mod_cmsadmin_contentitem', 'cmsadmin').':'.'&nbsp;'.$this->objLanguage->languageText('word_new');

            $objForm->addToForm('<p><br />'.$button->show().'</p>');
			
			$display = $objForm->show(); 	
            return $display;
        }
        /**
     * Method to show  the body of a pages
     *
     * @access public
     * @return string The page content to be displayed
     */
    public function showBody($contentId, $menuNodeId, $edit = FALSE, $contentedit=TRUE)
    {
        if ($edit) {
            return $this->editPage($contentId, $menuNodeId);
        }
        $page = $this->_objContent->getContentPage($contentId);

        if (count($page) > 0) {
            //Create heading
            $objHeader = $this->newObject('htmlheading', 'htmlelements');
            $objHeader->type = '3';
            $objHeader->str = $page['title'];
            $strBody = $objHeader->show();
            $strBody .= stripslashes($page['body']).'<br />';
            $strBody .= '<span class="warning">'.$this->_objUser->fullname($page['created_by']).'</span><br />';
            $strBody .= '<span class="warning">'.$page['modified'].'</span>';

            if (($this->_objUser->isAdmin()) || ($this->_objUser->userId() == $page['created_by']))
			{
				if($contentedit)
				{
					$link = &new Link($this->uri(array('pageid'=>$menuNodeId, 'edit' => 'true'),'cmsadmin'));
					$link->link = 'Edit page';
					//$strBody .= $link->show();
				}
            }
        } else {
            $strBody = '';   //Will change this later to correct language element
        }
        return $strBody;
    }
        
     /**
     * Method to output a rss feeds box
     *
     * @param string $url
     * @param string $name
     * @return string
     */
    public function rssBox($url, $name)
    {
    	$objFeatureBox = $this->getObject('featurebox', 'navigation');
        $objRss = $this->getObject('rssreader', 'feed');
        $objRss->parseRss($url);
        $head = $this->objLanguage->languageText("mod_cms_word_headlinesfrom", "cmsadmin");
        $head .= " " . $name;
        $content = "<ul>\n";
        foreach ($objRss->getRssItems() as $item)
        {
        	if(!isset($item['link']))
        	{
        		$item['link'] = NULL;
        	}
    		@$content .= "<li><a href=\"" . htmlentities($item['link']) . "\">" . htmlentities($item['title']) . "</a></li>\n";
		}
		$content .=  "</ul>\n";
		return $objFeatureBox->show($head, $content);
    }

    public function rssRefresh($rssurl, $name, $feedid)
    {
    	$objFeatureBox = $this->getObject('featurebox', 'navigation');
    	$objRss = $this->getObject('rssreader', 'feed');
    	$this->objConfig = $this->getObject('altconfig', 'config');

    	//get the proxy info if set
    	$objProxy = $this->getObject('proxyparser', 'utilities');
    	$proxyArr = $objProxy->getProxy();

    	$ch = curl_init();
    	curl_setopt($ch, CURLOPT_URL, $rssurl);
    	//curl_setopt($ch, CURLOPT_HEADER, 1);
    	curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    	if(!empty($proxyArr) && $proxyArr['proxy_protocol'] != '')
    	{
    		curl_setopt($ch, CURLOPT_PROXY, $proxyArr['proxy_host'].":".$proxyArr['proxy_port']);
    		curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyArr['proxy_user'].":".$proxyArr['proxy_pass']);
    	}
    	$rsscache = curl_exec($ch);
    	curl_close($ch);
    	//var_dump($rsscache);
    	//put in a timestamp
    	$addtime = time();
    	$addarr = array('url' => $rssurl, 'rsstime' => $addtime);

    	//write the file down for caching
    	$path = $this->objConfig->getContentBasePath() . "/cms/rsscache/";
    	$rsstime = time();
    	if(!file_exists($path))
    	{

    		mkdir($path);
    		chmod($path, 0777);
    		$filename = $path . $this->objUser->userId() . "_" . $rsstime . ".xml";
    		if(!file_exists($filename))
    		{
    			touch($filename);

    		}
    		$handle = fopen($filename, 'wb');
    		fwrite($handle, $rsscache);
    	}
    	else {
    		$filename = $path . $this->objUser->userId() . "_" . $rsstime . ".xml";
    		$handle = fopen($filename, 'wb');
    		fwrite($handle, $rsscache);
    	}
    	//update the db
    	$addarr = array('url' => htmlentities($rssurl), 'rsscache' => $filename, 'rsstime' => $addtime);
    	//print_r($addarr);
    	$this->objDbBlog->updateRss($addarr, $feedid);

    	$objRss->parseRss($rsscache);
    	$head = $this->objLanguage->languageText("mod_cms_word_headlinesfrom", "cmsadmin");
    	$head .= " " . $name;
    	$content = "<ul>\n";
    	foreach ($objRss->getRssItems() as $item)
    	{
    		if(!isset($item['link']))
    		{
    			$item['link'] = NULL;
    		}
    		@$content .= "<li><a href=\"" . htmlentities($item['link']) . "\">" . htmlentities($item['title']) . "</a></li>\n";
    	}
    	$content .=  "</ul>\n";
    	return $objFeatureBox->show($head, $content);

    }

    public function rssEditor($featurebox = FALSE, $rdata = NULL)
    {
    	//print_r($rdata);
    	$this->loadClass('href', 'htmlelements');
        $this->loadClass('label', 'htmlelements');
        $this->loadClass('textinput', 'htmlelements');
        $this->loadClass('textarea', 'htmlelements');

    	$this->objUser = $this->getObject('user', 'security');
    	if($rdata == NULL)
    	{
        	$rssform = new form('addrss', $this->uri(array(
            	'action' => 'addrss'
        	)));
    	}
    	else {
    		$rdata = $rdata[0];
    		$rssform = new form('addrss', $this->uri(array(
            	'action' => 'rssedit', 'mode' => 'edit', 'id' => $rdata['id']
        	)));
    	}
        //add rules
        $rssform->addRule('rssurl', $this->objLanguage->languageText("mod_cms_phrase_rssurlreq", "cmsadmin") , 'required');
        $rssform->addRule('name', $this->objLanguage->languageText("mod_cms_phrase_rssnamereq", "cmsadmin") , 'required');
        //start a fieldset
        $rssfieldset = $this->getObject('fieldset', 'htmlelements');
        $rssadd = $this->newObject('htmltable', 'htmlelements');
        $rssadd->cellpadding = 3;

        //url textfield
        $rssadd->startRow();
        $rssurllabel = new label($this->objLanguage->languageText('mod_cms_rssurl', 'cmsadmin') .':', 'input_rssuser');
        $rssurl = new textinput('rssurl');
        if(isset($rdata['url']))
        {
        $rssurl->setValue($rdata['url']);
       // $rssurl->setValue('url');

		}
        $rssadd->addCell($rssurllabel->show());
        $rssadd->addCell($rssurl->show());
        $rssadd->endRow();

        //name
        $rssadd->startRow();
        $rssnamelabel = new label($this->objLanguage->languageText('mod_cms_rssname', 'cmsadmin') .':', 'input_rssname');
        $rssname = new textinput('name');
        if(isset($rdata['name']))
        {
        	$rssname->setValue($rdata['name']);
        }
        $rssadd->addCell($rssnamelabel->show());
        $rssadd->addCell($rssname->show());
        $rssadd->endRow();

        //description
        $rssadd->startRow();
        $rssdesclabel = new label($this->objLanguage->languageText('mod_cms_rssdesc', 'cmsadmin') .':', 'input_rssname');
        $rssdesc = new textarea('description');
        if(isset($rdata['description']))
        {
          	//var_dump($rdata['description']);
        	$rssdesc->setValue($rdata['description']);
        }
        $rssadd->addCell($rssdesclabel->show());
        $rssadd->addCell($rssdesc->show());
        $rssadd->endRow();

        //end off the form and add the buttons
        $this->objRssButton = &new button($this->objLanguage->languageText('word_save', 'system'));
        $this->objRssButton->setValue($this->objLanguage->languageText('word_save', 'system'));
        $this->objRssButton->setToSubmit();
        $rssfieldset->addContent($rssadd->show());
        $rssform->addToForm($rssfieldset->show());
        $rssform->addToForm($this->objRssButton->show());
        $rssform = $rssform->show();

        //ok now the table with the edit/delete for each rss feed
        $efeeds = $this->objRss->getUserRss($this->objUser->userId());
        $ftable = $this->newObject('htmltable', 'htmlelements');
        $ftable->cellpadding = 3;
        //$ftable->border = 1;
        //set up the header row
        $ftable->startHeaderRow();
        $ftable->addHeaderCell($this->objLanguage->languageText("mod_cms_fhead_name", "cmsadmin"));
        $ftable->addHeaderCell($this->objLanguage->languageText("mod_cms_fhead_description", "cmsadmin"));
        $ftable->addHeaderCell('');
        $ftable->endHeaderRow();

        //set up the rows and display
        if (!empty($efeeds)) {
            foreach($efeeds as $rows) {
                $ftable->startRow();
                $feedlink = new href($rows['url'], $rows['name']);
                $ftable->addCell($feedlink->show());
                //$ftable->addCell(htmlentities($rows['name']));
                $ftable->addCell(($rows['description']));
                $this->objIcon = &$this->getObject('geticon', 'htmlelements');
                $edIcon = $this->objIcon->getEditIcon($this->uri(array(
                    'action' => 'addrss',
                    'mode' => 'edit',
                    'id' => $rows['id'],
                    //'url' => $rows['url'],
                    //'description' => $rows['description'],
                    'module' => 'cmsadmin'
                )));
                $delIcon = $this->objIcon->getDeleteIconWithConfirm($rows['id'], array(
                    'module' => 'cmsadmin',
                    'action' => 'deleterss',
                    'id' => $rows['id']
                ) , 'cmsadmin');
                $ftable->addCell($edIcon.$delIcon);
                $ftable->endRow();
            }
            //$ftable = $ftable->show();
        }
      
            return $rssform . $ftable->show();

    }
    /**
     * Method to show  the edit page screen
     *
     * @access public
     * @return string The page content to be displayed
     */
    public function editPage($contentId, $menuNodeId)
    {
   		$this->loadClass('textinput','htmlelements');
   		$this->loadClass('hiddeninput','htmlelements');

        $page = $this->objContent->getContentPage($contentId);

        //initiate objects
        $table = & $this->newObject('htmltable', 'htmlelements');
        $objForm = new form('editfrm', $this->uri(array('action' => 'save', 'id' => $contentId, 'pageid'=>$menuNodeId)));
        $objForm->setDisplayType(3);


        // Title Input
        $titleInput = new textinput ('title');
        $titleInput->extra = ' style="width: 100%"';

        // Content Area
        $bodyInput = $this->newObject('htmlarea', 'htmlelements');
        $bodyInput->name = 'body';
        $bodyInput->height = '400px';
        $bodyInput->width = '100%';

        // Submit Button
        $button = new button('submitform', $this->objLanguage->languageText('word_save'));
        $button->setToSubmit();

        //Extra fields cms needs which we do not want to edit or even care about for the portal
        $publishedInput = new hiddeninput('published', $page['published']);
        $accessInput = new hiddeninput('access', $page['access']);
        $orderingInput = new hiddeninput('ordering', $page['ordering']);
        $parentInput = new hiddeninput('parent', $page['sectionid']);
        $creativeCommonsInput = new hiddeninput('creativecommons', $page['post_lic']);
        $introInput = new hiddeninput('intro', htmlentities($page['introtext']));

        $titleInput->value = $page['title'];

        $bodyInput->setContent((stripslashes($page['body'])));

        $table->startRow();
        $table->addCell($this->objLanguage->languageText('word_title'), 150);
        $table->addCell($titleInput->show(), NULL, NULL, NULL, NULL, ' colspan="3"');
        $table->endRow();
        $objForm->addToForm($table->show());
        $objForm->addRule('title', $this->objLanguage->languageText('mod_cmsadmin_pleaseaddtitle', 'cmsadmin'), 'required');


        //body
        $objForm->addToForm('<br /><h3>'.$this->objLanguage->languageText('mod_cmsadmin_maintext', 'cmsadmin').'</h3>');
        $objForm->addToForm($bodyInput->show());
        $objForm->addToForm('<p><br />'.$button->show().'</p>');


        //hidden elements
        $objForm->addToForm($publishedInput->show());
        $objForm->addToForm($accessInput->show());
        $objForm->addToForm($orderingInput->show());
        $objForm->addToForm($parentInput->show());
        $objForm->addToForm($creativeCommonsInput->show());
        $objForm->addToForm($introInput->show());

        $objH = $this->newObject('htmlheading', 'htmlelements');
        $objH->type = '3';
        $objH->str = 'Edit '. $page['title'];
        $strBody = $objH->show().'<p/>';

        $strBody .= $objForm->show();


        return $strBody;
    }

    /**
    * Method to check if the user is in the CMS Authors group
    *
    * @access public
    */
    public function checkPermission()
    {
        $objGroups = $this->getObject('groupadminmodel', 'groupadmin');
        $groupId = $objGroups->getLeafId(array('CMSAuthors'));
        if($objGroups->isGroupMember($this->_objUser->pkId(), $groupId)){
            return TRUE;
        }else{
            return FALSE;
        }   
    }


}

?>