<?php
/**
 *
 * Provides functionality specifically aimed at the UWC Elearning Mobile website
 *
 * PHP version 5
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the
 * Free Software Foundation, Inc.,
 * 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 * @category  Chisimba
 * @package   uwcelearning
 * @author    Qhamani Fenana qfenama@uwc.ac.za/qfenama@gmail.com
 * @copyright 2010 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id: controller.php,v 1.4 2007-11-25 09:13:27 qfenama Exp $
 * @link      http://avoir.uwc.ac.za
 */

// security check - must be included in all scripts
if (!
/**
 * The $GLOBALS is an array used to control access to certain constants.
 * Here it is used to check if the file is opening in engine, if not it
 * stops the file from running.
 *
 * @global entry point $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 *
 */
$GLOBALS['kewl_entry_point_run'])
{
        die("You cannot view this page directly");
}
// end security check

/**
*
* Controller class for Chisimba for the module uwcelearningmobile
*
* @author Qhamani Fenama
*
*/
class uwcelearningmobile extends controller
{

    /**
    *
    * @var string $objConfig String object property for holding the
    * configuration object
    * @access public;
    *
    */
    public $objConfig;

    /**
    *
    * @var string $objLanguage String object property for holding the
    * language object
    * @access public
    *
    */
    public $objLanguage;
    /**
    *
    * @var string $objLog String object property for holding the
    * logger object for logging user activity
    * @access public
    *
    */
    public $objLog;

    /**
    *
    * Intialiser for the Uwc Elearning Mobile controller
    * @access public
    *
    */
    public function init()
    {
        $this->objUser = $this->getObject('user', 'security');
        $this->objLanguage = $this->getObject('language', 'language');
        // Create the configuration object
        $this->objConfig = $this->getObject('config', 'config');
		$this->objContext = $this->getObject('usercontext', 'context');
		$this->dbContext = $this->getObject('dbcontext', 'context');
		$this->objModuleCatalogue = $this->getObject('modules', 'modulecatalogue');
		$this->objDate = $this->newObject('dateandtime', 'utilities');
		$this->objMobileSecurity = $this->getObject('mobilesecurity', 'uwcelearningmobile');

		// Store Context Code
		$this->userId = $this->objUser->userId();
		$this->userContext = $this->objContext->getUserContext($this->userId);
        $this->contextCode = $this->dbContext->getContextCode();
		$this->contextTitle = $this->dbContext->getField('title', $this->contextCode);
		
	}


    /**
     * Override the login object in the parent class
     *
     * @param void
     * @return bool
     * @access public
     */
    public function requiresLogin($action)
    {	
		$actions = array('', 'home', 'login');
		//This Hack help to prevent going to the security module when :-
		//1 - action requires login and
		//2 - the user is no logged on		
		if(!in_array($action, $actions) && !$this->objUser->isLoggedIn())
		{
			return $this->goToLogin();
		}


		if (in_array($action, $actions)) {
            return FALSE;
        } else {
            return TRUE;
        }
    }
    

    public function dispatch($action='home')
    {
		$actions = array('', 'home', 'login', 'context', 'readmail', 'internalmail', 'filemanager');
		
		if ($this->contextCode == NULL && !in_array($action, $actions)) {
			 $action = 'home';
        }
		/*
        * Convert the action into a method (alternative to 
        * using case selections)
        */
        $method = $this->__getMethod($action);
        /*
        * Return the template determined by the method resulting 
        * from action
        */
        return $this->$method();
    }

    /**
    * 
    * Method to convert the action parameter into the name of 
    * a method of this class.
    * 
    * @access private
    * @param string $action The action parameter passed byref
    * @return stromg the name of the method
    * 
    */
    function __getMethod(& $action)
    {
        if ($this->__validAction($action)) {
            return "__" . $action;
        } else {
            return "__home";
        }
    }

	/**
    * 
    * Method to check if a given action is a valid method
    * of this class preceded by double underscore (__). If it __action 
    * is not a valid method it returns FALSE, if it is a valid method
    * of this class it returns TRUE.
    * 
    * @access private
    * @param string $action The action parameter passed byref
    * @return boolean TRUE|FALSE
    * 
    */
    function __validAction(& $action)
    {
        if (method_exists($this, "__".$action)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
    * Default Action for Uwc Elearning Mobile module
    * It shows the prelogin or postlogin depending if you logged in or not
    * @access private	
    */
    private function __home()
    {
		if($this->objUser->isLoggedIn()){
			$this->dbContext->leaveContext();
			$usercontexts = $this->objContext->getUserContext($this->objUser->userId());
			$this->setVarByRef('usercontexts', $usercontexts);
			return 'postlogin_tpl.php';
		}
		else{
			$error = $this->getParam('error');
			if($error)
			{
				$this->setVarByRef('error', $error);
			}
			return 'prelogin_tpl.php';
		}
    }

	/**
	* Mothod that takes care of the login in the mobile site
    * @access private
	*	
    */
    private function __login()
    {
		$error = $this->objMobileSecurity->CheckErrors();
		if($error != true)
		{
			return $this->nextAction('login', array(), 'security');
		}
		else
		{
			return $this->nextAction('home', array('error' => $error));
		} 
    }

	/**
	* Method to view course tools
	* @access private
	*/
	private function __context()
	{
		$contextcode = $this->getParam('contextcode');
		$status = $this->dbContext->joinContext ( $contextcode );
		$con = $this->dbContext->getContext($contextcode);
		$conexttitle = $con['title'];
		$this->setVarByRef('conextcode', $contextcode);
		$this->setVarByRef('conexttitle', $conexttitle);

		//Modules that should be able to be viewable
		/*Course Content - File Manager - Assignments - Discussion Forum - Internal Email - Calander - Notifications*/
		$modules = array('contextcontent', 'filemanager', 'assignment', 'forum', 'internalmail', 'calendar', 			'announcements', 'mcqtests');

		$tools = array();
		$objContextModules = $this->getObject ( 'dbcontextmodules', 'context');
        $contextModules = $objContextModules->getContextModules($contextcode);
		//Get Context tools
		foreach($contextModules as $mod)
		{	
			if(in_array($mod, $modules)){
				$tools[] = $mod;
			}
		}
		$this->setVarByRef('tools', $tools);
		return 'context_tpl.php';
	}

	/**
	* Method to view courses announcements
	* @access private
	*/
	private function __announcements()
	{
		$this->objAnnouncements = $this->getObject('dbannouncements', 'announcements');
		
		//Get a current course announcement		
		$coursesann = $this->objAnnouncements->getContextAnnouncements($this->contextCode);
		$coursesanncount = $this->objAnnouncements->getNumContextAnnouncements($this->contextCode);
		//or $coursesanncount = count($coursesann);

		//Get all my courses announcement
		$allann = $this->objAnnouncements->getAllAnnouncements($this->userContext);
		$allanncount = count($allann);

		$this->setVarByRef('coursesann', $coursesann);
		$this->setVarByRef('coursesanncount', $coursesanncount);
		$this->setVarByRef('allann', $allann);
		$this->setVarByRef('allanncount', $allanncount);
		return 'announcements_tpl.php';
		
	}

	/**
	* Method to view a single announcement
	* @access private
	*/
	private function __viewannouncements()
	{
		$id = $this->getParam('id');
		$this->objAnnouncements = $this->getObject('dbannouncements', 'announcements');
		$ann = $this->objAnnouncements->getMessage($id);
		
		$this->setVarByRef('announcement', $ann);
		return 'viewannouncements_tpl.php';
	}
	
	/**
	* Method to view course MCQ Test
	* @access private
	*/
	private function __mcqtests()
	{
		$this->dbTestadmin = $this->newObject('dbtestadmin', 'mcqtests');
		$tests = $this->dbTestadmin->getTests($this->contextCode);
		
		$this->setVarByRef('tests', $tests);
		return 'mcqtests_tpl.php';
	}
	
	/**
	* Method to view course forums
	* @access private
	*/
	private function __forum()
	{
		$this->objForum = $this->getObject('dbforum', 'forum');
		$forumNum = $this->objForum->getNumForums($this->contextCode);
        $allForums = $this->objForum->showAllForums($this->contextCode);
        
		$this->setVarByRef('forums', $allForums);
		return 'forum_tpl.php';		
	}

	/**
	* Method to view a selected forum's topics
	* @access private
	*/
	private function __viewforum()
	{
		$this->objTopic = $this->getObject('dbtopic', 'forum');
		$this->objForum = $this->getObject('dbforum', 'forum');
		$id = $this->getParam('id');
		$limit = $this->objTopic->getNumTopicsInForum($id, true);
		$forum = $this->objForum->getForum($id);
		$order = 'date';
        $direction = 'asc';
		$limit = ' LIMIT 0, '.$limit;
		$allTopics = $this->objTopic->showTopicsInForum($id, $this->userId, $forum['archivedate'], $order, $direction, NULL, $limit);

		$this->setVarByRef('allTopics', $allTopics);
        $this->setVarByRef('forum', $forum);
		return 'singleforum_tpl.php';		
	}

	/**
	* Method to view a selected topic
	* @access private
	*/
	private function __topic()
	{
		$id = $this->getParam('id');
		$this->objPost = $this->getObject('dbpost', 'forum');
		$posts = $this->objPost->getFlatThread($id);

		$this->setVarByRef('posts', $posts);
		return 'viewtopic_tpl.php';
	}

	/**
	* Method to view a assignments
	* @access private
	*/
	private function __assignment()
	{
		$this->objAssignment = $this->getObject('dbassignment', 'assignment');
		$assignments = $this->objAssignment->getAssignments($this->contextCode);
        
		$this->setVarByRef('assignments', $assignments);
        return 'assignment_tpl.php';
	}
	
	/**
	* Method to view a assignment details
	* @access private
	*/
	private function __viewassignment()
	{
		$id = $this->getParam('id');
		$this->objAssignment = $this->getObject('dbassignment', 'assignment');
		$assignment = $this->objAssignment->getAssignment($id);

        $this->setVarByRef('assignment', $assignment);
        return 'viewassignment_tpl.php';
	}
	
	/**
    * Method to show events for the current/selected month.
	*@access private
    */
    private function __calendar()
    { 
        $month = $this->getParam('month', date('m'));
        $year = $this->getParam('year', date('Y'));
        $this->setVarByRef('month', $month);
        $this->setVarByRef('year', $year);

		$this->objCalendarInterface = $this->getObject('calendarinterface', 'calendar');
        $this->objCalendarInterface->setupCalendar($month, $year);

        $eventsCalendar = $this->objCalendarInterface->getCalendar();

        $this->setVarByRef('userEvents', $this->objCalendarInterface->numUserEvents);
        $this->setVarByRef('contextEvents', $this->objCalendarInterface->numContextEvents);
        $this->setVarByRef('otherContextEvents', $this->objCalendarInterface->numOtherEvents);
        $this->setVarByRef('siteEvents', $this->objCalendarInterface->numSiteEvents);
        $this->setVarByRef('eventsCalendar', $eventsCalendar);
        $this->setVarByRef('eventsList', $this->objCalendarInterface->getSmallEventsList());

        return 'calendar_tpl.php';
    }	

	/**
	* Method to view a new course content
	* @access private
	*/
	private function __contextcontent()
	{
		$this->objContextChapters = $this->getObject('db_contextcontent_contextchapter', 'contextcontent');
		$this->objContextActivityStreamer = $this->getObject('db_contextcontent_activitystreamer', 'contextcontent');
		$chapters = $this->objContextChapters->getContextChapters($this->contextCode);
		$arr = array();
		foreach($chapters as $con)
		{
			$ischapterlogged = $this->objContextActivityStreamer->getRecord($this->objUser->userId(), $con['chapterid'], $this->contextCode);
			if($ischapterlogged == FALSE) {
				$arr[] = $con; 
			}
		}
		$this->setVarByRef('content', $arr);
		return 'contextcontent_tpl.php';
	}
	
	/**
	* Method to view a internal mails
	* @access private
	*/
	private function __internalmail()
	{
		$folderId = $this->getParam('folderId', 'init_1');
		$this->dbFolders = $this->newObject('dbfolders', 'internalmail');
		$this->dbRouting = $this->newObject('dbrouting', 'internalmail');

		$arrFolderList = $this->dbFolders->listFolders();
		$arrFolderData = $this->dbFolders->getFolder($folderId);
		$arrEmailListData = $this->dbRouting->getAllMail($folderId, $sortOrder, $filter);
		
		$this->setVarByRef('arrFolderData', $arrFolderData);
		$this->setVarByRef('arrEmailListData', $arrEmailListData);
		$this->setVarByRef('folderId', $folderId);
		$this->setVarByRef('arrFolderList', $arrFolderList);

		return 'internalmail_tpl.php';
	}
	
	/**
	* Method to view/read the email
	* @access private
	*/
	private function __readmail()
	{	
		$routingId = $this->getParam('routingid');
		$this->dbRouting = $this->newObject('dbrouting', 'internalmail');
		$this->dbemail = $this->newObject('dbemail', 'internalmail');
		$route = $this->dbRouting->getMail($routingId);
		
		$msgid = $route['email_id'];
		$msg = $this->dbemail->getMail($msgid);
		$this->dbRouting->markAsRead($routingId);
		
		$this->setVarByRef('routing', $route);
		$this->setVarByRef('message', $msg);
		return 'readmail_tpl.php';
	}
	
	/**
	* Method to redirect the user to the login screen
	* @access private
	*/
	private function goToLogin()
	{	
		return $this->nextAction('home', array('error' => 'Login is required'));
	}
	
	/**
	* Method to view a course and personal files and folder
	* @access private
	*/
	private function __filemanager()
	{	//My files
		$this->objFiles = $this->getObject('dbfile', 'filemanager');
        $this->objFolders = $this->getObject('dbfolder', 'filemanager');
		$this->objFileIcons = $this->getObject('fileicons', 'files');
		// Get Folder Details
        $folderpath = 'users/'.$this->objUser->userId();
        $folderId = $this->objFolders->getFolderId($folderpath);
		$folderId = $this->getParam('folderid', $folderId);
		$folders = $this->objFolders->getSubFolders($folderId);
		$singlefolder = $this->objFolders->getFolderPath($folderId);
		$files = $this->objFiles->getFolderFiles($singlefolder);
		$currid = $this->getSuperFolder($folderId);
		$foldername = basename($singlefolder);
		if($foldername == basename($folderpath))
		{
			$foldername = $this->objLanguage->languageText('mod_uwcelearningmobile_wordmyfiles', 'uwcelearningmobile');
		}
		$this->setVarByRef('currname', $foldername);
		$this->setVarByRef('currfolder', $currid);
		$this->setVarByRef('files', $files);
		$this->setVarByRef('folders', $folders);
		
		//Get Context Files
		if ($this->contextCode != NULL)
		{
			// Get Folder Details
		    $coursefolderpath = 'context/'.$this->contextCode;
		    $coursefolderId = $this->objFolders->getFolderId($coursefolderpath);
			$coursefolderId = $this->getParam('coursefolderid', $coursefolderId);
			$coursefolders = $this->objFolders->getSubFolders($coursefolderId);
			$coursesinglefolder = $this->objFolders->getFolderPath($coursefolderId);
			$coursefiles = $this->objFiles->getFolderFiles($coursesinglefolder);
			$coursecurrid = $this->getSuperFolder($coursefolderId);
			$coursefoldername = basename($coursesinglefolder);
			if($coursefoldername == basename($coursefolderpath))
			{
				$coursefoldername = $this->contextTitle.' - Files';
			}
			$this->setVarByRef('coursecurrname', $coursefoldername);
			$this->setVarByRef('coursecurrfolder', $coursecurrid);
			$this->setVarByRef('coursefiles', $coursefiles);
			$this->setVarByRef('coursefolders', $coursefolders);
			}
		return 'filemanager_tpl.php';
	}

	/**
	* Method to get the folder's super folder
	* @access private
	*/
	private function getSuperFolder($folderid){
		$folder = $this->objFolders->getFolder($folderid);
		$del = '/'.basename($folder['folderpath']);
		$path = explode($del, $folder['folderpath']);
		return $this->objFolders->getFolderId($path[0]);
	}
}
?>
