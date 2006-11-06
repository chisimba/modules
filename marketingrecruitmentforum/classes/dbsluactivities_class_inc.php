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
      $stmt = "select distinct(activity),date,schoolname,area,province from tbl_sluactivities order by date,activity";
      $sluresults  = $this->getArray($stmt);
      return  $sluresults;
 } 
/*------------------------------------------------------------------------------*/
//display all activities between two dates
  public function getactivitydate($begindate,$enddate)
  {
    //how to specify the following
    /* 2.	Select date, activity_type
        	From activity
          Where date between $this->getParam(date1) and $this->getParam(date2);
    */
      
      $stmt = "select distinct(activity),date from tbl_sluactivities where date between '$begindate' and '$enddate' order by date,activity";
      $activitydate  = $this->getArray($stmt);
      return  $activitydate;
  }   
/*------------------------------------------------------------------------------*/
//display all activities by type
  public function getactivitytype()
  {   //use filter and specify type
  
      $stmt = "select distinct(activity) from tbl_sluactivities order by activity";
      $type  = $this->getArray($stmt);
      return  $type;
  }   
/*------------------------------------------------------------------------------*/
//display all activities by province
  public function getactivityprovince()
  {   //use filter and specify province
      $stmt = "select distinct(activity),province from tbl_sluactivities order by province";
      $province  = $this->getArray($stmt);
      return  $province;
  }   
/*------------------------------------------------------------------------------*/
//display all activities by area
  public function getactivityarea()
  {   //use filter and specify area
      $stmt = "select distinct(activity),area from tbl_sluactivities order by area";
      $area  = $this->getArray($stmt);
      return  $area;
    
  }   
/*------------------------------------------------------------------------------*/
//display all activities by school
  public function getactivityschool($useToPopTbl)
  {    
       //use filter and specify school
      $stmt = "select distinct(activity),schoolname from tbl_sluactivities where schoolname = '$useToPopTbl' order by schoolname";
      $school  = $this->getArray($stmt);
      return  $school;
    
  }   
/*------------------------------------------------------------------------------*/
  
}//end of class 
?>
