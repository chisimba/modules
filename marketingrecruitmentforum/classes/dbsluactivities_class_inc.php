<?php
//class use to perform all database manipulation of all SLU info captured
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
class dbsluactivities extends dbTable{ 

  //protected $_objUser;
 function init()
	{
	 try {
     
     
      parent::init('tbl_sluactivities');
		
			
		}catch (Exception $e){
       		echo 'Caught exception: ',  $e->getMessage();
        	exit();
    }
	}
/*------------------------------------------------------------------------------*/
  //insert all studcard information to database
  public function addsluactivity($sluactivity)
	{
        $sluinfo = $this->insert($sluactivity);
        return $sluinfo;
  }	
/*------------------------------------------------------------------------------*/
 //select all informatio from the stud card table
 public function getallsluactivity()
 {
      $sluresults  = $this->getAll();
      return  $sluresults;
 } 
/*------------------------------------------------------------------------------*/
//display all activities between two dates
  public function getactivitydate()
  {
    //how to specify the following
    /* 2.	Select date, activity_type
        	From activity
          Where date between $this->getParam(date1) and $this->getParam(date2);
    */
      $activitydate  = $this->getAll();
      return  $activitydate;
  }   
/*------------------------------------------------------------------------------*/
//display all activities by type
  public function getactivitytype()
  {   //use filter and specify type
      $filter = 'order by activity';
      $type  = $this->getAll($filter);
      return  $type;
  }   
/*------------------------------------------------------------------------------*/
//display all activities by province
  public function getactivityprovince()
  {   //use filter and specify province
      $filter = 'order by province';
      $province  = $this->getAll($filter);
      return  $province;
  }   
/*------------------------------------------------------------------------------*/
//display all activities by area
  public function getactivityarea()
  {   //use filter and specify area
      $filter = 'order by area';
      $area  = $this->getAll($filter);
      return  $area;
    
  }   
/*------------------------------------------------------------------------------*/
//display all activities by school
  public function getactivityschool()
  {     //use filter and specify school
      $school  = $this->getAll();
      return  $school;
    
  }   
/*------------------------------------------------------------------------------*/
  
}//end of class 
?>
