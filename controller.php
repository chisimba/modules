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
        
        public $presentationsURL;

        public $moduleRootPath;

        public $objAltConfig;

        public $objLink;
	/**
	 * Constructor method to instantiate objects and get variables
	 */
	function init()
	{
		$this->objLink=& $this->newObject('link', 'htmlelements');
		//Get configuration class
		$this->objConfig =& $this->getObject('config','config');
		
	        $this->objAltConfig =& $this->getObject('altconfig','config');
		
	        //Get language class
		$this->objLanguage =& $this->getObject('language', 'language');
		
		//Get the activity logger class
		$this->config =& $this->getObject('config','config');
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
		$this->presentationsURL = $location . $this->getResourceUri('presentations', 'realtime');
                $this->moduleRootPath=$this->objAltConfig->getModulePath();
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

	                case 'audience_applet' :
				return "realtime-presentations-audience-applet_tpl.php";
			 case 'presenter_applet' :
				return "realtime-presentations-presenter-applet_tpl.php";
			case 'whiteboard' :
				return "realtime-whiteboard_tpl.php";
			case 'presentations' :
                                 $this->generateJnlp();
				return "realtime-presentations_tpl.php";
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

/**
 * Function to generate jnlp file for webstart 
 */

function generateJnlp(){

//first, the one for presenter
$fp = fopen($this->moduleRootPath."realtime/resources/presentations/Presenter.jnlp", "w") or die("Couldn't create new file");
fwrite($fp, "<?xml version=\"1.0\" encoding=\"utf-8\"?>");
fwrite($fp, "\n<jnlp spec=\"1.0+\"      codebase=\"". $this->presentationsURL."\"      href=\"Presenter.jnlp\">");
fwrite($fp, "\n<information>");
fwrite($fp, "\n<title>Chisimba Presentations [Presenter]</title>");
fwrite($fp, "\n <vendor>AVOIR</vendor>");
fwrite($fp, "\n <description>Chisimba OO realtime presentations</description>");
fwrite($fp, "\n <homepage href=\"http://avoir.uwc.ac.za\"/>");
fwrite($fp, "\n<description kind=\"short\">Chisimba OO Presentations</description>");
fwrite($fp, "\n </information>");
fwrite($fp, "\n <application-desc main-class=\"avoir.realtime.presentations.client.presenter.MainClass\">");
fwrite($fp, "\n	<argument>".$_SERVER['REMOTE_ADDR']."</argument>");
fwrite($fp, "\n	<argument>1962</argument>");
fwrite($fp, "\n	<argument>".$this->objAltConfig->getcontentBasePath()."</argument>");
fwrite($fp, "\n	<argument>".$this->userId."</argument>");
fwrite($fp, "\n</application>");
fwrite($fp, "\n <resources>");
fwrite($fp, "\n <jar href=\"presentations-client.jar\"/>");
fwrite($fp, "\n <j2se version=\"1.5+\"");
fwrite($fp, "\nhref=\"http://java.sun.com/products/autodl/j2se\" initial-heap-size=\"128m\" max-heap-size=\"512m\"/>");
fwrite($fp, "\n <extension name=\"jgoodies\" href=\"jgoodies.jnlp\"/>");
fwrite($fp, "\n <security>");
fwrite($fp, "\n<all-permissions/>");
fwrite($fp, "\n </security>");
fwrite($fp, "\n<application-desc main-class=\"avoir.realtime.presentations.client.presenter.MainClass\"/>");
fwrite($fp, "\n</jnlp>");
fclose($fp); 

//audience
$fp = fopen($this->moduleRootPath."realtime/resources/presentations/Audience.jnlp", "w") or die("Couldn't create new file");
fwrite($fp, "<?xml version=\"1.0\" encoding=\"utf-8\"?>");
fwrite($fp, "\n<jnlp spec=\"1.0+\"      codebase=\"". $this->presentationsURL."\"      href=\"Audience.jnlp\">");
fwrite($fp, "\n<information>");
fwrite($fp, "\n<title>Chisimba Presentations</title>");
fwrite($fp, "\n <vendor>AVOIR</vendor>");
fwrite($fp, "\n <description>Chisimba OO realtime presentations</description>");
fwrite($fp, "\n <homepage href=\"http://avoir.uwc.ac.za\"/>");
fwrite($fp, "\n<description kind=\"short\">Chisimba OO Presentations</description>");
fwrite($fp, "\n </information>");
fwrite($fp, "\n <application-desc main-class=\"avoir.realtime.presentations.client.ClientViewer\">");
fwrite($fp, "\n	<argument>".$_SERVER['REMOTE_ADDR']."</argument>");
fwrite($fp, "\n	<argument>1962</argument>");
fwrite($fp, "\n	<argument>".$this->objAltConfig-> getcontentBasePath()."</argument>");
fwrite($fp, "\n	<argument>".$this->userId."</argument>");
fwrite($fp, "\n</application>");
fwrite($fp, "\n <resources>");
fwrite($fp, "\n <jar href=\"presentations-client.jar\"/>");
fwrite($fp, "\n <j2se version=\"1.5+\"");
fwrite($fp, "\nhref=\"http://java.sun.com/products/autodl/j2se\" initial-heap-size=\"128m\" max-heap-size=\"512m\"/>");
fwrite($fp, "\n <extension name=\"jgoodies\" href=\"jgoodies.jnlp\"/>");
fwrite($fp, "\n <security>");
fwrite($fp, "\n<all-permissions/>");
fwrite($fp, "\n </security>");
fwrite($fp, "\n<application-desc main-class=\"avoir.realtime.presentations.client.ClientViewer\"/>");
fwrite($fp, "\n</jnlp>");
fclose($fp); 

//for jgoodies
$fp = fopen($this->moduleRootPath."realtime/resources/presentations/jgoodies.jnlp", "w") or die("Couldn't create new file");
fwrite($fp, "\n<?xml version=\"1.0\" encoding=\"UTF-8\"?>");
fwrite($fp, "\n<jnlp spec=\"1.0+\" codebase=\"". $this->presentationsURL."\" href=\"jgoodies.jnlp\">");
fwrite($fp, "\n  <information>");
fwrite($fp, "\n      <title>JGoodies</title>");
fwrite($fp, "\n      <vendor>JGoodies</vendor>");
fwrite($fp, "\n   </information>");
fwrite($fp, "\n   <resources>");
fwrite($fp, "\n        <jar href=\"forms-1.1.0.jar\"/> ");  
fwrite($fp, "\n   </resources>");
fwrite($fp, "\n   <component-desc/>");
fwrite($fp, "\n</jnlp>");

fclose($fp); 
}
    /**
     * Informs the server that a user is requesting a voice token, assigns token to User if the token is
     * available.
     */	
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

    /**
     * Inform applet that User has released token.
     */
	function releaseToken($userid, $userlevel, $contextcode)
	{
		$hasToken = $this->objrealtime->releaseToken($userid, $contextcode);
		$this->setVar('hastoken', $hasToken);
		return "redirect_tpl.php";

	}

    /**
     * Informs server that lecturer would like to start a conversation.
     */
	function startConversation($userid, $userlevel, $contextcode)
	{
		if (empty ($userid) || empty ($userlevel) || empty ($contextcode))
		{
			$hasToken = "FAILURE";
			$this->setVar('hastoken', $hasToken);
			return "redirect_tpl.php";
		} else
		{
			$this->deleteFilesInServer();
			$hasToken = $this->objrealtime->startConversation($userid, $contextcode);
			$this->setVar('hastoken', $hasToken);
			return "redirect_tpl.php";
		}
	}

    /**
     * Informs the server that the lecturer is stopping a conversation.
     */
	function stopConversation($userid, $userlevel, $contextcode)
	{
		$hasToken = $this->objrealtime->stopConversation($userid, $contextcode);
		$this->setVar('hastoken', $hasToken);
		return "redirect_tpl.php";
	}

    /**
     * Informs server that user wants to leave a conversation.
     */	
	function leaveConversation($userid, $contextcode)
	{
		$hasToken = $this->objrealtime->leaveConversation($userid,  $contextcode);
		$this->setVar('hastoken', $hasToken);
		return "redirect_tpl.php";
	}

    /**
     * Informs server that a user wants to join a conversation.
     */
	function joinConversation($userid, $userlevel, $contextcode)
	{
		$hasToken = $this->objrealtime->joinConversation($userid,  $contextcode);
		$this->setVar('hastoken', $hasToken);
		return "redirect_tpl.php";
	}

	/**
	 *	checks availability of token
	 */
	function checkToken($userid, $userLevel, $contextcode)
	{
		$hasToken = $this->objrealtime->checkToken($userid, $contextcode);
		$this->setVar('hastoken', $hasToken);
		return "redirect_tpl.php";
	}
	
	/**
	 * creates queue(list) of user's who request token
	 */
	function makeQueue($userId, $userLevel, $contextCode)
	{
		
		if(eregi("lecturer", $userLevel)){
			
			$users = $this->objrealtime->makeQueue($userId, $userLevel, $contextCode);
			$this->setVar('hastoken', $users);

			return "redirect_tpl.php";
		}else { 
			$notAllowed = "You,are,not-authorised,to,view-this,page,aka-";
			$this->setVar('hastoken', $notAllowed);

			return "redirect_tpl.php";
		}
	}

    /**
     * Informs the server that the lecturer is assigning the token to a particular user.
     */	
	function assignToken($userId, $contextcode)
	{
		$hasToken = $this->objrealtime->assignToken($userId, $contextcode);
		$this->setVar('hastoken', $hasToken);
		return "redirect_tpl.php";
	}
	
	/**
	 *	deletes audio files of the previous conversation when the next conversation stars.
	 */
	function deleteFilesInServer(){
		$this->basePath = $this->objConfig->getModulePath();
		$this->modulePath = $this->basePath."realtime/resources/voice/audio";
		$this->file = "";
		if($this->handle = opendir($this->modulePath)){
			while(false !== ($this->file = readdir($this->handle))){
				if(ereg(".gsm", $this->file))
					unlink($this->modulePath.'/'.$this->file);
			}
			closedir($this->handle);
		}
	}
	
	/**
	 * shows voice applet to user if user first entered a context.
	 */
	function showVoiceApplet($contextcode)
	{
		if(empty($contextcode)){
			$this->setVar("noContextCode", $this->objLanguage->languageText('mod_realtime_nocontextcode', 'realtime'));
		}
		return "realtime-voice_tpl.php";
	}
	
	/**
	 * shows classroom applet to user if the user first entered a context
	 */
	function showClassRoom($contextcode)
	{
		if(empty($contextcode)){
			$this->setVar("noContextCode", $this->objLanguage->languageText('mod_realtime_nocontextcode', 'realtime'));
		} 
		return "realtime-classroom_tpl.php";
	}
}
?>
