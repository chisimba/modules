<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
	die("You cannot view this page directly");
}
// end security check
/**
 *  $Id$
 *
 *  Copyright (C) GNU/GPL AVOIR 2007
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
/**
* Realtime Controller
* This class controls all functionality to run the realtime module.
* @author Jessie
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
        $this->objGroups = $this->getObject('groupadminmodel', 'groupadmin');


	// The realtime class

	// Get action from input parameter
	$this->action = $this->getParam('action', NULL);
    }
    public function getGroupID($groupName)
    {
     return $this->objGroups->getLeafId(array($this->contextcode, $groupName));
    }
    
    /**
     * Method to determine the user level
     *
     * @param string $userName 
     * @return string $userLevel
     */
    public function getUserLevel($userName)
    {
        // First, check for admin -- the most access
        if ($this->objUser->isAdmin()) {
            return "admin";
        } else {
            // Get userKey from PK, which needs user ID from username
            $userid = $this->objUser->getUserId($userName);
            $uKey = $this->objUser->PKId($userid);

            // Check if lecturer first, then student
            if ($this->objGroups->isGroupMember($uKey, $this->getGroupID("Lecturers")))
	        {
        		return "lecturer";
            } else if ($this->objGroups->isGroupMember($uKey, $this->getGroupID("Students") ))
	        {
        		return "student";
	        }
        }
		// if user is in no group, guest -- the least access
        return "guest";
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
	$uName =  $this->objUser->userName();

		if ($this->objUser->isLoggedIn())
		{
		   $this->setVar('userName', $uName);
		} else {
		   $this->setVar('userName', "Guest");
		}
		$this->setVar('userLevel', $this->getUserLevel($uName));
	
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
