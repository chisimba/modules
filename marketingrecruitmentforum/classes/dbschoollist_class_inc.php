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
            $objSysconfig =& $this->getObject('dbsysconfig', 'sysconfig');
            $this->objSoapClient = new SoapClient('http://'.$objSysconfig->getValue('SEMSGENERICWS', 'marketingrecruitmentforum'));
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
        $query = $this->objCrypt->encrypt($query, $this->sessionKey);
        $arr =  $this->objSoapClient->genericQuery($query);
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
            $data[] = array( 'field' => 'ID', 'value' => "init" . "_" . rand(1000,9999) . "_" . time());
            $data[] = array( 'field' => 'CREATEDBY', 'value' => $createdby);
            $data[] = array( 'field' => 'DATECREATED', 'value' => "to_date('".$datecreate."', 'dd-mm-yyyy')");
            $data[] = array( 'field' => 'SCHOOLNAME', 'value' => $namevalue);
            $data[] = array( 'field' => 'SCHOOLADDRESS', 'value' => $schooladdress);
            $data[] = array( 'field' => 'SCHOOLAREA', 'value' => $scharea);
            $data[] = array( 'field' => 'SCHOOLPROV', 'value' => $schprovince);
            $data[] = array( 'field' => 'TELCODE', 'value' => $txttelcode);
            $data[] = array( 'field' => 'TELNUMBER', 'value' => $telnumber);
            $data[] = array( 'field' => 'FAXCODE', 'value' => $txtfaxcode);
            $data[] = array( 'field' => 'FAXNUMBER', 'value' => $faxnumber);
            $data[] = array( 'field' => 'EMAIL', 'value' => $email);
            $data[] = array( 'field' => 'PRINCIPAL', 'value' => $principal);
            $data[] = array( 'field' => 'PRINCIPALEMAIL', 'value' => $principalemailaddy);
            $data[] = array( 'field' => 'PRINCIPALCELLNO', 'value' => $principalcellno);
            $data[] = array( 'field' => 'GUIDANCETEACHER', 'value' => $guidanceteacher);
            $data[] = array( 'field' => 'GUIDANCETEACHEMAIL', 'value' => $guidanceteacheamil);
            $data[] = array( 'field' => 'GUIDANCETEACHCELLNO', 'value' => $guidanceteachcellno);
            //$data[] = array( 'field' => 'schoolcode', 'value' => $schcodeval);

            $keys = array();
            $results = $this->writeWSQuery('tbl_mrf_schoollist', $data ,$keys);
            return $results;
       }catch (Exception $e) {
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
            $query = 'SELECT DISTINCT(SCHOOLNAME) FROM '.$this->schema.'.tbl_mrf_schoollist';
            $results =  $this->getWSGenericQuery($query);
            return $results;
          } catch (Exception $e) {
            return NULL;
          } 
}
/**
 * @author Colleen Tinker
 * Method used to retrieve all unique school names from table schoollist in db,using limits
 * @param return object $results,   
 */

public function schoollimitdata($startat,$endat)
{
	try {
		
  			if($startat!=0){
  			$startat++;
  			}
  			$sortfield = 'SCHOOLNAME';
			
			
		    return $this->getWSQuery('tbl_mrf_schoollist',$sortfield,'','', '', $startat, $endat, '');
	} catch(Exception $e) {
         return NULL;
    }
}     
  
/**
  * Method to get all school information matching the school name passed to function
  *   
  * @param string $schname: The value to search for in the database
  * @param string $field: The field to search on in the database
  * @return array: The array of matching records from the database
  * @access public
  */
public function getschoolbyname($schname, $field = 'SCHOOLNAME', $start = 0, $limit = 0)
{
       try {
            $keys = array();
            $keys[] = array( 'field' => $field, 'value' => $schname);
            $fields = array( 'SCHOOLNAME', 'SCHOOLADDRESS', 'SCHOOLAREA','SCHOOLPROV','TELNUMBER','FAXNUMBER','EMAIL','PRINCIPAL','PRINCIPALEMAIL','PRINCIPALCELLNO','GUIDANCETEACHER','GUIDANCETEACHEMAIL','GUIDANCETEACHCELLNO','TELCODE','FAXCODE');
            
            return $this->getWSQuery('tbl_mrf_schoollist','SCHOOLNAME',null, null, $keys, 0, 0, $fields);

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
            $query = 'SELECT DISTINCT(SCHOOLNAME), SCHOOLAREA FROM '.$this->schema.'.tbl_mrf_schoollist';
            $results =  $this->getWSGenericQuery($query);
            return $results;
        } catch(Exception $e) {
         return NULL;
        }
       
}
/**
 * @author Colleen Tinker
 * Method used to return unique school names, area from tables tbl schoollist using limits
  * @param return object $results   
 */
public function schoolarealimit($startat,$endat)
{
	try {
  			if($startat!=0){
  			$startat++;
  			}
			  $sortfield = 'SCHOOLAREA';
			return $this->getWSQuery('tbl_mrf_schoollist',$sortfield,null,null,'' , $startat, $endat,'');
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
            $query = 'SELECT DISTINCT(SCHOOLNAME), SCHOOLPROV FROM '.$this->schema.'.tbl_mrf_schoollist';
            $results =  $this->getWSGenericQuery($query);
            return $results;
        } catch(Exception $e) {
          return NULL;
        }
       
}   
/**
 * @author Colleen Tinker
 * Method used to return unique school names, province from tables tbl schoollist using limits 
 * @param return object $results   
 */
public function schoolprovlimit($startat,$endat)
{
	try {
			if($startat!=0){
			$startat++;
			}
			
      $sortfield = 'SCHOOLPROV';
			return $this->getWSQuery('tbl_mrf_schoollist',$sortfield,null,null,'' , $startat, $endat,'');
	  }catch(Exception $e) {
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
            $data[] = array( 'field' => 'ID', 'value' => "init" . "_" . rand(1000,9999) . "_" . time());
            $data[] = array( 'field' => 'CREATEDBY', 'value' => $createdby);
            $data[] = array( 'field' => 'DATECREATED', 'value' => "to_date('".$datecreate."', 'dd-mm-yyyy')");
            $data[] = array( 'field' => 'SCHOOLADDRESS', 'value' => $schooladdress);
            $data[] = array( 'field' => 'SCHOOLAREA', 'value' => $scharea);
            $data[] = array( 'field' => 'SCHOOLPROV', 'value' => $schprovince);
            $data[] = array( 'field' => 'TELCODE', 'value' => $txttelcode);
            $data[] = array( 'field' => 'TELNUMBER', 'value' => $telnumber);
            $data[] = array( 'field' => 'FAXCODE', 'value' => $txtfaxcode);
            $data[] = array( 'field' => 'FAXNUMBER', 'value' => $faxnumber);
            $data[] = array( 'field' => 'EMAIL', 'value' => $email);
            $data[] = array( 'field' => 'PRINCIPAL', 'value' => $principal);
            $data[] = array( 'field' => 'PRINCIPALEMAIL', 'value' => $principalemailaddy);
            $data[] = array( 'field' => 'PRINCIPALCELLNO', 'value' => $principalcellno);
            $data[] = array( 'field' => 'GUIDANCETEACHER', 'value' => $guidanceteacher);
            $data[] = array( 'field' => 'GUIDANCETEACHEMAIL', 'value' => $guidanceteacheamil);
            $data[] = array( 'field' => 'GUIDANCETEACHCELLNO', 'value' => $guidanceteachcellno);
            //$data[] = array( 'field' => 'schoolcode', 'value' => $schcodeval);
            
            $keys = array();
            $keys[] = array( 'field' => 'SCHOOLNAME', 'value' => $schname);
            
            return $this->writeWSQuery('tbl_mrf_schoollist ', $data ,$keys);
        } catch(Exception $e) {
         return NULL;
        }
    
}

}//end of class 
?>
