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
		 * The security object
		 *
		 * @access public
		 * @var object
		 */
		public $_objSecurity;


		/**
		 * Class Constructor
		 *
		 * @access public
		 * @return void
		 */
		public function init()
		{
				try {
						$this->_objPageMenu =  $this->newObject('dbpagemenu', 'cmsadmin');
						$this->_objSecurity =  $this->newObject('dbsecurity', 'cmsadmin');
						$this->_objSections =$this->newObject('dbsections', 'cmsadmin');
						$this->_objDBContext =$this->getObject('dbcontext', 'context');
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
						$this->objModule=&$this->getObject('modules','modulecatalogue');

						$objModule =$this->newObject('modules', 'modulecatalogue');
						if ($objModule->checkIfRegistered('context')) {
								$this->inContextMode = $this->_objContext->getContextCode();
								$this->contextCode = $this->_objContext->getContextCode();
						} else {
								$this->inContextMode = FALSE;
						}

						$this->objDateTime = $this->getObject('dateandtime', 'utilities');

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
						$this->loadClass('layer', 'htmlelements');

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
				$objIcon = $this->newObject('geticon', 'htmlelements');
				//Not visible
				$objIcon->setIcon('not_visible');
				$objIcon->title = $this->objLanguage->languageText('phrase_notpublished');
				$notVisibleIcon = $objIcon->show();
				//Visible
				$objIcon->setIcon('visible');
				$objIcon->title = $this->objLanguage->languageText('word_published');
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
		 * Method to get the control panel for the context
		 * 
		 * @param 
		 * @return string
		 * 
		 */
		public function getContextControlPanel()
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

				//view content link
				$link = $this->objLanguage->languageText('mod_cmsadmin_viewcontent', 'cmsadmin');
				$arrSection = $this->_objSections->getSectionByContextCode();
				$url = $this->uri(array('action' => 'viewsection','id' => $arrSection['id']));
				$icnViewContent = $objIcon->getBlockIcon($url, 'add_article', $link, 'png', 'icons/cms/');

				//content link
				$link = $this->objLanguage->languageText('mod_cmsadmin_contentitem', 'cmsadmin');
				$url = $this->uri(array('action' => 'addcontent'));
				$icnContent = $objIcon->getBlockIcon($url, 'add_article', $link, 'png', 'icons/cms/');

				//Import link
				$link = $this->objLanguage->languageText('mod_cmsadmin_import', 'cmsadmin'); 
				$url = $this->uri(array('action' => 'import'));
				$icnImport = $objIcon->getBlockIcon($url, 'import', $link, 'png', 'icons/cms/');

				//Create export manager link
				$link = $this->objLanguage->languageText('mod_cmsadmin_export', 'cmsadmin');
				$url = $this->uri(array('action' => 'export'), 'cmsadmin');
				$icnExport = $objIcon->getBlockIcon($url, 'export', $link, 'png', 'icons/cms/');

				//Page Organisor
				$link = $this->objLanguage->languageText('mod_cmsadmin_organisor', 'cmsadmin');
				$url = $this->uri(array('action' => 'organisor'), 'cmsadmin');
				$icnPageOrganisor = $objIcon->getBlockIcon($url, 'organisor', $link, 'png', 'icons/cms/');

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
				$tbl->addCell($icnViewContent);
				$tbl->addCell($icnContent);
				//$tbl->addCell($icnImport);
				// $tbl->addCell($icnExport);
				$tbl->addCell($icnMenu);
				//$tbl->addCell($icnPageOrganisor);
				$tbl->endRow();

				$tbl->startRow();
				$tbl->addCell($icnArchive);
				$tbl->addCell($icnRss);

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

				//permissions link
				$link = $this->objLanguage->languageText('mod_cmsadmin_permissionsmanager', 'cmsadmin'); 
				//$link = "Permissions Manager"; 
				$url = $this->uri(array('action' => 'permissions'));
				$icnPermissions = $objIcon->getBlockIcon($url, 'permissions', $link, 'png', 'icons/cms/');

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
				$tbl->addCell($icnPermissions);
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
				$icon_publish = $this->newObject('geticon', 'htmlelements');

				$iconList = '';

				switch ($action) {

						case 'editmenu':

								// Apply
								//$url = $this->uri(array('action' => 'releaselock'), 'cms');
								$script = '<script type="text/javascript">
											function testing(){
												alert("testing");
											}

											function applyChanges(){
												document.getElementById(\'must_apply\').value = \'1\';
												document.getElementById(\'form_addmenu\').submit();
												return true;
											}
										  </script>';
								$this->appendArrayVar('headerParams', $script);

								//$url = "javascript:document.getElementById(must_apply).value = '1';document.getElementById('form_addmenu').submit();";
								$url = "javascript:applyChanges()";
								$linkText = $this->objLanguage->languageText('word_apply');
								$iconList = $icon_publish->getTextIcon($url, 'apply', $linkText, 'gif', 'icons/cms/');

								// Save
								$url = "javascript:document.getElementById('form_addmenu').submit();";
								$linkText = $this->objLanguage->languageText('word_save');
								$iconList .= $icon_publish->getTextIcon($url, 'save', $linkText, 'gif', 'icons/cms/');

								// Cancel	 		
								$url = "javascript:history.back();";
								$linkText = ucwords($this->objLanguage->languageText('word_back'));
								$iconList .= $icon_publish->getTextIcon($url, 'cancel', $linkText, 'gif', 'icons/cms/');

								// Preview
								$url1 = $this->uri('', 'cms');
								$url = '#';
								$extra = "onclick=\"window.open('{$url1}', 'preview_cms', ' width=700, height=500, resizable=yes, toolbar=yes, scrollbars=yes')\"";
								$linkText = $this->objLanguage->languageText('mod_cmsadmin_viewcms', 'cmsadmin');
								$iconList .= $icon_publish->getTextIcon($url, 'preview', $linkText, 'png', 'icons/cms/');

								return '<p style="align:right;">'.$iconList.'</p>';
								break;



						case 'createcontent':

								// Apply
								$script = '<script type="text/javascript">
                                            function applyChanges(){
                                                document.getElementById(\'must_apply\').value = \'1\';
                                                document.getElementById(\'form_addfrm\').submit();
                                            }
                                          </script>';

                                $this->appendArrayVar('headerParams', $script);

								$url = "javascript:applyChanges();";
								$linkText = $this->objLanguage->languageText('word_apply');
								$iconList = $icon_publish->getTextIcon($url, 'apply', $linkText, 'gif', 'icons/cms/');

								// Save
								$url = "javascript:if(validate_addfrm_form(document.getElementById('form_addfrm')) == true){ document.getElementById('form_addfrm').submit(); }";
								$linkText = $this->objLanguage->languageText('word_save');
								$iconList .= $icon_publish->getTextIcon($url, 'save', $linkText, 'gif', 'icons/cms/');

								// Cancel	 		
								$url = "javascript:history.back();";
								$linkText = ucwords($this->objLanguage->languageText('word_back'));
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
								$alertText = $this->objLanguage->languageText('mod_cmsadmin_selectpublishlist', 'cmsadmin');
								$url = "javascript:if(checkSelect('select','input_arrayList[]')==false){alert('{$alertText}');}else{submitbutton('select','publish');}";
								$linkText = $this->objLanguage->languageText('word_publish');
								$iconList = $icon_publish->getTextIcon($url, 'publish', $linkText, 'png', 'icons/cms/');
								// New - add
								$url = $this->uri(array('action' => 'addsection'), 'cmsadmin');
								$linkText = $this->objLanguage->languageText('word_new');
								$iconList .= $icon_publish->getTextIcon($url, 'new', $linkText, 'gif', 'icons/cms/');

								// Unpublish
								$alertText = $this->objLanguage->languageText('mod_cmsadmin_selectunpublishlist', 'cmsadmin');
								$url = "javascript:if(checkSelect('select','input_arrayList[]')==false){alert('{$alertText}');}else{submitbutton('select','unpublish');}";
								$linkText = $this->objLanguage->languageText('word_unpublish');
								$iconList .= $icon_publish->getTextIcon($url, 'unpublish', $linkText, 'png', 'icons/cms/');
								/*
								// Copy
								$url = $this->uri('', 'cmsadmin');
								$linkText = $this->objLanguage->languageText('word_copy');
								$iconList .= $icon_publish->getTextIcon($url, 'copy', $linkText, 'gif', 'icons/cms/');
								 */


								// Cancel	 		
								$url = "javascript:history.back();";
								$linkText = ucwords($this->objLanguage->languageText('word_back'));
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
								$alertText = $this->objLanguage->languageText('mod_cmsadmin_selectpublishlist', 'cmsadmin');
								$url = "javascript: if(checkSelect('select','input_arrayList[]')==false){alert('{$alertText}');}else{submitbutton('select','publish');}";
								$linkText = $this->objLanguage->languageText('word_publish');
								$iconList = $icon_publish->getTextIcon($url, 'publish', $linkText, 'png', 'icons/cms/');

								// Unpublish
								$alertText = $this->objLanguage->languageText('mod_cmsadmin_selectunpublishlist', 'cmsadmin');
								$url = "javascript:if(checkSelect('select','input_arrayList[]')==false){alert('{$alertText}');}else{submitbutton('select','unpublish');}";
								$linkText = $this->objLanguage->languageText('word_unpublish');
								$iconList .= $icon_publish->getTextIcon($url, 'unpublish', $linkText, 'png', 'icons/cms/');

								// Cancel	 		
								$url = "javascript:history.back();";
								$linkText = ucwords($this->objLanguage->languageText('word_back'));
								$iconList .= $icon_publish->getTextIcon($url, 'cancel', $linkText, 'gif', 'icons/cms/');

								// Preview
								$url1 = $this->uri('', 'cms');
								$url = '#';
								$extra = "onclick=\"window.open('{$url1}', 'preview_cms', ' width=700, height=500, resizable=yes, toolbar=yes, scrollbars=yes')\"";
								$linkText = $this->objLanguage->languageText('mod_cmsadmin_viewcms', 'cmsadmin');
								$iconList .= $icon_publish->getTextIcon($url, 'preview', $linkText, 'png', 'icons/cms/', $extra);

								return '<p style="align:right;">'.$iconList.'</p>';
								break;

						case 'permissions':

								//permissions link
								$link = $this->objLanguage->languageText('mod_cmsadmin_permissionsmanager', 'cmsadmin'); 
								//$link = "Permissions Manager"; 
								$url = $this->uri(array('action' => 'permissions'));
								$iconList .= $icon_publish->getTextIcon($url, 'permissions', $link, 'png', 'icons/cms/');

								// Cancel	 		
								$url = "javascript:history.back();";
								$linkText = ucwords($this->objLanguage->languageText('word_back'));
								$iconList .= $icon_publish->getTextIcon($url, 'cancel', $linkText, 'gif', 'icons/cms/');

								return '<p style="align:right;">'.$iconList.'</p>';
								break;

						case 'addpermissions':

								//permissions link
								$link = $this->objLanguage->languageText('mod_cmsadmin_permissionsmanager', 'cmsadmin'); 
								//$link = "Permissions Manager"; 
								$url = $this->uri(array('action' => 'permissions'));
								$iconList .= $icon_publish->getTextIcon($url, 'permissions', $link, 'png', 'icons/cms/');

								// Cancel	 		
								$url = "javascript:history.back();";
								$linkText = ucwords($this->objLanguage->languageText('word_back'));
								$iconList .= $icon_publish->getTextIcon($url, 'cancel', $linkText, 'gif', 'icons/cms/');

								return '<p style="align:right;">'.$iconList.'</p>';
								break;

						case 'view_permissions_section':

								//permissions link
								$link = $this->objLanguage->languageText('mod_cmsadmin_permissionsmanager', 'cmsadmin'); 
								//$link = "Permissions Manager"; 
								$url = $this->uri(array('action' => 'permissions'));
								$iconList .= $icon_publish->getTextIcon($url, 'permissions', $link, 'png', 'icons/cms/');

								// Cancel	 		
								$url = "javascript:history.back();";
								$linkText = ucwords($this->objLanguage->languageText('word_back'));
								$iconList .= $icon_publish->getTextIcon($url, 'cancel', $linkText, 'gif', 'icons/cms/');

								return '<p style="align:right;">'.$iconList.'</p>';
								break;

						case 'addpermissions_user_group':

								// Cancel	 		
								$url = "javascript:history.back();";
								$linkText = ucwords($this->objLanguage->languageText('word_back'));
								$iconList .= $icon_publish->getTextIcon($url, 'cancel', $linkText, 'gif', 'icons/cms/');

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
								$linkText = ucwords($this->objLanguage->languageText('word_back'));
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
								$linkText = ucwords($this->objLanguage->languageText('word_back'));
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

								// Restore
								$alertText = $this->objLanguage->languageText('mod_cmsadmin_selectrestorelist', 'cmsadmin');
								$url = "javascript: if(checkSelect('select','input_arrayList[]')==false){alert('{$alertText}');}else{submitbutton('select','restore');}";
								$linkText = $this->objLanguage->languageText('mod_cmsadmin_restore', 'cmsadmin');
								$iconList = $icon_publish->getTextIcon($url, 'restore', $linkText, 'png', 'icons/cms/');

								// Restore sections
								$alertText = $this->objLanguage->languageText('mod_cmsadmin_selectrestoresections', 'cmsadmin');
								$url = "javascript: if(checkSelect('selectsections','input_arrayList[]')==false){alert('{$alertText}');}else{submitbutton('selectsections','restore');}";
								$linkText = $this->objLanguage->languageText('mod_cmsadmin_restoresections', 'cmsadmin');
								$iconList .= $icon_publish->getTextIcon($url, 'restoresection', $linkText, 'png', 'icons/cms/');

								// Cancel	 		
								$url = "javascript:history.back();";
								$linkText = ucwords($this->objLanguage->languageText('word_back'));
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

								// New menu
								$url = $this->uri(array('action' => 'addnewmenu','pageid'=>'0','add'=>'TRUE'), 'cmsadmin');
								$linkText = $this->objLanguage->languageText('word_new');
								$iconList .= $icon_publish->getTextIcon($url, 'new', $linkText, 'gif', 'icons/cms/');
								 */

								// Cancel	 		
								$url = "javascript:history.back();";
								$linkText = ucwords($this->objLanguage->languageText('word_back'));
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
								$linkText = ucwords($this->objLanguage->languageText('word_back'));
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
				$tabs =$this->newObject('tabcontent','htmlelements');
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
						$users = $this->_objUserModel->getUsers('surname', 'listall');

						if(!empty($users)){
								foreach($users as $item){
										$creator->addOption($item['userid'], $item['surname'].', '.$item['firstname']);
								}
								$creator->setSelected($arrContent['created_by']);
						}
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
						$users = $this->_objUserModel->getUsers('surname', 'listall');

						if(!empty($users)){
								foreach($users as $item){
										$creator->addOption($item['userid'], $item['surname'].', '.$item['firstname']);
								}
								$creator->setSelected($this->_objUser->userId());
						}
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


				//Moved this section outside of the tabs due to slow load issue
				/*
				   if (isset($arrContent['body'])) {
				   $bodyInputValue = stripslashes($arrContent['body']);
				   }else{
				   $bodyInputValue = null;
				   }
				   $bodyInput = $this->newObject('htmlarea', 'htmlelements');
				   $bodyInput->init('body', $bodyInputValue);
				   $bodyInput->setContent($bodyInputValue);
				   $bodyInput->setDefaultToolBarSet();
				   $bodyInput->height = '400px';
				 */


				//$bodyInput->width = '50%';
				$tbl_meta->endRow();
				//Add items to tabs

				//$tabs->addTab($this->objLanguage->languageText('mod_cmsadmin_maintext', 'cmsadmin'),$bodyInput->show(),'',TRUE,'');

				$tabs->addTab($this->objLanguage->languageText('mod_cmsadmin_basic','cmsadmin'),$tbl_basic->show(),'',False,'');
				$tabs->addTab($this->objLanguage->languageText('mod_cmsadmin_advanced','cmsadmin'),$tbl_advanced->show(),'',False,'');
				$tabs->addTab($this->objLanguage->languageText('mod_cmsadmin_meta','cmsadmin'), $tbl_meta->show(),'',False,'');
				$tabs->width = '70%';
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
				$objIcon = $this->newObject('geticon', 'htmlelements');

				if ($isCheck) {
						$objIcon->setIcon('visible', 'gif');
						$objIcon->title = $this->objLanguage->languageText('word_published');
				} else {
						if ($returnFalse) {
								$objIcon->setIcon('not_visible', 'gif');
								$objIcon->title = $this->objLanguage->languageText('phrase_notpublished');
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
				//TODO: Must Fork for different menus here
				$objCmsTree =$this->newObject('cmstree', 'cmsadmin');
				//$objSimpleCmsTree =$this->newObject('simplecontenttree', 'cmsadmin');

				$objConfig =$this->newObject('altconfig', 'config');
				$objFeatureBox = $this->newObject('featurebox', 'navigation');
				//Instantiate link object
				$link =$this->newObject('link', 'htmlelements');
				$objIcon = $this->newObject('geticon', 'htmlelements');

				//Roundconer object
				$objRound =$this->newObject('roundcorners','htmlelements');
				//Create heading

				//Create cms admin link
				$link = $this->objLanguage->languageText('phrase_controlpanel');
				$url = $this->uri('', 'cmsadmin');
				$cmsAdminLink = $objIcon->getTextIcon($url, 'control_panel', $link, 'png', 'icons/cms/');

				// Create RSS link
				$link = $this->objLanguage->languageText('phrase_rssfeeds');
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

				$nav = '';

				//TODO: Must fork for different menu types
				$objCMSTree = $this->getObject('cmstree');
				//$objSimpleCMSTree = $this->getObject('simplecontenttree');

				//Add links to the output layer
				$currentNode = $this->getParam('sectionid');

				//TODO: Must fork for different menu types
				$nav = $objCMSTree->getCMSAdminTree($currentNode);
				//$nav = $objSimpleCMSTree->getSimpleCMSAdminTree($currentNode);
				$nav="<div id='cmsnavigation'>".$nav."</div>\n"; 
				$nav .= '<br/>'.'&nbsp;'.'<br />';
				//$nav .= $viewCmsLink.'<br /><br />';
				$nav .= $objFeatureBox->showContent('Navigation Links',
								$cmsAdminLink.'<br />
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
		 * Method to get a list of groups with write access to this section
		 *
		 * The sections tables groupid field will store a comma separated list of id's
		 *
		 * @param string $sectionid
		 * @return array of groups with write access to this section
		 * @access public
		 */

		public function getSectionGroupNames($sectionid){

				$objGroups = & $this->newObject('dbgroups', 'cmsadmin');
				$group = $objGroups->getNode($groupid);
				$names = array();
				foreach ($group as $grp){
						array_push($names, $grp['name']);
				}

				$hasValue = false;
				foreach ($names as $name){
						if ($name != '' && $name != null){
								$hasValue = true;
						}
				}

				if ($hasValue){
						return $names;
				} else {
						return array('');
				}
		} 

		/**
		 * Method to get a list of users with access to this section
		 *
		 * The sections tables groupid field will store a comma separated list of id's
		 *
		 * @param string $sectionid
		 * @return array of groups with write access to this section
		 * @access public
		 */

		public function getSectionUserNames($sectionid){

				$objGroups = & $this->newObject('dbgroups', 'cmsadmin');
				$group = $objGroups->getNode($groupid);
				$names = array();
				foreach ($group as $grp){
						array_push($names, $grp['name']);
				}

				$hasValue = false;
				foreach ($names as $name){
						if ($name != '' && $name != null){
								$hasValue = true;
						}
				}

				if ($hasValue){
						return $names;
				} else {
						return array('');
				}
		} 


		/**
		 * Method to get a list of groups with write access to this CONTENT
		 *
		 * The sections tables groupid field will store a comma separated list of id's
		 *
		 * @param string $contentid
		 * @return array of groups with write access to this section
		 * @access public
		 */

		public function getContentGroupNames($contentid){

				$objGroups = & $this->newObject('dbgroups', 'cmsadmin');
				$group = $objGroups->getNode($groupid);
				$names = array();
				foreach ($group as $grp){
						array_push($names, $grp['name']);
				}

				$hasValue = false;
				foreach ($names as $name){
						if ($name != '' && $name != null){
								$hasValue = true;
						}
				}

				if ($hasValue){
						return $names;
				} else {
						return array('');
				}
		} 

		/**
		 * Method to get a list of users with access to this CONTENT
		 *
		 * The sections tables groupid field will store a comma separated list of id's
		 *
		 * @param string $contentid
		 * @return array of groups with write access to this section
		 * @access public
		 */

		public function getContentUserNames($contentid){

				$objGroups = & $this->newObject('dbgroups', 'cmsadmin');
				$group = $objGroups->getNode($groupid);
				$names = array();
				foreach ($group as $grp){
						array_push($names, $grp['name']);
				}

				$hasValue = false;
				foreach ($names as $name){
						if ($name != '' && $name != null){
								$hasValue = true;
						}
				}

				if ($hasValue){
						return $names;
				} else {
						return array('');
				}
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
                $tree = $objCMSTree->getCMSAdminDropdownTree($setSelected, $noRoot);
                return $tree;

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
		public function getContentTreeDropdown($setSelected = NULL, $noRoot = TRUE)
		{
				$objCMSTree = $this->getObject('cmstree');
				$sections = $objCMSTree->getFlatTree($setSelected, $noRoot);

				//var_dump($sections);

				$dropdown = new dropdown('parent');

				foreach ($sections as $section){
						$dropdown->addOption($section['id'], $section['title']);
				}

				return $dropdown;
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
				$this->loadClass('textinput', 'htmlelements');
				$this->loadClass('htmltable', 'htmlelements');

				$table =$this->newObject('htmltable', 'htmlelements');
				$objRound =$this->newObject('roundcorners','htmlelements');
				$objIcon =  $this->newObject('geticon', 'htmlelements');
				$tbl = $this->newObject('htmltable', 'htmlelements');
				$h3 =$this->newObject('htmlheading', 'htmlelements');
				$button =$this->newObject('button', 'htmlelements');
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

				if ($editmode) {
						$objDrop->setSelected($section['layout']);
						$imgPath = $this->getResourceUri('section_'.$section['layout'].'.gif', 'cmsadmin');
						$this->appendArrayVar('bodyOnLoad', "sa_processSection('{$section['layout']}');");
				} else {
						$objDrop->setSelected('page');
						$imgPath = $this->getResourceUri('section_page.gif', 'cmsadmin');
						$this->appendArrayVar('bodyOnLoad', "sa_processSection('page');");
				}

				$objDrop->extra = "onchange=\"javascript:
						sa_processSection(this.value); 
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
				$drp_image->extra = 'onchange="javascript:if(this.value!=\'\'){$(\'imagelib\').src = \'usrfiles/\'+this.value;$(\'input_imagesrc\').value = \'usrfiles/\'+this.value}else{$(\'imagelib\').src = \'../images/blank.png\'}"';
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
				$button = new button('save', $this->objLanguage->languageText('word_save'));
				$button->id = 'save';
				$button->setToSubmit(); 
				if ($editmode) {
						$titleInput->value = $section['title'];
						$menuTextInput->value = $section['menutext'];
						$layout = $section['layout'];
						$isPublished = $section['published'];
						$hideTitle = isset($section['hidetitle']) ? $section['hidetitle'] : '0';
						//Set rootid as hidden field
						$objRootId = new textinput();
						$objRootId->name = 'rootid';
						$objRootId->id = 'rootid';
						$objRootId->fldType = 'hidden';
						$objRootId->value = $section['rootid'];
						//Set parentid as hidden field
						$objParentId = new textinput();
						$objParentId->name = 'parent';
						$objParentId->id = 'parent';
						$objParentId->fldType = 'hidden';
						$objParentId->value = $section['parentid'];
						//Set parentid as hidden field
						$objCount = new textinput();
						$objCount->name = 'count';
						$objCount->fldType = 'hidden';
						$objCount->value = $section['nodelevel'];
						//Set parentid as hidden field
						$objOrdering = new textinput();
						$objOrdering->name = 'ordering';
						$objOrdering->fldType = 'hidden';
						$objOrdering->value = $section['ordering'];
				} else {
						$titleInput->value = '';
						$menuTextInput->value = '';
						$bodyInput->value = '';
						$layout = 0;
						$isPublished = '1';
						$hideTitle = '0';
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
				$table->addCell($this->objLanguage->languageText('word_section').'&nbsp;'.$this->objLanguage->languageText('word_publish').': ');
				$table->addCell($this->getYesNoRadion('published', $isPublished),'','','','',"colspan='2'");
				$table->endRow();

				// hide title
				$objRadio = new radio('hidetitle');
				$objRadio->addOption('1', '&nbsp;&nbsp;'.$this->objLanguage->languageText('word_yes'));
				$objRadio->addOption('0', '&nbsp;&nbsp;'.$this->objLanguage->languageText('word_no'));
				$objRadio->setSelected($hideTitle);
				$objRadio->setBreakSpace('&nbsp;&nbsp;&nbsp;&nbsp;');

				$table->startRow();
				$table->addCell($this->objLanguage->languageText('phrase_hidetitle').': ');
				$table->addCell($objRadio->show(),'','','','',"colspan='2'");
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
						$showdate->setSelected($section['showintroduction']);
				} else {
						$showdate->setSelected('1');
				}
				$showdate->setBreakSpace(' &nbsp; ');

				//Intro text
				$introText = $this->newObject('htmlarea', 'htmlelements');
				$introText->height = '200px';
				$introText->width = '50%';

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
		 * Method to return the add edit section PERMISSIONS form
		 *
		 * @param string $sectionId The id of the section to be edited. Default NULL for adding new section
		 * @return string $middleColumnContent The form used to create and edit a section
		 * @access public
		 * @author Charl Mert <charl.mert@gmail.com>
		 */
		public function getAddEditPermissionsSectionForm($sectionid = NULL, $returnSubView=0)
		{

				if ($returnSubView == 0){
						$returnSubView = $this->getParam('subview');
				}

				$subSecId = $this->getParam('parent');

				$sectionName = $this->_objSections->getSection($sectionid);
				$sectionName = $sectionName['title'];

				$this->loadClass('form', 'htmlelements');
				$this->loadClass('checkbox', 'htmlelements');
				$this->loadClass('htmltable', 'htmlelements');
				$this->loadClass('button', 'htmlelements');

				if ($returnSubView == 1){
						//Setting for to return to the SubView Page
						$objForm = new form('add_permissions_frm', $this->uri(array('action' => 'view_permissions_section', 'id' => $sectionid, 'parent' => $subSecId), 'cmsadmin'));
				} else {
						//Setting form to return to the Sections List Page (Default)
						$objForm = new form('add_permissions_frm', $this->uri(array('action' => 'addpermissions', 'id' => $sectionid), 'cmsadmin'));

				}
				$objForm->setDisplayType(3);

				//Start Header
				//initiate objects for header
				$table =  $this->newObject('htmltable', 'htmlelements');
				$objH = $this->newObject('htmlheading', 'htmlelements');
				$link =  $this->newObject('link', 'htmlelements');
				$objIcon =  $this->newObject('geticon', 'htmlelements');
				$this->loadClass('form', 'htmlelements');
				$objRound =$this->newObject('roundcorners','htmlelements');
				$objLayer =$this->newObject('layer','htmlelements');
				$this->loadClass('dropdown', 'htmlelements');
				$this->loadClass('textinput', 'htmlelements');
				$this->loadClass('checkbox', 'htmlelements');
				$this->loadClass('button', 'htmlelements');
				$this->loadClass('htmltable', 'htmlelements');

				$topNav = $this->topNav('addpermissions');

				$tbl = $this->newObject('htmltable', 'htmlelements');
				$tbl->cellpadding = 3;
				$tbl->align = "left";

				//create a heading
				$objH->type = '1';

				//Heading box
				$objIcon->setIcon('section', 'png', 'icons/cms/');
				//$objIcon->title = $this->objLanguage->languageText('mod_cmsadmin_permissionsmanager', 'cmsadmin');
				$objIcon->title = 'Edit Section Permissions';
				//$objH->str =  $objIcon->show().'&nbsp;'.$this->objLanguage->languageText('mod_cmsadmin_permissionsmanager', 'cmsadmin')."<br/><h3>Sections List</h3>";
				$objH->str =  $objIcon->show().'&nbsp;'.$objIcon->title."<br/><h3>Sections Title: $sectionName</h3>";
				$tbl->startRow();
				$tbl->addCell($objH->show(), '', 'center');
				$tbl->addCell($topNav, '','center','right');
				$tbl->endRow();

				$objLayer->str = $objH->show();
				$objLayer->border = '; float:left; align: left; margin:0px; padding:0px;';
				$header = $objLayer->show();
				$objLayer->str = $topNav;
				$objLayer->border = '; float:right; align:right; margin:0px; padding:0px;';
				$header .= $objLayer->show();

				$objLayer->str = '';
				$objLayer->border = '; clear:both; margin:0px; padding:0px;';
				$headShow = $objLayer->show();
				// end header

				//Setting up the table
				$table = new htmlTable();
				$table->width = "100%";
				$table->cellspacing = "0";
				$table->cellpadding = "0";
				$table->border = "0";
				$table->attributes = "align ='center'";

				$table->startHeaderRow();
				$table->addHeaderCell('Users / Groups');
				$table->addHeaderCell('Read');
				$table->addHeaderCell('Write');
				$table->endHeaderRow();

				//Looping through Users and Groups Assigned to Current Section
				//for (){...


				//TODO: Put the data query below into a freakin function charl ;-)
				//Getting origonal members from DB
				//Preparing a list of USER ID's
				$usersList = $this->_objSecurity->getAssignedSectionUsers($sectionid);
				$usersCount = count($usersList);

				//Preparing a list of GROUP_ID's
				$groupsList = $this->_objSecurity->getAssignedSectionGroups($sectionid);
				$groupsCount = count($groupsList);
				$globalChkCounter = 0;

				//Displaying Groups
				for ($x = 0; $x < $groupsCount; $x++){
						$memberName = $groupsList[$x]['name'];
						$memberReadAccess = $groupsList[$x]['read_access'];			
						$memberWriteAccess = $groupsList[$x]['write_access'];			

						$oddOrEven = ($rowcount == 0) ? "even" : "odd";

						$canRead = (($memberReadAccess == 1) ? true : false);
						$canWrite = (($memberWriteAccess == 1) ? true : false);


						$chkRead = new checkbox('chk_read-'.$globalChkCounter, 'Read', $canRead);
						$chkWrite = new checkbox('chk_write-'.$globalChkCounter, 'Write', $canWrite);

						$globalChkCounter += 1;

						//Adding Users / Groups
						$table->startRow();
						$table->addCell($memberName);
						$table->addCell($chkRead->show());
						$table->addCell($chkWrite->show());
						$table->endRow();

				}	//End Loop

				//Displaying Users
				for ($x = 0; $x < $usersCount; $x++){
						$memberName = $usersList[$x]['username'];
						$memberReadAccess = $usersList[$x]['read_access'];
						$memberWriteAccess = $usersList[$x]['write_access'];

						$oddOrEven = ($rowcount == 0) ? "even" : "odd";

						$canRead = (($memberReadAccess == 1) ? true : false);
						$canWrite = (($memberWriteAccess == 1) ? true : false);

						$chkRead = new checkbox('chk_read-'.$globalChkCounter, 'Read', $canRead);
						$chkWrite = new checkbox('chk_write-'.$globalChkCounter, 'Write', $canWrite);

						$globalChkCounter += 1;


						//Adding Users / Groups
						$table->startRow();
						$table->addCell($memberName);
						$table->addCell($chkRead->show());
						$table->addCell($chkWrite->show());
						$table->endRow();

				}       //End Loop

				//Add User / Group Link
				$lnkAddUserGroup = new link();
				$lnkAddUserGroup->link = "Add User/Group";

				if ($returnSubView == 1){
						//Setting for to return to the SubView Page
						$lnkAddUserGroup->href = $this->uri(array('action' => 'addpermissions_user_group', 'id' => $sectionid, 'parent' => $subSecId, 'subview' => '1'));
				} else {
						//Setting form to return to the Sections List Page (Default)
						$lnkAddUserGroup->href = $this->uri(array('action' => 'addpermissions_user_group', 'id' => $sectionid, 'parent' => $subSecId));
				}


				$btnSubmit = new button('save_btn', 'Save');
				$btnSubmit->setToSubmit(); 


				//Setting up the Owner table
				$tblOwner = new htmlTable();
				$tblOwner->width = "100%";
				$tblOwner->cellspacing = "0";
				$tblOwner->cellpadding = "0";
				$tblOwner->border = "0";
				$tblOwner->attributes = "align ='center'";

				//Setting up the Owner table

				$drpOwner = new dropdown('drp_owner');
				$allUsers = $this->_objSecurity->getAllUsers();
				$allUsersCount = count($allUsers);
				//var_dump($allUsers);	
				for ($i = 0; $i < $allUsersCount; $i++){
						//$drpOwner->addOption($allUsers[$i]['userid'], $allUsers[$i]['firstname'].' '.$allUsers[$i]['surname']);
						//echo 'Owner ID : '.$allUsers[$i]['userid'].'<br/>';
						$drpOwner->addOption($allUsers[$i]['userid'], $allUsers[$i]['username']);
				}

				//Getting the current owner of the section
				$section = $this->_objSections->getSection($sectionid);
				$ownerName = $this->_objUser->userName($section['userid']);

				$drpOwner->setSelected($section['userid']);	

				//must set the default owner
				//$drpOwner->setSelected();

				$chkPropagate = new checkbox('chk_propagate', 'Propagate', false);
				$chkPropagate->setLabel("Allow permissions to propagate to child items");

				$chkPropagateOwner = new checkbox('chk_propagate_owner', 'Propagate Owner', false);
				$chkPropagateOwner->setLabel("Make this the owner of all child items in this section");

				//Owner Select Box
				$tblOwner->startRow();
				$tblOwner->addCell($drpOwner->show());
				$tblOwner->endRow();

				//Check box to force inheritence on child sections and content

				//$tblOwner->startRow();
				//$tblOwner->addCell($chkInherit->show()." Force Ownership on Child Sections &amp; Content Items");
				//$tblOwner->endRow();

				$topNav = $this->topNav('addpermissions');

				//$objForm->addToForm("<h1>Edit Section Permissions</h1><h3>Section Title : $sectionName</h3><br/>");
				$objForm->addToForm($header.$headShow);
				$objForm->addToForm("<br/><h4>Authorised Members:</h4><br/>");
				$objForm->addToForm($table->show());
				$objForm->addToForm('<br/>');
				$objForm->addToForm($lnkAddUserGroup->show());
				$objForm->addToForm('<br/>');
				$objForm->addToForm($chkPropagate->show()." Allow permissions to propagate to child items.");
				$objForm->addToForm('<br/><input type="hidden" name="id" value="'.$this->getParam('parent').'"/>');
				$objForm->addToForm('<br/><input type="hidden" name="cid" value="'.$sectionid.'"/>');
				$objForm->addToForm( "<input type='hidden' name='subview' value='$returnSubView'>" );
				$objForm->addToForm('<br/><input type="hidden" name="chkCount" value="'.$globalChkCounter.'"/>');
				if ($returnSubView == 1){
						//Setting for to return to the SubView Page
						$objForm->addToForm('<br/><input type="hidden" name="action" value="view_permissions_section"/>');
				} else {
						//Setting form to return to the Sections List Page (Default)
						$objForm->addToForm('<br/><input type="hidden" name="action" value="addpermissions"/>');
				}
				$objForm->addToForm( "<input type='hidden' name='button' value='saved'>" ); 
				$objForm->addToForm("<h4>Owner:</h4>");
				$objForm->addToForm($tblOwner->show());
				$objForm->addToForm('<br/>');
				$objForm->addToForm($chkPropagateOwner->show()." Make this the owner of all child items in this section.");
				$objForm->addToForm('<br/>');
				$objForm->addToForm($btnSubmit->show());

				$display = $objForm->show();
				return $display;
		}


		/**
		 * Method to return the add edit section permissions USER GROUP form
		 *
		 * @param string $sectionId The id of the section to be edited. Default NULL for adding new section
		 * @return string $middleColumnContent The form used to create and edit a section
		 * @access public
		 * @author Charl Mert <charl.mert@gmail.com>
		 */
		public function getAddEditPermissionsSectionUserGroupForm($sectionid = NULL)
		{
				$parentId = $this->getParam('parent');
				$returnSubView = $this->getParam('subview');

				$objLanguage = $this->objLanguage;

				$memberList = $this->sectionMemberList($sectionid);
				//$memberList = array();

				$usersList = $this->sectionUsersList($sectionid);

				if ( $sectionid == NULL) {
						$errMsg = 'unknown section';
						$this->setVar( 'errorMsg', $errMsg );
				} 

				// Members list dropdown
				$this->loadClass('dropdown', 'htmlelements');
				$lstMembers = new dropdown('list2[]');
				//$lstMembers->name = 'list2[]';
				$lstMembers->extra = ' style="width:100pt" MULTIPLE SIZE=10 onDblClick="moveSelectedOptions(this.form[\'list2[]\'],this.form[\'list1[]\'],true)"';
				foreach ( $memberList as $user ) {
						if (($user['firstname'] != '') && ($user['surname'] != '')){
								$fullName = $user['firstname'] . " " . $user['surname'];
								$userPKId = $user['id'].'|user';
						} else {
								$fullName = $user['username'];
								$userPKId = $user['id'].'|group';
						}
						$lstMembers->addOption( $userPKId, $fullName );
				} 
				// Users list dropdown
				$lstUsers = new dropdown('list1[]');
				$lstUsers->extra = ' style="width:100pt" MULTIPLE SIZE=10 onDblClick="moveSelectedOptions(this.form[\'list1[]\'],this.form[\'list2[]\'],true)"';


				foreach ( $usersList as $user ) {

						if (($user['firstname'] != '') && ($user['surname'] != '')){
								$fullName = $user['firstname'] . " " . $user['surname'];
								$userPKId = $user['id'].'|user';
						} else {
								$fullName = $user['username'];
								$userPKId = $user['id'].'|group';
						}
						$lstUsers->addOption( $userPKId, $fullName );
				} 

				// Build the nonMember table.
				//$hdrUsers = $objLanguage->languageText('mod_cmsadmin_word_user','cmsadmin');
				$hdrUsers = "User / Group";

				$tblUsers = '<table><tr><th>' . $hdrUsers . '</th></tr><tr><td>' . $lstUsers->show() . '</td></tr></table>'; 

				// Build the Member table.
				//$hdrMemberList = $objLanguage->languageText( 'mod_groupadmin_hdrMemberList','groupadmin' );
				$hdrMemberList = "Members";

				$tblMembers = '<table><tr><th>' . $hdrMemberList . '</th></tr><tr><td>' . $lstMembers->show() . '</td></tr></table>'; 

				// The save button
				$btnSave = $this->newObject( 'button', 'htmlelements' );
				$btnSave->name = 'btnSave';
				$btnSave->cssClass = null;
				$btnSave->value = $objLanguage->languageText( 'word_save' );
				$btnSave->onclick = "selectAllOptions(this.form['list2[]'])";
				$btnSave->setToSubmit(); 

				// The save link button
				$btnSave = $this->newObject( 'button', 'htmlelements' );
				$btnSave->name = 'btnSave';
				$btnSave->cssClass = null;
				$btnSave->value = $objLanguage->languageText( 'word_save' );
				$btnSave->onclick = "selectAllOptions(this.form['list2[]'])";
				$btnSave->setToSubmit(); 

				// Link method
				$lnkSave = "<a href=\"#\" onclick=\"javascript:selectAllOptions(document.frmEdit['list2[]']); document.frmEdit['button'].value='save'; document.frmEdit.submit()\">";
				$lnkSave .= $objLanguage->languageText( 'word_save' ) . "</a>"; 

				// The cancel button
				$btnCancel = $this->newObject( 'button', 'htmlelements' );
				$btnCancel->name = 'btnCancel';
				$btnCancel->value = $objLanguage->languageText( 'word_Cancel' );
				$btnCancel->setToSubmit(); 

				// Link method
				$lnkCancel = "<a href=\"#\" onclick=\"javascript:document.frmEdit['button'].value='cancel'; document.frmEdit.submit()\">";
				$lnkCancel .= $objLanguage->languageText( 'word_cancel' ) . "</a>"; 

				// Form control buttons
				$buttons = array( $lnkSave, $lnkCancel ); 

				// The move selected items right button
				$btnRight = $this->newObject( 'button', 'htmlelements' );
				$btnRight->name = 'right';
				$btnRight->value = htmlspecialchars( '>>' );
				$btnRight->onclick = "moveSelectedOptions(this.form['list1[]'],this.form['list2[]'],true)"; 

				// Link method
				$lnkRight = "<a href=\"#\" onclick=\"javascript:moveSelectedOptions(document.frmEdit['list1[]'],document.frmEdit['list2[]'],true)\">";
				$lnkRight .= htmlspecialchars( '>>' ) . "</a>"; 

				// The move all items right button
				$btnRightAll = $this->newObject( 'button', 'htmlelements' );
				$btnRightAll->name = 'right';
				$btnRightAll->value = htmlspecialchars( 'All >>' );
				$btnRightAll->onclick = "moveAllOptions(this.form['list1[]'],this.form['list2[]'],true)"; 

				// Link method
				$lnkRightAll = "<a href=\"#\" onclick=\"javascript:moveAllOptions(document.frmEdit['list1[]'],document.frmEdit['list2[]'],true)\">";
				$lnkRightAll .= htmlspecialchars( 'All >>' ) . "</a>"; 

				// The move selected items left button
				$btnLeft = $this->newObject( 'button', 'htmlelements' );
				$btnLeft->name = 'left';
				$btnLeft->value = htmlspecialchars( '<<' );
				$btnLeft->onclick = "moveSelectedOptions(this.form['list2[]'],this.form['list1[]'],true)"; 

				// Link method
				$lnkLeft = "<a href=\"#\" onclick=\"javascript:moveSelectedOptions(document.frmEdit['list2[]'],document.frmEdit['list1[]'],true)\">";
				$lnkLeft .= htmlspecialchars( '<<' ) . "</a>"; 

				// The move all items left button
				$btnLeftAll = $this->newObject( 'button', 'htmlelements' );
				$btnLeftAll->name = 'left';
				$btnLeftAll->value = htmlspecialchars( 'All <<' );
				$btnLeftAll->onclick = "moveAllOptions(this.form['list2[]'],this.form['list1[]'],true)"; 

				// Link method
				$lnkLeftAll = "<a href=\"#\" onclick=\"javascript:moveAllOptions(document.frmEdit['list2[]'],document.frmEdit['list1[]'],true)\">";
				$lnkLeftAll .= htmlspecialchars( 'All <<' ) . "</a>"; 

				// The move items (Insert and Remove) buttons
				$btns = array( $lnkRight, $lnkRightAll, $lnkLeft, $lnkLeftAll );
				$tblInsertRemove = '<div>' . implode( '<br /><br />', $btns ) . '</div>'; 

				// Form Layout Elements
				$tblLayout = $this->newObject( 'htmltable', 'htmlelements' );
				$tblLayout->row_attributes = 'align=center';
				$tblLayout->width = '99%';
				$tblLayout->startRow();
				$tblLayout->addCell( $tblUsers, null, null );
				$tblLayout->addCell( $tblInsertRemove, null, null );
				$tblLayout->addCell( $tblMembers, null, null );
				$tblLayout->endRow();

				// Title and Header
				$ttlEditGroup = $objLanguage->languageText( 'mod_groupadmin_ttlEditGroup','groupadmin' );
				$hlpEditGroup = $objLanguage->languageText( 'mod_groupadmin_hlpEditGroup','groupadmin' );
				$hdrEditGroup = $objLanguage->languageText( 'mod_groupadmin_hdrEditGroup','groupadmin' ); 

				// Context Home Icon
				$lblContextHome = $this->objLanguage->languageText( "word_course" ) . ' ' . $this->objLanguage->languageText( "word_home" );
				$icnContextHome = $this->newObject( 'geticon', 'htmlelements' );
				$icnContextHome->setIcon( 'home' );
				$icnContextHome->alt = $lblContextHome;

				$lnkContextHome = $this->newObject( 'link', 'htmlelements' );
				$lnkContextHome->href = $this->uri( array(), 'context' );
				$lnkContextHome->link = $icnContextHome->show() . $lblContextHome;

				$return = $this->getParam( 'return' ) == 'context' ? 'context' : 'main';
				$confirm = $this->getParam( 'confirm' ) ? TRUE : FALSE; 

				// Form Elements
				$frmEdit = $this->newObject( 'form', 'htmlelements' );
				$frmEdit->name = 'frmEdit';
				$frmEdit->displayType = '3';
				$frmEdit->action = $this->uri ( array( 'action' => 'add_section_members' ) );
				$frmEdit->addToForm( "<div id='blog-content'>" . $tblLayout->show() . "</div>" );
				$frmEdit->addToForm( "<div id='blog-footer'>" . implode( '&#160;', $buttons ) . "</div>" );
				$frmEdit->addToForm( "<input type='hidden' name='id' value='$sectionid'>" );
				$frmEdit->addToForm( "<input type='hidden' name='parent' value='$parentId'>" );
				$frmEdit->addToForm( "<input type='hidden' name='subview' value='$returnSubView'>" );
				$frmEdit->addToForm( "<input type='hidden' name='return' value='$return'>" );
				$frmEdit->addToForm( "<input type='hidden' name='confirm' value='TRUE'>" );
				$frmEdit->addToForm( "<input type='hidden' name='button' value='saved'>" ); 

				// Back link button
				$lnkBack = $this->newObject( 'link', 'htmlelements' );
				$lnkBack->href = $this->uri ( array() );
				$lnkBack->link = $objLanguage->languageText( 'mod_groupadmin_back' ,'groupadmin'); 

				// $lnkBack->cssClass = 'pseudobutton';
				$this->setVar( 'frmEdit', $frmEdit );
				$this->setVar( 'return', $return );
				$this->setVar( 'lnkContextHome', $lnkContextHome );
				$this->setVar( 'lnkBack', $lnkBack );
				$this->setVar( 'ttlEditGroup', $ttlEditGroup );
				$this->setVar( 'fullPath', $fullPath );
				$this->setVar( 'confirm', $confirm );


				$sectionName = $this->_objSections->getSection($sectionid);
				$sectionName = $sectionName['title'];

				//Start Header
				//initiate objects for header
				$table =  $this->newObject('htmltable', 'htmlelements');
				$objH = $this->newObject('htmlheading', 'htmlelements');
				$link =  $this->newObject('link', 'htmlelements');
				$objIcon =  $this->newObject('geticon', 'htmlelements');
				$this->loadClass('form', 'htmlelements');
				$objRound =$this->newObject('roundcorners','htmlelements');
				$objLayer =$this->newObject('layer','htmlelements');
				$this->loadClass('dropdown', 'htmlelements');
				$this->loadClass('textinput', 'htmlelements');
				$this->loadClass('checkbox', 'htmlelements');
				$this->loadClass('button', 'htmlelements');
				$this->loadClass('htmltable', 'htmlelements');

				$topNav = $this->topNav('addpermissions');

				$tbl = $this->newObject('htmltable', 'htmlelements');
				$tbl->cellpadding = 3;
				$tbl->align = "left";

				//create a heading
				$objH->type = '1';

				//Heading box
				$objIcon->setIcon('section', 'png', 'icons/cms/');
				//$objIcon->title = $this->objLanguage->languageText('mod_cmsadmin_permissionsmanager', 'cmsadmin');
				$objIcon->title = 'Edit Section Permissions';
				//$objH->str =  $objIcon->show().'&nbsp;'.$this->objLanguage->languageText('mod_cmsadmin_permissionsmanager', 'cmsadmin')."<br/><h3>Sections List</h3>";
				$objH->str =  $objIcon->show().'&nbsp;'.$objIcon->title."<br/><h3>Section Title: $sectionName</h3><br/><h2>Add Authorized Members</h2>";
				$tbl->startRow();
				$tbl->addCell($objH->show(), '', 'center');
				$tbl->addCell($topNav, '','center','right');
				$tbl->endRow();

				$objLayer->str = $objH->show();
				$objLayer->border = '; float:left; align: left; margin:0px; padding:0px;';
				$header = $objLayer->show();
				$objLayer->str = $topNav;
				$objLayer->border = '; float:right; align:right; margin:0px; padding:0px;';
				$header .= $objLayer->show();

				$objLayer->str = '';
				$objLayer->border = '; clear:both; margin:0px; padding:0px;';
				$headShow = $objLayer->show();
				// end header

				$this->setVar('header', $header);
				$this->setVar('headShow', $headShow);

				return 'cms_permissions_add_user_group_tpl.php';
		}








		/**
		 * Method to return the add edit content PERMISSIONS form for CONTENT
		 *
		 * @param string $contentId The id of the content to be edited. Default NULL for adding new content
		 * @return string $middleColumnContent The form used to create and edit a content
		 * @access public
		 * @author Charl Mert <charl.mert@gmail.com>
		 */
		public function getAddEditPermissionsContentForm($contentid = NULL)
		{

				$sectionid = $this->getParam('parent');

				$contentName = $this->_objContent->getContentPage($contentid);
				$contentName = $contentName['title'];

				$this->loadClass('form', 'htmlelements');
				$this->loadClass('checkbox', 'htmlelements');
				$this->loadClass('htmltable', 'htmlelements');
				$this->loadClass('button', 'htmlelements');

				$objForm = new form('add_content_permissions_frm', 

								$this->uri(array('action' => 'view_permissions_section', 'cid' => $contentid, 'id' => $this->getParam('parent')), 'cmsadmin'));

				$objForm->setDisplayType(3);

				//Start Header
				//initiate objects for header
				$table =  $this->newObject('htmltable', 'htmlelements');
				$objH = $this->newObject('htmlheading', 'htmlelements');
				$link =  $this->newObject('link', 'htmlelements');
				$objIcon =  $this->newObject('geticon', 'htmlelements');
				$this->loadClass('form', 'htmlelements');
				$objRound =$this->newObject('roundcorners','htmlelements');
				$objLayer =$this->newObject('layer','htmlelements');
				$this->loadClass('dropdown', 'htmlelements');
				$this->loadClass('textinput', 'htmlelements');
				$this->loadClass('checkbox', 'htmlelements');
				$this->loadClass('button', 'htmlelements');
				$this->loadClass('htmltable', 'htmlelements');

				$topNav = $this->topNav('addpermissions');

				$tbl = $this->newObject('htmltable', 'htmlelements');
				$tbl->cellpadding = 3;
				$tbl->align = "left";

				//create a heading
				$objH->type = '1';

				//Heading box
				$objIcon->setIcon('section', 'png', 'icons/cms/');
				//$objIcon->title = $this->objLanguage->languageText('mod_cmsadmin_permissionsmanager', 'cmsadmin');
				$objIcon->title = 'Edit Content Permissions';
				//$objH->str =  $objIcon->show().'&nbsp;'.$this->objLanguage->languageText('mod_cmsadmin_permissionsmanager', 'cmsadmin')."<br/><h3>Sections List</h3>";
				$objH->str =  $objIcon->show().'&nbsp;'.$objIcon->title."<br/><h3>Content Title: $contentName</h3>";
				$tbl->startRow();
				$tbl->addCell($objH->show(), '', 'center');
				$tbl->addCell($topNav, '','center','right');
				$tbl->endRow();

				$objLayer->str = $objH->show();
				$objLayer->border = '; float:left; align: left; margin:0px; padding:0px;';
				$header = $objLayer->show();
				$objLayer->str = $topNav;
				$objLayer->border = '; float:right; align:right; margin:0px; padding:0px;';
				$header .= $objLayer->show();

				$objLayer->str = '';
				$objLayer->border = '; clear:both; margin:0px; padding:0px;';
				$headShow = $objLayer->show();
				// end header


				//Setting up the table
				$table = new htmlTable();
				$table->width = "100%";
				$table->cellspacing = "0";
				$table->cellpadding = "0";
				$table->border = "0";
				$table->attributes = "align ='center'";

				$table->startHeaderRow();
				$table->addHeaderCell('Users / Groups');
				$table->addHeaderCell('Read');
				$table->addHeaderCell('Write');
				$table->endHeaderRow();

				//Looping through Users and Groups Assigned to Current content
				//for (){...


				//TODO: Put the data query below into a freakin function charl ;-)
				//Getting origonal members from DB
				//Preparing a list of USER ID's
				$usersList = $this->_objSecurity->getAssignedContentUsers($contentid);
				$usersCount = count($usersList);

				//Preparing a list of GROUP_ID's
				$groupsList = $this->_objSecurity->getAssignedContentGroups($contentid);
				$groupsCount = count($groupsList);
				$globalChkCounter = 0;

				//Displaying Groups
				for ($x = 0; $x < $groupsCount; $x++){
						$memberName = $groupsList[$x]['name'];
						$memberReadAccess = $groupsList[$x]['read_access'];			
						$memberWriteAccess = $groupsList[$x]['write_access'];			

						$oddOrEven = ($rowcount == 0) ? "even" : "odd";

						$canRead = (($memberReadAccess == 1) ? true : false);
						$canWrite = (($memberWriteAccess == 1) ? true : false);


						$chkRead = new checkbox('chk_read-'.$globalChkCounter, 'Read', $canRead);
						$chkWrite = new checkbox('chk_write-'.$globalChkCounter, 'Write', $canWrite);

						$globalChkCounter += 1;

						//Adding Users / Groups
						$table->startRow();
						$table->addCell($memberName);
						$table->addCell($chkRead->show());
						$table->addCell($chkWrite->show());
						$table->endRow();

				}	//End Loop

				//Displaying Users
				for ($x = 0; $x < $usersCount; $x++){
						$memberName = $usersList[$x]['username'];
						$memberReadAccess = $usersList[$x]['read_access'];
						$memberWriteAccess = $usersList[$x]['write_access'];

						$oddOrEven = ($rowcount == 0) ? "even" : "odd";

						$canRead = (($memberReadAccess == 1) ? true : false);
						$canWrite = (($memberWriteAccess == 1) ? true : false);

						$chkRead = new checkbox('chk_read-'.$globalChkCounter, 'Read', $canRead);
						$chkWrite = new checkbox('chk_write-'.$globalChkCounter, 'Write', $canWrite);

						$globalChkCounter += 1;


						//Adding Users / Groups
						$table->startRow();
						$table->addCell($memberName);
						$table->addCell($chkRead->show());
						$table->addCell($chkWrite->show());
						$table->endRow();

				}       //End Loop

				//Add User / Group Link
				$lnkAddUserGroup = new link();
				$lnkAddUserGroup->link = "Add User/Group";
				$lnkAddUserGroup->href = $this->uri(array('action' => 'addpermissions_content_user_group', 'id' => $contentid, 'parent' => $sectionid));

				$btnSubmit = new button('save_btn', 'Save');
				$btnSubmit->setToSubmit(); 


				//Setting up the Owner table
				$tblOwner = new htmlTable();
				$tblOwner->width = "100%";
				$tblOwner->cellspacing = "0";
				$tblOwner->cellpadding = "0";
				$tblOwner->border = "0";
				$tblOwner->attributes = "align ='center'";

				//Setting up the Owner table

				$drpOwner = new dropdown('drp_owner');
				$allUsers = $this->_objSecurity->getAllUsers();
				$allUsersCount = count($allUsers);
				//var_dump($allUsers);	
				for ($i = 0; $i < $allUsersCount; $i++){
						//$drpOwner->addOption($allUsers[$i]['userid'], $allUsers[$i]['firstname'].' '.$allUsers[$i]['surname']);
						//echo 'Owner ID : '.$allUsers[$i]['userid'].'<br/>';
						$drpOwner->addOption($allUsers[$i]['userid'], $allUsers[$i]['username']);
				}

				//Getting the current owner of the content
				$content = $this->_objContent->getContentPage($contentid);
				$ownerName = $this->_objUser->userName($content['created_by']);

				$drpOwner->setSelected($content['created_by']);	

				//must set the default owner
				//$drpOwner->setSelected();

				$chkInherit = new checkbox('chk_read', 'Read', false);
				//$chkInherit->setLabel("Force owner on child objects");

				//Owner Select Box
				$tblOwner->startRow();
				$tblOwner->addCell($drpOwner->show());
				$tblOwner->endRow();

				//Check box to force inheritence on child contents and content

				//$tblOwner->startRow();
				//$tblOwner->addCell($chkInherit->show()." Force Ownership on Child contents &amp; Content Items");
				//$tblOwner->endRow();

				$topNav = $this->topNav('addpermissions');

				//$objForm->addToForm("<h1>Edit Content Permissions</h1><h3>Content Title : $contentName</h3><br/>");
				$objForm->addToForm($header.$headShow);
				$objForm->addToForm("<br/><h4>Authorised Members:</h4><br/>");
				$objForm->addToForm($table->show());
				$objForm->addToForm('<br/>');
				$objForm->addToForm($lnkAddUserGroup->show());
				$objForm->addToForm('<br/>');
				$objForm->addToForm('<br/><input type="hidden" name="chkCount" value="'.$globalChkCounter.'"/>');
				$objForm->addToForm('<br/><input type="hidden" name="id" value="'.$this->getParam('parent').'"/>');
				$objForm->addToForm('<br/><input type="hidden" name="cid" value="'.$contentid.'"/>');
				$objForm->addToForm('<br/><input type="hidden" name="action" value="view_permissions_section"/>');
				$objForm->addToForm("<h4>Owner:</h4>");
				$objForm->addToForm($tblOwner->show());
				$objForm->addToForm('<br/>');
				$objForm->addToForm($btnSubmit->show());

				$display = $objForm->show();
				return $display;
		}


		/**
		 * Method to return the add edit CONTENT permissions USER GROUP form
		 *
		 * @param string $contentId The id of the content to be edited. Default NULL for adding new content
		 * @return string $middleColumnContent The form used to create and edit a content
		 * @access public
		 * @author Charl Mert <charl.mert@gmail.com>
		 */
		public function getAddEditPermissionsContentUserGroupForm($contentid = NULL)
		{
				//echo "$contentid"; exit;

				$sectionid = $this->getParam('parent');	

				$objLanguage = $this->objLanguage;

				$memberList = $this->contentMemberList($contentid);
				//$memberList = array();

				$usersList = $this->contentUsersList($contentid);

				if ( $contentid == NULL) {
						$errMsg = 'unknown content';
						$this->setVar( 'errorMsg', $errMsg );
				} 

				// Members list dropdown
				$this->loadClass('dropdown', 'htmlelements');
				$lstMembers = new dropdown('list2[]');
				//$lstMembers->name = 'list2[]';
				$lstMembers->extra = ' style="width:100pt" MULTIPLE SIZE=10 onDblClick="moveSelectedOptions(this.form[\'list2[]\'],this.form[\'list1[]\'],true)"';
				foreach ( $memberList as $user ) {
						if (($user['firstname'] != '') && ($user['surname'] != '')){
								$fullName = $user['firstname'] . " " . $user['surname'];
								$userPKId = $user['id'].'|user';
						} else {
								$fullName = $user['username'];
								$userPKId = $user['id'].'|group';
						}
						$lstMembers->addOption( $userPKId, $fullName );
				} 
				// Users list dropdown
				$lstUsers = new dropdown('list1[]');
				$lstUsers->extra = ' style="width:100pt" MULTIPLE SIZE=10 onDblClick="moveSelectedOptions(this.form[\'list1[]\'],this.form[\'list2[]\'],true)"';


				foreach ( $usersList as $user ) {

						if (($user['firstname'] != '') && ($user['surname'] != '')){
								$fullName = $user['firstname'] . " " . $user['surname'];
								$userPKId = $user['id'].'|user';
						} else {
								$fullName = $user['username'];
								$userPKId = $user['id'].'|group';
						}
						$lstUsers->addOption( $userPKId, $fullName );
				} 

				// Build the nonMember table.
				//$hdrUsers = $objLanguage->languageText('mod_cmsadmin_word_user','cmsadmin');
				$hdrUsers = "User / Group";

				$tblUsers = '<table><tr><th>' . $hdrUsers . '</th></tr><tr><td>' . $lstUsers->show() . '</td></tr></table>'; 

				// Build the Member table.
				//$hdrMemberList = $objLanguage->languageText( 'mod_groupadmin_hdrMemberList','groupadmin' );
				$hdrMemberList = "Members";

				$tblMembers = '<table><tr><th>' . $hdrMemberList . '</th></tr><tr><td>' . $lstMembers->show() . '</td></tr></table>'; 

				// The save button
				$btnSave = $this->newObject( 'button', 'htmlelements' );
				$btnSave->name = 'btnSave';
				$btnSave->cssClass = null;
				$btnSave->value = $objLanguage->languageText( 'word_save' );
				$btnSave->onclick = "selectAllOptions(this.form['list2[]'])";
				$btnSave->setToSubmit(); 

				// The save link button
				$btnSave = $this->newObject( 'button', 'htmlelements' );
				$btnSave->name = 'btnSave';
				$btnSave->cssClass = null;
				$btnSave->value = $objLanguage->languageText( 'word_save' );
				$btnSave->onclick = "selectAllOptions(this.form['list2[]'])";
				$btnSave->setToSubmit(); 

				// Link method
				$lnkSave = "<a href=\"#\" onclick=\"javascript:selectAllOptions(document.frmEdit['list2[]']); document.frmEdit['button'].value='save'; document.frmEdit.submit()\">";
				$lnkSave .= $objLanguage->languageText( 'word_save' ) . "</a>"; 

				// The cancel button
				$btnCancel = $this->newObject( 'button', 'htmlelements' );
				$btnCancel->name = 'btnCancel';
				$btnCancel->value = $objLanguage->languageText( 'word_Cancel' );
				$btnCancel->setToSubmit(); 

				// Link method
				$lnkCancel = "<a href=\"#\" onclick=\"javascript:document.frmEdit['button'].value='cancel'; document.frmEdit.submit()\">";
				$lnkCancel .= $objLanguage->languageText( 'word_cancel' ) . "</a>"; 

				// Form control buttons
				$buttons = array( $lnkSave, $lnkCancel ); 

				// The move selected items right button
				$btnRight = $this->newObject( 'button', 'htmlelements' );
				$btnRight->name = 'right';
				$btnRight->value = htmlspecialchars( '>>' );
				$btnRight->onclick = "moveSelectedOptions(this.form['list1[]'],this.form['list2[]'],true)"; 

				// Link method
				$lnkRight = "<a href=\"#\" onclick=\"javascript:moveSelectedOptions(document.frmEdit['list1[]'],document.frmEdit['list2[]'],true)\">";
				$lnkRight .= htmlspecialchars( '>>' ) . "</a>"; 

				// The move all items right button
				$btnRightAll = $this->newObject( 'button', 'htmlelements' );
				$btnRightAll->name = 'right';
				$btnRightAll->value = htmlspecialchars( 'All >>' );
				$btnRightAll->onclick = "moveAllOptions(this.form['list1[]'],this.form['list2[]'],true)"; 

				// Link method
				$lnkRightAll = "<a href=\"#\" onclick=\"javascript:moveAllOptions(document.frmEdit['list1[]'],document.frmEdit['list2[]'],true)\">";
				$lnkRightAll .= htmlspecialchars( 'All >>' ) . "</a>"; 

				// The move selected items left button
				$btnLeft = $this->newObject( 'button', 'htmlelements' );
				$btnLeft->name = 'left';
				$btnLeft->value = htmlspecialchars( '<<' );
				$btnLeft->onclick = "moveSelectedOptions(this.form['list2[]'],this.form['list1[]'],true)"; 

				// Link method
				$lnkLeft = "<a href=\"#\" onclick=\"javascript:moveSelectedOptions(document.frmEdit['list2[]'],document.frmEdit['list1[]'],true)\">";
				$lnkLeft .= htmlspecialchars( '<<' ) . "</a>"; 

				// The move all items left button
				$btnLeftAll = $this->newObject( 'button', 'htmlelements' );
				$btnLeftAll->name = 'left';
				$btnLeftAll->value = htmlspecialchars( 'All <<' );
				$btnLeftAll->onclick = "moveAllOptions(this.form['list2[]'],this.form['list1[]'],true)"; 

				// Link method
				$lnkLeftAll = "<a href=\"#\" onclick=\"javascript:moveAllOptions(document.frmEdit['list2[]'],document.frmEdit['list1[]'],true)\">";
				$lnkLeftAll .= htmlspecialchars( 'All <<' ) . "</a>"; 

				// The move items (Insert and Remove) buttons
				$btns = array( $lnkRight, $lnkRightAll, $lnkLeft, $lnkLeftAll );
				$tblInsertRemove = '<div>' . implode( '<br /><br />', $btns ) . '</div>'; 

				// Form Layout Elements
				$tblLayout = $this->newObject( 'htmltable', 'htmlelements' );
				$tblLayout->row_attributes = 'align=center';
				$tblLayout->width = '99%';
				$tblLayout->startRow();
				$tblLayout->addCell( $tblUsers, null, null );
				$tblLayout->addCell( $tblInsertRemove, null, null );
				$tblLayout->addCell( $tblMembers, null, null );
				$tblLayout->endRow();

				// Title and Header
				$ttlEditGroup = $objLanguage->languageText( 'mod_groupadmin_ttlEditGroup','groupadmin' );
				$hlpEditGroup = $objLanguage->languageText( 'mod_groupadmin_hlpEditGroup','groupadmin' );
				$hdrEditGroup = $objLanguage->languageText( 'mod_groupadmin_hdrEditGroup','groupadmin' ); 

				// Context Home Icon
				$lblContextHome = $this->objLanguage->languageText( "word_course" ) . ' ' . $this->objLanguage->languageText( "word_home" );
				$icnContextHome = $this->newObject( 'geticon', 'htmlelements' );
				$icnContextHome->setIcon( 'home' );
				$icnContextHome->alt = $lblContextHome;

				$lnkContextHome = $this->newObject( 'link', 'htmlelements' );
				$lnkContextHome->href = $this->uri( array(), 'context' );
				$lnkContextHome->link = $icnContextHome->show() . $lblContextHome;

				$return = $this->getParam( 'return' ) == 'context' ? 'context' : 'main';
				$confirm = $this->getParam( 'confirm' ) ? TRUE : FALSE; 

				// Form Elements
				$frmEdit = $this->newObject( 'form', 'htmlelements' );
				$frmEdit->name = 'frmEdit';
				$frmEdit->displayType = '3';
				$frmEdit->action = $this->uri ( array( 'action' => 'add_content_permissions', 'parent' => $sectionid ) );
				$frmEdit->addToForm( "<div id='blog-content'>" . $tblLayout->show() . "</div>" );
				$frmEdit->addToForm( "<div id='blog-footer'>" . implode( '&#160;', $buttons ) . "</div>" );
				$frmEdit->addToForm( "<input type='hidden' name='id' value='$contentid'>" );
				$frmEdit->addToForm( "<input type='hidden' name='parent' value='".$this->getParam('parent')."'>" );
				$frmEdit->addToForm( "<input type='hidden' name='return' value='$return'>" );
				$frmEdit->addToForm( "<input type='hidden' name='confirm' value='TRUE'>" );
				$frmEdit->addToForm( "<input type='hidden' name='button' value='saved'>" ); 

				// Back link button
				$lnkBack = $this->newObject( 'link', 'htmlelements' );
				$lnkBack->href = $this->uri ( array() );
				$lnkBack->link = $objLanguage->languageText( 'mod_groupadmin_back' ,'groupadmin'); 

				// $lnkBack->cssClass = 'pseudobutton';
				$this->setVar( 'frmEdit', $frmEdit );
				$this->setVar( 'return', $return );
				$this->setVar( 'lnkContextHome', $lnkContextHome );
				$this->setVar( 'lnkBack', $lnkBack );
				$this->setVar( 'ttlEditGroup', $ttlEditGroup );
				$this->setVar( 'fullPath', $fullPath );
				$this->setVar( 'confirm', $confirm );

				//Start Header
				//initiate objects for header
				$table =  $this->newObject('htmltable', 'htmlelements');
				$objH = $this->newObject('htmlheading', 'htmlelements');
				$link =  $this->newObject('link', 'htmlelements');
				$objIcon =  $this->newObject('geticon', 'htmlelements');
				$this->loadClass('form', 'htmlelements');
				$objRound =$this->newObject('roundcorners','htmlelements');
				$objLayer =$this->newObject('layer','htmlelements');
				$this->loadClass('dropdown', 'htmlelements');
				$this->loadClass('textinput', 'htmlelements');
				$this->loadClass('checkbox', 'htmlelements');
				$this->loadClass('button', 'htmlelements');
				$this->loadClass('htmltable', 'htmlelements');

				$topNav = $this->topNav('addpermissions');

				$tbl = $this->newObject('htmltable', 'htmlelements');
				$tbl->cellpadding = 3;
				$tbl->align = "left";


				$contentName = $this->_objContent->getContentPage($contentid);
				$contentName = $contentName['title'];

				//create a heading
				$objH->type = '1';

				//Heading box
				$objIcon->setIcon('section', 'png', 'icons/cms/');
				//$objIcon->title = $this->objLanguage->languageText('mod_cmsadmin_permissionsmanager', 'cmsadmin');
				$objIcon->title = 'Edit Content Permissions';
				//$objH->str =  $objIcon->show().'&nbsp;'.$this->objLanguage->languageText('mod_cmsadmin_permissionsmanager', 'cmsadmin')."<br/><h3>Sections List</h3>";
				$objH->str =  $objIcon->show().'&nbsp;'.$objIcon->title."<br/><h3>Content Title: $contentName</h3><br/><h2>Add Authorized Members</h2>";
				$tbl->startRow();
				$tbl->addCell($objH->show(), '', 'center');
				$tbl->addCell($topNav, '','center','right');
				$tbl->endRow();

				$objLayer->str = $objH->show();
				$objLayer->border = '; float:left; align: left; margin:0px; padding:0px;';
				$header = $objLayer->show();
				$objLayer->str = $topNav;
				$objLayer->border = '; float:right; align:right; margin:0px; padding:0px;';
				$header .= $objLayer->show();

				$objLayer->str = '';
				$objLayer->border = '; clear:both; margin:0px; padding:0px;';
				$headShow = $objLayer->show();
				// end header

				$this->setVar('header', $header);
				$this->setVar('headShow', $headShow);

				return 'cms_permissions_add_user_group_tpl.php';
		}





		/**
		 * Method to get all members of the current SECTION.
		 * 
		 * @access private
		 * @return array   containing the members.
		 */
		function sectionMemberList($sectionid = null)
		{

				$authorizedMembers = $this->_objSecurity->getAuthorizedSectionMembers($sectionid);

				return $authorizedMembers;

				return array( 
								'user1' => array(
										'firstname' => 'Charl', 
										'surname' => 'Mert', 
										'id' => 'init_1'
										),
								'user2' => array(
										'firstname' => 'Ayia', 
										'surname' => 'Barry', 
										'id' => 'init_2'
										)
							);
		} 

		/**
		 * Method to get all users and groups that are NOT authorized for the current section
		 * 
		 * @access private
		 * @return array   of all non members.
		 */
		function sectionUsersList($sectionid = null)
		{

				$unAuthorizedMembers = $this->_objSecurity->getUnAuthorizedSectionMembers($sectionid);
				return $unAuthorizedMembers;

				return array( 
								'user1' => array(
										'firstname' => 'Truman', 
										'surname' => 'Show', 
										'id' => 'init_3'
										),
								'user2' => array(
										'firstname' => 'Rick', 
										'surname' => 'Ross', 
										'id' => 'init_4'
										)
							);
		}


		/**
		 * Method to get all members of the current SECTION.
		 * 
		 * @access private
		 * @return array   containing the members.
		 */
		function contentMemberList($contentid = null)
		{

				$authorizedMembers = $this->_objSecurity->getAuthorizedContentMembers($contentid);

				return $authorizedMembers;

		} 

		/**
		 * Method to get all users and groups that are NOT authorized for the current content
		 * 
		 * @access private
		 * @return array   of all non members.
		 */
		function contentUsersList($contentid = null)
		{

				$unAuthorizedMembers = $this->_objSecurity->getUnAuthorizedContentMembers($contentid);
				return $unAuthorizedMembers;

		}


		/**
		 * Method to return the EDIT LINK
		 *
		 * @param string $menuId The id of the MENU ITEM to be edited. Default Null to EDIT THE MAIN MENU
		 * @access public
		 * @return string html icon
		 * @author Charl Mert
		 */
		public function getEditLink($params = array(), $tooltip = '')
		{
			$icon = $this->getObject('geticon','htmlelements');
            $icon->setIcon('edit');
            $icon->alt = $tooltip;
            $link = $this->getObject('link','htmlelements');
            $link->link($this->uri($params), 'cmsadmin');
            $link->link = $icon->show();
            $ret = " ".$link->show();
			return $ret;
		}




		/**
		 * Method to return the DELETE LINK
		 *
		 * @param string $menuId The id of the MENU ITEM to be edited. Default Null to EDIT THE MAIN MENU
		 * @access public
		 * @return string html icon
		 * @author Charl Mert
		 */
		public function getDeleteLink($menuId, $params = array(), $tooltip = '')
		{

                $this->objIcon = &$this->getObject('geticon', 'htmlelements');
                $delIcon = $this->objIcon->getDeleteIconWithConfirm($menuId, $params, 'cmsadmin');
	
			return $delIcon;
		}


		/**
		 * Method to return the EDIT MENU form
		 *
		 * @param string $menuId The id of the MENU ITEM to be edited. Default Null to EDIT THE MAIN MENU
		 * @access public
		 * @return string html form used to create and edit a page
		 * @author Charl Mert
		 */
		public function getEditMenuForm($headerMessage, $menuId = NULL, $menuType = NULL)
		{

				$isSub = $this->getParam('sub');

 				//Getting Default Menu
				if ($isSub != '1'){
					$objForm = new form('addmenu', $this->uri(array('action' => 'addmenu', 'id' => $menuId), 'cmsadmin'));
				}else {
					$objForm = new form('addmenu', $this->uri(array('action' => 'editmenu', 'id' => $menuId, 'menutype' => $menuType), 'cmsadmin'));
				}
		
				$h3 = $this->newObject('htmlheading', 'htmlelements');

				//Edit Existing Menu Item
				if ($menuId != ''){
					$arrContent = $this->_objPageMenu->getMenuRow($menuId);
					if (isset($arrContent[0]['menukey'])){
						$titleInputValue = $arrContent[0]['menukey'];
					}
				}

 				//Getting Default Menu
				if ($isSub != '1'){
						$arrContent = $this->_objPageMenu->getMenuRowByKey('default');
				}
	
				$table = new htmlTable();
				$table->width = "100%";
				$table->cellspacing = "0";
				$table->cellpadding = "0";
				$table->border = "0";
				$table->attributes = "align ='center'";


				//Only showing the Sub Menu Link if the default menu exists
				if ($this->_objPageMenu->hasDefaultMenu()){

						if ($isSub != '1'){
				$table->startRow();
				$table->addCell('<br/>');
				$table->endRow();

								$h3->str = 'Edit Sub Menu\'s';
								$h3->type = 3;

								$table->startRow();
								$table->addCell($h3->show(), null, 'top', null, null, 'colspan="2"');
								$table->endRow();



								$table_list = new htmlTable();
								$table_list->width = "300px";
								$table_list->cellspacing = "0";
								$table_list->cellpadding = "0";
								$table_list->border = "0";
								$table_list->attributes = "align ='center'";

								//Displaying The List Of Menus
								$menuRows = $this->_objPageMenu->getAll();
								if (count($menuRows) > 0){
										foreach ($menuRows as $menu){
												if ($menu['menukey'] != 'default'){
														$editLink = $this->getEditLink(array('action' => 'editmenu', 'menutype' => 'page', 'sub' => '1', 'id' => $menu['id']), 'Edit Menu Item');	
														$deleteLink = $this->getDeleteLink($menu['id'], array('action' => 'deletemenu', 'menutype' => 'page', 'id' => $menu['id']), 'Edit Menu Item');	
														$table_list->startRow();
														$table_list->addCell($menu['menukey'], 150);
														$table_list->addCell($editLink.' '.$deleteLink, 150);
														$table_list->endRow();
												}
										}

										$table->startRow();
										$table->addCell($table_list->show(), 150);
										$table->endRow();

								}


								$subMenuLink = '<a href="?module=cmsadmin&action=editmenu&menutype=page&sub=1"><b>Add a Sub Menu</b></a>';

								if ($isSub != '1'){
										$table->startRow();
										$table->addCell($subMenuLink, null, 'top', null, null, 'colspan="2"');
										$table->endRow();


								}
						}

				}

				if ($isSub == '1'){

					// Title Input
					$titleInput = new textinput ('menukey', $titleInputValue);
					$titleInput->cssId = 'input_title'; 
					$titleInput->extra = ' style="width: 25%"';

					$table->startRow();
					$table->addCell('Menu Key', 150);
					$table->addCell($titleInput->show());
					$table->endRow();
				}

				/*
				   $lbNo = $this->objLanguage->languageText('word_no');
				   $lbYes = $this->objLanguage->languageText('word_yes');
				   $objRadio = new radio('hide_title');
				   $objRadio->addOption('1', '&nbsp;'.$lbYes);
				   $objRadio->addOption('0', '&nbsp;'.$lbNo);
				   $objRadio->setSelected($hide_title);
				   $objRadio->setBreakSpace('&nbsp;&nbsp;');

				   $table->startRow();
				   $table->addCell($this->objLanguage->languageText('phrase_hidetitle').': &nbsp; ');
				   $table->addCell($objRadio->show());
				//$table->addCell($published->show());
				$table->endRow();
				 */

				//Adding the FCK_EDITOR
				if (isset($arrContent[0]['body'])) {
						$bodyInputValue = stripslashes($arrContent[0]['body']);
				}else{
						$bodyInputValue = null;
				}


				$bodyInput = $this->newObject('htmlarea', 'htmlelements');
				$bodyInput->init('body', $bodyInputValue);
				$bodyInput->setContent($bodyInputValue);
				$bodyInput->setDefaultToolBarSet();
				$bodyInput->height = '400px';

				//echo $bodyInput->show(); exit;

				$h3->str = $headerMessage;
				$h3->type = 3;

				$table->startRow();
				$table->addCell('<br/>');
				$table->endRow();

				$table->startRow();
				$table->addCell($h3->show(), null, 'top', null, null, 'colspan="2"');
				$table->endRow();

				$table->startRow();
				$table->addCell('', null, 'top', null, null, 'style="padding-bottom:10px"');
				$table->endRow();

				$table->startRow();
				$table->addCell($bodyInput->show(), null, 'top', null, null, 'colspan="2"');
				$table->endRow();

				$table->startRow();
				$table->addCell('', null, 'top', null, null, 'style="padding-bottom:10px"');
				$table->endRow();


				if ($isSub != '1'){
					$table->startRow();
					$table->addCell('<input type="hidden" name="menukey" value="default"/>', null, 'top', null, null, 'colspan="2"');
					$table->endRow();
				}

				$table->startRow();
				$table->addCell('<input type="submit" value=" Save " name="submitter" id="submitter">', null, 'top', null, null, 'style="padding-top:10px"');
				$table->addCell('<input type="hidden" value="save" name="save">', null, 'top', null, null, 'style="padding-top:10px"');
				$table->addCell('<input type="hidden" value="0" name="must_apply" id="must_apply">', null, 'top', null, null, 'style="padding-top:10px"');
				$table->endRow();

	
				$objForm->addToForm($table->show());

				//Add validation for title            
				/*
				   $errTitle = $this->objLanguage->languageText('mod_cmsadmin_entertitle', 'cmsadmin');
				   $objForm->addRule('title', $errTitle, 'required');
				   $objForm->addToForm($table->show());
				//add action
				$objForm->addToForm($txt_action);
				 */

				$display = $objForm->show(); 	
				return $display;

		} 










		/**
		 * Method to return the add edit content form
		 *
		 * @param string $contentId The id of the content to be edited. Default NULL for adding new section
		 * @access public
		 * @return string $middleColumnContent The form used to create and edit a page
		 * @author Warren Windvogel
		 */
		public function getAddEditContentForm($contentId = NULL, $section = NULL, $fromModule = NULL, $fromAction = NULL, $s_param = NULL)
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


				$published = new checkbox('published');
				$frontPage = new checkbox('frontpage');
				$frontPage->value = 1;
				// Hide the Introtext part
				$this->appendArrayVar('headerParams', '
								<script type="text/javascript">
								//<![CDATA[

								function toggleIntroRequired()
								{
								if (document.forms[\'addfrm\'].frontpage.checked == true)
								{
								document.getElementById(\'row_id\').style.display =\'\';
								document.getElementById(\'introdiv\').style.display =\'block\';
								document.getElementById(\'introrequiredtext\').style.display =\'inline\';
								adjustLayout();
								} else {
								document.getElementById(\'row_id\').style.display =\'none\';
								document.getElementById(\'introdiv\').style.display =\'none\';
								document.getElementById(\'introrequiredtext\').style.display =\'none\';
								adjustLayout();
								}

								}
								//]]>
				</script>');
				$this->appendArrayVar('bodyOnLoad', 'toggleIntroRequired();');
				$frontPage->extra = 'onchange="toggleIntroRequired();"';


				$objOrdering = new textinput();
				$objCCLicence = $this->newObject('licensechooser', 'creativecommons');
				$is_front = FALSE;
				$show_content = '0';
				if ($contentId == NULL) {
						$action = 'createcontent';
						$editmode = FALSE;
						$titleInputValue = '';
						$bodyInputValue = '';
						$introInputValue = '';
						$published->setChecked(TRUE);
						$visible = TRUE;
						$hide_title = '0';
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
						$titleInputValue = $arrContent['title'];

						$introInputValue = stripslashes($arrContent['introtext']);
						$bodyInputValue = stripslashes($arrContent['body']);

						$frontData = $this->_objFrontPage->getFrontPage($arrContent['id']);
						if($frontData === FALSE){
								$is_front = FALSE;
						}else{
								$show_content = $frontData['show_content'];
								$is_front = TRUE;
						}

						$frontPage->setChecked($is_front);
						$published->setChecked($arrContent['published']);
						$visible = $arrContent['published'];
						$hide_title = $arrContent['hide_title'];
						if(isset($arrContent['post_lic'])){
								$objCCLicence->defaultValue = $arrContent['post_lic'];
						}
				}

				//setup form
				$frontMan = $this->getParam('frontmanage', FALSE);
				$objForm = new form('addfrm', $this->uri(array('action' => $action, 'id' => $contentId, 'frontman' => $frontMan), 'cmsadmin'));
				$objForm->setDisplayType(3);

				if ($editmode) {
						//Set ordering as hidden field
						$sections = new hiddeninput('parent', $arrContent['sectionid']);
						//                $objOrdering = new hiddeninput('ordering', $arrContent['ordering']);

				} else {
						if (isset($section) && !empty($section)) {
								$sections = $this->getContentTreeDropdown($section, FALSE);
						} else {
								$sections = $this->getContentTreeDropdown(NULL, FALSE);
						}
				}

				$table = new htmlTable();
				$table->width = "100%";
				$table->cellspacing = "0";
				$table->cellpadding = "0";
				$table->border = "0";
				$table->attributes = "align ='center'";
				$this->loadClass('textinput', 'htmlelements');
				// Title Input
				$titleInput = new textinput ('title', $titleInputValue);
				$titleInput->cssId = 'input_title'; 
				$titleInput->extra = ' style="width: 25%"';

				$table->startRow();
				$table->addCell($this->objLanguage->languageText('word_title').': ', 150);
				$table->addCell($titleInput->show());
				$table->endRow();

				if (!$editmode) {
						$table->startRow();
						$table->addCell($this->objLanguage->languageText('word_section').': ');
						$table->addCell($sections->show());
						$table->endRow();
				} else {
						$table->startRow();
						$table->addCell($sections->show());
						$table->endRow();
				}

				$table->startRow();
				$table->addCell($this->objLanguage->languageText('word_publish').': &nbsp; ');
				$table->addCell($this->getYesNoRadion('published', $visible));
				//$table->addCell($published->show());
				$table->endRow();

				$lbNo = $this->objLanguage->languageText('word_no');
				$lbYes = $this->objLanguage->languageText('word_yes');
				$objRadio = new radio('hide_title');
				$objRadio->addOption('1', '&nbsp;'.$lbYes);
				$objRadio->addOption('0', '&nbsp;'.$lbNo);
				$objRadio->setSelected($hide_title);
				$objRadio->setBreakSpace('&nbsp;&nbsp;');

				$table->startRow();
				$table->addCell($this->objLanguage->languageText('phrase_hidetitle').': &nbsp; ');
				$table->addCell($objRadio->show());
				//$table->addCell($published->show());
				$table->endRow();

				$table->startRow();
				$table->addCell($this->objLanguage->languageText('mod_cmsadmin_showonfrontpage', 'cmsadmin').': ');
				$table->addCell($frontPage->show().'<span id="introrequiredtext" class="warning">'.$this->objLanguage->languageText('mod_cmsadmin_pleaseenterintrotext', 'cmsadmin').'</span>');
				$table->endRow();




				// Radio button to display the full content or only the summary on the front page
				$lbDisplay = $this->objLanguage->languageText('mod_cmsadmin_displaysummaryorcontent', 'cmsadmin');
				$lbIntroOnly = $this->objLanguage->languageText('mod_cmsadmin_introonly', 'cmsadmin');
				$lbFullContent = $this->objLanguage->languageText('mod_cmsadmin_fullcontent', 'cmsadmin');

				$objRadio = new radio('show_content');
				$objRadio->addOption('0', $lbIntroOnly);
				$objRadio->addOption('1', $lbFullContent);
				$objRadio->setBreakSpace('&nbsp;&nbsp;');
				$objRadio->setSelected($show_content);

				$table_fr = new htmltable();

				$table_fr->startRow();
				$table_fr->addCell('<br/>');
				$table_fr->endRow();

				$table_fr->startRow();
				$table_fr->addCell($lbDisplay.': ', null, 'top', null, null, 'colspan="2"');
				$table_fr->endRow();

				$table_fr->startRow();
				$table_fr->addCell($objRadio->show(), '', 'center');
				$table_fr->endRow();


				$table->row_attributes = "id='row_id'";
				$table->startRow();
				$table->addCell($table_fr->show(), null, 'top', null, null, 'colspan="2"');
				$table->endRow();


				// Introduction Area
				$introInput = $this->newObject('htmlarea', 'htmlelements');
				$introInput->init('intro', $introInputValue);
				$introInput->setContent($introInputValue);
				$introInput->setBasicToolBar();
				$introInput->height = '200px';
				$introInput->width = '50%';

				$h3 = $this->newObject('htmlheading', 'htmlelements');
				$h3->str = $this->objLanguage->languageText('word_introduction').' ('.$this->objLanguage->languageText('word_required').')';
								$h3->type = 3;

								//add hidden text input
								$table->row_attributes = '';
								$table->startRow();
								//$table->addCell(NULL);
								$table->addCell('<div id="introdiv"><br />'.$h3->show().$introInput->show().'</div>','','left','left', null, 'colspan="2"');
								$table->endRow();



								//Adding the FCK_EDITOR

								if ($editmode) {

								if (isset($arrContent['body'])) {
								$bodyInputValue = stripslashes($arrContent['body']);
								}else{
								$bodyInputValue = null;
								}

								}

								$bodyInput = $this->newObject('htmlarea', 'htmlelements');
								$bodyInput->init('body', $bodyInputValue);
								$bodyInput->setContent($bodyInputValue);
								$bodyInput->setDefaultToolBarSet();
								$bodyInput->height = '400px';

								//echo $bodyInput->show(); exit;
								$h3->str = $this->objLanguage->languageText('mod_cmsadmin_maintext', 'cmsadmin');
								$h3->type = 3;

								$table->startRow();
								$table->addCell('<br/>');
								$table->endRow();

								$table->startRow();
								$table->addCell($h3->show(), null, 'top', null, null, 'colspan="2"');
								$table->endRow();

								$table->startRow();
								$table->addCell('', null, 'top', null, null, 'style="padding-bottom:10px"');
								$table->endRow();

								$table->startRow();
								$table->addCell($bodyInput->show(), null, 'top', null, null, 'colspan="2"');
								$table->endRow();

								//add the main body
								$table2 = new htmltable();

								$h3->str = "Page Parameters";
								$h3->type = 3;

								$table2->startRow();
								$table2->addCell('<br/>');
								$table2->endRow();

								$table2->startRow();
								$table2->addCell($h3->show(), null, 'top', null, null, 'colspan="2"');
								$table2->endRow();

								$table2->startRow();
								$table2->addCell('', null, 'top', null, null, 'style="padding-bottom:10px"');
								$table2->endRow();

								$table2->startRow();

								if (!$editmode) {
										$table2->addCell($this->getConfigTabs());
								}else{
										$table2->addCell($this->getConfigTabs($arrContent));
								}
								$table2->endRow();
								// Content Area

								//Header for main body
								//$h3->str = $this->objLanguage->languageText('mod_cmsadmin_maintext', 'cmsadmin');
								//Pass action
								$txt_action = new textinput('action',$action,'hidden');
								$table->startRow();
								//$table->addCell($h3->show(),null,'center','left');
								$table->addCell($table2->show(),null,'top','left', null, 'colspan="2"');
								if ($fromModule) { 
										$mod = new textinput('frommodule',$fromModule,'hidden');
										$act = new textinput('fromaction',$fromAction,'hidden');
										$param = new textinput('s_param',$s_param,'hidden');
										$table->addCell($mod->show().$act->show().$param->show()); 
								}
								//$table->addCell(,null,'bottom','center');
								$table->endRow();         		
								//Lets do the CC Licence
								$objModulesInfo = $this->getObject('modules', 'modulecatalogue');

								//cc licence input
								if ($objModulesInfo->checkIfRegistered('creativecommons')) {
										$h3->str = $this->objLanguage->languageText('word_licence');
										$h3->type = 3;
										//if (!$editmode) {

										$table->startRow();
										$table->addCell('<br/>');
										$table->endRow();

										$table->startRow();
										$table->addCell($h3->show(),null,'center','left');
										$table->endRow();

										$table->startRow();
										$table->addCell('', null, 'top', null, null, 'style="padding-bottom:10px"');
										$table->endRow();

										$table->startRow();
										$table->addCell($objCCLicence->show(),null,'top','left', null, 'colspan="2"'); 
										$table->endRow();

										$table->startRow();
										$table->addCell('<input type="hidden" value="0" name="must_apply" id="must_apply">', null, 'top', null, null, 'style="padding-top:10px"');
										$table->endRow();


										//}else{
										/*
										   $table->startRow();
										   $table->addCell($h3->show(),null,'center','left');
										   $table->addCell($objCCLicence->show(),null,'top','left'); 
										   $table->endRow();
										 */
										//}


								} 


								//Add validation for title            
								$errTitle = $this->objLanguage->languageText('mod_cmsadmin_entertitle', 'cmsadmin');
								$objForm->addRule('title', $errTitle, 'required');
								$objForm->addToForm($table->show());
								//add action
								$objForm->addToForm($txt_action);


								//body
								// $dialogForm = new form();


								// $dialogForm->addToForm($table2->show());
								//add page header for the body

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
						@$content .= "<li><a href=\"" . $item['link'] . "\">" . $item['title'] . "</a></li>\n";
				}
				$content .=  "</ul>\n";
				return $objFeatureBox->show($head, $content);

		}

		public function rssEditor($featurebox = FALSE, $rdata = NULL)
		{
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
														'action' => 'addrss', 'mode' => 'edit', 'id' => $rdata['id']
														)));
				}
				//add rules
				//$rssform->addRule('rssurl', $this->objLanguage->languageText("mod_cms_phrase_rssurlreq", "cmsadmin") , 'required');
				//$rssform->addRule('name', $this->objLanguage->languageText("mod_cms_phrase_rssnamereq", "cmsadmin") , 'required');
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
				$this->objRssButton = new button($this->objLanguage->languageText('word_save', 'system'));
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
																'action' => 'rssedit',
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
				$introInput = new hiddeninput('intro', $page['introtext']);

				$titleInput->value = $page['title'];

				$bodyInput->setContent((stripslashes($page['body'])));

				$table->startRow();
				$table->addCell($this->objLanguage->languageText('word_title'), 150);
				$table->addCell($titleInput->show(), NULL, NULL, NULL, NULL, ' colspan="3"');
				$table->endRow();
				$objForm->addToForm($table->show());
				//$objForm->addRule('title', $this->objLanguage->languageText('mod_cmsadmin_pleaseaddtitle', 'cmsadmin'), 'required');


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
		 * Method to return the form for adding/removing blocks from the front page
		 *
		 * @access public
		 * @return string html
		 */
		public function showFrontBlocksForm()
		{
				$objCMSBlocks = $this->_objBlocks;
				$thisPageBlocks = $objCMSBlocks->getBlocksForFrontPage();
				$leftBlocks = $objCMSBlocks->getBlocksForFrontPage(1);

				//$init = 'fm_init();';
				$init = "bl_init('adddynamicfrontpageblock', 'addleftfrontpageblock', 'removedynamicfrontpageblock', 'init_x', 'init_x');";
				$str = $this->showBlocksForm($thisPageBlocks, $leftBlocks, $init);

				return $str;
		}

		/**
		 * Method to return the form for adding/removing blocks from the content page
		 *
		 * @access public
		 * @param string $id The row id of the content page where the blocks will be added
		 * @param string $section The id of the section containing the content page
		 * @return string html
		 */
		public function showContentBlocksForm($id, $section)
		{
				$objCMSBlocks = $this->_objBlocks;
				$thisPageBlocks = $objCMSBlocks->getBlocksForPage($id);
				$leftBlocks = $objCMSBlocks->getBlocksForPage($id, '', 1);

				//        $init = "ca_init('{$id}', '{$section}');";
				$init = "bl_init('adddynamicpageblock', 'addleftpageblock', 'removedynamicpageblock', '{$id}', '{$section}');";
				$str = $this->showBlocksForm($thisPageBlocks, $leftBlocks, $init);

				return $str;
		}

		/**
		 * Method to return the form for adding/removing blocks from the section
		 *
		 * @access public
		 * @param string $id The row id of the section
		 * @return string html
		 */
		public function showSectionBlocksForm($id)
		{
				$objCMSBlocks = $this->_objBlocks;
				$thisPageBlocks = $objCMSBlocks->getBlocksForSection($id);
				$leftBlocks = $objCMSBlocks->getBlocksForSection($id, 1);

				//        $init = "sa_init('{$id}')";
				$init = "bl_init('adddynamicsectionblock', 'addleftsectionblock', 'removedynamicsectionblock', '', '{$id}');";
				$str = $this->showBlocksForm($thisPageBlocks, $leftBlocks, $init);

				return $str;
		}

		/**
		 * Method to display the form for adding and removing blocks to/from the left and right hand columns
		 *
		 * @access public
		 * @return string html
		 */
		public function showBlocksForm($thisPageBlocks, $leftBlocks, $onload = '')
		{
				$objIcon = $this->newObject('geticon', 'htmlelements');
				$objModuleBlocks = $this->getObject('dbmoduleblocks', 'modulecatalogue');
				$objBlocks = $this->getObject('blocks', 'blocks');

				$blocks = $objModuleBlocks->getBlocks('normal');

				// add js script library
				$headerParams = $this->getJavascriptFile('scripts.js', 'cmsadmin');
				$this->appendArrayVar('headerParams', $headerParams);

				// initialize onload scripts
				$this->appendArrayVar('bodyOnLoad', $onload);

				// language elements
				$lbAddedBl = $this->objLanguage->languageText('mod_cmsadmin_rightsideblocks', 'cmsadmin');
				$lbLeftBl = $this->objLanguage->languageText('mod_cmsadmin_leftsideblocks', 'cmsadmin');
				$lbDragBl = $this->objLanguage->languageText('mod_cmsadmin_dragaddblocks', 'cmsadmin');
				$lbPageBl = $this->objLanguage->languageText('mod_cmsadmin_pageblocks', 'cmsadmin');
				$lbLinkDis = $this->objLanguage->languageText('mod_cmsadmin_warnlinkdisabled', 'cmsadmin');
				$lbLoading = $this->objLanguage->languageText('word_loading');
				$lbAvailBl = $this->objLanguage->languageText('mod_cmsadmin_availableblocks', 'cmsadmin');
				$lbDragRem = $this->objLanguage->languageText('mod_cmsadmin_dragremoveblocks', 'cmsadmin');

				$blStr = ''; $usedBlocks = array();

				// Display loding bar
				$objIcon->setIcon('loading_bar', 'gif', 'icons/');
				$objIcon->title = $lbLoading;

				$objLayer = new layer();
				$objLayer->str = $objIcon->show();
				$objLayer->id = 'loading';
				$objLayer->display = 'none';
				$blStr .= $objLayer->show();

				/* Create right side drop zone */
				$objHead = new htmlheading();
				$objHead->str = $lbAddedBl;
				$objHead->type = 4;
				$dropStr = $objHead->show();
				$dropStr .= '<p>'.$lbDragBl.'</p>';

				if(!empty($thisPageBlocks)){
						foreach ($thisPageBlocks as $block){
								$str = trim($objBlocks->showBlock($block['blockname'], $block['moduleid'], '', 20, TRUE, TRUE, 'none'));
								$str = preg_replace('/type\\s??=\\s??"submit"/', 'type="button"', $str);
								$str = preg_replace('/href=".+?"/', 'href="javascript:alert(\''.$lbLinkDis.'\');"', $str);
								$str = preg_replace('/onchange =".+"/', 'onchange="javascript:alert(\''.$lbLinkDis.'\');"', $str);

								$usedBlocks[] = $block['blockid'];

								$objLayer = new layer();
								$objLayer->str = $str;
								$objLayer->id = $block['blockid'];
								$objLayer->cssClass = 'usedblock';
								$dropStr .= $objLayer->show();
						}
				}

				// Drop zone for adding blocks
				$objLayer = new layer();
				$objLayer->str = $dropStr;
				$objLayer->id = 'dropzone';
				$objLayer->cssClass = 'dropblock';
				$rightStr = $objLayer->show();

				/* Create left side drop zone */
				$objHead = new htmlheading();
				$objHead->str = $lbLeftBl;
				$objHead->type = 4;
				$dropStr = $objHead->show();
				$dropStr .= '<p>'.$lbDragBl.'</p>';

				if(!empty($leftBlocks)){
						foreach ($leftBlocks as $block){
								$str = trim($objBlocks->showBlock($block['blockname'], $block['moduleid'], '', 20, TRUE, TRUE, 'none'));
								$str = preg_replace('/type\\s??=\\s??"submit"/', 'type="button"', $str);
								$str = preg_replace('/href=".+?"/', 'href="javascript:alert(\''.$lbLinkDis.'\');"', $str);
								$str = preg_replace('/onchange =".+"/', 'onchange="javascript:alert(\''.$lbLinkDis.'\');"', $str);

								$usedBlocks[] = $block['blockid'];

								$objLayer = new layer();
								$objLayer->str = $str;
								$objLayer->id = $block['blockid'];
								$objLayer->cssClass = 'leftblocks';
								$dropStr .= $objLayer->show();
						}
				}


				// Drop zone for adding blocks
				$objLayer = new layer();
				$objLayer->str = $dropStr;
				$objLayer->id = 'leftzone';
				$objLayer->cssClass = 'dropleft';
				$leftStr = $objLayer->show();

				/* Create delete zone */
				$objHead = new htmlheading();
				$objHead->str = $lbAvailBl;
				$objHead->type = '4';
				$delStr = $objHead->show();
				$delStr .= '<p>'.$lbDragRem.'</p>';

				if(!empty($blocks)){
						foreach ($blocks as $block){
								if (!in_array($block['id'], $usedBlocks)) {
										$str = trim($objBlocks->showBlock($block['blockname'], $block['moduleid'], '', 20, TRUE, TRUE, 'none'));
										$str = preg_replace('/type\\s??=\\s??"submit"/', 'type="button"', $str);
										$str = preg_replace('/href=".+?"/', 'href="javascript:alert(\''.$lbLinkDis.'\');"', $str);
										$str = preg_replace('/onchange =".+"/', 'onchange="javascript:alert(\''.$lbLinkDis.'\');"', $str);

										$objLayer = new layer();
										$objLayer->str = $str;
										$objLayer->id = $block['id'];
										$objLayer->cssClass = 'addblocks';
										$delStr .= $objLayer->show();
								}
						}
				}

				$objLayer = new layer();
				$objLayer->str = $delStr;
				$objLayer->id = 'deletezone';
				$objLayer->cssClass = 'deleteblock';
				$allStr = $objLayer->show();


				$blStr .= $leftStr.$allStr.$rightStr.'<br clear="left" />';

				$objLayer = new layer();
				$objLayer->str = $blStr;
				$objLayer->id = 'selectblocks';

				return $objLayer->show();
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
