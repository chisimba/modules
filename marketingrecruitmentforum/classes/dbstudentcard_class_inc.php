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
  function addstudcard($createdby,$datecreate,$id,$date,$surname,$name,$schoolname,$postaddress,$postcode,$telnumber,$telcode,$exemption,$faculty,$course,$relsubject,$sdcase)
	{
        //$studinfo = $this->insert($createdby,$datecreate,$id,$date,$surname,$name,$schoolname,$postaddress,$postcode,$telnumber,$telcode,$faculty,$course);
        //return $studinfo;
        $stmt = "INSERT INTO tbl_studcard(createdby,datecreated,id,date,surname,name,schoolname,postaddress,postcode,telnumber,telcode,exemption,faculty,course,relevantsubject,sdcase) values('$createdby','$datecreate','$id','$date','$surname','$name','$schoolname','$postaddress','$postcode','$telnumber','$telcode','$exemption','$faculty','$course','$relsubject','$sdcase')";
        $name = $this->getArray($stmt);
        return $name;
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
      $filter = 'order by date,schoolname,surname';
      $studresults  = $this->getAll($filter);
      return  $studresults;
 } 
/*------------------------------------------------------------------------------*/

public function getstudqualify()
 {
      $filter = 'where relevantsubject = 1 and exemption = 1';
      $studresults  = $this->getAll($filter);
      return  $studresults;
 } 
/*------------------------------------------------------------------------------*/
  //get all students from a certain school
 public function getstudschool($useToPopTbl)
 {    
      $stmt = "select surname,name,schoolname from tbl_studcard where schoolname = '$useToPopTbl'";
      $studschool = $this->getArray($stmt);
      return  $studschool;
 }
/*------------------------------------------------------------------------------*/ 
 public function allstudsexemption(){
      //get all students that qualify for an exemption
      // 1 = true 
     // $filter1 = 'order by schoolname,surname';
      
      $filter = "where exemption = '1' order by schoolname,surname";
      $exemption = $this->getAll($filter);
      return  $exemption;
 }  
/*------------------------------------------------------------------------------*/
 public function allrelsubject(){
    //display all students with relevant subject 
    // 1 = true 
    $filter = "where relevantsubject = 1"; 
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
    
    $stmt = "Select count(id) sdresult from tbl_studcard where sdcase = 1 and exemption = 0";
    $totalsd  = $this->getArray($stmt);
    return  $totalsd;
}  
/*------------------------------------------------------------------------------*/
  public function sdcases(){
    
    $stmt = "Select surname,name,schoolname,sdcase from tbl_studcard where sdcase = 1 and exemption = 0 order by surname";
    $totalsd  = $this->getArray($stmt);
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
    
    $stmt = "Select count(id) entry from tbl_studcard where exemption = 1 AND relevantsubject = 1";
    $val = $this->getArray($stmt);
    return $val;
  }  
/*------------------------------------------------------------------------------*/
  public function allstudfaculty($facultynameval){
  //get all students entered for a specific faculty
      
    $stmt = "select surname, name, schoolname, faculty from tbl_studcard where faculty = '$facultynameval'";
    $facultyresult = $this->getArray($stmt);
    return $facultyresult;  
    
  } 
/*------------------------------------------------------------------------------*/ 
  public function facultyexempted($facultynameval){
  //get all students that entered for the faculty and has an exemption
    $stmt = "Select surname, name,schoolname,exemption,faculty from tbl_studcard where exemption = 1 and faculty = '$facultynameval'";
    $facexemption = $this->getArray($stmt);
    return $facexemption;
  }
/*------------------------------------------------------------------------------*/
  public function facsubject($facultynameval){
  //get all students that entered for the faculty and have relevant subjects
    $stmt = "Select surname, name, schoolname,faculty,relevantsubject from tbl_studcard where relevantsubject = 1 and faculty = '$facultynameval'";
    $facexemption = $this->getArray($stmt);
    return $facexemption;
  }
/*------------------------------------------------------------------------------*/
  public function faccourse($facultynameval){
    //get all students that entered for the faculty and related courses -- might be wrong could be students by course selected
    $stmt = "Select surname, name, schoolname,faculty,course from tbl_studcard where faculty = '$facultynameval'";
    $facultycourse = $this->getArray($stmt);
    return $facultycourse;
  }
/*------------------------------------------------------------------------------*/
  public function facultysdcase($facultynameval){
    //get all sdcases that entered for the faculty 
    $stmt = "Select surname, name, schoolname,faculty,sdcase from tbl_studcard where sdcase = 1 and faculty = '$facultynameval'";
    $facultycourse = $this->getArray($stmt);
    return $facultycourse;
  }
/*------------------------------------------------------------------------------*/
  public function facultycount($facultyname){
    //count the total no of students entered for speciic faculty
    $stmt = "Select surname, name,postaddress, schoolname,faculty,count(id) totstud from tbl_studcard where faculty = '$facultyname' group by postaddress";
    $facultycount = $this->getArray($stmt);
    return $facultycount;
  }
/*------------------------------------------------------------------------------*/
  public function getallstudaddy()
  {
    //get all student addresses from studcard table
    $stmt = "select distinct(postaddress), surname, name from tbl_studcard";
    $studaddress = $this->getArray($stmt);
    return $studaddress;
  }
/*------------------------------------------------------------------------------*/
  public  function  getstudInfo()  //example used for follow up letter
  {
    //text for letter follow up
    $stmt = "select surname, name, postaddress from tbl_studcard where name = 'kader' and surname = 'jaffer'";
    $studtest = $this->getArray($stmt);
    return $studtest;
  }
/*------------------------------------------------------------------------------*/
  public function getstudbyarea()
  {
    $stmt = "select stud.surname, stud.name, stud.postaddress, stud.schoolname studschoool, slu.schoolname sluschool, slu.area from tbl_studcard stud, tbl_sluactivities slu where stud.schoolname = slu.schoolname order by slu.area";
    $studarea = $this->getArray($stmt);
    return $studarea;
  }
/*------------------------------------------------------------------------------*/
  public function getstudbyid($idsearch)
  {
    $stmt = "select * from tbl_studcard where idnumber = '$idsearch'";
    $studid = $this->getArray($stmt);
    return $studid;
  }
/*------------------------------------------------------------------------------*/
  public function updatestudinfo($idsearch,$date,$surname,$name,$schoolname,$postaddress,$postcode,$telnumber,$telcode,$exemption,$faculty,$course,$relsubject,$sdcase){
  
    //$res  = $this->update($id,$date,$surname,$name,$schoolname,$postaddress,$postcode,$telnumber,$telcode)
    $stmt = "UPDATE tbl_studcard SET date = '$date', surname = '$surname', name ='$name', schoolname = '$schoolname', postaddress='$postaddress', postcode='$postcode', telnumber='$telnumber', telcode='$telcode',exemption='$exemption',relevantsubject='$relsubject',sdcase='$sdcase' where idnumber = '$idsearch'";
    $res  = $this->query($stmt);
    return  $stmt;
  }
/*------------------------------------------------------------------------------*/
  public function addfaculties()
  {
    $faculties = " ";
    $stmt = "SELECT * from tbl_academicprogramme_faculties";
    $facvals = $this->getArray($stmt);
    
    if(empty($facvals)){
    
    $stmt = "LOAD DATA INFILE 'faculty_values.txt' INTO TABLE tbl_academicprogramme_faculties FIELDS TERMINATED BY ','";
    $faculties = $this->getArray($stmt);
    }
    return $faculties;
  }
/*------------------------------------------------------------------------------*/
  public function addcoursevalues()
  {
    $course = " ";
    $stmt = "SELECT * from tbl_academicprogramme_courses";
    $coursevals = $this->getArray($stmt);
    
    if(empty($coursevals)){
    
        $stmt = "LOAD DATA INFILE 'course_values.txt' INTO TABLE tbl_academicprogramme_courses FIELDS TERMINATED BY ','";
        $course = $this->getArray($stmt);
        return $course;
    }
  }
/*------------------------------------------------------------------------------*/
  public function getFaculties()
  {
    $stmt = "Select * from tbl_academicprogramme_faculties";
    $name = $this->getArray($stmt);
    return $name;
  }
/*------------------------------------------------------------------------------*/
  public function getcourse()
  {
    //$facselected  = $this->getSession('faculty');
    $stmt = "Select * from tbl_academicprogramme_courses";// where faculty_code  =  '$facselected'";
    $course = $this->getArray($stmt);
    return $course;
  }
/*------------------------------------------------------------------------------*/
  /*public function getfaccode()
  {
      $filter = 'order by date,schoolname,surname';
      $studresults  = $this->getRow($filter);
      return  $studresults;
  } */ 
}//end of class 
?>
