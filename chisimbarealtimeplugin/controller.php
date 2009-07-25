<?php

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}
// end security check
class chisimbarealtimeplugin extends controller {

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
 * @access public
 * @var contexctcode
 */
public $contextCode;

    function init() {
        $this->objUser = $this->getObject ( 'user', 'security' );
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objLog = $this->getObject('logactivity', 'logger');
        $this->objContext = $this->getObject('dbcontext', 'context');
        $this->objLog->log();
    }

    public function dispatch($action) {

       $this->contextCode = $this->objContext->getContextCode();
       if($this->contextCode == ''){
           echo 'You must be in contenxt';
           die();
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
    function __home() {
        return "home_tpl.php"; //"courseproposallist_tpl.php";
    }
  function __addsession(){
    return "addsession_tpl.php";
  }
}