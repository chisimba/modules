<?php
/* ----------- data class extends dbTable for tbl_loggedinusers------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
    {
        die("You cannot view this page directly");
    }


/**
* Model class for the table tbl_loggedinusers
*/
class loggedin extends dbTable
{
	/*
	*@var contextCode
	*/
	var $contextCode;	
	
	
    /**
    * Constructor method to define the table
    */
    function init() {
        parent::init('tbl_loggedinusers');
		$objContext=&$this->getObject('dbcontext','context');
		$this->contextCode=$objContext->getContextCode();		
    }

   function getLoggedInStudent(){
   
   }
   
   function getLoggedInLecturers(){
   
   }
   
   function getLoggedInUsers(){
	   return $this->getAll();
   }
    
} #end of class
?>
