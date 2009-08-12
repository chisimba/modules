<?php

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}
// end security check

class fossad extends controller {

    function init() {
        $this->objUser = $this->getObject ( 'user', 'security' );
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objLog = $this->getObject('logactivity', 'logger');
        $this->objLog->log();
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

    function __home() {
        return "home_tpl.php"; //"courseproposallist_tpl.php";
    }
    /**
     * save a new registration
     */
    function __register() {
        $firstame=$this->getParam('firstname');
        $lastname=$this->getParam('lastname');
        $company=$this->getParam('company');
        $email=$this->getParam('emailfield');
        $reg = $this->getObject('dbregistration');
        if($reg->addRegistration($firstame,$lastname,$company,$email)){
            $this->sendMail($email);
            $this->nextAction("success");
        }
        else{
            $this->nextAction(NULL);
        }
    }


    function __success(){
        return "success_tpl.php";
    }

    /**
     *  Sends the email to the newly registered member
     */
    function sendMail($to){
        $objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
        $contactemail=$objSysConfig->getValue('CONTACT_EMAIL', 'fossad');
        $subject=$objSysConfig->getValue('EMAIL_SUBJECT', 'fossad');
        $body=$objSysConfig->getValue('EMAIL_BODY', 'fossad');
        $emailName=$objSysConfig->getValue('EMAIL_NAME', 'fossad');
        $objMailer = $this->getObject('email', 'mail');
        $objMailer->setValue('to', array($to));
        $objMailer->setValue('from', $contactemail);
        $objMailer->setValue('fromName', $emailName);
        $objMailer->setValue('subject', $subject);
        $objMailer->setValue('body', $body);
        $objMailer->send();
    }
      /**
     * Overridden method to determine whether or not login is required
     *
     * @return FALSE
     */
    public function requiresLogin() {
        switch ($this->getParam('action')) {
            case 'admin':
                return TRUE;
                default:
                    return FALSE;
                }
            }
        }