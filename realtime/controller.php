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
        $this->userId = $this->objUser->userId();
        $this->userName = $this->objUser->username($this->userId);
        if($this->objUser->isAdmin()){
            $this->userLevel = 'admin';
        }elseif($this->objUser->isLecturer()){
            $this->userLevel = 'lecturer';
        }elseif($this->objUser->isStudent()){
            $this->userLevel = 'student';
        }else{
            $this->userLevel = 'guest';
        }        
        
        $this->objConfig = $this->getObject('altconfig', 'config');
        $location = "http://". $_SERVER['HTTP_HOST'];
        $this->whiteboardURL = $location.$this->getResourceUri('whiteboard', 'realtime');
        $this->voiceURL = $location.$this->getResourceUri('voice', 'realtime');
    }
    
    /**
    * Method to process actions to be taken
    *
    * @param string $action String indicating action to be taken
	*/
    function dispatch($action=Null)
    {
		switch($action)
		{
		  case 'classroom':
		     return "realtime-classroom_tpl.php";
		
		  case 'whiteboard':
		     return "realtime-whiteboard_tpl.php";

		  case 'voice':
		     return "realtime-voice_tpl.php";
	
		  default:
		     return "realtime_tpl.php";
		}
	
    }
}
?>