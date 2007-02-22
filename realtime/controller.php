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

	// The realtime classes

	// Get action from input parameter
	$this->action = $this->getParam('action', NULL);
    }
    
    /**
    * Method to process actions to be taken
    *
5A
    * @param string $action String indicating action to be taken
	*/
    function dispatch($action=Null)
    {
        $this->objLog->log();
	if ($this->objUser->isLoggedIn())
	   $this->setVar('userName', $this->objUser->userName());
	else
	   $this->setVar('userName', "Guest");

	switch($action)
	{
	  case 'voice':
	  default:
	     $modUri =  $this->objConfig->getItem('MODULE_URI');
	     $this->setVar('appUrl',
			"http://". $_SERVER['HTTP_HOST'].":8080".
			substr($modUri, strpos($modUri,"/")) 
			. "realtime/voice");

	     return "realtime-voice_tpl.php";
	}

	return "realtime_tpl.php";
    }
}
