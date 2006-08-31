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
    var $NAMESPACE;
    var $objSoapClient;

    
	function init(){
		parent::init();
        $this->NAMESPACE="http://127.0.0.1/webservices/testws5.php?wsdl";
        $this->objSoapClient = new SoapClient($this->NAMESPACE);
        
    }


    /**
    *
    * Function to retrieve application details from the database
    *
    * @param string $value: The value to search for in the database
    * @param string $field: The field to search on in the database
    * @return array: The array of matching records from the database
    *
    */
    function getApplication($value, $field = 'appNumber'){
  		return $this->objSoapClient->getApplication($field, $value, 0, 0);
    }
    
    /**
    *
    * Function to retrieve all application details from the database
    *
    * @param string $value: The value to search for in the database
    * @param string $field: The field to search on in the database
    * @return array: The array of matching records from the database
    *
    */
    function getAllApplications(){
  		return $this->objSoapClient->getAllApplications(0, 0);
    }

    /**
    *
    * Function to retrieve next-of-kin details from the database
    *
    * @param string $value: The value to search for in the database
    * @param string $field: The field to search on in the database
    * @return array: The array of matching records from the database
    *
    */
    function getNextofkin($value, $field = 'appNumber'){
  		return $this->objSoapClient->getNextofkin($field, $value, 0, 0);
    }
    
    /**
    *
    * Function to retrieve dependant details from the database
    *
    * @param string $value: The value to search for in the database
    * @param string $field: The field to search on in the database
    * @return array: The array of matching records from the database
    *
    */
    function getDependants($value, $field = 'appNumber'){
  		return $this->objSoapClient->getDependants($field, $value, 0, 0);
    }

    /**
    *
    * Function to retrieve parttime job details from the database
    *
    * @param string $value: The value to search for in the database
    * @param string $field: The field to search on in the database
    * @return array: The array of matching records from the database
    *
    */
    function getParttimejob($value, $field = 'appNumber'){
  		return $this->objSoapClient->getParttimejob($field, $value, 0, 0);
    }
    
    /**
    *
    * Function to retrieve students in family details from the database
    *
    * @param string $value: The value to search for in the database
    * @param string $field: The field to search on in the database
    * @return array: The array of matching records from the database
    *
    */
    function getStudentsInFamily($value, $field = 'appNumber'){
  		return $this->objSoapClient->getStudentsInFamily($field, $value, 0, 0);
    }

    /**
    *
    * Function to save applications to the database
    *
    * @param string $mode: add or edit mode
    * @param string $fields: The fields to write to the database
    *
    */
    function saveApplication($mode, $fields){
  		return $this->objSoapClient->saveApplication($mode, $fields);
    }

    /**
    *
    * Function to save next-of-kin details to the database
    *
    * @param string $mode: add or edit mode
    * @param string $fields: The fields to write to the database
    *
    */
    function saveNextofkin($mode, $fields){
  		return $this->objSoapClient->saveNextofkin($mode, $fields);
    }

    /**
    *
    * Function to save dependant details to the database
    *
    * @param string $mode: add or edit mode
    * @param string $fields: The fields to write to the database
    *
    */
    function saveDependant($mode, $fields){
  		return $this->objSoapClient->saveDependant($mode, $fields);
    }

    /**
    *
    * Function to save parttimejob details to the database
    *
    * @param string $mode: add or edit mode
    * @param string $fields: The fields to write to the database
    *
    */
    function saveParttimejob($mode, $fields){
  		return $this->objSoapClient->saveParttimejob($mode, $fields);
    }

    /**
    *
    * Function to save students in family to the database
    *
    * @param string $mode: add or edit mode
    * @param string $fields: The fields to write to the database
    *
    */
    function saveStudentInFamily($mode, $fields){
  		return $this->objSoapClient->saveStudentInFamily($mode, $fields);
    }
}
