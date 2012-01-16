<?php
/* ----------- controller class extends controller for tbl_quotes------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}


/**
*
* Controller class for the table tbl_quotes
*
* @author Administrative User
*
*
* @version $Id: controller.php 14552 2009-08-27 11:52:17Z jsc $
* @version $Id: controller.php,v 1.3 2006/09/14 Abdurahim Ported to PHP5
* @copyright 2005 GNU GPL
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
        // Load the module helper Javascript
        $this->appendArrayVar('headerParams',
        $this->getJavaScriptFile('textblock.js',
          'textblock'));
        //Get the activity logger class
        $this->objLog=$this->newObject('logactivity', 'logger');
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
            case 'narrowblock':
            case 'view':
                // Set the layout template to compatible one
                $this->setLayoutTemplate('layout_tpl.php');
                return 'narrowblockview_tpl.php';
                break;
            case 'middleblock':
                // Set the layout template to compatible one
                $this->setLayoutTemplate('layout_tpl.php');
                return 'wideblockview_tpl.php';
                break;
            case 'deleteajax':
                $this->objDb->delete("id", $this->getParam('id', Null));
                die("RECORD_DELETED");
                break;
            case 'ajaxedit':
                // Set the layout template to compatible one
                $this->setLayoutTemplate('layout_tpl.php');
                return "editajax_tpl.php";
                break;
            
            
            
            case "oldview":
		  $pageNr = $this->getParam('pagenum', '1');
		  $records = 20;
		  $start = (($pageNr * $records) - $records);
		  $end = $pageNr * $records;
                  $ar = $this->objDb->getAll(" LIMIT $start, $end");
                  $this->setVarByRef('ar', $ar);
                  return "main_tpl.php";
                  break; //@Deprecated

            case 'edit':
                 $this->getForEdit('edit');
                 $this->setVar('mode', 'edit');
                 return "edit_tpl.php";
                 break;

            case 'delete':
                // retrieve the confirmation code from the querystring
                $confirm=$this->getParam("confirm", "no");
                if ($confirm=="yes") {
                    $this->objDb->delete("id", $this->getParam('id', Null));
                    return $this->nextAction('view',null);
                }
                break;
                

				
            case 'add':
                $this->setVar('mode', 'add');
                return "edit_tpl.php";
                break;

            case 'save':
            	$objUser = $this->getObject("user", "security");
                $this->objDb->saveRecord($this->getParam('mode', Null), $objUser->userId());
                return $this->nextAction('view',null);
                break;

            default:
                $this->setVar('str', $this->objLanguage->languageText("phrase_actionunknown",NULL,"Unknown action").": ".$this->action);
                return 'dump_tpl.php';

        }//switch
    } // dispatch
    
    /**
    * Override the default requirement for login
    */
    public function requiresLogin()
    {
        return TRUE;  
    }
    
    /** 
    *
    * Method to retrieve the data for edit and prepare 
    * the vars for the edit template.
    *    @param string $mode The edit or add mode @values edit | add
    */
    public function getForEdit($mode)
    {
        $this->setvar('mode', $mode);
        // retrieve the PK value from the querystring
        $keyvalue=$this->getParam("id", NULL);
        if (!$keyvalue) {
            die($this->objLanguage->languageText("modules_badkey").": ".$keyvalue);
        }
        // Get the data for edit
        $this->setVar('ar', $this->objDb->getRow('id', $keyvalue));
    }//getForedit
}
?>
