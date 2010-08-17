<?php
/* ----------- controller class extends controller for tbl_quotes------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}


/**
*
* Controller class for the text blocks module
*
* @author Charl Mert, Nic Appleby
* @version $Id$
* @copyright 2010 GNU GPL
*
*/
class textblock extends controller
{

    /**
    * @var string $action The action parameter from the querystring 
    */
    public $action;

    /**
    * Standard constructor method 
    */
    public function init()
    {
        //Retrieve the action parameter from the querystring
        $this->action = $this->getParam('action', Null);
        //Create an instance of the database class for this module
        $this->objDb = $this->getObject("dbtextblock");

        //Create an instance of the language object
        $this->objLanguage = $this->getObject("language", "language");
        
        //Get the activity logger class
        $this->objLog = $this->newObject('logactivity', 'logger');
        //Log this module call
        $this->objLog->log();
    }

    /**
    * Standard dispatch method 
    */
    public function dispatch()
    {
        switch ($this->action) {
            case null:
            case "view":
				$pageNr = $this->getParam('pagenum', '1');
				$records = 10;
				$start = (($pageNr * $records) - $records);
				$end = $pageNr * $records;
				$total = $this->objDb->getRecordCount();
				$end = ($end > $total)? $total : $end;
                $ar = $this->objDb->getAll("ORDER BY title LIMIT $start, $end");
                $this->setVar('ar', $ar);
                $this->setVar('total', $total);
                $this->setVar('page', $pageNr);
                $this->setVar('start', $start+1);
                $this->setVar('end', $end);
                return "main_tpl.php";

            case 'edit':
                $this->getForEdit('edit');
                $this->setVar('mode', 'edit');
                return "edit_tpl.php";

            case 'delete':
                // retrieve the confirmation code from the querystring
                $confirm = $this->getParam("confirm", "no");
                if ($confirm == "yes") {
                    $this->objDb->delete("id", $this->getParam('id', NULL));
                    return $this->nextAction('view', NULL);
                }
                break;
				
            case 'add':
                $this->setVar('mode', 'add');
                return "edit_tpl.php";

            case 'save':
            	$id = $this->getParam('id');
				$blockid = $this->getParam('blockid');
				$title = $this->getParam('title');
				$blocktext = $this->getParam('blocktext');
				$showTitle = $this->getParam('show_title');

				$showTitle = ($showTitle == 'on')? '1' : '0';
	
				$cssId = $this->getParam('css_id');
				$cssClass = $this->getParam('css_class');
                $mode = $this->getParam('mode');
				
				$this->objDb->saveRecord($id, $mode, $blockid, $title, $blocktext, $cssId, $cssClass, $showTitle);
                return $this->nextAction('view');

            default:
                $this->setVar('str', $this->objLanguage->languageText("phrase_actionunknown").": ".$action);
                return 'dump_tpl.php';

        }
    }
    
    /**
    * Override the default requirement for login
    */
    public function requiresLogin() {
        return TRUE;  
    }
    
    /** 
    *
    * Method to retrieve the data for edit and prepare 
    * the vars for the edit template.
    *    @param string $mode The edit or add mode @values edit | add
    */
    public function getForEdit($mode) {
        $this->setvar('mode', $mode);
        // retrieve the PK value from the querystring
        $keyvalue=$this->getParam("id", NULL);
        if (!$keyvalue) {
            die($this->objLanguage->languageText("modules_badkey").": ".$keyvalue);
        }
        // Get the data for edit
        $this->setVar('ar', $this->objDb->getRow('id', $keyvalue));
    }
}
?>
