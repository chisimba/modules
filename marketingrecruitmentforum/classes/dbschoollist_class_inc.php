<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}

/**
* This is a class used to perform database manipulation on school information
* @package 
* @category sems
* @copyright 2004, University of the Western Cape & AVOIR Project
* @license GNU GPL
* @version
* @author Colleen Tinker
*/
class dbschoollist extends object{ 


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
            $objSysconfig =& $this->getObject('dbsysconfig', 'sysconfig');
            $this->objSoapClient = new SoapClient('http://'.$objSysconfig->getValue('SEMSGENERICWS', 'marketingrecruitmentforum'));
        } catch (Exception $e) {
           die($e->getMessage());
        }
        
   }
/*-----------------------------------------------------------------------------------------------------------------------------------*/
/**
 * @author Colleen Tinker
 * Method to write variables passed to the function into the table tbl schoollist
 * @param string $createdby
 * @param date $datecreate
 * @param string $result
 * @param string $schooladdress
 * @param string $scharea
 * @param string $schprovince
 * @param string $telnumber
 * @param string $faxnumber
 * @param string $email
 * @param string $principal
 * @param string $guidanceteacher
 * @param return object $results  
 */ 
public function addsschoollist($createdby,$datecreate,$namevalue,$schooladdress,$scharea,$schprovince,$txttelcode,$telnumber,$txtfaxcode,$faxnumber,$email,$principal,$guidanceteacher,$principalemailaddy,$principalcellno,$guidanceteacheamil,$guidanceteachcellno,$schcodeval)
{
        try {
            $keys = NULL;
            $data = array();
            $data[] = array( 'field' => 'id', 'value' => "init" . "_" . rand(1000,9999) . "_" . time());
            $data[] = array( 'field' => 'createdby', 'value' => $createdby);
            $data[] = array( 'field' => 'datecreated', 'value' => "to_date('".$datecreate."', 'dd-mm-yyyy HH24:mi:ss')");
            $data[] = array( 'field' => 'schoolname', 'value' => $namevalue);
            $data[] = array( 'field' => 'schooladdress', 'value' => $schooladdress);
            $data[] = array( 'field' => 'schoolarea', 'value' => $scharea);
            $data[] = array( 'field' => 'schoolprov', 'value' => $schprovince);
            $data[] = array( 'field' => 'telcode', 'value' => $txttelcode);
            $data[] = array( 'field' => 'telnumber', 'value' => $telnumber);
            $data[] = array( 'field' => 'faxcode', 'value' => $txtfaxcode);
            $data[] = array( 'field' => 'faxnumber', 'value' => $faxnumber);
            $data[] = array( 'field' => 'email', 'value' => $email);
            $data[] = array( 'field' => 'principal', 'value' => $principal);
            $data[] = array( 'field' => 'principalemail', 'value' => $principalemailaddy);
            $data[] = array( 'field' => 'principalCellno', 'value' => $principalcellno);
            $data[] = array( 'field' => 'guidanceteacher', 'value' => $guidanceteacher);
            $data[] = array( 'field' => 'guidanceteachemail', 'value' => $guidanceteacheamil);
            $data[] = array( 'field' => 'guidanceteachcellno', 'value' => $guidanceteachcellno);
            //$data[] = array( 'field' => 'schoolcode', 'value' => $schcodeval);

            $keys = array();
             $results = $this->objSoapClient->writeQuery('tbl_mrf_schoollist', $data ,$keys,  $this->schema);
            return $results;
       } catch (Exception $e) {
            return NULL;
       }   
}
  
	
/**
 * @author Colleen Tinker
 * Method used to retrieve all unique school names from table schoollist in db
 * @param return object $results,   
 */

public function getallsschools()
{
          try {    
            $query = 'select distinct(schoolname) FROM '.$this->schema.'.tbl_mrf_schoollist';
            $results =  $this->objSoapClient->genericQuery($query);
            return $results;
          } catch (Exception $e) {
            return NULL;
          } 
}

public function schoollimitdata($startat,$endat)
{
	try {
		//print $startat;die('dsdsa');
			if($startat!=0){
			$startat++;
			}
			$sortfield = 'schoolname';
			
			//print $endat;die;
		  return $this->objSoapClient->getlimitQuery('tbl_mrf_schoollist','','', $sortfield, $startat, $endat,  $this->schema);
	} catch(Exception $e) {
         return NULL;
    }
}     

/**
 * @author Colleen Tinker
 * Method to get all school information matching the school name passed to function
 * @param stmt, contains sql query to retrieve all school details where school name in db matches the school name passed to the function
 * @param return object name    
 */
/*  public function getschoolbyname($namevalue)
  {
      $stmt = "select distinct(schoolname),schooladdress,area,province,telnumber,faxnumber,email,principal,guidanceteacher from tbl_schoollist where schoolname = '$namevalue'";
      $name = $this->getArray($stmt);
      return  $name;
  }*/
  
/**
  * Method to get all school information matching the school name passed to function
  *   
  * @param string $schname: The value to search for in the database
  * @param string $field: The field to search on in the database
  * @return array: The array of matching records from the database
  * @access public
  */
public function getschoolbyname($schname, $field = 'schoolname', $start = 0, $limit = 0)
{
       try {
            $keys = array();
            $keys[] = array( 'field' => $field, 'value' => $schname);
            $fields = array( 'SCHOOLNAME', 'SCHOOLADDRESS', 'SCHOOLAREA','SCHOOLPROV','TELNUMBER','FAXNUMBER','EMAIL','PRINCIPAL','PRINCIPALEMAIL','PRINCIPALCELLNO','GUIDANCETEACHER','GUIDANCETEACHEMAIL','GUIDANCETEACHCELLNO','TELCODE','FAXCODE');
            return $this->objSoapClient->getlimitQuery('tbl_mrf_schoollist', $fields, $keys, $field, 0, 0,  $this->schema);

       } catch(Exception $e) {
         return NULL;
       }
}
/**
 * @author Colleen Tinker
 * Method used to return unique school names, area from tables tbl schoollist 
  * @param return object $results   
 */
public function getschoolbyarea()
{
        try {
            $query = 'SELECT DISTINCT(SCHOOLNAME), schoolarea FROM '.$this->schema.'.tbl_mrf_schoollist';
            $results =  $this->objSoapClient->genericQuery($query);
            return $results;
        } catch(Exception $e) {
         return NULL;
        }
       
}

public function schoolarealimit($startat,$endat)
{
	try {
			if($startat!=0){
			$startat++;
			}
			$sortfield = 'schoolarea';
			return $this->objSoapClient->getlimitQuery('tbl_mrf_schoollist','','', $sortfield, $startat, $endat,  $this->schema);
	} catch(Exception $e) {
         return NULL;
    }
}  
     
/**
 * @author Colleen Tinker
 * Method used to return unique school names, province from tables tbl schoollist 
 * @param return object $results   
 */
public function getschoolbyprovince()
{
        try {
            $query = 'SELECT DISTINCT(SCHOOLNAME), schoolprov FROM '.$this->schema.'.tbl_mrf_schoollist';
            $results =  $this->objSoapClient->genericQuery($query);
            return $results;
        } catch(Exception $e) {
          return NULL;
        }
       
}   

public function schoolprovlimit($startat,$endat)
{
	try {
			if($startat!=0){
			$startat++;
			}
			
      $sortfield = 'schoolprov';
			
			return $this->objSoapClient->getlimitQuery('tbl_mrf_schoollist','','', $sortfield, $startat, $endat,  $this->schema);
	} catch(Exception $e) {
         return NULL;
    }
}  
/**
 * @param Colleen Tinker
 * Method to update school information if already exist within db
 * @param string $createdby
 * @param date $datecreate
 * @param string $result
 * @param string $schooladdress
 * @param string $scharea
 * @param string $schprovince
 * @param string $telnumber
 * @param string $faxnumber
 * @param string $email
 * @param string $principal
 * @param string $guidanceteacher 
 * @param return object $res   
 * This function is used to update exisiting school info within the db, it uses the $key value as a search indicator and if this value exist replace it with new info 
 */
public function updateschoollist($createdby,$datecreate,$schname,$schooladdress,$scharea,$schprovince,$txttelcode,$telnumber,$txtfaxcode,$faxnumber,$email,$principal,$guidanceteacher,$principalemailaddy,$principalcellno,$guidanceteacheamil,$guidanceteachcellno,$schcodeval)
{
       try {
            $data = array();
            $data[] = array( 'field' => 'id', 'value' => "init" . "_" . rand(1000,9999) . "_" . time());
            $data[] = array( 'field' => 'createdby', 'value' => $createdby);
            $data[] = array( 'field' => 'datecreated', 'value' => "to_date('".$datecreate."', 'dd-mm-yyyy HH24:mi:ss')");
            $data[] = array( 'field' => 'schooladdress', 'value' => $schooladdress);
            $data[] = array( 'field' => 'schoolarea', 'value' => $scharea);
            $data[] = array( 'field' => 'schoolprov', 'value' => $schprovince);
            $data[] = array( 'field' => 'telcode', 'value' => $txttelcode);
            $data[] = array( 'field' => 'telnumber', 'value' => $telnumber);
            $data[] = array( 'field' => 'faxcode', 'value' => $txtfaxcode);
            $data[] = array( 'field' => 'faxnumber', 'value' => $faxnumber);
            $data[] = array( 'field' => 'email', 'value' => $email);
            $data[] = array( 'field' => 'principal', 'value' => $principal);
            $data[] = array( 'field' => 'principalemail', 'value' => $principalemailaddy);
            $data[] = array( 'field' => 'principalCellno', 'value' => $principalcellno);
            $data[] = array( 'field' => 'guidanceteacher', 'value' => $guidanceteacher);
            $data[] = array( 'field' => 'guidanceteachemail', 'value' => $guidanceteacheamil);
            $data[] = array( 'field' => 'guidanceteachcellno', 'value' => $guidanceteachcellno);
            //$data[] = array( 'field' => 'schoolcode', 'value' => $schcodeval);
            
            $keys = array();
            $keys[] = array( 'field' => 'schoolname', 'value' => $schname);
            return $this->objSoapClient->writeQuery('tbl_mrf_schoollist ', $data ,$keys,  $this->schema);
        } catch(Exception $e) {
         return NULL;
        }
    
}

}//end of class 
?>
