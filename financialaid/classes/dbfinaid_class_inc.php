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

class dbfinaid extends object
{

 //Web services variables
    var $NAMESPACE;
    var $objSoapClient;

    
	function init(){
		parent::init();
        //Uses NUSOAP
        //require_once("lib/nusoap/nusoap.php");
      $this->NAMESPACE="http://172.16.65.134/webserviceDEV/studentinfo4.php?wsdl";

        $this->objSoapClient = new SoapClient($this->NAMESPACE);
	}


    /**
    *
    * Function to retrieve student account details from the database
    *
    * @param string $value: The value to search for in the database
    * @param string $field: The field to search on in the database
    * @return array: The array of matching records from the database
    *
    */
    function getStudentAccountDetails($value, $field = 'STDNUM'){
  		return $this->objSoapClient->getlimitSTACC($field, $value, 0, 0);
    }



    
    /**
    *
    * Function to retrieve student account history details from the database
    *
    * @param string $value: The value to search for in the database
    * @param string $field: The field to search on in the database
    * @return array: The array of matching records from the database
    *
    */
    function getStudentAccountHistory($value, $field = 'STDNUM'){
  		return $this->objSoapClient->getlimitSTACH($field, $value, 0, 0);
    }

    /**
    *
    * Function to retrieve transaction details from the database
    *
    * @param string $value: The value to search for in the database
    * @param string $field: The field to search on in the database
    * @return array: The array of matching records from the database
    *
    */
    function getTransactionDetails($value, $field = 'TRNCDE')
    {
  		return $this->objSoapClient->getlimitTRNAC($field, $value, 0, 0);
    }

    /**
    *
    * Function to retrieve matric subject details from the database
    *
    * @param string $value: The value to search for in the database
    * @param string $field: The field to search on in the database
    * @return array: The array of matching records from the database
    *
    */
    function getStudentMatricSubjects($value, $field = 'STDNUM'){
  		return $this->objSoapClient->getlimitSCLSB($field, $value, 0, 0);
    }

    /**
    *
    * Function to retrieve matric subject details from the database
    *
    * @param string $value: The value to search for in the database
    * @param string $field: The field to search on in the database
    * @return array: The array of matching records from the database
    *
    */
    function getMatricSubjectDetails($value, $field = 'MTRSBJCDE'){
  		return $this->objSoapClient->getlimitMTRSB($field, $value, 0, 0);
    }
    
    /**
    *
    * Function to retrieve school details from the database
    *
    * @param string $value: The value to search for in the database
    * @param string $field: The field to search on in the database
    * @return array: The array of matching records from the database
    *
    */
    function getSecondarySchoolDetails($value, $field = 'SCLCDE')
    {
  		return $this->objSoapClient->getlimitSCHL($field, $value, 0, 0);
    }
    
    /**
    *
    * Function to retrieve student school details from the database
    *
    * @param string $value: The value to search for in the database
    * @param string $field: The field to search on in the database
    * @return array: The array of matching records from the database
    *
    */
   	function getStudentSchool($value, $field = 'STDNUM'){
  		return $this->objSoapClient->getlimitSTSCL($field, $value, 0, 0);
	}


   //--------------------------------------------------
   // Test functions
   	function getParam($value, $field = 'PRMIDN'){
  		return $this->objSoapClient->getlimitPARAM($field, $value, 0, 0);
	}
 
    function getStudent($value, $field = 'STDNUM'){
  		return $this->objSoapClient->getlimitSTDET($field, $value, 0, 0);
    }

    function getBursary($value, $field = 'BRSCDE'){
  		return $this->objSoapClient->getlimitBRSRY($field, $value, 0, 0);
    }

    function getBursaryApp($value, $field = 'BSTDNUM'){
  		return $this->objSoapClient->getlimitSTBAD($field, $value, 0, 0);
    }

    function getBursaryAllowance($value, $field = 'ALWCDE'){
  		return $this->objSoapClient->getlimitALWNC($field, $value, 0, 0);
    }
}
