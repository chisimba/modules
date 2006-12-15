<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check

/**
* The controller for the cms module that extends the base controller
*
* @package cms
* @category chisimba
* @copyright AVOIR
* @license GNU GPL
* @author Wesley  Nitsckie
* @author Warren Windvogel
*/

class cms extends controller
{


        /**
         * The contextcore  object
         *
         * @access private
         * @var object
         */
        protected $_objContextCore;

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
         * The CMS Utilities object
         *
         * @access private
         * @var object
         */
        protected $_objUtils;

        /**
         * The contextCode
         *
         * @access private
         * @var object
         */
        protected $contextCode;

        /**
        * The contextCode
        *
        * @access private
        * @var object
        */
        protected $inContextMode;

        /**
        * The user object
        *
        * @access private
        * @var object
        */
        protected $_objUser;

        /**
         * Method to initialise the cms object
         *
         * @access public
         */
        public function init()
        {
            // instantiate object
            $this->_objSections = & $this->newObject('dbsections', 'cmsadmin');
            $this->_objContent = & $this->newObject('dbcontent', 'cmsadmin');
            $this->_objUtils = & $this->newObject('cmsutils', 'cmsadmin');
            $this->_objContext = & $this->newObject('dbcontext', 'context');
            $this->_objUser = & $this->newObject('user', 'security');
            $this->objLanguage = & $this->newObject('language', 'language');

            $objModule = & $this->newObject('modules', 'modulecatalogue');

            if($objModule->checkIfRegistered('context')) {
                $this->inContextMode = $this->_objContext->getContextCode();
                $this->contextCode = $this->_objContext->getContextCode();
            } else {
                $this->inContextMode = FALSE;
            }
        }

        /**
        * This is a method to determine if the user has to be logged in or not
        *
        * @access public
        * @return bool FALSE
        */
        public function requiresLogin() // overides that in parent class
        {
            return FALSE;

        }

        /**
         * Method to handle actions from templates
         *
         * @access public
         * @param string $action Action to be performed
         * @return mixed Name of template to be viewed or function to call
         */
        public function dispatch()
        {
            $isLoggedIn = $this->_objUser->isLoggedIn();
            $action = $this->getParam('action');
            $this->setLayoutTemplate('cms_layout_tpl.php');

            switch ($action) {
            case null:
                case 'home':
                $content = $this->_objUtils->getFrontPageContent();
                if($content!='') {
                	$this->bbcode = $this->getObject('bbcodeparser', 'utilities');
                	$content = $this->bbcode->parse4bbcode($content);
                    $this->setVarByRef('content', $content);
                    return 'cms_main_tpl.php';
                } else {
                    $firstSectionId = $this->_objSections->getFirstSectionId(TRUE);
                    return $this->nextAction('showsection', array('id'=>$firstSectionId,'sectionid'=>$firstSectionId));
                }

            case 'showsection':
                $sectionId = $this->getParam('id');
                if($sectionId != '') {
                    $section = $this->_objSections->getSection($sectionId);
                    $siteTitle = $section['title'];
                } else {
                    $siteTitle = '';
                }
                $this->setVarByRef('pageTitle', $siteTitle);
                if($sectionId != '') {
                	$this->bbcode = $this->getObject('bbcodeparser', 'utilities');
                	$content = $this->_objUtils->showSection();
                	$content = nl2br($this->bbcode->parse4bbcode($content));
                    $this->setVar('content', $content);
                } else {
                    $this->setVar('content', '<div class="noRecordsMessage">'.$this->objLanguage->languageText('mod_cms_novisiblesections', 'cms').'</div>');
                }
                return 'cms_section_tpl.php';

            case 'showcontent':
            case 'showfulltext':
                $fromadmin = $this->getParam('fromadmin', FALSE);
                $sectionId = $this->getParam('sectionid', NULL);
                $this->setVarByRef('sectionId', $sectionId);
                $this->setVarByRef('fromadmin', $fromadmin);
                $page = $this->_objContent->getContentPage($this->getParam('id'));
                $siteTitle = $page['title'];
                $this->setVarByRef('pageTitle', $siteTitle);
                $this->bbcode = $this->getObject('bbcodeparser', 'utilities');
                $content = $this->_objUtils->showBody();
                $content = ($this->bbcode->parse4bbcode($content));
                $this->setVar('content', $content);
                return 'cms_content_tpl.php';

            case 'ical':
                $objBlocks = & $this->newObject('blocks', 'blocks');
                //$objBlocks->showBlock('calendar', 'calendar')
                $objCal = & $this->newObject('block_calendar', 'calendar');

                $this->setVar('content', $objCal->show(TRUE));
                $this->setVar('title', $objCal->title);
                return 'cms_calendar_tpl.php';

            }
        }

        /**
         * Method to get the Sections on the left side of the menu
         *
         * @access public
         * @return string
         */
        public function getSectionMenu()
        {
            $calArr =  array('text' => 'Calendar', 'uri' => $this->uri(array('action' => 'ical')));
            return $this->_objUtils->getSectionMenu();
        }


        /**
         * Method to get the Bread Crumbs
         *
         * @access public
         * @return string Html for the breadcrumbs
         */
        public function getBreadCrumbs()
        {
            return $this->_objUtils->getBreadCrumbs();
        }

}

?>
