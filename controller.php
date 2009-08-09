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
    * @author David Wafula
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
         * @access public
         * @var contexctcode
         */
    public $contextCode;

        /**
         * This points to module root path
         * @var <type>
         */
    public $moduleRootPath;

        /**
         * config object
         * @var <type>
         */
    public $objAltConfig;

        /**
         * Link object
         * @var <type>
         */
    public $objLink;

        /**
         * JOD doc converter path
         * @var <type>
         */
    public $jodconverterPath;

        /**
         * Files object
         * @var <type>
         */
    public $objFiles;

        /**
         *  convert obj
         * @var <type>
         */
    public $converter;

        /**
         * Upload path
         * @var <type>
         */
    public $uploadPath;
        /**
         *For starting the slide server
         * @var <type>
         */
    public  $realtimeManager;

        /**
         * link to requirements test
         * @var String
         */
    public $reqTest;

        /**
         *unique session id
         * @var String
         */
    public $sessionId;

        /**
         * This is the session title
         * @var String
         */
    public $sessionTitle;

    /**
     *Need a room, everything rotates around a room
     * @var <type>
     */
    public $room='default';


/**
 * flag to shw whether we are starting a presentation or joining one
 * @var <type>
 */
    public $ispresenter='yes';

    /**
     * whether password is required for the meeting or not
     * @var <type>
     */

    public $passwordrequired='no';
    /**
     *unique id of jnlp generated
     * @var <type>
     */

    public $jnlpId="";
    /**
     *the id generated as short url
     * @var <type>
     */

    public $joinMeetingId="none";
/**
   *Holds reference to class dbschedules used to manipulating entries
   * in tbl_virtualclassroon_schedules table
   * @var <type>
   */
    public $objDbSchedules;


/**
 *holds reference to schedule members
 * @var <type>
 */
    public $objDbScheduleMembers;
    function init()
    {
        $this->objDbSchedules=$this->getObject('dbschedules');
        $this->objDbScheduleMembers=$this->getObject('dbschedulemembers');
        $this->objLink= $this->getObject('link', 'htmlelements');
        //Get configuration class
        $this->objConfig =$this->getObject('config','config');

        $this->objAltConfig = $this->getObject('altconfig','config');

        //Get language class
        $this->objLanguage = $this->getObject('language', 'language');

        //Get the activity logger class
        $this->config = $this->getObject('config','config');
        $this->objLog = $this->getObject('logactivity', 'logger');

        //Log this module call
        $this->objLog->log();

        $this->objStarter= $this->getObject('realtimestarter');
        $this->realtimeManager = $this->getObject('realtimemanager','webpresent');
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

        $this->sessionId="default";
        $this->sessionTitle="Default Session";
        $this->jnlpId=$this->realtimeManager->randomString(20);
    }


    public function dispatch($action) {

    /*
    * Convert the action into a method (alternative to
    * using case selections)
    */
        $method = $this->getMethod($action);
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
  * @return string the name of the method
  *
  */
    function getMethod(& $action) {
        if ($this->validAction($action)) {
            return '__'.$action;
        }
        else {
            return '__home';
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
    function validAction(& $action) {
        if (method_exists($this, '__'.$action)) {
            return TRUE;
        }
        else {
            return FALSE;
        }
    }

    /**
     *Takes us to default home pages
     * @return <type>
     */
    function __home() {
        return "home_tpl.php";
    }

    function __addroommember(){
        $id=$this->getParam('id');
        $this->setVarByRef('sessionid',$id);
        return "sessionmembers_tpl.php";

    }
    function __deleteroommember(){
        $userid=$this->getParam('userid');
        $sessionid=$this->getParam('sessionid');
        $this->objDbScheduleMembers->deleteRoomMember($userid);
        $this->setVarByRef('sessionid',$sessionid);
        return "sessionmembers_tpl.php";

    }
    function __saveroommember(){
        $sessionid=$this->getParam('sessionid');
        $userid=$this->getParam('userfield');
        $this->objDbScheduleMembers->addRoomMember($userid,$sessionid);
        $this->setVarByRef('sessionid',$sessionid);
        return "sessionmembers_tpl.php";
    }
    /**
     * Ssave the newly created  session details into the database
     */
    function __saveschedule(){
        $sessionTitle=$this->getParam('title');
        $date=$this->getParam('date');
        $starttime=$this->getParam('starttime');
        $endtime=$this->getParam('endtime');
        $this->objDbSchedules->addSchedule(
            $sessionTitle,
            $date,
            $starttime,
            $endtime
        );
        $this->nextAction(NULL);
    }
   function __updatesession(){
        $sessionTitle=$this->getParam('title');
        $date=$this->getParam('date');
        $starttime=$this->getParam('starttime');
        $endtime=$this->getParam('endtime');
        $id=$this->getParam('sessionid');
        $this->objDbSchedules->updateSchedule(
            $sessionTitle,
            $date,
            $starttime,
            $endtime,
            $id
        );
        $this->nextAction(NULL);
    }
    function __deletesession(){
        $id=$this->getParam('sessionid');
        $this->objDbSchedules->deleteSchedule($id);
        $this->objDbScheduleMembers->deleteSession($id);
        $this->nextAction(NULL);
    }

}
?>
