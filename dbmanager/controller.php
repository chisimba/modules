<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
	die("You cannot view this page directly");
}
// end security check

/**
* DB Manager Controller
*
* @author Paul Scott
* @copyright (c) 2004 University of the Western Cape
* @package dbmanager
* @version 1
*/
class dbmanager extends controller
{

    /**
	* Constructor method to instantiate objects and get variables
	*/
    function init()
    {
        $this->manager =& $this->getObject('dbmanagerdb');

        // User Details
        $this->objUser =& $this->getObject('user', 'security');
        $this->userId =& $this->objUser->userId();

        // Load Language Class
        $this->objLanguage = &$this->getObject('language', 'language');
        $this->setVarByRef('objLanguage', $this->objLanguage);

        //Get the activity logger class
        $this->objLog=$this->newObject('logactivity', 'logger');
        //Log this module call
        $this->objLog->log();
    }

    /**
	* Method to process actions to be taken
    *
    * @param string $action String indicating action to be taken
	*/
    function dispatch($action=Null)
    {
        switch ($action)
        {
            default:
                die("choose action");
                break;

            case 'dumpdb':
                $this->manager->getSchema();
                return ;

            case 'parsefile':
                return ;

            case 'getdefinition':
                return;


        }
    }
}
?>