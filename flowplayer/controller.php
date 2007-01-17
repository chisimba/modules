<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}

/**
*
* Controller class for Chisimba for the module flowplayer
*
* This module is a test module for flow player. It takes a FLV file supplied
* as a URL in the querystring and plays it. For example it will play
*    http://localhost/app/index.php?module=flowplayer&movie=http://www.youtube.com/somevideo.flv
* in a window.
*
* @author Derek Keats
* @package flowplayer
*
*/
class flowplayer extends controller
{

    /**
     *
    * @var $objLanguage String object property for holding the
    * language object
    *
    * @access public
    *
    */
    public $objLanguage;
    /**
    *
    * @var $objLog String object property for holding the
    * logger object for logging user activity
    *
    * @access public
    *
    */
    public $objLog;

    /**
    *
    * Standard Chisimba init method
    *
    */
    public function init()
    {
        $this->objUser = $this->getObject('user', 'security');
        $this->objLanguage = $this->getObject('language', 'language');
        //Get the activity logger class
        $this->objLog=$this->newObject('logactivity', 'logger');
        //Log this module call
        $this->objLog->log();
    }


    /**
     *
     * The standard dispatch method for the {yourmodulename} module.
     * The dispatch method uses methods determined from the action
     * parameter of the  querystring and executes the appropriate method,
     * returning its appropriate template. This template contains the code
     * which renders the module output.
     *
     */
    public function dispatch()
    {
    	$action = $this->getParam('action', NULL);
    	switch($action) {
		    case "demo":
		    	$str="Working here";
		    	$this->setVarByRef('str', $str);
		        return "dump_tpl.php";
		        break;
    		case NULL:
    		default:
        		$this->objBuildPlayer = $this->getObject('buildflowplayer', 'flowplayer');
        		$str = $this->objBuildPlayer->show();
        		$this->setVarByRef('str', $str);
		        return "dump_tpl.php";
		        break;
		}
    }


    /**
    *
    * This method allows anonymous access to the module. Without this
    * it could only be used by logged in users.
    *
    * @return boolean TRUE|FALSE
    *
    */
    public function requiresLogin()
    {
        return FALSE;
    }
}
?>