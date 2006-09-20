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
    public $objSoapClient;

    
	public function init(){
		parent::init();
        try{
            $this->objSoapClient = new SoapClient('http://172.16.65.134/webserviceDEV/finaid8.php?wsdl');
        }catch(Exception $e){
            return $e->getMessage();
        }

    }


    /**
    *
    * public function to retrieve avg mark for student from the database
    *
    * @param string $stdnum: The student in the database
    * @param string $year: The relevant year
    * @return int: The avg of the students marks
    *
    */
    public function getAvgMark($stdnum, $year){
        try{
  		    return $this->objSoapClient->getAVGMARK($stdnum, $year);
        }catch(Exception $e){
            return NULL;
        }
    }
    /**
    *
    * public function to retrieve avg mark for student from the database
    *
    * @param string $stdnum: The student in the database
    * @param string $year: The relevant year
    * @return int: The avg of the students marks
    *
    */
    public function getStudentCount($value, $field = 'STDNUM'){
        try{
  		    return $this->objSoapClient->getSTDETCOUNT($field, $value);
        }catch(Exception $e){
            return NULL;
        }
    }

    /**
    *
    * public function to retrieve account info from the database
    *
    * @param string $stdnum: The student in the database
    * @param string $year: The relevant year
    * @return int: The avg of the students marks
    *
    */
    public function getStudentAccount($stdnum, $type)
    {
        try{
  		    return $this->objSoapClient->getSTACC($stdnum, $type, 0, 100000000000);
        }catch(Exception $e){
            return NULL;
        }

    }
    /**
    *
    * public function to retrieve number of sponsors from the database
    *
    * @param string $stdnum: The student in the database
    * @param string $year: The relevant year
    * @return int: The avg of the students marks
    *
    */
    public function getSponsorCount()
    {
        try{
  		    return $this->objSoapClient->getBRSRYCOUNT();
        }catch(Exception $e){
            return NULL;
        }
    }

    /**
    *
    * public function to retrieve number of passed subjects for student from the database
    *
    * @param string $stdnum: The student in the database
    * @param string $year: The relevant year
    * @return int: The number of passed subjects
    *
    */
    public function getPassedSubjects($stdnum, $year)
    {
        try{
            return $this->objSoapClient->getPASSEDSUBJECTS($stdnum, $year);
        }catch(Exception $e){
            return NULL;
        }
    }
    
    /**
    *
    * public function to retrieve sponsor details from the database
    *
    * @return array: The sponsor list
    *
    */
    public function getAllSponsors($orderby = 'BRSCDE', $start = 0, $offset = 0)
    {
        try{
  		    return $this->objSoapClient->getAllBRSRY($orderby,$start,$start + $offset);
        }catch(Exception $e){
            return NULL;
        }
    }
}
