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

class financialaidcustomws extends object
{

 //Web services variables
    var $NAMESPACE;
    var $objSoapClient;

    
	function init(){
		parent::init();
        $this->NAMESPACE="http://172.16.65.134/webserviceDEV/finaid5.php?wsdl";
        $this->objSoapClient = new SoapClient($this->NAMESPACE);
        
    }


    /**
    *
    * Function to retrieve avg mark for student from the database
    *
    * @param string $stdnum: The student in the database
    * @param string $year: The relevant year
    * @return int: The avg of the students marks
    *
    */
    function getAvgMark($stdnum, $year){
  		return $this->objSoapClient->getAVGMARK($stdnum, $year);
    }
    /**
    *
    * Function to retrieve avg mark for student from the database
    *
    * @param string $stdnum: The student in the database
    * @param string $year: The relevant year
    * @return int: The avg of the students marks
    *
    */
    function getStudentCount($value, $field = 'STDNUM'){
  		return $this->objSoapClient->getSTDETCOUNT($field, $value);
    }
    /**
    *
    * Function to retrieve avg mark for student from the database
    *
    * @param string $stdnum: The student in the database
    * @param string $year: The relevant year
    * @return int: The avg of the students marks
    *
    */
    function getSponsorCount(){
  		return $this->objSoapClient->getBRSRYCOUNT();
    }

    /**
    *
    * Function to retrieve number of passed subjects for student from the database
    *
    * @param string $stdnum: The student in the database
    * @param string $year: The relevant year
    * @return int: The number of passed subjects
    *
    */
    function getPassedSubjects($stdnum, $year){
  		return $this->objSoapClient->getPASSEDSUBJECTS($stdnum, $year);
    }
    
    /**
    *
    * Function to retrieve sponsor details from the database
    *
    * @return array: The sponsor list
    *
    */
    function getAllSponsors(){
  		return $this->objSoapClient->getAllBRSRY(0,0);
        return NULL;
    }

}
