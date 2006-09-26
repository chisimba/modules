<?php

if (!$GLOBALS['kewl_entry_point_run'])     
{
	die("You cannot view this page directly");
}

/*******************************************************************************
/**
* Marketing Controller                                                                 
* This class controls all functionality to run the marketingrecruitmentforum module.
* @author Colleen Tinker
* @copyright (c) 2004 University of the Western Cape
* @package invoice
* @version 1
********************************************************************************/

class marketingrecruitmentforum extends controller
{
    /** 
     *declare variable used 
     *@param public and private
     */
     
    function init()
    {
      //initialise all class objects
      $this->objLanguage =& $this->getObject('language', 'language');
      $this->setLayoutTemplate('default_layout_tpl.php');
    }
    
    
    /**
    	* Method to process actions to be taken
      * @param string $action String indicating action to be taken
    	*/
	  function dispatch($action)
    {
        
        $this->setVar('pageSuppressXML',true);
             
        switch($action){
            
            case 'activitylist':
                 // $this->setLayoutTemplate('default_layout_tpl.php');
                  return 'sluactivities_tpl.php';
            break;
            
            case  'studentcard':
                return 'studentcards_tpl.php';
            break;
            
            case  'shoollist':
              return 'schoollist_tpl.php';
            
            default:
                return $this->nextAction('activitylist', array(NULL));
                
       }
    }
    
}               
           
    
?>
