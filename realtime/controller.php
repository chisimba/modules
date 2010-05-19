<?php

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
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
class realtime extends controller {
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

    /**
     * the context obj
     */
    public $objContext;
    function init() {

        $this->objRealtimeUtil= $this->getObject('realtimeutil');
        $this->objUser = $this->newObject('user', 'security');
        $this->objContext=$this->getObject('dbcontext','context');
        $this->objLanguage=$this->getObject('language','language');

    }


    public function dispatch($action) {
        if(!$this->objContext->isInContext()) {
            return "needtojoin_tpl.php";
        }

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
        $this->objRealtimeUtil->generateJNLP();
        return "home_tpl.php";
    }


}
?>
