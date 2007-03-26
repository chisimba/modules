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
	
/*------------------------------------------------------------------------------*/
/**
 * Method to insert all SLU Activity into tbl sluactivities
 */
public function addsluactivity($cdate,$ddate,$date,$activity,$schoolname,$area,$province)
{  
    try {
            $data = array();
            $data[] = array( 'field' => 'id', 'value' => "init" . "_" . rand(1000,9999) . "_" . time());
            $data[] = array( 'field' => 'createdby', 'value' => $cdate);
            $data[] = array( 'field' => 'datecreated', 'value' => "to_date('".$ddate."', 'dd-mm-yyyy')");
            $data[] = array( 'field' => 'activitydate', 'value' => "to_date('".$date."', 'dd-mm-yyyy')");
            $data[] = array( 'field' => 'activity', 'value' => $activity);
            $data[] = array( 'field' => 'schoolname', 'value' => $schoolname);
            $data[] = array( 'field' => 'area', 'value' => $area);
            $data[] = array( 'field' => 'province', 'value' => $province);
               
            $keys = array();
            return $this->objSoapClient->writeQuery('tbl_mrf_sluactivities', $data ,$keys,  $this->schema);
           //return $data;
          //die;

        } catch (Exception $e) {
            return NULL;
        }
}	

/*------------------------------------------------------------------------------*/
/**
 * Method to retrieve all slu activity info 
 * @return obj $results    
 */  
public function getallsluactivity(){
    $query = 'SELECT activity, activitydate, schoolname, area, province FROM '.$this->schema.'.tbl_mrf_sluactivities';
    $results =  $this->objSoapClient->genericQuery($query);
     return $results;
}

public function activitydetails($startat,$endat)
{
	try {
		
			if($startat!=0){
			$startat++;
			}
			$sortfield = 'activitydate';
			
			
		  return $this->objSoapClient->getlimitQuery('tbl_mrf_sluactivities','','', $sortfield, $startat, $endat,  $this->schema);
	} catch(Exception $e) {
         return NULL;
    }
}

  
/*------------------------------------------------------------------------------*/
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
            
            $where  = "where activitydate between '$begindate' and '$enddate' order by activitydate";
            $query = 'SELECT activity, activitydate FROM '.$this->schema.'.tbl_mrf_sluactivities '.$where;
            return $this->objSoapClient->genericQuery($query);

          } catch(Exception $e) {
            return NULL;
          }
}   
    
   
/*------------------------------------------------------------------------------*/
/**
 * Method to retrieve slu activities by type
 * @return obj $type
 */
  /*public function getactivitytype()
  {   
      $stmt = "select distinct(activity) from tbl_sluactivities order by activity";
      $type  = $this->getArray($stmt);
      return  $type;
  } */
public function getactivitytype(){
    $query = 'SELECT distinct(activity) FROM '.$this->schema.'.tbl_mrf_sluactivities order by ACTIVITY';
    $results =  $this->objSoapClient->genericQuery($query);
     return $results;
}

public function activtypelimit($startat,$endat)
{
	try {
			if($startat!=0){
			$startat++;
			}

      $sortfield = 'activity';
			return $this->objSoapClient->getlimitQuery('tbl_mrf_sluactivities','','', $sortfield, $startat, $endat,  $this->schema);
	} catch(Exception $e) {
         return NULL;
    }
}
/*------------------------------------------------------------------------------*/
/**
 * Method to retrieve all activities by province
 * @return $province  
 */
  /*public function getactivityprovince()
  {   
      $stmt = "select distinct(activity),province from tbl_sluactivities order by province";
      $province  = $this->getArray($stmt);
      return  $province;
  }*/
public function getactivityprovince(){
    $query = 'SELECT distinct(activity),province FROM '.$this->schema.'.tbl_mrf_sluactivities order by PROVINCE';
    $results =  $this->objSoapClient->genericQuery($query);
    return $results;
}   

public function activprovincedata($startat,$endat)
{
	try {
		//print $startat;die('dsdsa');
			if($startat!=0){
			$startat++;
			}
			
      $sortfield = 'province';
			return $this->objSoapClient->getlimitQuery('tbl_mrf_sluactivities','','', $sortfield, $startat, $endat,  $this->schema);
	} catch(Exception $e) {
         return NULL;
    }
}

/*------------------------------------------------------------------------------*/
/**
 * Method to retrieve activities by area
 * @return $area
 */
/*  public function getactivityarea()
  {
      $stmt = "select distinct(activity),area from tbl_sluactivities order by area";
      $area  = $this->getArray($stmt);
      return  $area;
    
  }*/
public function getactivityarea(){
    $query = 'SELECT distinct(activity),area FROM '.$this->schema.'.tbl_mrf_sluactivities order by AREA';
    $results =  $this->objSoapClient->genericQuery($query);
     return $results;
}

public function activareaedata($startat,$endat)
{
	try {
		//print $startat;die('dsdsa');
			if($startat!=0){
			$startat++;
			}
			
      $sortfield = 'area';
			return $this->objSoapClient->getlimitQuery('tbl_mrf_sluactivities','','', $sortfield, $startat, $endat,  $this->schema);
	} catch(Exception $e) {
         return NULL;
    }
}   
/*------------------------------------------------------------------------------*/
/**
 * Method to retrieve activities by a specfic school
 * @param string $useToPopTbl, schoolname value passed to function
 */   
public function getactivityschool($useToPopTbl, $field = 'schoolname', $start = 0, $limit = 0)
{
       try {
            $keys = array();
            $keys[] = array( 'field' => $field, 'value' => $useToPopTbl);

            $fields = array( 'ACTIVITY', 'SCHOOLNAME');

            return $this->objSoapClient->getlimitQuery('tbl_mrf_sluactivities ',$fields, $keys, 'ACTIVITY', $start, $limit,  $this->schema);

       } catch(Exception $e) {
         return NULL;
       }
}   
/*------------------------------------------------------------------------------*/
  
}//end of class 
?>
