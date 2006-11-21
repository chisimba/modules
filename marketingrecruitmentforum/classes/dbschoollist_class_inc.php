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
  public function addsschoollist($createdby,$datecreate,$result,$schooladdress,$telnumber,$faxnumber,$email,$principal,$guidanceteacher)
	{
       // $schooldata = $this->insert($result,$schooladdress,$telnumber,$faxnumber,$email,$principal,$guidanceteacher);
       // return $schooldata;
        $stmt = "INSERT INTO tbl_schoollist(createdby,datecreated,schoolname,schooladdress,telnumber,faxnumber,email,principal,guidanceteacher) values('$createdby','$datecreate','$result','$schooladdress','$telnumber','$faxnumber','$email','$principal','$guidanceteacher')";
        $schooldata = $this->getArray($stmt);
        return $schooldata;
  }	
/*------------------------------------------------------------------------------*/
 //select all informatio from the schoollist table
 public function getallsschools()
 {
      $filter = 'select distinct(schoolname) from tbl_schoollist order by schoolname';
      $results  = $this->getArray($filter);
      return  $results;
 } 
/*------------------------------------------------------------------------------*/
//display all schools with a certain name OR grouped OR order by name -- check
  public function getschoolbyname($namevalue)
  {
      $stmt = "select distinct(schoolname),schooladdress,telnumber,faxnumber,email,principal,guidanceteacher from tbl_schoollist where schoolname = '$namevalue'";
      $name = $this->getArray($stmt);
      return  $name;
  }   
/*------------------------------------------------------------------------------*/
//display all schools by area
  public function getschoolbyarea()
  {   //use filter and specify area
      $stmt = "select distinct(sch.schoolname),slu.area area from tbl_schoollist sch, tbl_sluactivities slu where sch.schoolname = slu.schoolname order by area";
      $area  = $this->getArray($stmt);;
      return  $area;
  }   
/*------------------------------------------------------------------------------*/
//display all by province
  public function getschoolbyprovince()
  {   //use filter and specify province
      $stmt = "select distinct(sch.schoolname),slu.province prov from tbl_schoollist sch, tbl_sluactivities slu where sch.schoolname = slu.schoolname order by slu.province";
      $province  = $this->getArray($stmt);
      return  $province;
  }   
/*------------------------------------------------------------------------------*/
  public function updateschoollist($result,$schooladdress,$telnumber,$faxnumber,$email,$principal,$guidanceteacher){
    
    $stmt = "UPDATE tbl_schoollist SET schoolname = '$result', schooladdress = '$schooladdress', telnumber ='$telnumber', faxnumber = '$faxnumber', email ='$email', principal ='$principal', guidanceteacher='$guidanceteacher' where schoolname = '$result'";
    $res  = $this->query($stmt);
    return  $res;
  }
}//end of class 
?>
