<?php
/* -------------------- stories class extends controller ----------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
} 

/**
* 
* Test controller for date time. It really serves
* no purpose other than testing.
* 
* @author Derek Keats 
*/
class datetime extends controller {
    function init()
    { 
        $this->objCal = $this->getObject("simplecal", "dateTime");
        $this->objCal->callingModule = "datetime";
//        $this->objCal->startweek="sun"; //I cannot get this to work correctly
    } 
    /**
    * *The standard dispatch method for the module. The dispatch() method must 
    * return the name of a page body template which will render the module 
    * output (for more details see Modules and templating)
    */
    function dispatch($action)
    {
        return 'main_tpl.php';
    } 
} 

?>