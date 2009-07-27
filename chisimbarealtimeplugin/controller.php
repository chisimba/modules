<?php

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}
// end security check
class chisimbarealtimeplugin extends controller {

  /**
   * holds refrence to this user's information
   * @var <type>
   */
   public $objUser;

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
   *Holds reference to class dbschedules used to manipulating entries
   * in tbl_virtualclassroon_schedules table
   * @var <type>
   */
   public $objDbSchedules;

  /**
   * @access public
   * @var contexctcode
   */
    public $contextCode;


   /**
     * config object
     * @var <type>
     */
    public $objAltConfig;

    function init() {
        $this->objUser = $this->getObject ( 'user', 'security' );
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objLog = $this->getObject('logactivity', 'logger');
        $this->objContext = $this->getObject('dbcontext', 'context');
        $this->objDbSchedules=$this->getObject('dbschedules');
        $this->objAltConfig = $this->getObject('altconfig','config');

        $this->objLog->log();
    }

    public function dispatch($action) {

        $this->contextCode = $this->objContext->getContextCode();
        if($this->contextCode == ''){
            return $this->nextAction(NULL,NULL,'postlogin');

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
        return "home_tpl.php";
    }


    /**
     * Ssave the newly created  session details into the database
     */
    function __saveschedule(){
        $sessionTitle=$this->getParam('title');
        $category=$this->getParam('category');
        $startDate=$this->getParam('startdate');
        $starttime=$this->getParam('starttime');
        $endtime=$this->getParam('endtime');

        $about=$this->getParam('about');
     
        $this->objDbSchedules->addSchedule(
            $this->contextCode,
            $sessionTitle,
            $category,
            $about,
            $startDate,
            $starttime,
            $endtime
        );
        $this->nextAction(NULL);
    }

    function __deleteschedule(){
         $id=$this->getParam('id');
         $this->objDbSchedules->deleteSchedule($id);
         $this->nextAction(NULL);
    }
}