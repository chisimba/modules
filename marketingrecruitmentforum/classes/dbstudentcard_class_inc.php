<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}

/**
* This class is use to perform all database manipulation of all student card info captured
* @package 
* @category sems
* @copyright 2004, University of the Western Cape & AVOIR Project
* @license GNU GPL
* @version 1
* @author Colleen Tinker
*/
class dbstudentcard extends object{ 

	 /**
    *
    * @var string $objSoapClient A property used to hold the soap client object.
    *
    * @access public
    *
    */
    public $objSoapClient;

    /**
    *
    * @var string $schema A property used to hold the shema to use for the oracle db
    *
    * @access public
    *
    */
    public $schema;
    
    

  /**
   * Standard init function
   * @param reference tbl studcard 
   * @param void
   * @return void
   */    

 
 	public function init()
  {
      		parent::init();
        
        try {
            $this->schema = 'sems';
            $this->schema2 = 'linc';
            $objSysconfig =& $this->getObject('dbsysconfig', 'sysconfig');
			
            $this->objSoapClient = new SoapClient('http://'.$objSysconfig->getValue('SEMSGENERICWS', 'marketingrecruitmentforum'));
            $this->objSoapClient2 = new SoapClient('http://'.$objSysconfig->getValue('LINCGENERICWS', 'marketingrecruitmentforum'));
            
            
        } catch (Exception $e) {
           die($e->getMessage());
        }
        
   }
/*------------------------------------------------------------------------------*/
/**
 * Method to insert all student card data captured into db tbl studcard
 * @param date $createdby
 * @param date $datecreate
 * @param string $id
 * @param date $date
 * @param string $surname
 * @param string $name
 * @param string $schoolname
 * @param string $postaddress
 * @param string $postcode
 * @param string $telnumber
 * @param string $telcode
 * @param boolean $exemption
 * @param string $faculty
 * @param string $course
 * @param boolean $relsubject
 * @param boolean $sdcase  
 * @param string $fields: The fields to write to the database
 * @return bool: returns true if save succeeded or else false
 * @access public
 *
 */
 
public function addstudcard($createdby,$datecreate,$id,$date,$surname,$name,$dob,$grade,$schoolname,$postaddress,$postcode,$telnumber,$telcode,$exemption,$faculty,$course,$sdcase,$areastud,$grade,$cellnumber,$studemail,$subject1,$subject2,$subject3,$subject4,$subject5,$subject6,$subject7,$info,$faculty2,$course2,$residence,$gradetype1,$gradetype2,$gradetype3,$gradetype4,$gradetype5,$gradetype6,$gradetype7,$markval,$markval2,$markval3,$markval4,$markval5,$markval6,$markval7,$markgrade,$confirmation,$sportPart,$leadershipPos,$sportCode,$achievlevel,$sportBursary,$keys = NULL)
{
        //  try {
           //print_r($studcarddata);die;
           //$sdate = $studcarddata['date'];
           //$ddate = $studcarddata['datecreated'];
           
            $data = array();
            $data[] = array( 'field' => 'id', 'value' => "init" . "_" . rand(1000,9999) . "_" . time());
            $data[] = array( 'field' => 'createdby', 'value' => $createdby);
            $data[] = array( 'field' => 'datecreated', 'value' => "to_date('".$datecreate."', 'dd-mm-yyyy')");
            $data[] = array( 'field' => 'idnumber', 'value' => $id);
            $data[] = array( 'field' => 'entryDate', 'value' => "to_date('".$date."', 'dd-mm-yyyy')");
            $data[] = array( 'field' => 'name', 'value' => $name);
            $data[] = array( 'field' => 'surname', 'value' => $surname);
            $data[] = array( 'field' => 'schoolname', 'value' => $schoolname);
            $data[] = array( 'field' => 'postaddress', 'value' => $postaddress);
            $data[] = array( 'field' => 'postcode', 'value' => $postcode);
            $data[] = array( 'field' => 'telnumber', 'value' => $telnumber);
            $data[] = array( 'field' => 'telcode', 'value' => $telcode);
            $data[] = array( 'field' => 'exemption', 'value' => $exemption);
            $data[] = array( 'field' => 'faculty', 'value' => $faculty);
            $data[] = array( 'field' => 'course', 'value' => $course);
            $data[] = array( 'field' => 'sdcase', 'value' => $sdcase);
            $data[] = array( 'field' => 'area', 'value' => $areastud);
            $data[] = array( 'field' => 'dob', 'value' => "to_date('".$dob."', 'dd-mm-yyyy')");
            $data[] = array( 'field' => 'grade', 'value' => $grade);            
            $data[] = array( 'field' => 'cellnumber', 'value' => $cellnumber);
            $data[] = array( 'field' => 'studemail', 'value' => $studemail);
            $data[] = array( 'field' => 'subject1', 'value' => $subject1);
            $data[] = array( 'field' => 'subject2', 'value' => $subject2);
            $data[] = array( 'field' => 'subject3', 'value' => $subject3);
            $data[] = array( 'field' => 'subject4', 'value' => $subject4);
            $data[] = array( 'field' => 'subject5', 'value' => $subject5);
            $data[] = array( 'field' => 'subject6', 'value' => $subject6);
            $data[] = array( 'field' => 'subject7', 'value' => $subject7);
            $data[] = array( 'field' => 'infodepartment', 'value' => $info); 
            $data[] = array( 'field' => 'faculty2', 'value' => $faculty2);
            $data[] = array( 'field' => 'course2', 'value' => $course2);
            $data[] = array( 'field' => 'residence', 'value' => $residence);
            $data[] = array( 'field' => 'gradetype1', 'value' => $gradetype1);
            $data[] = array( 'field' => 'gradetype2', 'value' => $gradetype2);
            $data[] = array( 'field' => 'gradetype3', 'value' => $gradetype3);
            $data[] = array( 'field' => 'gradetype4', 'value' => $gradetype4);
            $data[] = array( 'field' => 'gradetype5', 'value' => $gradetype5);
            $data[] = array( 'field' => 'gradetype6', 'value' => $gradetype6);
            $data[] = array( 'field' => 'gradetype7', 'value' => $gradetype7);
            $data[] = array( 'field' => 'markgrade', 'value' => $markgrade);
            $data[] = array( 'field' => 'MARK1', 'value' => $markval);
            $data[] = array( 'field' => 'MARK2', 'value' => $markval2);
            $data[] = array( 'field' => 'MARK3', 'value' => $markval3);
            $data[] = array( 'field' => 'MARK4', 'value' => $markval4);
            $data[] = array( 'field' => 'MARK5', 'value' => $markval5);
            $data[] = array( 'field' => 'MARK6', 'value' => $markval6);
            $data[] = array( 'field' => 'MARK7', 'value' => $markval7);
            $data[] = array( 'field' => 'CONFIRM', 'value' => $confirmation);
            $data[] = array( 'field' => 'SPORTPART', 'value' => $sportPart);
           $data[] = array( 'field' => 'LEADERSHIPPOS', 'value' => $leadershipPos);
            $data[] = array( 'field' => 'SPORTCODE', 'value' => $sportCode);
            $data[] = array( 'field' => 'ACHIEVELEVEL', 'value' => $achievlevel);
            $data[] = array( 'field' => 'SPORTBURSARY', 'value' => $sportBursary);
            
            /*echo "<pre>";
            VAR_DUMP($data);DIE;*/
            
            $keys = array();
           return  $this->objSoapClient->writeQuery('tbl_mrf_studcard', $data ,$keys,$this->schema);
      //  } catch(Exception $e) {
      //    return NULL;
      //  }
            
        
}	
/*------------------------------------------------------------------------------*/
/**
 * Method to retrieve all studcard info captured from db tbl studcard
 * @return array: The array of matching records from the database
 * @access public
 */
   
public function getallstudinfo()
{
        try {
        
            $query = 'SELECT * FROM '.$this->schema.'.tbl_mrf_studcard order by ENTRYDATE';
            $results =  $this->objSoapClient->genericQuery($query);
            return $results;
        } catch(Exception $e) {
          return NULL;
        }
   
       
}


 /*------------------------------------------------------------------------------*/
/**
 * Method to retrieve all studcard info captured from db tbl studcard
 * @return array: The array of matching records from the database
 * @access public
 */
	function getstudinfo($startat,$endat)
	{
	try {
		//print $startat;die('dsdsa');
			if($startat!=0){
			$startat++;
			}
			
			//print $endat;die;
		return $this->objSoapClient->getlimitQuery('tbl_mrf_studcard','', '', 'ENTRYDATE', $startat, $endat,  $this->schema);
		} catch(Exception $e) {
         return NULL;
       }
	}



/*------------------------------------------------------------------------------*/
/**
 * Method to retrieve school subjects from db
 */  
public function getSubjects()
{
        //try {
        
            $query = 'SELECT lngdsc, mtrsbjcde FROM '.$this->schema2.'.stdsys_mtrsb';
            $results =  $this->objSoapClient2->genericQuery($query);
            return $results;
            
        //} catch(Exception $e) {
         // return NULL;
       // }
   
       
}
  
/*------------------------------------------------------------------------------*/
/**
 * Method used to get details of all students that qualify/sucessfull for entry into the university
 * $filter, contains sql query to get student particulars:
 * NOTE: entry qualification requires relevantsubject and exemption to be = '1'... (i.e) true(boolean variable)
 * @return array $studresults     
 */
public function getstudqualify($where = 'where sdcase = 0 and exemption = 1')
{
        try {
            $query = 'SELECT name, surname, exemption FROM '.$this->schema.'.tbl_mrf_studcard '.$where;
            $results =  $this->objSoapClient->genericQuery($query);
            return $results;

        } catch(Exception $e) {
          return NULL;
        }
}
/*------------------------------------------------------------------------------*/
/**
 * Method to return student particulars of all students from a certain school
 * @param string $useToPopTbl schoolname value passed to function
 * $stmt, contains sql query used to get students details where schoolname in studcad tbl equal / same as variable $useToPopTbl passed to func
 * @return array $studschool   
 */
public function getstudschool($useToPopTbl, $field = 'schoolname', $start = 0, $limit = 0)
{
       try {
            $keys = array();
            $keys[] = array( 'field' => $field, 'value' => $useToPopTbl);

            $fields = array( 'SCHOOLNAME', 'SURNAME', 'NAME');

            return $this->objSoapClient->getlimitQuery('tbl_mrf_studcard ',$fields, $keys, 'SCHOOLNAME', $start, $limit,  $this->schema);

      } catch(Exception $e) {
         return NULL;
       }
}
/*------------------------------------------------------------------------------*/ 
/**
 * Method to get all students that qualify for an exemption
 * $filter, contains sql query that gets students where exemption is true 
 * results returned are ordered by surname within schoolname   
 * @return array  $exemption
 */   
public function allstudsexemption($where = 'where exemption = 1')
{
        try {
            $query = 'SELECT schoolname, surname, name, exemption FROM '.$this->schema.'.tbl_mrf_studcard '.$where;
            $results =  $this->objSoapClient->genericQuery($query);
            return $results;

        } catch(Exception $e) {
          return NULL;
        }
} 
           
public function exemption($startat,$endat)
{
	try {
		//print $startat;die('dsdsa');
			if($startat!=0){
			$startat++;
			}
			$keys = array();
      $keys[] = array( 'field'=>'exemption', 'value'=>'1');
      $sortfield = 'schoolname';
			
			//print $endat;die;
		  return $this->objSoapClient->getlimitQuery('tbl_mrf_studcard','',$keys, $sortfield, $startat, $endat,  $this->schema);
	} catch(Exception $e) {
         return NULL;
    }
}
/*------------------------------------------------------------------------------*/
/**
 * Method to get all students that have the relevant subjects
 * $filter, contains sql query that gets students where relevantsubject is true
 * @return array  $exemption  
 */ 
public function allrelsubject($where = 'where relevantsubject = 1')
{
        try {
            $query = 'SELECT schoolname, surname, name, relevantsubject FROM '.$this->schema.'.tbl_mrf_studcard '.$where;
            $results =  $this->objSoapClient->genericQuery($query);
            return $results;

        } catch(Exception $e) {
          return NULL;
        }
} 
/*------------------------------------------------------------------------------*/
/**
 * Method to return all students ordered by faculty
 * @param string $filter a SQL WHERE clause,specifies the order of resultset returned by SQL statement 
 * $faculty, contains the sql query that retrieves student info by faculty, calls db function getAll()
 * @return array $faculty    
 */   
public function allbyfaculty(){

            $query = 'SELECT * FROM '.$this->schema.'.tbl_mrf_studcard order by FACULTY';
            $results =  $this->objSoapClient->genericQuery($query);
            return $results;
}

public function studfacultycount($startat,$endat)
{
	try {
		//print $startat;die('dsdsa');
			if($startat!=0){
			$startat++;
			}
			//$keys = array();
      //$keys[] = array( 'field'=>'exemption', 'value'=>'1');
      $sortfield = 'faculty';
			
			//print $endat;die;
		  return $this->objSoapClient->getlimitQuery('tbl_mrf_studcard','','', $sortfield, $startat, $endat,  $this->schema);
	} catch(Exception $e) {
         return NULL;
    }
}

public function studfacultycount2($startat,$endat)
{
	try {
		//print $startat;die('dsdsa');
			if($startat!=0){
			$startat++;
			}
			//$keys = array();
      //$keys[] = array( 'field'=>'exemption', 'value'=>'1');
      $sortfield = 'faculty2';
			
			//print $endat;die;
		  return $this->objSoapClient->getlimitQuery('tbl_mrf_studcard','','', $sortfield, $startat, $endat,  $this->schema);
	} catch(Exception $e) {
         return NULL;
    }
}
/*------------------------------------------------------------------------------*/ 
/**
 * Method to return all students by course
 * @param string $filter a SQL WHERE clause,specifies the order of resultset returned by SQL statement 
 * @return array $course  
 */
public function allbycourse(){
    $query = 'SELECT * FROM '.$this->schema.'.tbl_mrf_studcard order by COURSE';
    $results =  $this->objSoapClient->genericQuery($query);
     return $results;
}

public function studcoursecount($startat,$endat)
{
	try {
		//print $startat;die('dsdsa');
			if($startat!=0){
			$startat++;
			}
			//$keys = array();
      //$keys[] = array( 'field'=>'exemption', 'value'=>'1');
      $sortfield = 'course,faculty';
			
			//print $endat;die;
		  return $this->objSoapClient->getlimitQuery('tbl_mrf_studcard','','', $sortfield, $startat, $endat,  $this->schema);
	} catch(Exception $e) {
         return NULL;
    }
}

public function studcoursecount2($startat,$endat)
{
	try {
		//print $startat;die('dsdsa');
			if($startat!=0){
			$startat++;
			}
			//$keys = array();
      //$keys[] = array( 'field'=>'exemption', 'value'=>'1');
      $sortfield = 'course2,faculty2';
			
			//print $endat;die;
		  return $this->objSoapClient->getlimitQuery('tbl_mrf_studcard','','', $sortfield, $startat, $endat,  $this->schema);
	} catch(Exception $e) {
         return NULL;
    }
}
/*------------------------------------------------------------------------------*/ 
/**
 * Method to count the no of students that are sd cases
 * @param string $stmt used in a SQL WHERE clause,counts all id values where sdcase = true(1) and exemption = false(2) 
 * @return array $totalsd 
 */
public function allsdcases($where =  'where sdcase = 1 and exemption = 0'){
     try {
        
           $query = 'SELECT count(id) sdresult FROM '.$this->schema. '.tbl_mrf_studcard '.$where;
           $results =  $this->objSoapClient->genericQuery($query);
           return $results;

         }catch(Exception $e) {
          return NULL;
      }
} 

public function studsdcasecount($startat,$endat)
{
	try {
		//print $startat;die('dsdsa');
			if($startat!=0){
			$startat++;
			}
			//$keys = array();
      //$keys[] = array( 'field'=>'exemption', 'value'=>'1');
      $sortfield = 'surname';
			
			//print $endat;die;
		  return $this->objSoapClient->getlimitQuery('tbl_mrf_studcard','','', $sortfield, $startat, $endat,  $this->schema);
	} catch(Exception $e) {
         return NULL;
    }
} 
/*------------------------------------------------------------------------------*/
/**
 * Method to get all student details that are sdcases
 * @param string $stmt used in a SQL WHERE clause,counts all id values where sdcase = true(1) and exemption = false(2)
 * @return array $totalsd   
 */
  public function sdcases($where = 'where sdcase = 1 and exemption = 2 '){
       try {

          
        $query = 'SELECT surname, name, schoolname, sdcase FROM '.$this->schema.'.tbl_mrf_studcard '.$where;
        $results =  $this->objSoapClient->genericQuery($query);
        return $results;

      }catch(Exception $e) {
       return NULL;
      }
}  
/*------------------------------------------------------------------------------*/ 
/**
 * Method to get the total of students interested in a specific faculty
 * @param string $stmt SQL statement used to count all students interested in a faculty 
 * @return array $faculty  
 */
  public function facultyinterest($where = 'group by faculty'){
   try {
      
      $query = 'SELECT faculty, COUNT(faculty) totalstudent FROM'.$this->schema.'.tbl_studcard'.$where;
      $results =  $this->objSoapClient->genericQuery($query);
      return $results;

      }catch(Exception $e) {
          return NULL;
      }
  }
/*------------------------------------------------------------------------------*/
/**
 * Method to get all students that qualify for entry based on exemption and relevantsubject == true(1)
 * @param string $stmt SQL statement counts all student id's where exemption = true(1) and relevantsubject = true(1)
 * @return array $val
 */  
  
    public function allstudq($where = 'where exemption = 1 and sdcase = 0')
    {
       try {

            $query = 'SELECT COUNT(id) AS ENTRY FROM '.$this->schema.'.tbl_mrf_studcard '.$where;
            $result = $this->objSoapClient->genericQuery($query);
            return $result;
            
  		    // return $result[0]->ENTRY;
        } catch (Exception $e) {
            return NULL;
        }
     } 
/*------------------------------------------------------------------------------*/
/**
 * Method to get all students from a certain faculty
 * @param string $facultynameval, variable passed to function
 * @param string $stmt SQL statement, gets student info where faculty name in tbl studcard =  $facultynameval
 * @return array $facultyresult  
 */  
//  public function allstudfaculty($facultynameval){
  //get all students entered for a specific faculty
//    $stmt = "select surname, name, schoolname, faculty from tbl_studcard where faculty = '$facultynameval'";
//    $facultyresult = $this->getArray($stmt);
//    return $facultyresult;  
    
//  } 
  
public function allstudfaculty($facultynameval, $field = 'faculty', $start = 0, $limit = 0)
{
       try {
            $keys = array();
            $keys[] = array( 'field' => $field, 'value' => $facultynameval);

            //$fields = array( 'FACULTY', 'SURNAME', 'NAME');

            return $this->objSoapClient->getlimitQuery('tbl_mrf_studcard ','', $keys, 'FACULTY', $start, $limit,  $this->schema);

       } catch(Exception $e) {
         return NULL;
       }
}

public function allstudfaculty2($facultynameval2, $field = 'faculty2', $start = 0, $limit = 0)
{
       try {
            $keys = array();
            $keys[] = array( 'field' => $field, 'value' => $facultynameval2);

            //$fields = array( 'FACULTY', 'SURNAME', 'NAME');

            return $this->objSoapClient->getlimitQuery('tbl_mrf_studcard ','', $keys, 'FACULTY', $start, $limit,  $this->schema);

       } catch(Exception $e) {
         return NULL;
       }
}
/*------------------------------------------------------------------------------*/ 
/**
 * Method to get all students qualifying for an exemption within a certain faculty
 * @param string $facultynameval, variable passed to function
 * @param string $stmt SQL statement, gets student info where exemption = true(1) and faculty name = $facultynameval 
 * @return array $facexemption 
 */
//  public function facultyexempted($facultynameval){
  //get all students that entered for the faculty and has an exemption
//    $stmt = "Select surname, name,schoolname,exemption,faculty from tbl_studcard where exemption = 1 and faculty = '$facultynameval'";
//    $facexemption = $this->getArray($stmt);
//    return $facexemption;
//  }
    public function facultyexempted($facultynameval)
    {
        try {
            $where  = "where exemption = 1 and faculty = '$facultynameval'";
            $query = 'SELECT * FROM '.$this->schema.'.tbl_mrf_studcard '.$where;
            return $this->objSoapClient->genericQuery($query);

        } catch(Exception $e) {
            return NULL;
        }
    }
    
    public function facultyexempted2($facultynameval2)
    {
        try {
            $where  = "where exemption = 1 and faculty2 = '$facultynameval2'";
            $query = 'SELECT * FROM '.$this->schema.'.tbl_mrf_studcard '.$where;
            return $this->objSoapClient->genericQuery($query);

        } catch(Exception $e) {
            return NULL;
        }
    }    
/*------------------------------------------------------------------------------*/
/**
 * Method to get all students by faculty that have relevantsubject 
 * @param string $facultynameval, variable passed to function
 * @return array $facexemption   
 */
//  public function facsubject($facultynameval){
  //get all students that entered for the faculty and have relevant subjects
//    $stmt = "Select surname, name, schoolname,faculty,relevantsubject from tbl_studcard where relevantsubject = 1 and faculty = '$facultynameval'";
//    $facexemption = $this->getArray($stmt);
//    return $facexemption;
//  }
public function facsubject($facultynameval){
  try {
            $where  = "where relevantsubject = 1 and faculty = '$facultynameval'";
            $query = 'SELECT * FROM '.$this->schema.'.tbl_mrf_studcard '.$where;
            return $this->objSoapClient->genericQuery($query);

      } catch(Exception $e) {
            return NULL;
      }
}

public function facsubject2($facultynameval2){
  try {
            $where  = "where relevantsubject = 1 and faculty2 = '$facultynameval2'";
            $query = 'SELECT * FROM '.$this->schema.'.tbl_mrf_studcard '.$where;
            return $this->objSoapClient->genericQuery($query);

      } catch(Exception $e) {
            return NULL;
      }
}
/*------------------------------------------------------------------------------*/
/**
 * Method to get all students with a same course where with a certain facultyname
 * @return $facultycourse  
 */
//  public function faccourse($facultynameval){
//    $stmt = "Select surname, name, schoolname,faculty,course from tbl_studcard where faculty = '$facultynameval'";
//    $facultycourse = $this->getArray($stmt);
//    return $facultycourse;
//  }
public function faccourse($facultynameval, $field = 'faculty', $start = 0, $limit = 0)
{
       try {
            $keys = array();
            $keys[] = array( 'field' => $field, 'value' => $facultynameval);

            $fields = array( 'SURNAME', 'NAME','SCHOOLNAME', 'FACULTY','COURSE');

            return $this->objSoapClient->getlimitQuery('tbl_mrf_studcard ',$fields, $keys, 'COURSE', $start, $limit,  $this->schema);

       } catch(Exception $e) {
         return NULL;
       }
}

public function faccourse2($facultynameval2, $field = 'faculty2', $start = 0, $limit = 0)
{
       try {
            $keys = array();
            $keys[] = array( 'field' => $field, 'value' => $facultynameval2);
                
           $fields = array( 'SURNAME', 'NAME','SCHOOLNAME', 'FACULTY2','COURSE2');

            return $this->objSoapClient->getlimitQuery('tbl_mrf_studcard ', $fields, $keys, 'COURSE2', $start, $limit,  $this->schema);

       } catch(Exception $e) {
         return NULL;
       }
}

/*------------------------------------------------------------------------------*/
/**
 * Method to get all students qualifying for an sdcase within a certain faculty
 * @param string $facultynameval, variable passed to function
 * @param string $stmt SQL statement, gets student info where sdcase = true(1) and faculty name = $facultynameval 
 * @return array $facultycourse 
 */
/* public function facultysdcase($facultynameval){
    $stmt = "Select surname, name, schoolname,faculty,sdcase from tbl_studcard where sdcase = 1 and faculty = '$facultynameval'";
//    $facultycourse = $this->getArray($stmt);
//    return $facultycourse;
  }*/
/*    public function facultysdcase($where = 'where sdcase = 1',$facultynameval)
    {
        try {
            $query = 'SELECT surname, name, schoolname,faculty,sdcase FROM '.$this->schema.'.tbl_mrf_studcard '.$where.$facultynameval;
            return $this->objSoapClient->genericQuery($query);

        } catch(Exception $e) {
            return NULL;
        }
    }*/
public function facultysdcase($facultynameval){
    try {
            $where  = "where sdcase = 1 and faculty = '$facultynameval'";
            $query = 'SELECT * FROM '.$this->schema.'.tbl_mrf_studcard '.$where;
            return $this->objSoapClient->genericQuery($query);

       } catch(Exception $e) {
            return NULL;
       }
}    

public function facultysdcase2($facultynameval2){
    try {
            $where  = "where sdcase = 1 and faculty2 = '$facultynameval2'";
            $query = 'SELECT * FROM '.$this->schema.'.tbl_mrf_studcard '.$where;
            return $this->objSoapClient->genericQuery($query);

       } catch(Exception $e) {
            return NULL;
       }
}  
/*------------------------------------------------------------------------------*/
/**
 * Method to count the total amount of students for a certain faculty
 * @param string $facultynameval, variable passed to function
 * @return array $facultycount  
 */
//  public function facultycount($facultyname){
//    $stmt = "Select surname, name,postaddress, schoolname,faculty,count(id) totstud from tbl_studcard where faculty = '$facultyname' group by postaddress";
//    $facultycount = $this->getArray($stmt);
//    return $facultycount;
//  }
public function facultycount($facultyname)
{
       try {
            $where = "where faculty = '$facultyname'"; 
            $query = 'SELECT surname, name, postaddress, schoolname, faculty FROM '.$this->schema.'.tbl_mrf_studcard '.$where;
            $result = $this->objSoapClient->genericQuery($query);
            return $result;

       } catch(Exception $e) {
        return NULL;
       }
}

/*public function facultycount($facultyname, $field = 'FACULTY', $start = 0, $limit = 0)
{
     //  try {
            $keys = array();
            $keys[] = array( 'field' => $field, 'value' => $facultyname);

            $fields = array( 'surname', 'name', 'postaddress', 'schoolname', 'faculty');

            return $this->objSoapClient->getlimitQuery('tbl_mrf_studcard ',$fields, $keys, 'surname', $start, $limit, $this->schema);

     //  } catch(Exception $e) {
     //    return NULL;
     //  }
}*/

public function faccountval($facultyname){
        
            $where = "where faculty = '$facultyname'"; 
            $query = 'SELECT count(id) AS totstud FROM '.$this->schema.'.tbl_mrf_studcard '.$where;
            $result = $this->objSoapClient->genericQuery($query);
            return $result;
}
/*------------------------------------------------------------------------------*/
/**
 * Method used to get all unique student addresses 
 * @return array $studaddress  
 */
public function getallstudaddy()
{
       try {
            $where  = 'order by surname, name';
            $query = 'SELECT distinct(postaddress), surname, name FROM '.$this->schema.'.tbl_mrf_studcard '.$where;
            $result = $this->objSoapClient->genericQuery($query);
            return $result;
          
       } catch (Exception $e) {
            return NULL;
       }
}  
/*------------------------------------------------------------------------------*/
/**
 * Method to return all students by area 
 * $studarea, contains info retrieved from tbl studcard ...calls db table function getArray() that gets all data 
 * NOTE: tbl sluactivities and tbl studcard are joined by schoolname in order to get results 
 * @return array $studarea 
 */
/*  public function getstudbyarea()
  {
   // $stmt = "select stud.surname, stud.name, stud.postaddress, stud.schoolname studschoool, slu.schoolname sluschool, slu.area from tbl_studcard stud, tbl_sluactivities slu where stud.schoolname = slu.schoolname order by slu.area";
    $where = 'where tbl_mrf_studcard.schoolname = tbl_mrf_sluactivities.schoolname';
    $query = 'SELECT tbl_mrf_studcard.name, tbl_mrf_studcard.surname, tbl_mrf_studcard.schoolname studschoolname, tbl_mrf_studcard.postaddress, tbl_mrf_sluactivities.schoolname, tbl_mrf_sluactivities.area FROM '.$this->schema.'.tbl_mrf_studcard , tbl_mrf_sluactivities '.$where;
    $result = $this->objSoapClient->genericQuery($query);
    return $result;
//    $studarea = $this->getArray($stmt);
//    return $studarea;
  }*/
/*------------------------------------------------------------------------------*/
/**
 * Method to return all students by area 
 * $studarea, contains info retrieved from tbl studcard ...calls db table function getArray() that gets all data 
 * NOTE: tbl sluactivities and tbl studcard are joined by schoolname in order to get results 
 * @return array $studarea 
 */
  public function getstudbyarea()
  {
    $query = 'SELECT name, surname, schoolname, postaddress, area FROM '.$this->schema.'.tbl_mrf_studcard';
    $result = $this->objSoapClient->genericQuery($query);
    return $result;
  }

  
public function studarealimit($startat,$endat)
{
	try {
		//print $startat;die('dsdsa');
			if($startat!=0){
			$startat++;
			}
			//$keys = array();
      //$keys[] = array( 'field'=>'exemption', 'value'=>'1');
      $sortfield = 'surname';
			
			//print $endat;die;
		  return $this->objSoapClient->getlimitQuery('tbl_mrf_studcard','','', $sortfield, $startat, $endat,  $this->schema);
	} catch(Exception $e) {
         return NULL;
    }
}
/*------------------------------------------------------------------------------*/
/**
  * Method to get a students information using their id no
  *   
  * @param string $idnumber: The value to search for in the database
  * @param string $field: The field to search on in the database
  * @return array: The array of matching records from the database
  * @access public
 
 *///getstudbyid($idsearch, $field = 'IDNUMBER', $start = 0, $limit = 0)
public function getstudbyid($idnumber, $field = 'IDNUMBER', $firstname, $field2 = 'NAME', $lastname, $field3 = 'SURNAME', $start = 0, $limit = 0) 
{
      // try {
            $keys  = array();
            $keys[] = array( 'field' => $field, 'value' => $idnumber);
            $keys[] = array( 'field' => $field2,'value' => $lastname);
            $keys[] = array( 'field' => $field3,'value' => $firstname);
            
            $fields = array( 'ID', 'ENTRYDATE', 'IDNUMBER', 'SURNAME', 'NAME', 'SCHOOLNAME', 'POSTADDRESS', 'POSTCODE', 'AREA', 'TELNUMBER', 'TELCODE', 'EXEMPTION', 'FACULTY', 'COURSE', 'SDCASE', 'CELLNUMBER', 'STUDEMAIL','SUBJECT1','SUBJECT2','SUBJECT3','SUBJECT4','SUBJECT5','SUBJECT6','SUBJECT7','INFODEPARTMENT','FACULTY2','COURSE2','RESIDENCE','GRADETYPE1','GRADETYPE2','GRADETYPE3','GRADETYPE4','GRADETYPE5','GRADETYPE6','GRADETYPE7','GRADE','DOB','MARK1','MARK2','MARK3','MARK4','MARK5','MARK6','MARK7','MARKGRADE','SPORTPART','LEADERSHIPPOS','SPORTCODE','ACHIEVELEVEL','SPORTBURSARY');

            return $this->objSoapClient->getlimitQuery('tbl_mrf_studcard',$fields, $keys, 'IDNUMBER', $start, $limit,  $this->schema);

      // } catch(Exception $e) {
        // return NULL;
       //}
      
}
  
/*public function getstudbyid11($idnum, $field = 'IDNUMBER', $start = 0, $limit = 0) 
{
       try {
            $keys  = array();
            $keys[] = array( 'field' => $field, 'value' => $idnum);
            
            $fields = array( 'ID', 'ENTRYDATE', 'SURNAME', 'NAME', 'SCHOOLNAME', 'POSTADDRESS', 'POSTCODE', 'AREA', 'TELNUMBER', 'TELCODE', 'EXEMPTION', 'FACULTY', 'COURSE', 'RELEVANTSUBJECT', 'SDCASE');

            return $this->objSoapClient->getlimitQuery('tbl_mrf_studcard',$fields, $keys, 'IDNUMBER', $start, $limit,  $this->schema);

       } catch(Exception $e) {
         return NULL;
       }
      
}*/  
  
/*------------------------------------------------------------------------------*/
/**
 * Method used to update student info with matching idno
 * @param string $idsearch
 * @param date $date
 * @param string $surname
 * @param string $name
 * @param string $schoolname
 * @param string $postaddress
 * @param string $postcode
 * @param string $telnumber
 * @param string $telcode
 * @param boolean $exemption
 * @param string $faculty
 * @param string $course
 * @param boolean $relsubject
 * @param boolean $sdcase   
 * @param string $stmt SQL statement,updates all columns specified with variable values passed to function 
 * @return array $stmt 
 */
  public function updatestudinfo($createdby,$datecreate,$idsearch,$date,$surname,$name,$dob,$grade,$schoolname,$postaddress,$postcode,$telnumber,$telcode,$exemption,$faculty,$course,$sdcase,$areastud,$grade,$cellnumber,$studemail,$subject1,$subject2,$subject3,$subject4,$subject5,$subject6,$subject7,$info,$faculty2,$course2,$residence,$gradetype1,$gradetype2,$gradetype3,$gradetype4,$gradetype5,$gradetype6,$gradetype7,$latestID,$markval,$markval2,$markval3,$markval4,$markval5,$markval6,$markval7,$markgrade,$confirmation,$sportPart,$leadershipPos,$sportCode,$achievlevel,$sportBursary)
  {
  
            $data = array();
            $data[] = array( 'field' => 'id', 'value' => "init" . "_" . rand(1000,9999) . "_" . time());
            $data[] = array( 'field' => 'createdby', 'value' => $createdby);
            $data[] = array( 'field' => 'datecreated', 'value' => "to_date('".$datecreate."', 'dd-mm-yyyy')");
            $data[] = array( 'field' => 'idnumber', 'value' => $latestID);
            $data[] = array( 'field' => 'entryDate', 'value' => "to_date('".$date."', 'dd-mm-yyyy')");
          //  $data[] = array( 'field' => 'name', 'value' => $name);
          //  $data[] = array( 'field' => 'surname', 'value' => $surname);
            $data[] = array( 'field' => 'dob', 'value' => "to_date('".$dob."', 'dd-mm-yyyy')");
         
            $data[] = array( 'field' => 'schoolname', 'value' => $schoolname);
            $data[] = array( 'field' => 'postaddress', 'value' => $postaddress);
            $data[] = array( 'field' => 'postcode', 'value' => $postcode);
            $data[] = array( 'field' => 'telnumber', 'value' => $telnumber);
            $data[] = array( 'field' => 'telcode', 'value' => $telcode);
            $data[] = array( 'field' => 'exemption', 'value' => $exemption);
            $data[] = array( 'field' => 'faculty', 'value' => $faculty);
            $data[] = array( 'field' => 'course', 'value' => $course);
            $data[] = array( 'field' => 'sdcase', 'value' => $sdcase);
            $data[] = array( 'field' => 'area', 'value' => $areastud);
            $data[] = array( 'field' => 'grade', 'value' => $grade);            
            $data[] = array( 'field' => 'cellnumber', 'value' => $cellnumber);
            $data[] = array( 'field' => 'studemail', 'value' => $studemail);
            $data[] = array( 'field' => 'subject1', 'value' => $subject1);
            $data[] = array( 'field' => 'subject2', 'value' => $subject2);
            $data[] = array( 'field' => 'subject3', 'value' => $subject3);
            $data[] = array( 'field' => 'subject4', 'value' => $subject4);
            $data[] = array( 'field' => 'subject5', 'value' => $subject5);
            $data[] = array( 'field' => 'subject6', 'value' => $subject6);
            $data[] = array( 'field' => 'subject7', 'value' => $subject7);
            $data[] = array( 'field' => 'infodepartment', 'value' => $info); 
            $data[] = array( 'field' => 'faculty2', 'value' => $faculty2);
            $data[] = array( 'field' => 'course2', 'value' => $course2);
            $data[] = array( 'field' => 'residence', 'value' => $residence);
            $data[] = array( 'field' => 'gradetype1', 'value' => $gradetype1);
            $data[] = array( 'field' => 'gradetype2', 'value' => $gradetype2);
            $data[] = array( 'field' => 'gradetype3', 'value' => $gradetype3);
            $data[] = array( 'field' => 'gradetype4', 'value' => $gradetype4);
            $data[] = array( 'field' => 'gradetype5', 'value' => $gradetype5);
            $data[] = array( 'field' => 'gradetype6', 'value' => $gradetype6);
            $data[] = array( 'field' => 'gradetype7', 'value' => $gradetype7);
            $data[] = array( 'field' => 'markgrade', 'value' => $markgrade);
            $data[] = array( 'field' => 'MARK1', 'value' => $markval);
            $data[] = array( 'field' => 'MARK2', 'value' => $markval2);
            $data[] = array( 'field' => 'MARK3', 'value' => $markval3);
            $data[] = array( 'field' => 'MARK4', 'value' => $markval4);
            $data[] = array( 'field' => 'MARK5', 'value' => $markval5);
            $data[] = array( 'field' => 'MARK6', 'value' => $markval6);
            $data[] = array( 'field' => 'MARK7', 'value' => $markval7);
            $data[] = array( 'field' => 'CONFIRM', 'value' => $confirmation);
            $data[] = array( 'field' => 'SPORTPART', 'value' => $sportPart);
            $data[] = array( 'field' => 'LEADERSHIPPOS', 'value' => $leadershipPos);
            $data[] = array( 'field' => 'SPORTCODE', 'value' => $sportCode);
            $data[] = array( 'field' => 'ACHIEVELEVEL', 'value' => $achievlevel);
            $data[] = array( 'field' => 'SPORTBURSARY', 'value' => $sportBursary);
            
            $keys = array();
            //$keys[] = array( 'field' => 'idnumber', 'value' => $idsearch);
            $keys[] = array( 'field' => 'surname', 'value' => $surname);
            $keys[] = array( 'field' => 'name', 'value' => $name);
            
            
            return $this->objSoapClient->writeQuery('tbl_mrf_studcard', $data , $keys, $this->schema);
            //return $data;
  }

/*------------------------------------------------------------------------------*/
/**
 * Method to get all faculty values from tbl_academicprogramme_faculties
 * @return $result
 * @access public  
 */
public function getFaculties()
{
     try {

            $query = 'SELECT * FROM '.$this->schema.'.tbl_mrf_academicprog_faculties ';
            $result = $this->objSoapClient->genericQuery($query);
            return $result;
        
    } catch (Exception $e) {
             return NULL;
    }
 
}
/*------------------------------------------------------------------------------*/
public function getFacultyCode($field = 'NAME', $facultylist)
{
     // try {
            $keys  = array();
            $keys[] = array( 'field' => $field, 'value' => $facultylist);
            
            $fields = array( 'CODE');
            return $this->objSoapClient->getlimitQuery('tbl_mrf_academicprog_faculties',$fields, $keys, 'ID', 0, 0,  $this->schema);

      // } catch(Exception $e) {
         //return NULL;
       //} 
}
/*------------------------------------------------------------------------------*/
/**
 * Method to get all faculty values from tbl_academicprogramme_courses
 * @return $result
 * @access public 
 */
public function getcourse()
{
    try {

            $query = 'SELECT * FROM '.$this->schema.'.tbl_mrf_academicprog_courses ';
            $result = $this->objSoapClient->genericQuery($query);
            return $result;
          
    } catch (Exception $e) {
            return NULL;
    }
 
}
/*------------------------------------------------------------------------------*/
/*public function getCoursevalues($where = 'where FACULTY_CODE = '$facultycode'')
{
     // try {
            $query = 'SELECT NAME FROM '.$this->schema.'.tbl_mrf_academicprog_courses '.$where;
            $result = $this->objSoapClient->genericQuery($query);
            return $result;

      // } catch(Exception $e) {
         //return NULL;
       //} 
}
/*------------------------------------------------------------------------------*/

/**
 * Method to get all school name values from tbl_mrf_schoolnames
 * @access public 
 * @return array $result 
 */
public function getSchools()
{
    try {

            $query = 'SELECT * FROM '.$this->schema.'.tbl_mrf_schoolnames ';
            $result = $this->objSoapClient->genericQuery($query);
            return $result;
          
    } catch (Exception $e) {
            return NULL;
    }
 
}
/*------------------------------------------------------------------------------*/
/**
 * Method to get all postal information i.e POSTCODES AND AREA NAMES from table tbl_mrf_postcodes
 * @return array $result
 * @access public  
 */
public function getPostInfo()
{
    try {

            $query = 'SELECT * FROM '.$this->schema.'.tbl_mrf_postcodes ';
            $result = $this->objSoapClient->genericQuery($query);
            return $result;
            
    } catch (Exception $e) {
            return NULL;
    }
 
}
/*------------------------------------------------------------------------------*/

}//end of class 
?>
