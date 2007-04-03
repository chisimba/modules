<?php

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
	die("You cannot view this page directly");
}
// end security check
/**
* Realtime Controller
* This class controls all functionality to run the realtime module.
* @package realtime
* @version $Id$
*/
class realtime extends controller
{
	/**
	* @var object $objUser: The user class in the security module
	* @access public
	*/
	public $objUser;

	/**
	* @var string $userId: The user id of the currently logged in user
	* @access public
	*/
	public $userId;

	/**
	* @var string $userName: The username of the currently logged in user
	* @access public
	*/
	public $userName;

	/**
	* @var string $userLevel: The user's access level
	* @access public
	*/
	public $userLevel;

	/**
	* @var object $objConfig: The altconfig class in the config module
	* @access public
	*/
	public $objConfig;

	/**
	* @var object $objLog: The logactivity class in the logger module
	* @access public
	*/
	public $objLog;

	/**
	* @var object $whiteboardURL: The URL for the whiteboard applet files
	* @access public
	*/
	public $whiteboardURL;

	/**
	* @var object $voiceURL: The URL for the voice applet files
	* @access public
	*/
	public $voiceURL;

	/**
	 * @access public
	 * @var contexctcode
	 */
	public $contextCode;

	public $realtimeControllerURL;

	/**
	 * Constructor method to instantiate objects and get variables
	 */
	function init()
	{
		$this->objLanguage =& $this->getObject('language', 'language');
		//Get the activity logger class
		$this->objLog = $this->newObject('logactivity', 'logger');
		//Log this module call
		$this->objLog->log();
		$this->objrealtime = & $this->getObject('dbrealtime');
		// classes we need
		$this->objUser = $this->newObject('user', 'security');
		$this->userId = $this->objUser->userId();
		$this->userName = $this->objUser->username($this->userId);
		if ($this->objUser->isAdmin())
		{
			$this->userLevel = 'admin';
		}
		elseif ($this->objUser->isLecturer())
		{
			$this->userLevel = 'lecturer';
		}
		elseif ($this->objUser->isStudent())
		{
			$this->userLevel = 'student';
		} else
		{
			$this->userLevel = 'guest';
		}
		$this->objContext = $this->getObject('dbcontext', 'context');
		$this->objConfig = $this->getObject('altconfig', 'config');
		$location = "http://" . $_SERVER['HTTP_HOST'];
		$this->whiteboardURL = $location . $this->getResourceUri('whiteboard', 'realtime');
		$this->voiceURL = $location . $this->getResourceUri('voice', 'realtime');
		$this->realtimeControllerURL = $location . "/chisimba_framework/app/index.php?module=realtime";
	}

	/**
	* Method to process actions to be taken
	*
	* @param string $action String indicating action to be taken
	*/
	function dispatch($action = Null)
	{
		$this->contextCode = $this->objContext->getContextCode();
		switch ($action)
		{
			case 'classroom' :
				return $this->showClassRoom($this->contextCode);

			case 'jmfinstall' :
				return "realtime-jmfinstall_tpl.php";

			case 'voice' :
				return $this->showVoiceApplet($this->contextCode);

			case 'whiteboard' :
				return "realtime-whiteboard_tpl.php";
			case 'requesttoken' :
				return $this->requestToken($this->userId, $this->userLevel, $this->contextCode);
			case 'releasetoken' :
				return $this->releaseToken($this->userId, $this->userLevel, $this->contextCode);
			case 'startconversation' :
				return $this->startConversation($this->userId, $this->userLevel, $this->contextCode);
			case 'stopconversation' :
				return $this->stopConversation($this->userId, $this->userLevel, $this->contextCode);
			case 'joinconversation' :
				return $this->joinConversation($this->userId, $this->userLevel, $this->contextCode);
			case 'leaveconversation' :
				return $this->leaveConversation($this->userId, $this->contextCode);
			case 'checktoken' :
				return $this->checkToken($this->userId, $this->userLevel, $this->contextCode);
			case 'checkturn' :
				return $this->checkToken($this->userId, $this->userLevel, $this->contextCode);
			case 'voicequeue' :
				return $this->makeQueue($this->userId, $this->userLevel, $this->contextCode);
			case 'assigntoken' :
				return $this->assignToken($this->getParam('id'), $this->contextCode);	
			default :
				return "realtime_tpl.php";
		}

	}

	function requestToken($userid, $userlevel, $contextcode)
	{
		if (empty ($userid) || empty ($userlevel) || empty ($contextcode))
		{
			return "realtime_tpl.php";
		} else
		{
			$hasToken = $this->objrealtime->requestToken($userid, $userlevel, $contextcode);
			$this->setVar('hastoken', $hasToken);
			return "redirect_tpl.php";
		}
	}

	function releaseToken($userid, $userlevel, $contextcode)
	{
		$hasToken = $this->objrealtime->releaseToken($userid, $contextcode);
		$this->setVar('hastoken', $hasToken);
		return "redirect_tpl.php";

	}

	function startConversation($userid, $userlevel, $contextcode)
	{
		if (empty ($userid) || empty ($userlevel) || empty ($contextcode))
		{
			$hasToken = "FAILURE";
			$this->setVar('hastoken', $hasToken);
			return "redirect_tpl.php";
		} else
		{
			$hasToken = $this->objrealtime->startConversation($userid, $contextcode);
			$this->setVar('hastoken', $hasToken);
			return "redirect_tpl.php";
		}
	}

	function stopConversation($userid, $userlevel, $contextcode)
	{
		$hasToken = $this->objrealtime->stopConversation($userid, $contextcode);
		$this->setVar('hastoken', $hasToken);
		return "redirect_tpl.php";
	}

	function leaveConversation($userid, $contextcode)
	{
		$hasToken = $this->objrealtime->leaveConversation($userid,  $contextcode);
		$this->setVar('hastoken', $hasToken);
		return "redirect_tpl.php";
	}

	function joinConversation($userid, $userlevel, $contextcode)
	{
		$hasToken = $this->objrealtime->joinConversation($userid,  $contextcode);
		$this->setVar('hastoken', $hasToken);
		return "redirect_tpl.php";
	}

	function checkToken($userid, $userLevel, $contextcode)
	{
		$hasToken = $this->objrealtime->checkToken($userid, $contextcode);
		$this->setVar('hastoken', $hasToken);
		return "redirect_tpl.php";
	}
	
	function makeQueue($userid, $userLevel, $contextCode)
	{
		
		if(eregi("lecturer", $userLevel)){
			
			$users = $this->objrealtime->makeQueue($userid, $userLevel, $contextCode);
			$this->setVar('hastoken', $users);

			return "redirect_tpl.php";
		}else { 
			$notAllowed = "You,are,not-authorised,to,view-this,page,aka-";
			$this->setVar('hastoken', $notAllowed);

			return "redirect_tpl.php";
		}
	}
	
	function assignToken($userId, $contextcode)
	{
		$hasToken = $this->objrealtime->assignToken($userId, $contextcode);
		$this->setVar('hastoken', $hasToken);
		return "redirect_tpl.php";
	}
	
	function showVoiceApplet($contextcode)
	{
		if(empty($contextcode)){
			$this->setVar("noContextCode", $this->objLanguage->languageText('mod_realtime_nocontextcode', 'realtime'));
		}
		return "realtime-voice_tpl.php";
	}
	
	function showClassRoom($contextcode)
	{
		if(empty($contextcode)){
			$this->setVar("noContextCode", $this->objLanguage->languageText('mod_realtime_nocontextcode', 'realtime'));
		} 
		return "realtime-classroom_tpl.php";
	}
}
?>