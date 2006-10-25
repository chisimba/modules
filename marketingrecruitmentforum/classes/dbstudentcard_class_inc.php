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
      $filter = 'order by schoolname,surname';
      $studresults  = $this->getAll($filter);
      return  $studresults;
 } 
/*------------------------------------------------------------------------------*/
  //get all students from a certain school
 public function getstudschool($useToPopTbl)
 {    //FIX NB
      //$schoolname = $this->getParam('schoollistnames');
      $stmt = "select surname,name,schoolname from tbl_studcard where schoolname = '$useToPopTbl'";
      //$filter = 'where schoolname = ' . $useToPopTbl;
      $studschool = $this->getArray($stmt);
      return  $studschool;
 }
/*------------------------------------------------------------------------------*/ 
 public function allstudsexemption(){
      //get all students that qualify for an exemption
      // 1 = true 
      $filter1 = 'order by schoolname,surname';
      
      $filter = "where exemption = 1";
      $exemption = $this->getAll($filter1,$filter);
      return  $exemption;
 }  
/*------------------------------------------------------------------------------*/
 public function allrelsubject(){
    //display all students with relevant subject 
    // 1 = true 
    $filter = 'where relevantsubject = 1'; 
    $relevansub = $this->getAll($filter);
    return  $relevansub;
 }
/*------------------------------------------------------------------------------*/
 public function allbyfaculty(){
    //order by faculty...check how
    $filter = 'order by faculty';
    $faculty = $this->getAll($filter);
    return  $faculty;
 }
/*------------------------------------------------------------------------------*/ 
 public function allbycourse(){
    //display all students , order by course, faculty
    $filter = 'order by course, faculty';
    $course = $this->getAll($filter);
    return  $course;
 }
/*------------------------------------------------------------------------------*/ 
 public function allbyarea(){ //CHECK nb area within activities class
    $area = $this->getAll();
    return  $area;
 }
/*------------------------------------------------------------------------------*/
  public function allsdcases(){
    
    $filter = 'where sdcase = 1';
    $totalsd  = $this->getAll($filter);
    return  $totalsd;
}  
/*------------------------------------------------------------------------------*/ 
  public function facultyinterest(){
      
      $stmt = 'Select faculty, count(faculty) totalstudent from tbl_studcard groupedby faculty';
      $faculty = $this->getAll($stmt);
      return  $faculty;
  }
/*------------------------------------------------------------------------------*/
  public function allstudq(){
    
    $stmt = 'Select count(*) entry from tbl_studcard where exemption = 1 AND relevantsubject = 1';
    
    $val = $this->getArray($stmt);
    return $val;
  }  
 
}//end of class 
?>
