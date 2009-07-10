<?php

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}
// end security check

class gifts extends controller {

    function init() {
        //Get language class
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objDB = $this->getObject("dbgifttable");
        $this->objGift = $this->getObject("giftops");
        $this->objUser = $this->getObject('user', 'security');
        $this->objLog->log();
    }

    /*
     * Standard Dispatch Function for Controller
     * @param <type> $action
     * @return <type>
     */
    public function dispatch($action) {

        /*
        * Convert the action into a method
        *
        */
        $method = $this->getMethod($action);
        /*
        * Return the template determined by the method resulting
        * from action
        */
        return $this->$method();
    }

    /*
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

    /*
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

    /*
     * Method to show the Home Page of the Module
     */
    function __home() {
        return "home_tpl.php";
    }

    /*
     * Method to show add gift page of the module
     */
    function __addedit() {
        /*
         * if edit, edit param set to true
         *          id present
         * if add, edit param set to false
         *         there is no id
         *
         */

        if($this->getParam("edit")) {
            $this->id = $this->getParam("id");
            
            $param = $this->getParam("edit");
            $this->setVarByRef("param", $param);
        }

        return "addedit_tpl.php";
    }

    function __submit() {

        // get the form data
        $donor = $this->getParam("dnvalue");
        $receiver = $this->objUser->firstName()." ".$this->objUser->surName();
        $gift = $this->getParam("gnvalue");
        $description = $this->getParam("descripvalue");
        $value = $this->getParam("gvalue");
        $state = $this->getParam("gstatevalue");

        $fields = array("donor"=>$donor, "receiver"=>$receiver, "giftname"=>$gift, "value"=>$value, "date"=>$this->objDB->now(), "state"=>$date, "approved_state"=>null, "approved_by"=>null, "other1"=>null, "other2"=>null);

        $this->objDB->insert($fields);

        return "home_tpl.php";
    }

    function __sendemail() {
        /*
         * Method to transfer the email form information to the function that
         * sends out the actual email
         *
         * @access private
         * @return name of the send email template
         */

        $subject = $this->getParam("subject");
        $body =$this->getParam("body");

        $this->sendemail($subject, $body);
        
        return "home_tpl.php";
    }
}
?>

