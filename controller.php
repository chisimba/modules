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
* @author Jessie
* @copyright (c) 2006 University of the Western Cape
* @package realtime
* @version 1
*/
class realtime extends controller
{
    var $action;

    /**
     * Constructor method to instantiate objects and get variables
     */
    function init()
    {
        //Get the activity logger class
        $this->objLog = $this->newObject('logactivity', 'logger');
        //Log this module call
        $this->objLog->log();

	// classes we need
	$this->objUser = $this->newObject('user', 'security');
        $this->objConfig = $this->getObject('altconfig', 'config');

	// The realtime class

	// Get action from input parameter
	$this->action = $this->getParam('action', NULL);
    }
    
    /**
    * Method to process actions to be taken
    *
    * @param string $action String indicating action to be taken
	*/
    function dispatch($action=Null)
    {
	    $this->objLog->log();
		$modUri = $this->objConfig->getItem('MODULE_URI');

		if ($this->objUser->isLoggedIn())
		{
		   $this->setVar('userName', $this->objUser->userName());
		   $this->setVar('userLevel', $this->objUser->userLevel());
		} else {
		   $this->setVar('userName', "Guest");
		   $this->setVar('userLevel', "Guest");
		}
	
		switch($action)
		{
		  case 'whiteboard':

		     $this->setVar('whiteboardUrl',
				"http://". $_SERVER['HTTP_HOST']."/".$modUri."realtime/whiteboard");
		     return "realtime-whiteboard_tpl.php";

		  case 'voice':
		     $this->setVar('voiceUrl',
				"http://". $_SERVER['HTTP_HOST']."/".$modUri."realtime/voice");
		     $this->setVar('realtimeUrl',
				"http://". $_SERVER['HTTP_HOST']."/".$modUri."realtime");
	
		     return "realtime-voice_tpl.php";
	
		  default:
		     return "realtime_tpl.php";
		}
	
    }
}
