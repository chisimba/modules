<?php
//class use to perform all database manipulation of all School info captured
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check
/**
* This object generates data used perform database manipulation
* @package 
* @category sems
* @copyright 2004, University of the Western Cape & AVOIR Project
* @license GNU GPL
* @version
* @author Colleen Tinker
*/
class dbschoollist extends dbTable{ 

  //protected $_objUser;
 function init()
	{
	 try {
     
     
      parent::init('tbl_schoollist');
		
			
		}catch (Exception $e){
       		echo 'Caught exception: ',  $e->getMessage();
        	exit();
    }
	}
/*------------------------------------------------------------------------------*/
  //insert all studcard information to database
  public function addsschoollist($schoolinfodata)
	{
        $schoolinfo = $this->insert($schoolinfodata);
        return $schoolinfo;
  }	
/*------------------------------------------------------------------------------*/
 //select all informatio from the stud card table
 public function getallsluactivity()
 {
      $sluresults  = $this->getAll();
      return  $sluresults;
 } 
   
} 
?>
