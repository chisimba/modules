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
    public $objSoapClient;

	public function init(){
		parent::init();
        $this->objSoapClient = new SoapClient("http://172.16.65.134/webserviceDEV/studentinfo4.php?wsdl");
	}


    /**
    *
    * public function to retrieve student account details from the database
    *
    * @param string $value: The value to search for in the database
    * @param string $field: The field to search on in the database
    * @return array: The array of matching records from the database
    *
    */
    public function getStudentAccountDetails($value, $field = 'STDNUM'){
        try{
     		return $this->objSoapClient->getlimitSTACC($field, $value, 0, 0);
        }catch(Exception $e){
            return NULL;
        }
    }



    
    /**
    *
    * public function to retrieve student account history details from the database
    *
    * @param string $value: The value to search for in the database
    * @param string $field: The field to search on in the database
    * @return array: The array of matching records from the database
    *
    */
    public function getStudentAccountHistory($value, $field = 'STDNUM'){
        try{
     		return $this->objSoapClient->getlimitSTACH($field, $value, 0, 0);
        }catch(Exception $e){
            return NULL;
        }
    }

    /**
    *
    * public function to retrieve transaction details from the database
    *
    * @param string $value: The value to search for in the database
    * @param string $field: The field to search on in the database
    * @return array: The array of matching records from the database
    *
    */
    public function getTransactionDetails($value, $field = 'TRNCDE')
    {
        try{
     		return $this->objSoapClient->getlimitTRNAC($field, $value, 0, 0);
        }catch(Exception $e){
            return NULL;
        }
    }

    /**
    *
    * public function to retrieve matric subject details from the database
    *
    * @param string $value: The value to search for in the database
    * @param string $field: The field to search on in the database
    * @return array: The array of matching records from the database
    *
    */
    public function getStudentMatricSubjects($value, $field = 'STDNUM'){
        try{
     		return $this->objSoapClient->getlimitSCLSB($field, $value, 0, 0);
        }catch(Exception $e){
            return NULL;
        }
    }

    /**
    *
    * public function to retrieve matric subject details from the database
    *
    * @param string $value: The value to search for in the database
    * @param string $field: The field to search on in the database
    * @return array: The array of matching records from the database
    *
    */
    public function getMatricSubjectDetails($value, $field = 'MTRSBJCDE'){
        try{
     		return $this->objSoapClient->getlimitMTRSB($field, $value, 0, 0);
        }catch(Exception $e){
            return NULL;
        }
    }

    /**
    *
    * public function to retrieve faculty details from the database
    *
    * @param string $value: The value to search for in the database
    * @param string $field: The field to search on in the database
    * @return array: The array of matching records from the database
    *
    */
    public function getFacultyDetails($value, $field = 'FCLTYCDE'){
        try{
     		return $this->objSoapClient->getlimitFCLTY($field, $value, 0, 0);
        }catch(Exception $e){
            return NULL;
        }
    }

    /**
    *
    * public function to retrieve school details from the database
    *
    * @param string $value: The value to search for in the database
    * @param string $field: The field to search on in the database
    * @return array: The array of matching records from the database
    *
    */
    public function getSecondarySchoolDetails($value, $field = 'SCLCDE')
    {
        try{
     		return $this->objSoapClient->getlimitSCHL($field, $value, 0, 0);
        }catch(Exception $e){
            return NULL;
        }
    }
    
    /**
    *
    * public function to retrieve student school details from the database
    *
    * @param string $value: The value to search for in the database
    * @param string $field: The field to search on in the database
    * @return array: The array of matching records from the database
    *
    */
   	public function getStudentSchool($value, $field = 'STDNUM')
    {
        try{
     		return $this->objSoapClient->getlimitSTSCL($field, $value, 0, 0);
        }catch(Exception $e){
            return NULL;
        }
	}
 
    /**
    *
    * public function to retrieve subject details from the database
    *
    * @param string $value: The value to search for in the database
    * @param string $field: The field to search on in the database
    * @return array: The array of matching records from the database
    *
    */
   	public function getSubject($value, $field = 'SBJCDE')
    {
        try{
     		return $this->objSoapClient->getlimitSBJCT($field, $value, 0, 0);
        }catch(Exception $e){
            return NULL;
        }
	}

    /**
    *
    * public function to retrieve sponsor details from the database
    *
    * @param string $value: The value to search for in the database
    * @param string $field: The field to search on in the database
    * @return array: The array of matching records from the database
    *
    */
   	public function getSponsor($value, $field = 'BRSCDE')
    {
        try{
    		return $this->objSoapClient->getlimitBRSRY($field, $value, 0, 0);
        }catch(Exception $e){
            return NULL;
        }
	}
   //--------------------------------------------------
   // Test public functions
   	public function getParam($value, $field = 'PRMIDN'){
  		return $this->objSoapClient->getlimitPARAM($field, $value, 0, 0);
	}
   	public function getParam2($value, $field = 'PRMCOD'){
  		return $this->objSoapClient->getlimitPARM2($field, $value, 0, 0);
	}

    public function getStudent($value, $field = 'STDNUM'){
  		return $this->objSoapClient->getlimitSTDET($field, $value, 0, 0);
    }
    public function getStudentSubjects($value, $field = 'STDNUM'){
  		return $this->objSoapClient->getlimitSTSBJ($field, $value, 0, 0);
    }
    public function getBursary($value, $field = 'BRSCDE'){
  		return $this->objSoapClient->getlimitBRSRY($field, $value, 0, 0);
    }

    public function getBursaryApp($value, $field = 'BSTDNUM'){
  		return $this->objSoapClient->getlimitSTBAD($field, $value, 0, 0);
    }

    public function getBursaryAllowance($value, $field = 'ALWCDE'){
  		return $this->objSoapClient->getlimitALWNC($field, $value, 0, 0);
    }
}
