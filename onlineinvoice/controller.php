<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
	die("You cannot view this page directly");
}
// end security check

/**
* Calendar Controller
* This class controls all functionality to run the calendar module. It now integrates user calendar and contextcalendar
* @author Tohir Solomons
* @copyright (c) 2004 University of the Western Cape
* @package calendar
* @version 2
*/
class onlineinvoice extends controller
{

    /**
	* Constructor method to instantiate objects and get variables
	*/
    function init()
    {
        $this->dbTev =& $this->getObject('dbtev');
    
        $this->objLanguage =& $this->getObject('language', 'language');
        // User Details
        $this->objUser =& $this->getObject('user', 'security');
        $this->setVarByRef('fullname', $this->objUser->fullname());
        $this->userId = $this->objUser->userId();
		
	}
    
    /**
	* Method to process actions to be taken
    *
    * @param string $action String indicating action to be taken
	*/
    function dispatch($action=Null)
    {
        $this->setLayoutTemplate('calendar_layout_tpl.php');
        
        switch ($action)
        {
            default:
                return 'main_tpl.php';
        }
    }
}

?>
