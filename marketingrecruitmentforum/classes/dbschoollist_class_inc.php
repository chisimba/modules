<?php
//class use to perform all database manipulation of all school info captured
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
        $schooldata = $this->insert($schoolinfodata);
        return $schooldata;
  }	
/*------------------------------------------------------------------------------*/
 //select all informatio from the schoollist table
 public function getallsschools()
 {
      $filter = 'order by schoolname';
      $results  = $this->getAll($filter);
      return  $results;
 } 
/*------------------------------------------------------------------------------*/
//display all schools with a certain name OR grouped OR order by name -- check
  public function getschoolbyname()
  {
    //how to specify the following -- filter name
      $filter = 'order by schoolname';
      $name  = $this->getAll($filter);
      return  $name;
  }   
/*------------------------------------------------------------------------------*/
//display all schools by area
  public function getschoolbyarea()
  {   //use filter and specify area
      $area  = $this->getAll();
      return  $area;
  }   
/*------------------------------------------------------------------------------*/
//display all by province
  public function getschoolbyprovince()
  {   //use filter and specify province
      $province  = $this->getAll();
      return  $province;
  }   
/*------------------------------------------------------------------------------*/
  
}//end of class 
?>
