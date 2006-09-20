<?php
/* ----------- data class extends dbTable for tbl_guestbook------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
    {
        die("You cannot view this page directly");
    }


/*
* Module to retrieve data for student financial aid info using web services
*/

class dbfinancialaidws extends object
{

 //Web services variables
    var $objSoapClient;

    
	public function init(){
		parent::init();
        try{
            $this->objSoapClient = new SoapClient("http://172.16.65.128/webservices/testws12.php?wsdl");
        }catch(Exception $e){
            die($e->getMessage());
        }
        
    }


    /**
    *
    * public function to retrieve application details from the database
    *
    * @param string $value: The value to search for in the database
    * @param string $field: The field to search on in the database
    * @return array: The array of matching records from the database
    *
    */
    public function getApplication($value, $field = 'id', $start = 0, $limit = 100000000000){
        try{
            return $this->objSoapClient->getApplication($field, $value, $start, $limit);

        }catch(Exception $e){
          //  echo $e->getMessage();
            return NULL;
        }
    }
    
    /**
    *
    * public function to retrieve application details from the database
    *
    * @param string $value: The value to search for in the database
    * @param string $field: The field to search on in the database
    * @return bool: The array of matching records from the database
    *
    */
    public function applicationExists($studentNumber, $year, $semester){
        try{
            $where = "WHERE studentNumber = '".$studentNumber."' AND year = '".$year."' AND semester = '".$semester."'";
            
            $appCount = $this->objSoapClient->getApplicationCount($where);
            if ($appCount > 0){
                return TRUE;
            }else{
                return FALSE;
            }

        }catch(Exception $e){
          //  echo $e->getMessage();
            return NULL;
        }
    }
    /**
    *
    * public function to retrieve number of application from the database
    *
    * @param string $value: The value to search for in the database
    * @param string $field: The field to search on in the database
    * @return array: The array of matching records from the database
    *
    */
    public function getApplicationCount($where = ''){
        try{
  		    return $this->objSoapClient->getApplicationCount($where);
        }catch(Exception $e){
          //  echo $e->getMessage();
            return NULL;
        }
    }
    /**
    *
    * public function to retrieve number of application from the database
    *
    * @param string $value: The value to search for in the database
    * @param string $field: The field to search on in the database
    * @return array: The array of matching records from the database
    *
    */
    public function getAppCount($field = '', $value = ''){
        try{
  		    return $this->objSoapClient->getAppCount($field, $value);
        }catch(Exception $e){
          //  echo $e->getMessage();
            return NULL;
        }
    }
    /**
    *
    * public function to retrieve all application details from the database
    *
    * @param string $value: The value to search for in the database
    * @param string $field: The field to search on in the database
    * @return array: The array of matching records from the database
    *
    */
    public function getAllApplications($start = 0, $limit = 100000000000){
        try{
  		    return $this->objSoapClient->getAllApplications($start, $limit);
        }catch(Exception $e){
            echo $e->getMessage();
            return NULL;
        }
    }

    /**
    *
    * public function to retrieve next-of-kin details from the database
    *
    * @param string $value: The value to search for in the database
    * @param string $field: The field to search on in the database
    * @return array: The array of matching records from the database
    *
    */
    public function getNextofkin($value, $field = 'appId'){
        try{
  		    return $this->objSoapClient->getNextofkin($field, $value, 0, 0);
        }catch(Exception $e){
            return NULL;
        }
    }
    
    /**
    *
    * public function to retrieve dependant details from the database
    *
    * @param string $value: The value to search for in the database
    * @param string $field: The field to search on in the database
    * @return array: The array of matching records from the database
    *
    */
    public function getDependants($value, $field = 'appId'){
        try{
  		    return $this->objSoapClient->getDependants($field, $value, 0, 0);
        }catch(Exception $e){
            return NULL;
        }
    }

    /**
    *
    * public function to retrieve parttime job details from the database
    *
    * @param string $value: The value to search for in the database
    * @param string $field: The field to search on in the database
    * @return array: The array of matching records from the database
    *
    */
    public function getParttimejob($value, $field = 'appId'){
        try{
  		    return $this->objSoapClient->getParttimejob($field, $value, 0, 0);
        }catch(Exception $e){
            return NULL;
        }
    }
    
    /**
    *
    * public function to retrieve students in family details from the database
    *
    * @param string $value: The value to search for in the database
    * @param string $field: The field to search on in the database
    * @return array: The array of matching records from the database
    *
    */
    public function getStudentsInFamily($value, $field = 'appId'){
        try{
  		    return $this->objSoapClient->getStudentsInFamily($field, $value, 0, 0);
        }catch(Exception $e){
            return NULL;
        }
    }

    /**
    *
    * public function to save applications to the database
    *
    * @param string $mode: add or edit mode
    * @param string $fields: The fields to write to the database
    *
    */
    public function saveApplication($mode, $fields){
        try{
     		return $this->objSoapClient->saveApplication($mode, $fields);
        }catch(Exception $e){
            return NULL;
        }
    }

    /**
    *
    * public function to save next-of-kin details to the database
    *
    * @param string $mode: add or edit mode
    * @param string $fields: The fields to write to the database
    *
    */
    public function saveNextofkin($mode, $fields){
        try{
  		    return $this->objSoapClient->saveNextofkin($mode, $fields);
        }catch(Exception $e){
            return NULL;
        }
    }

    /**
    *
    * public function to save dependant details to the database
    *
    * @param string $mode: add or edit mode
    * @param string $fields: The fields to write to the database
    *
    */
    public function saveDependant($mode, $fields){
        try{
  		    return $this->objSoapClient->saveDependant($mode, $fields);
        }catch(Exception $e){
            return NULL;
        }
    }

    /**
    *
    * public function to save parttimejob details to the database
    *
    * @param string $mode: add or edit mode
    * @param string $fields: The fields to write to the database
    *
    */
    public function saveParttimejob($mode, $fields){
        try{
  		    return $this->objSoapClient->saveParttimejob($mode, $fields);
        }catch(Exception $e){
            return NULL;
        }
    }

    /**
    *
    * public function to save students in family to the database
    *
    * @param string $mode: add or edit mode
    * @param string $fields: The fields to write to the database
    *
    */
    public function saveStudentInFamily($mode, $fields){
        try{
  		    return $this->objSoapClient->saveStudentInFamily($mode, $fields);
        }catch(Exception $e){
            return NULL;
        }
    }
}
