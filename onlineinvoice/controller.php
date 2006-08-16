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
    //declare variable
    var $objUser;
    /**
         
	* Constructor method to instantiate objects and get variables
	*/
    function init()
    {//create an instance of classes
        
        $this->dbTev =& $this->getObject('dbtev');
        $this->objLanguage =& $this->getObject('language', 'language');
        $this->objLanguage = &$this->getObject('language', 'language');
        //$this->setVarByRef('objLanguage', $this->objLanguage);
        $this->objUser =& $this->getObject('user', 'security');
        // User Details
        
        //pass variables to the template
        
        $this->setVarByRef('fullname', $this->objUser->fullname());
        $this->userId = $this->objUser->userId();
	     	$this->getObject('sidemenu','toolbar');
        $this->setLayoutTemplate('calendar_layout_tpl.php');
       
        
        
       
	}
    
    /**
	* Method to process actions to be taken
    *
    * @param string $action String indicating action to be taken
	*/
    function dispatch($action)
    {
              
        switch($action){
          case 'createinvoice':
            return 'createInvoice_tpl.php';
            break;
          
          case 'createtev':
            return 'tev_tpl.php';
            break;
          
          case 'createexpenses':
            return  'expenses_tpl.php';
            break;
            
          default:
            return $this->nextAction('createinvoice', array(NULL));
                
        }
    }
}

?>
