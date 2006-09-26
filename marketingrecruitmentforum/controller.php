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

class onlineinvoice extends controller
{
    /** 
     *declare variable used 
     *@param public and private
     */
     
    function init()
    {
      //initialise all class objects
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
                  return 'sluactivities_tpl.php';
            
            break;
            
            default:
            return $this->nextAction('activitylist', array(NULL));
                
       }
    }
    
}               
           
    
?>
