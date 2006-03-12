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
                echo "Done";
                break;

            case 'parsefile':
                return ;

            case 'getdefinition':
                $this->manager->getDefFromDb();
                echo "Done";
                return;

            case 'createtable':
                $table = 'tbl_calendar';
$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32,
		),
	'multiday_event' => array(
		'type' => 'text',
		'length' => 1,
		'notnull' => 1,
        'default' => 0,
		),
	'eventdate' => array(
		'type' => 'date',
		'notnull' => 1,
		'default' => '0000-00-00',
		),
	'multiday_event_start_id' => array(
		'type' => 'text',
		),
	'eventtile' => array(
		'type' => 'text',
		'length' => 100,
		),
	'eventdetails' => array(
		'type' => 'text',
		),
	'eventurl' => array(
		'type' => 'text',
		'length' => 100,
		),
	'userorcontext' => array(
		'type' => 'text',
		'length' => 1,
		),
	'context' => array(
		'type' => 'text',
		'length' => 32
		),
	'workgroup' => array(
		'type' => 'text',
		'length' => 32,
		),
	'showusers' => array(
		'type' => 'text',
		'length' => 1,
		),
	'userFirstEntry' => array(
		'type' => 'text',
		'length' => 32,
		),
	'userLastModified' => array(
		'type' => 'text',
		'length' => 32,
		),
	'dateFirstEntry' => array(
		'type' => 'date',
		'notnull' => 1,
		'default' => '0000-00-00 00:00:00',
		),
	'dateLastModified' => array(
		'type' => 'date',
		'notnull' => 1,
		'default' => '0000-00-00 00:00:00',
		),
	'updated' => array(
		'type' => 'date',
		'notnull' => 1,
		'default' => '0000-00-00 00:00:00',
		),
	);
$options = array('comment' => 'blag', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');
//$options = NULL;
$this->manager->createKNGTable($fields, $table, $options);
                    echo "Good job";
                break;


        }
    }
}
?>