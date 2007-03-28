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
    *
    * @var string $objCrypt The encryption object.
    *
    * @access public
    *
    */
    public $objCrypt;
    /**
    *
    * @var string $sessionKey The session key for the encryption object.
    *
    * @access public
    *
    */
    public $sessionKey;
    
    

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
            $this->objCrypt = $this->newObject('blowcryptclient', 'blowcrypt');
            $this->sessionKey = $this->objCrypt->getSessionKey();
        } catch (Exception $e) {
           die($e->getMessage());
        }
        
   }
   

    /**
    *
    * function to retrieve and decrypt encrypted data from webservice
    *
    * @access public
    * @param string $value: The value to search for in the database
    * @param string $field: The field to search on in the database
    * @param string $tableName: The tablename in the database
    * @param string $orderBy: The orderby to search on in the database
    * @param string $retFields: The fields to return
    * @return array: The array of matching records from the database
    *
    */
public function getWSQuery($tableName, $orderBy, $value = null, $field = null, $keys = null, $start = 0, $limit = 0, $retFields = null)
    {
        if (is_null($keys)) {
            $keys = array();
            $keys[] = array( 'field' => $field, 'value' => $value);
        }
       

        $tableName = $this->objCrypt->encrypt($tableName, $this->sessionKey);
        $keys = $this->objCrypt->encryptArray($keys, $this->sessionKey);

        $keys = $this->objCrypt->arrayToObject($keys);
        $orderBy = $this->objCrypt->encrypt($orderBy, $this->sessionKey);
       
        $arr = $this->objSoapClient->getlimitQuery($tableName, $retFields, $keys, $orderBy, $start, $limit, 'SEMS');
        $arr = $this->objCrypt->encryptObject($arr, $this->sessionKey, TRUE);
        return $arr;

}

    /**
    *
    * function to retrieve and decrypt encrypted data from webservice
    *
    * @access public
    * @param string $value: The value to search for in the database
    * @param string $field: The field to search on in the database
    * @param string $tableName: The tablename in the database
    * @param string $orderBy: The orderby to search on in the database
    * @param string $retFields: The fields to return
    * @return array: The array of matching records from the database
    *
    */
public function writeWSQuery($tableName, $data, $keys = null)
    {
        if (!is_null($keys)) {
            if (count($keys) > 0) {
                $keys = $this->objCrypt->arrayToObject($keys);
            } else {
                $keys = null;
            }
        }
        $data = $this->objCrypt->arrayToObject($data);


        $schema = 'SEMS';
        $insert = TRUE;

        $where = '';
		if (is_array($keys)){
            if (count($keys)){
                $insert = FALSE;
                $counter = 0;
                foreach ($keys as $key){
                    if ($counter == 0){
                        $where = " WHERE ".$key->field."='".$key->value."'";
                    }else{
                        $where .= "AND ".$key->field."='".$key->value."'";
                    }
                    $counter++;
				}
            }
        }

        if ($insert){

            $counter = 1;
            $fields = "";
            $values = "";

            if (is_array($data)){
                foreach ($data as $item){
                    if ($counter == count($data)){
                        $fields .= $item->field;
						if (substr($item->value, 0, 7) == 'to_date') {
	                        $values .= $item->value;
						} else {
                            $values .= "'".$item->value."'";
						}
                    }else{
                        $fields .= $item->field.",";
						if (substr($item->value, 0, 7) == 'to_date') {
	                        $values .= $item->value.",";
						} else {
                            $values .= "'".$item->value."',";
						}
                    }
                    $counter++;
                }
            }else{
                return FALSE;
            }
    		$query = "INSERT INTO ".strtoupper($schema).".".strtoupper($tableName). " (".$fields.") VALUES (".$values.")";

        }else{
            $counter = 1;
            $fields = "";

            if (is_array($data)){
                foreach ($data as $item){
                    if ($counter == count($data)){
						if (substr($item->value, 0, 7) == 'to_date') {
                            $fields .= $item->field." = ".$item->value;
						} else {
                            $fields .= $item->field." = "."'".$item->value."'";
						}
                    }else{
						if (substr($item->value, 0, 7) == 'to_date') {
                            $fields .= $item->field." = ".$item->value.",";
						} else {
                            $fields .= $item->field." = "."'".$item->value."',";
						}
                    }
                    $counter++;
                }
            }else{
                return FALSE;
            }
    		$query = "UPDATE ".strtoupper($schema).".".strtoupper($tableName). " SET ".$fields.$where;
        }
        $query = $this->objCrypt->encrypt($query, $this->sessionKey);

        $arr = $this->objSoapClient->genericQuery($query);


//        $arr = $this->objSoapClient->writeQuery($tableName, $data ,$keys,  'SEMS');
        return $arr;
}
    /**
    *
    * function to retrieve and decrypt encrypted data from webservice
    *
    * @access public
    * @param string $value: The value to search for in the database
    * @param string $field: The field to search on in the database
    * @param string $tableName: The tablename in the database
    * @param string $orderBy: The orderby to search on in the database
    * @param string $retFields: The fields to return
    * @return array: The array of matching records from the database
    *
    */
public function getWSGenericQuery($query)
{
      //var_dump($query);
        $query = $this->objCrypt->encrypt($query, $this->sessionKey);
      //  var_dump($query);
       // var_dump($this->sessionKey);
        $arr =  $this->objSoapClient->genericQuery($query);
        $arr = $this->objCrypt->encryptObject($arr, $this->sessionKey, TRUE);
        return $arr;
}

/**
 *retrieve data from the linc db
 */ 
public function getWSGenericQuery2($query)
{
        $query = $this->objCrypt->encrypt($query, $this->sessionKey);
        $arr =  $this->objSoapClient2->genericQuery($query);
        $arr = $this->objCrypt->encryptObject($arr, $this->sessionKey, TRUE);
        return $arr;
}
    /**
    *
    * function to retrieve and decrypt encrypted data from webservice
    *
    * @access public
    * @param string $value: The value to search for in the database
    * @param string $field: The field to search on in the database
    * @param string $tableName: The tablename in the database
    * @param string $orderBy: The orderby to search on in the database
    * @param string $retFields: The fields to return
    * @return array: The array of matching records from the database
    *
    */
public function getWSCount($tableName, $value = null, $field = null, $keys = null)
{
        if (is_null($keys)) {
            $keys = array();
            $keys[] = array( 'field' => $field, 'value' => $value);
        }

        $tableName = $this->objCrypt->encrypt($tableName, $this->sessionKey);
        $keys = $this->objCrypt->encryptArray($keys, $this->sessionKey);

        $keys = $this->objCrypt->arrayToObject($keys);
        $count = $this->objSoapClient->getQueryCount($tableName, $keys, 'SEMS');
        $count = $this->objCrypt->decrypt($count, $this->sessionKey);
        return $count;

}

/**
 * Method to insert all student card data captured into db tbl studcard
 * @param string $fields: The fields to write to the database
 * @return bool: returns true if save succeeded or else false
 * @access public
 *
 */
 
public function addstudcard($createdby,$datecreate,$id,$date,$surname,$name,$dob,$grade,$schoolname,$postaddress,$postcode,$telnumber,$telcode,$exemption,$faculty,$course,$sdcase,$areastud,$grade,$cellnumber,$studemail,$subject1,$subject2,$subject3,$subject4,$subject5,$subject6,$subject7,$info,$faculty2,$course2,$residence,$gradetype1,$gradetype2,$gradetype3,$gradetype4,$gradetype5,$gradetype6,$gradetype7,$markval,$markval2,$markval3,$markval4,$markval5,$markval6,$markval7,$markgrade,$confirmation,$sportPart,$leadershipPos,$sportCode,$achievlevel,$sportBursary,$keys = NULL)
{
      try {
            $data = array();
            $data[] = array( 'field' => 'ID', 'value' => "init" . "_" . rand(1000,9999) . "_" . time());
            $data[] = array( 'field' => 'CREADTEDBY', 'value' => $createdby);
            $data[] = array( 'field' => 'DATECREATED', 'value' => "to_date('".$datecreate."', 'dd-mm-yyyy')");
            $data[] = array( 'field' => 'IDNUMBER', 'value' => $id);
            $data[] = array( 'field' => 'ENTRYDATE', 'value' => "to_date('".$date."', 'dd-mm-yyyy')");
            $data[] = array( 'field' => 'NAME', 'value' => $name);
            $data[] = array( 'field' => 'SURNAME', 'value' => $surname);
            $data[] = array( 'field' => 'SCHOOLNAME', 'value' => $schoolname);
            $data[] = array( 'field' => 'POSTADDRESS', 'value' => $postaddress);
            $data[] = array( 'field' => 'POSTCODE', 'value' => $postcode);
            $data[] = array( 'field' => 'TELNUMBER', 'value' => $telnumber);
            $data[] = array( 'field' => 'TELCODE', 'value' => $telcode);
            $data[] = array( 'field' => 'EXEMPTION', 'value' => $exemption);
            $data[] = array( 'field' => 'FACULTY', 'value' => $faculty);
            $data[] = array( 'field' => 'COURSE', 'value' => $course);
            $data[] = array( 'field' => 'SDCASE', 'value' => $sdcase);
            $data[] = array( 'field' => 'AREA', 'value' => $areastud);
            $data[] = array( 'field' => 'DOB', 'value' => "to_date('".$dob."', 'dd-mm-yyyy')");
            $data[] = array( 'field' => 'GRADE', 'value' => $grade);            
            $data[] = array( 'field' => 'CELLNUMBER', 'value' => $cellnumber);
            $data[] = array( 'field' => 'STUDEMAIL', 'value' => $studemail);
            $data[] = array( 'field' => 'SUBJECT1', 'value' => $subject1);
            $data[] = array( 'field' => 'SUBJECT2', 'value' => $subject2);
            $data[] = array( 'field' => 'SUBJECT3', 'value' => $subject3);
            $data[] = array( 'field' => 'SUBJECT4', 'value' => $subject4);
            $data[] = array( 'field' => 'SUBJECT5', 'value' => $subject5);
            $data[] = array( 'field' => 'SUBJECT6', 'value' => $subject6);
            $data[] = array( 'field' => 'SUBJECT7', 'value' => $subject7);
            $data[] = array( 'field' => 'INFODEPARTMENT', 'value' => $info); 
            $data[] = array( 'field' => 'FACULTY2', 'value' => $faculty2);
            $data[] = array( 'field' => 'COURSE2', 'value' => $course2);
            $data[] = array( 'field' => 'RESIDENCE', 'value' => $residence);
            $data[] = array( 'field' => 'GRADETYPE1', 'value' => $gradetype1);
            $data[] = array( 'field' => 'GRADETYPE2', 'value' => $gradetype2);
            $data[] = array( 'field' => 'GRADETYPE3', 'value' => $gradetype3);
            $data[] = array( 'field' => 'GRADETYPE4', 'value' => $gradetype4);
            $data[] = array( 'field' => 'GRADETYPE5', 'value' => $gradetype5);
            $data[] = array( 'field' => 'GRADETYPE6', 'value' => $gradetype6);
            $data[] = array( 'field' => 'GRADETYPE7', 'value' => $gradetype7);
            $data[] = array( 'field' => 'MARKGRADE', 'value' => $markgrade);
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
           return  $this->writeWSQuery('tbl_mrf_studcard', $data ,$keys);
      } catch(Exception $e) {
        return NULL;
      }
            
        
}	

/**
 * Method to retrieve all studcard info captured from db tbl studcard
 * @return array: The array of matching records from the database
 * @access public
 */
   
public function getallstudinfo()
{
        try {
              $query = 'SELECT * FROM '.$this->schema.'.tbl_mrf_studcard order by ENTRYDATE';
              return $this->getWSGenericQuery($query);
        }catch(Exception $e) {
          return NULL;
        }
}


/**
 * Method to retrieve all studcard info captured from db tbl studcard using limits
 * @return array: The array of matching records from the database
 * @access public
 */
function getstudinfo($startat,$endat)
{
	try {
		
    			if($startat!=0){
    			$startat++;
    			}
			
	 	return $this->objSoapClient->getWSQuery('tbl_mrf_studcard','ENTRYDATE', null, null,'', $startat, $endat,'');
		} catch(Exception $e) {
         return NULL;
       }
}
/**
 * Method to retrieve school subjects from linc db
 */  
public function getSubjects()
{
        try {
        
            $query = 'SELECT LNGDSC, MTRSBJCDE FROM '.$this->schema2.'.stdsys_mtrsb';
            $results = $this->getWSGenericQuery2($query);
            return $results;
            
        } catch(Exception $e) {
          return NULL;
        }
}

/**
 * Method used to get details of all students that qualify/sucessfull for entry into the university
 * NOTE: entry qualification requires sdcase = 0 and exemption to be = '1'... (i.e) true(boolean variable)
 * @return array $studresults     
 */
public function getstudqualify($where = 'WHERE SDCASE = 0 and EXEMPTION = 1')
{
        try {
            $query = 'SELECT NAME, SURNAME, EXEMPTION FROM '.$this->schema.'.tbl_mrf_studcard '.$where;
            $results =  $this->getWSGenericQuery($query);
            return $results;

        } catch(Exception $e) {
          return NULL;
        }
}


/**
 * Method to return student particulars of all students from a certain school
 * @param string $useToPopTbl schoolname value passed to function
 * $stmt, contains sql query used to get students details where schoolname in studcad tbl equal / same as variable $useToPopTbl passed to func
 * @return true of false   
 */
public function getstudschool($useToPopTbl, $field = 'SCHOOLNAME', $start = 0, $limit = 0)
{
       try {
            $keys = array();
            $keys[] = array( 'field' => $field, 'value' => $useToPopTbl);
            $fields = array( 'SCHOOLNAME', 'SURNAME', 'NAME');

            return $this->getWSQuery('tbl_mrf_studcard ','SCHOOLNAME', null, null, $keys, $start, $limit, $fields);

      } catch(Exception $e) {
         return NULL;
       }
}
 
/**
 * Method to get all students that qualify for an exemption
 * $filter, contains sql query that gets students where exemption is true 
 * @return array  $results
 */   
public function allstudsexemption($where = 'where EXEMPTION = 1')
{
        try {
            $query = 'SELECT SCHOOLNAME, SURNAME, NAME, EXEMPTION FROM '.$this->schema.'tbl_mrf_studcard '.$where;
            $results =  $this->getWSGenericQuery($query);
            return $results;

        } catch(Exception $e) {
          return NULL;
        }
} 

// get all students that qualify for an exemption using limits           
public function exemption($startat,$endat)
{
	try {
		
			if($startat!=0){
			$startat++;
			}
			$keys = array();
      $keys[] = array( 'field'=>'EXEMPTION', 'value'=>'1');
      $orderBy = 'SCHOOLNAME';
                                    	
		  return $this->getWSQuery('tbl_mrf_studcard',$orderBy,null,null,$keys,$startat, $endat,'');
	} catch(Exception $e) {
         return NULL;
    }
}

/**
 * Method to return all students ordered by faculty
 * @return array $faculty    
 */   
public function allbyfaculty(){
    try {
            $query = 'SELECT * FROM '.$this->schema.'tbl_mrf_studcard order by FACULTY';
            $results =  $this->getWSGenericQuery($query);
            return $results;
    } catch(Exception $e) {
         return NULL;
    }
}

//to return all students ordered by facultyusing limts
public function studfacultycount($startat,$endat)
{
	try {
		
  			if($startat!=0){
  			$startat++;
  			}
			  $orderBy = 'FACULTY';
			
			
		  return $this->getWSQuery('tbl_mrf_studcard', $orderBy, null, null, '', '', $startat, $endat,'');
	} catch(Exception $e) {
         return NULL;
    }
}

//to return all students ordered by 2nd faculty choice using limts
public function studfacultycount2($startat,$endat)
{
	try {
			if($startat!=0){
			$startat++;
			}
		  $sortfield = 'FACULTY2';
			
		  return $this->getWSQuery('tbl_mrf_studcard', $orderBy, null, null, '', '', $startat, $endat,'');
 	} catch(Exception $e) {
         return NULL;
  }
}
 
/**
 * Method to return all students by course
 * @param string $filter a SQL WHERE clause,specifies the order of resultset returned by SQL statement 
 * @return array $course  
 */
public function allbycourse(){
    $query = 'SELECT * FROM '.$this->schema.'.tbl_mrf_studcard order by COURSE';
    $results =  $this->getWSGenericQuery($query);
    return $results;
}
/**
 * Method to return all students by course using limits
 * @param string $filter a SQL WHERE clause,specifies the order of resultset returned by SQL statement 
 * @return array $course  
 */

public function studcoursecount($startat,$endat)
{
	try {

			if($startat!=0){
			$startat++;
			}
      $sortfield = 'COURSE,FACULTY';
			
		  return $this->getWSQuery('tbl_mrf_studcard', $orderBy, null, null, '', '', $startat, $endat,'');
	   } catch(Exception $e) {
         return NULL;
    }
}
//2nd faculty and course details
public function studcoursecount2($startat,$endat)
{
	try {
		
			if($startat!=0){
			$startat++;
			}
		  $sortfield = 'COURSE2,FACULTY2';
			
		  return $this->getWSQuery('tbl_mrf_studcard', $orderBy, null, null, '', '', $startat, $endat,'');
	    } catch(Exception $e) {
         return NULL;
    }
}
 
/**
 * Method to count the no of students that are sd cases
 * @param string $stmt used in a SQL WHERE clause,counts all id values where sdcase = true(1) and exemption = false(2) 
 * @return array $totalsd 
 */
public function allsdcases($where =  'where SDCASE = 1 and EXEMPTION = 0'){
     try {
        
           $query = 'SELECT COUNT(ID) SDRESULT FROM '.$this->schema. '.tbl_mrf_studcard '.$where;
           $results =  $this->getWSGenericQuery($query);
           return $results;

         }catch(Exception $e) {
          return NULL;
      }
} 

public function studsdcasecount($startat,$endat)
{
	try {
		
			if($startat!=0){
			$startat++;
			}
		  $orderBy = 'SURNAME';
		
		   return $this->getWSQuery('tbl_mrf_studcard', $orderBy, null, null, '', '', $startat, $endat,'');
	} catch(Exception $e) {
         return NULL;
    }
} 

/**
 * Method to get all student details that are sdcases
 * @param string $stmt used in a SQL WHERE clause,counts all id values where sdcase = true(1) and exemption = false(2)
 * @return array $totalsd   
 */
public function sdcases($where = 'where SDCASE = 1 and EXEMPTION = 2 '){
       try {

          
        $query = 'SELECT SURNAME, NAME, SCHOOLNAME, SDCASE FROM '.$this->schema.'.tbl_mrf_studcard '.$where;
        $results =  $this->getWSGenericQuery($query);
        return $results;

      }catch(Exception $e) {
       return NULL;
      }
}  
 
/**
 * Method to get the total of students interested in a specific faculty
 * @param string $stmt SQL statement used to count all students interested in a faculty 
 * @return array $faculty  
 */
public function facultyinterest($where = 'group by FACULTY'){
   try {
      
      $query = 'SELECT FACULTY, COUNT(FACULTY) TOTALSTUDENT FROM'.$this->schema.'.tbl_studcard'.$where;
      $results =  $this->getWSGenericQuery($query);
      return $results;

      }catch(Exception $e) {
          return NULL;
      }
  }

/**
 * Method to get all students that qualify for entry based on exemption and relevantsubject == true(1)
 * @param string $stmt SQL statement counts all student id's where exemption = true(1) and relevantsubject = true(1)
 * @return array $val
 */  
  
public function allstudq($where = 'where EXEMPTION = 1 and SDCASE = 0')
{
   try {

        $query = 'SELECT COUNT(ID) AS ENTRY FROM '.$this->schema.'.tbl_mrf_studcard '.$where;
        $result = $this->getWSGenericQuery($query);
        return $result;
            
  
   } catch (Exception $e) {
          return NULL;
   }
} 

/**
 * Method to get all students from a certain faculty
 * @param string $facultynameval, variable passed to function
 * @return array $facultyresult  
 */  
public function allstudfaculty($facultynameval, $field = 'faculty', $start = 0, $limit = 0)
{
       try {
            $keys = array();
            $keys[] = array( 'field' => $field, 'value' => $facultynameval);
            
            return $this->getWSQuery('tbl_mrf_studcard ','FACULTY','','', $keys, $start, $limit,'');

       } catch(Exception $e) {
         return NULL;
       }
}

public function allstudfaculty2($facultynameval2, $field = 'faculty2', $start = 0, $limit = 0)
{
       try {
            $keys = array();
            $keys[] = array( 'field' => $field, 'value' => $facultynameval2);

            return $this->getWSQuery('tbl_mrf_studcard ','FACULTY2','','', $keys, $start, $limit,'');

       } catch(Exception $e) {
         return NULL;
       }
}
 
/**
 * Method to get all students qualifying for an exemption within a certain faculty
 * @param string $facultynameval, variable passed to function
 * @return array $facexemption 
 */
public function facultyexempted($facultynameval)
{
        try {
            $where  = "where EXEMPTION = 1 and FACULTY = '$facultynameval'";
            $query = 'SELECT * FROM '.$this->schema.'.tbl_mrf_studcard '.$where;
            return $this->getWSGenericQuery($query);

        } catch(Exception $e) {
            return NULL;
        }
}
    
public function facultyexempted2($facultynameval2)
    {
        try {
            $where  = "where EXEMPTION = 1 and FACULTY2 = '$facultynameval2'";
            $query = 'SELECT * FROM '.$this->schema.'.tbl_mrf_studcard '.$where;
            return $this->getWSGenericQuery($query);

        } catch(Exception $e) {
            return NULL;
        }
}
    
/**
 * Method to get all students with a same course where with a certain facultyname
 * @return $facultycourse  
 */
public function faccourse($facultynameval, $field = 'FACULTY', $start = 0, $limit = 0)
{
       try {
            $keys = array();
            $keys[] = array( 'field' => $field, 'value' => $facultynameval);
            $fields = array( 'SURNAME', 'NAME','SCHOOLNAME', 'FACULTY','COURSE');

            return $this->getWSQuery('tbl_mrf_studcard ','COURSE',$facultynameval,$field,$keys, $start, $limit,$fields);

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

           return $this->getWSQuery('tbl_mrf_studcard ','COURSE2',$facultynameval2,$field,$keys, $start, $limit,$fields);

       } catch(Exception $e) {
         return NULL;
       }
}


/**
 * Method to get all students qualifying for an sdcase within a certain faculty
 * @param string $facultynameval, variable passed to function
 * @param string $stmt SQL statement, gets student info where sdcase = true(1) and faculty name = $facultynameval 
 * @return array $facultycourse 
 */
public function facultysdcase($facultynameval){
    try {
            $where  = "where SDCASE = 1 and FACULTY = '$facultynameval'";
            $query = 'SELECT * FROM '.$this->schema.'tbl_mrf_studcard '.$where;
            return $this->getWSGenericQuery($query);

       } catch(Exception $e) {
            return NULL;
       }
}    

public function facultysdcase2($facultynameval2){
    try {
            $where  = "where SDCASE = 1 and FACULTY2 = '$facultynameval2'";
            $query = 'SELECT * FROM '.$this->schema.'tbl_mrf_studcard '.$where;
            return $this->getWSGenericQuery($query);

       } catch(Exception $e) {
            return NULL;
       }
}  

/**
 * Method to count the total amount of students for a certain faculty
 * @param string $facultynameval, variable passed to function
 * @return array $facultycount  
 */

public function facultycount($facultyname)
{
       try {
            $where = "where FACULTY = '$facultyname'"; 
            $query = 'SELECT SURNAME, NAME, POSTADDRESS, SCHOOLNAME, FACULTY FROM '.$this->schema.'tbl_mrf_studcard '.$where;
            $result = $this->getWSGenericQuery($query);
            return $result;

       } catch(Exception $e) {
        return NULL;
       }
}

public function faccountval($facultyname){
        
            $where = "where faculty = '$facultyname'"; 
            $query = 'SELECT count(ID) AS TOTSTUD FROM '.$this->schema.'tbl_mrf_studcard '.$where;
            $result = $this->getWSGenericQuery($query);
            return $result;
}

/**
 * Method used to get all unique student addresses 
 * @return array $studaddress  
 */
public function getallstudaddy()
{
       try {
            $where  = 'order by SURNAME, NAME';
            $query = 'SELECT DISTINCT(POSTADDRESS), SURNAME, NAME FROM '.$this->schema.'.tbl_mrf_studcard '.$where;
            $result = $this->getWSGenericQuery($query);
            return $result;
          
       } catch (Exception $e) {
            return NULL;
       }
}  
/**
 * Method to return all students by area 
 * $studarea, contains info retrieved from tbl studcard ...calls db table function getArray() that gets all data 
 * NOTE: tbl sluactivities and tbl studcard are joined by schoolname in order to get results 
 * @return array $studarea 
 */
public function getstudbyarea()
{
    try {
        $query = 'SELECT NAME, SURNAME, SCHOOLNAME, POSTADDRESS, AREA FROM '.$this->schema.'.tbl_mrf_studcard';
        $result = $this->getWSGenericQuery($query);
        return $result;
    } catch (Exception $e) {
            return NULL;
    }
}

  
public function studarealimit($startat,$endat)
{
	try {
		
			if($startat!=0){
			$startat++;
			}
		  $sortfield = 'surname';    
                       
		  return $this->getWSQuery('tbl_mrf_studcard',$sortfield,'','','', $startat, $endat,'');
	} catch(Exception $e) {
         return NULL;
  }
}
/**
  * Method to get a students information using their id no
  *   
  * @param string $idnumber: The value to search for in the database
  * @param string $field: The field to search on in the database
  * @return array: The array of matching records from the database
  * @access public
 
 */
public function getstudbyid($idnumber, $field = 'IDNUMBER', $firstname, $field2 = 'NAME', $lastname, $field3 = 'SURNAME', $start = 0, $limit = 0) 
{
   //try {
            $keys  = array();
            $keys[] = array( 'field' => $field, 'value' => $idnumber);
            $keys[] = array( 'field' => $field2,'value' => $lastname);
            $keys[] = array( 'field' => $field3,'value' => $firstname);
            //var_dump($keys);
            $fields = array( 'ID', 'ENTRYDATE', 'IDNUMBER', 'SURNAME', 'NAME', 'SCHOOLNAME', 'POSTADDRESS', 'POSTCODE', 'AREA', 'TELNUMBER', 'TELCODE', 'EXEMPTION', 'FACULTY', 'COURSE', 'SDCASE', 'CELLNUMBER', 'STUDEMAIL','SUBJECT1','SUBJECT2','SUBJECT3','SUBJECT4','SUBJECT5','SUBJECT6','SUBJECT7','INFODEPARTMENT','FACULTY2','COURSE2','RESIDENCE','GRADETYPE1','GRADETYPE2','GRADETYPE3','GRADETYPE4','GRADETYPE5','GRADETYPE6','GRADETYPE7','GRADE','DOB','MARK1','MARK2','MARK3','MARK4','MARK5','MARK6','MARK7','MARKGRADE','SPORTPART','LEADERSHIPPOS','SPORTCODE','ACHIEVELEVEL','SPORTBURSARY');
                          
            return $this->getWSQuery('tbl_mrf_studcard','IDNUMBER',null,null,$keys,$start, $limit,$fields);

     // } catch(Exception $e) {
       //   return NULL;
      //}
}
  
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
        try {
            $data = array();
            $data[] = array( 'field' => 'ID', 'value' => "init" . "_" . rand(1000,9999) . "_" . time());
            $data[] = array( 'field' => 'CREATEDBY', 'value' => $createdby);
            $data[] = array( 'field' => 'DATECREATED', 'value' => "to_date('".$datecreate."', 'dd-mm-yyyy')");
            $data[] = array( 'field' => 'IDNUMBER', 'value' => $latestID);
            $data[] = array( 'field' => 'ENTRYDATE', 'value' => "to_date('".$date."', 'dd-mm-yyyy')");
          //  $data[] = array( 'field' => 'name', 'value' => $name);
          //  $data[] = array( 'field' => 'surname', 'value' => $surname);
            $data[] = array( 'field' => 'DOB', 'value' => "to_date('".$dob."', 'dd-mm-yyyy')");
            $data[] = array( 'field' => 'SCHOOLNAME', 'value' => $schoolname);
            $data[] = array( 'field' => 'POSTADDRESS', 'value' => $postaddress);
            $data[] = array( 'field' => 'POSTCODE', 'value' => $postcode);
            $data[] = array( 'field' => 'TELNUMBER', 'value' => $telnumber);
            $data[] = array( 'field' => 'TELCODE', 'value' => $telcode);
            $data[] = array( 'field' => 'EXEMPTION', 'value' => $exemption);
            $data[] = array( 'field' => 'FACULTY', 'value' => $faculty);
            $data[] = array( 'field' => 'COURSE', 'value' => $course);
            $data[] = array( 'field' => 'SDCASE', 'value' => $sdcase);
            $data[] = array( 'field' => 'AREA', 'value' => $areastud);
            $data[] = array( 'field' => 'GRADE', 'value' => $grade);            
            $data[] = array( 'field' => 'CELLNUMBER', 'value' => $cellnumber);
            $data[] = array( 'field' => 'STUDEMAIL', 'value' => $studemail);
            $data[] = array( 'field' => 'SUBJECT1', 'value' => $subject1);
            $data[] = array( 'field' => 'SUBJECT2', 'value' => $subject2);
            $data[] = array( 'field' => 'SUBJECT3', 'value' => $subject3);
            $data[] = array( 'field' => 'SUBJECT4', 'value' => $subject4);
            $data[] = array( 'field' => 'SUBJECT5', 'value' => $subject5);
            $data[] = array( 'field' => 'SUBJECT6', 'value' => $subject6);
            $data[] = array( 'field' => 'SUBJECT7', 'value' => $subject7);
            $data[] = array( 'field' => 'INFODEPARTMENT', 'value' => $info); 
            $data[] = array( 'field' => 'FACULTY2', 'value' => $faculty2);
            $data[] = array( 'field' => 'COURSE2', 'value' => $course2);
            $data[] = array( 'field' => 'RESIDENCE', 'value' => $residence);
            $data[] = array( 'field' => 'GRADETYPE1', 'value' => $gradetype1);
            $data[] = array( 'field' => 'GRADETYPE2', 'value' => $gradetype2);
            $data[] = array( 'field' => 'GRADETYPE3', 'value' => $gradetype3);
            $data[] = array( 'field' => 'GRADETYPE4', 'value' => $gradetype4);
            $data[] = array( 'field' => 'GRADETYPE5', 'value' => $gradetype5);
            $data[] = array( 'field' => 'GRADETYPE6', 'value' => $gradetype6);
            $data[] = array( 'field' => 'GRADETYPE7', 'value' => $gradetype7);
            $data[] = array( 'field' => 'MARKGRADE', 'value' => $markgrade);
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
            $keys[] = array( 'field' => 'SURNAME', 'value' => $surname);
            $keys[] = array( 'field' => 'NAME', 'value' => $name);
            
            
            return $this->writeWSQuery('tbl_mrf_studcard', $data , $keys);
      } catch(Exception $e) {
          return NULL;
      }
            
}


/**
 * Method to get all faculty values from tbl_academicprogramme_faculties
 * @return $result
 * @access public  
 */
public function getFaculties()
{
     try {

            $query = 'SELECT * FROM '.$this->schema.'tbl_mrf_academicprog_faculties ';
            $result = $this->getWSGenericQuery($query);
            return $result;
        
    } catch (Exception $e) {
             return NULL;
    }
 
}
/**
 * Method to get all faculty values from tbl_academicprogramme_courses
 * @return $result
 * @access public 
 */
public function getcourse()
{
    try {

            $query = 'SELECT * FROM '.$this->schema.'.tbl_mrf_academicprog_courses ';
            $result = $this->getWSGenericQuery($query);
            return $result;
          
    } catch (Exception $e) {
            return NULL;
    }
 
}

/**
 * Method to get all school name values from tbl_mrf_schoolnames
 * @access public 
 * @return array $result 
 */
public function getSchools()
{
    //try {

            $query = 'SELECT * FROM '.$this->schema.'.tbl_mrf_schoolnames';
            $result = $this->getWSGenericQuery($query);
            return $result;
          
    //} catch (Exception $e) {
      //      return NULL;
    //}
 
}

/**
 * Method to get all postal information i.e POSTCODES AND AREA NAMES from table tbl_mrf_postcodes
 * @return array $result
 * @access public  
 */
public function getPostInfo()
{
    try {

            $query = 'SELECT * FROM '.$this->schema.'.tbl_mrf_postcodes ';
            $result = $this->getWSGenericQuery($query);
            return $result;
            
    } catch (Exception $e) {
            return NULL;
    }
 
}


}//end of class 
?>
