<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check
/**
* This class use to perform all database manipulation of all SLU info captured
* @package 
* @category sems
* @copyright 2005, University of the Western Cape & AVOIR Project
* @license GNU GPL
* @version
* @author Colleen Tinker
*/
class dbsluactivities extends object{ 

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
 * Method to insert all SLU Activity into tbl sluactivities
 */
public function addsluactivity($cdate,$ddate,$date,$activity,$schoolname,$area,$province)
{  
    try {
            $data = array();
            $data[] = array( 'field' => 'ID', 'value' => "init" . "_" . rand(1000,9999) . "_" . time());
            $data[] = array( 'field' => 'CREATEDBY', 'value' => $cdate);
            $data[] = array( 'field' => 'DATECREATED', 'value' => "to_date('".$ddate."', 'dd-mm-yyyy')");
            $data[] = array( 'field' => 'ACTIVITYDATE', 'value' => "to_date('".$date."', 'dd-mm-yyyy')");
            $data[] = array( 'field' => 'ACTIVITY', 'value' => $activity);
            $data[] = array( 'field' => 'SCHOOLNAME', 'value' => $schoolname);
            $data[] = array( 'field' => 'AREA', 'value' => $area);
            $data[] = array( 'field' => 'PROVINCE', 'value' => $province);
               
            $keys = array();
            return $this->writeWSQuery('tbl_mrf_sluactivities', $data ,$keys);
           
        } catch (Exception $e) {
            return NULL;
        }
}	

/**
 * Method to retrieve all slu activity info 
 * @return obj $results    
 */  
public function getallsluactivity(){
    
    $query = "SELECT ACTIVITY, ACTIVITYDATE, SCHOOLNAME, AREA, PROVINCE FROM ".$this->schema.'.tbl_mrf_sluactivities';
    $results =  $this->getWSGenericQuery($query);
     return $results;
}
/**
 * Method to retrieve all slu activity info using limits 
 * @return obj $results    
 */  

public function activitydetails($startat,$endat)
{
	try {
		
  			if($startat!=0){
  			$startat++;
  			}
  			$sortfield = 'order by ACTIVITYDATE';
		   	$sqlQuery = "SELECT * FROM ".$this->schema.".tbl_mrf_sluactivities";
        
        $sql = "SELECT * FROM (SELECT a.*, ROWNUM rnum FROM (".$sqlQuery.") a WHERE ROWNUM <= ".$endat.") WHERE rnum > ".$startat.$sortfield;
        $result = $this->getWSGenericQuery($sql, 'SEMS');
			  return $result;
			  
	} catch(Exception $e) {
    return NULL;
  }
}   
/**
 * Method to retrieve all slu activities btween two date values
 * @param date $begindate
 * @param date $enddate
 */
public function getactivitydate($begindate,$enddate )
{
      try {
            $begindate = date("d-M-Y", strtotime($begindate));
            $enddate = date("d-M-Y", strtotime($enddate));
            
            $where  = "where ACTIVITYDATE between '$begindate' and '$enddate' order by ACTIVITYDATE";
            $query = 'SELECT ACTIVITY, ACTIVITYDATE FROM '.$this->schema.'.tbl_mrf_sluactivities '.$where;
            return $this->getWSGenericQuery($query);

          } catch(Exception $e) {
            return NULL;
          }
}   
    
/**
 * Method to retrieve slu activities by type
 * @return obj $type
 */
public function getactivitytype(){
    $query = 'SELECT DISTINCT(ACTIVITY) FROM '.$this->schema.'.tbl_mrf_sluactivities order by ACTIVITY';
    $results =  $this->getWSGenericQuery($query);
     return $results;
}
/**
 * Method to retrieve slu activities by type using limits
 * @return obj $type
 */
public function activtypelimit($startat,$endat)
{
	try {
		
  			if($startat!=0){
  			$startat++;
  			}
  			$sortfield = 'order by ACTIVITY';
		   	$sqlQuery = "SELECT DISTINCT(ACTIVITY) FROM ".$this->schema.".tbl_mrf_sluactivities";
        
        $sql = "SELECT * FROM (SELECT a.*, ROWNUM rnum FROM (".$sqlQuery.") a WHERE ROWNUM <= ".$endat.") WHERE rnum > ".$startat;
        $result = $this->getWSGenericQuery($sql, 'SEMS');
			  return $result;
			  
	} catch(Exception $e) {
    return NULL;
  }
}   
/**
 * Method to retrieve all activities by province
 * @return $province  
 */
public function getactivityprovince(){
    $query = 'SELECT DISTINCT(ACTIVITY),PROVINCE FROM '.$this->schema.'.tbl_mrf_sluactivities order by PROVINCE';
    $results =  $this->getWSGenericQuery($query);
    return $results;
}   
/**
 * Method to retrieve all activities by province using limits
 * @return $province  
 */
public function activprovincedata($startat,$endat)
{
	try {
		
  			if($startat!=0){
  			$startat++;
  			}
  			$sortfield = 'PROVINCE';
		   	$sqlQuery = "SELECT DISTINCT(ACTIVITY),PROVINCE FROM ".$this->schema.".tbl_mrf_sluactivities";
        
        $sql = "SELECT * FROM (SELECT a.*, ROWNUM rnum FROM (".$sqlQuery.") a WHERE ROWNUM <= ".$endat.") WHERE rnum > ".$startat;
        $result = $this->getWSGenericQuery($sql, 'SEMS');
			  return $result;
			  
	} catch(Exception $e) {
    return NULL;
  }
}

/**
 * Method to retrieve activities by area
 * @return $area
 */
public function getactivityarea(){
    $query = 'SELECT DISTINCT(ACTIVITY),AREA FROM '.$this->schema.'.tbl_mrf_sluactivities order by AREA';
    $results =  $this->getWSGenericQuery($query);
    return $results;
}

/**
 * Method to retrieve activities by area using limits
 * @return $area
 */
public function activareaedata($startat,$endat)
{
	try {
		
  			if($startat!=0){
  			$startat++;
  			}
  			$sortfield = 'order by AREA';
		   	$sqlQuery = "SELECT DISTINCT(ACTIVITY),AREA FROM ".$this->schema.".tbl_mrf_sluactivities";
        
        $sql = "SELECT * FROM (SELECT a.*, ROWNUM rnum FROM (".$sqlQuery.") a WHERE ROWNUM <= ".$endat.") WHERE rnum > ".$startat;
        $result = $this->getWSGenericQuery($sql, 'SEMS');
			  return $result;
			  
	} catch(Exception $e) {
    return NULL;
  }
}
/**
 * Method to retrieve activities by a specfic school
 * @param string $useToPopTbl, schoolname value passed to function
 */   
public function getactivityschool($useToPopTbl, $field = 'SCHOOLNAME', $start = 0, $limit = 0)
{
       try {
            $keys = array();
            $keys[] = array( 'field' => $field, 'value' => $useToPopTbl);

            $fields = array( 'ACTIVITY', 'SCHOOLNAME');

            return $this->getWSQuery('tbl_mrf_sluactivities ', 'ACTIVITY',null,null,$keys,$start, $limit,$fields);

       } catch(Exception $e) {
         return NULL;
       }
}   
}//end of class 
?>
