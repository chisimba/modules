<?php
//class use to perform all database manipulation of all student card info captured
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
class dbstudentcard extends dbTable{ 

  //protected $_objUser;
 function init()
	{
	 try {
     
     
      parent::init('tbl_studcard');
		
			
		}catch (Exception $e){
       		echo 'Caught exception: ',  $e->getMessage();
        	exit();
    }
	}
/*------------------------------------------------------------------------------*/
  //insert all studcard information to database
  function addstudcard($studcarddata)
	{
        $studinfo = $this->insert($studcarddata);
        return $studinfo;
  }	
/*------------------------------------------------------------------------------*/
  //function addfaccourse($faccourse)
  //{
  //      $faccourseinfo  = $this->insert($faccourse);
  //      return $faccourseinfo;
  //}
/*------------------------------------------------------------------------------*/  
 //select all informatio from the stud card table
 public function getallstudinfo()
 {
      $studresults  = $this->getAll();
      return  $studresults;
 } 
/*------------------------------------------------------------------------------*/
  //get all students from a certain school
 public function getstudschool($schoolname)
 {    //FIX NB
      $schoolname = $this->getParam('schoollistnames');
      $studschool = $this->getAll($schoolname);
      return  $studschool;
 }
/*------------------------------------------------------------------------------*/ 
 public function allstudsexemption(){
      //get all students that qualify for an exemption
      //NEED TO FIX QUERY
      $exemption = $this->getAll();
      return  $exemption;
 }  
/*------------------------------------------------------------------------------*/
 public function allrelsubject(){
    //display all students where relevant subject is true ...FIX nb  
    $relevansub = $this->getAll();
    return  $relevansub;
 }
/*------------------------------------------------------------------------------*/
 public function allbyfaculty(){
    //order by faculty...check how
    $faculty = $this->getAll();
    return  $faculty;
 }
/*------------------------------------------------------------------------------*/ 
 public function allbycourse(){
    //display all students , order by course, faculty
    $course = $this->getAll();
    return  $course;
 }
/*------------------------------------------------------------------------------*/ 
 public function allbyarea(){ //CHECK nb area within activities class
    $area = $this->getAll();
    return  $area;
 }
/*------------------------------------------------------------------------------*/ 

 
}//end of class 
?>
