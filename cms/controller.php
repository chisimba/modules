<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check

/**
* 
* The controller that extends the base controller for the cms module 
* 
* Note: 2006 12 16 - Converted to direct function access from action
*   parameter, and cleaned up some very messy code -- D. Keats
*
* @package cms
* @category chisimba
* @copyright AVOIR
* @license GNU GPL
* @author Wesley  Nitsckie
* @author Warren Windvogel
* @author Derek Keats
* 
*/

class cms extends controller
{
        /**
		*
        * @var string object $_objContextCore A string to hold an instance of the contextcore object which ....
        * @access protected 
        * 
        */
        protected $_objContextCore;

        /**
        *
        * @var string object $_objSections A string to hold an instance of the sections 
        * database access object from CMS admin
        * @access protected
        * 
        */
        protected $_objSections;

        /**
        * @var string object $_objContent A string to hold an instance of 
        * the Content object which ...
        * @access protected
        * 
        */
        protected $_objContent;

        /**
        *  
        * @var string object $_objUtils A string to hold an instance of the CMS 
        * Utilities object
        * @access protected
        * 
        */
        protected $_objUtils;

        /**
         * 
         * @var string $contextCode A string to hold the contextCode for the 
         * context that the user is in
         * @access protected
         * 
         */
        protected $contextCode;

        /**
        * 
        * @var string $inContextMode A string to hold the context code so that 
        * we can call it by another name for no apparent reason or a reason known 
        * only to the developer of this module who did not put in any explanation 
        * anywhere.
        * @access protected
        * 
        */
        protected $inContextMode;

        /**
        * 
        * @var string object $_objUser A string to hold an instance of the user object
        * @access protected
        * 
        */
        protected $_objUser;
        
        /**
        * 
        * @var string $action A string to hold the value of the action parameter
        *  retrieved from the querystring
        * @access public
        * 
        */
        public $action;

        /**
        * 
        * The standard init method to initialise the cms object and assign some of
        * the objects used in all action derived methods.
        *
        * @access public
        * 
        */
        public function init()
        {
            // instantiate the database object for sections
            $this->_objSections = & $this->newObject('dbsections', 'cmsadmin');
            // instantiate the database object for content
            $this->_objContent = & $this->newObject('dbcontent', 'cmsadmin');
            // instantiate the object for CMS utilities
            $this->_objUtils = & $this->newObject('cmsutils', 'cmsadmin');
            // instantiate the context object so we can get where the contex the user is in
            $this->_objContext = & $this->newObject('dbcontext', 'context');
            // instantiate the user object so we can retrieve user information
            $this->_objUser = & $this->newObject('user', 'security');
            //Create an instance of the language object for text rendering
            $this->objLanguage = & $this->newObject('language', 'language');
			//Create an instance of the modules object from modulecatalogue
            $objModule = & $this->newObject('modules', 'modulecatalogue');
			//Use the modules object instantiated above to see if context is registered
            if($objModule->checkIfRegistered('context')) {
            	//If context is registered, assign the current context value to
            	//   both the contextCode property and the inContextMode property
            	//   of this object because.........
                $this->inContextMode = $this->_objContext->getContextCode();
                $this->contextCode = $this->inContextMode;
            } else {
            	//If conmtext is not registered then assign boolean FALSE to
            	//   the inContextMode property of this object
                $this->inContextMode = FALSE;
            }
        }

        /**
         * 
        * This is a method that overrides the parent class to stipulate whether
        * the current module requires login. Having it set to false gives public
        * access to this module including all its actions.
        *
        * @access public
        * @return bool FALSE
        */
        public function requiresLogin() 
        {
            return FALSE;

        }

        /**
         * 
         * A standard method to handle actions from the querystring.
         * The dispatch function converts action values to function
         * names, and then calls those functions to perform the action
         * that was specified.
         *
         * @access public
         * @return string The results of the method denoted by the action
         *   querystring parameter. Usually this will be a template populated
         *   with content.
         * 
         */
        public function dispatch()
        {
            //Create a local variable for whether the user is logged in
            $isLoggedIn = $this->_objUser->isLoggedIn();
            $this->setLayoutTemplate('cms_layout_tpl.php');
            //Get action from query string and set default to view
            //  and assign the action to a property of this object
            $this->action=$this->getParam('action', 'home');
            /*
            * Convert the action into a method (alternative to
            * using case selections)
            */
            try {
        	    $method = $this->_getMethod();
            } catch (customException $e) {
			    customException::cleanUp();
			    exit;
		    }
        	/*
        	 * Return the template determined by the method resulting
        	 * from action
        	 */
        	return $this->$method();
        }
		    
        
        /**
         * A method that corresponds to the showsection action parameter
         * from the querystring. It returns the formatted section text for
         * display in the template.
         * 
         * @access private
         * @return string The populated cms_section_tpl.php template
         * 
         */
        private function _showsection()
        {
            //Retrieve the section id from the querystring
			$sectionId = $this->getParam('id');
			//If the section id is not null, get the section and set the 
			//  site title, otherwise set the site title to empty.
			if($sectionId != '') {
			    $section = $this->_objSections->getSection($sectionId);
			    $siteTitle = $section['title'];
			} else {
			    $siteTitle = '';
			}
			//Set the page title to be equal to the siteTitle from the section
			$this->setVarByRef('pageTitle', $siteTitle);
			//We need the two if statements because pageTitle needs to be set first
			if($sectionId != '') {
				$this->bbcode = $this->getObject('bbcodeparser', 'utilities');
				$content = $this->_objUtils->showSection();
				$content = $this->bbcode->parse4bbcode($content);
			    $this->setVar('content', $content);
			} else {
			    $this->setVar('content', '<div class="noRecordsMessage">'.$this->objLanguage->languageText('mod_cms_novisiblesections', 'cms').'</div>');
			}
			//Return the populated section template
			return 'cms_section_tpl.php';
        }
        
        
        /**
         * A method that corresponds to the showfulltext action parameter
         * from the querystring. It returns the formatted full page of content 
         * text for a particular content item.
         * 
         * @access private
         * @return string The populated cms_content_tpl.php template
         * 
         */
        private function _showfulltext()
        {
                $fromadmin = $this->getParam('fromadmin', FALSE);
                $sectionId = $this->getParam('sectionid', NULL);
                $this->setVarByRef('sectionId', $sectionId);
                $this->setVarByRef('fromadmin', $fromadmin);
                $page = $this->_objContent->getContentPage($this->getParam('id'));
                $siteTitle = $page['title'];
                $this->setVarByRef('pageTitle', $siteTitle);
                $this->bbcode = $this->getObject('bbcodeparser', 'utilities');
                $content = $this->_objUtils->showBody();
                $content = $this->bbcode->parse4bbcode($content);
                $this->setVarByRef('content', $content);
                return 'cms_content_tpl.php';
        }

        /**
         * A method that corresponds to the showcontent action parameter
         * from the querystring. It returns the formatted full page of content 
         * text for a particular content item. Since its the same as
         * showfulltext, it is not obvious why its needs to be here, but
         * it was in the dispatch method so I put it here.
         * 
         * @access private
         * @return string The populated cms_content_tpl.php template
         * @todo The author needs to check if this method is necessary
         * 
         */
        private function _showcontent()
        {
        	return $this->_showfulltext();
        }
        
        /**
         * A method that corresponds to the home action parameter
         * from the querystring. It returns the formatted content 
         * or next action depending on whether there is front page
         * comtent or not.
         * 
         * @access private
         * @return string The populated cms_content_tpl.php template
         * @todo The author needs to explain the logic here
         * 
         */
        private function _home()
        {
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
        }
        
	    /**
	    *
	    * Method to convert the action parameter into the name of
	    * a method of this class.
	    *
	    * @access private
	    * @param string $action The action parameter
	    * @return stromg the name of the method
	    *
	    */
	    private function _getMethod()
	    {
	        if ($this->_validAction()) {
	            return "_" . $this->action;
	        } else {
	            return "_actionError";
	        }
	    }
        
	    /**
	    *
	    * Method to check if a given action is a valid method
	    * of this class preceded by double underscore (_). If the action
	    * is not a valid method it returns FALSE, if it is a valid method
	    * of this class it returns TRUE.
	    *
	    * @access private
	    * @param string $action The action parameter
	    * @return boolean TRUE|FALSE
	    *
	    */
	    private function _validAction()
	    {
	        if (method_exists($this, "_".$this->action)) {
	            return TRUE;
	        } else {
	            return FALSE;
	        }
	    }
        
	    /**
	    *
	    * Method to return an error when the action is not a valid
	    * action method
	    *
	    * @access private
	    * @return string The dump template populated with the error message
	    *
	    */
	    private function _actionError()
	    {
	        $this->setVar('str', $this->objLanguage->languageText("mod_cms_errorbadaction", CMS) . ": <em>". $this->action . "</em>");
	        return 'dump_tpl.php';
	    }
        



        
        //THE METHODS BELOW HERE SEEM TO SERVE NO PURPOSE.-------------------------
        
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