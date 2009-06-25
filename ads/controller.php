<?php

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}
// end security check

class ads extends controller
{

   /**
    * var that holds reference to course proposals db object
    */
    public  $objCourseProposals;
    function init()
    {
        //Get language class
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objLog = $this->getObject('logactivity', 'logger');
        $this->objCourseProposals = $this->getObject('dbcourseproposals');
        $this->objUser = $this->getObject ( 'user', 'security' );
        //Log this module call
        $this->objLog->log();

    }
       /**
         * Method to override login for certain actions
         * @param <type> $action
         * @return <type>
         */
    public function requiresLogin($action)
    {
        $required = array('overview','addcourseproposal');


        if (in_array($action, $required)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

           /**
        * Standard Dispatch Function for Controller
        * @param <type> $action
        * @return <type>
        */
    public function dispatch($action)
    {

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
    function getMethod(& $action)
    {
        if ($this->validAction($action)) {
            return '__'.$action;
        } else {
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
    function validAction(& $action)
    {
        if (method_exists($this, '__'.$action)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

          /**
       * Method to show the Home Page of the Module
       */
    function __home()
    {
        return "courseproposallist_tpl.php";
    }

    function __overview(){
        $id=$this->getParam('id');
        $data=$this->objCourseProposals->getCourseProposal($id);
        $this->setVarByRef('data', $data);
        return "overview_tpl.php";
    }

    function __addcourseproposal(){
        return "addcourseproposal_tpl.php";
    }

    function __savecourseproposal(){
       $courseTitle= $this->getParam('title');
       $courseProposalId=$this->objCourseProposals->addCourseProposal($courseTitle);
       return $this->nextAction('overview', array('id'=>$courseProposalId));
    }

    function __rulesandsyllabus(){
        return "rulesandsyllabusbook_tpl.php";
    }
}
?>
