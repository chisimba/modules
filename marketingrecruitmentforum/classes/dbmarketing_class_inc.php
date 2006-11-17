<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
    {
        die("You cannot view this page directly");
    }


/**
* Model class for the table  
*/

class dbmarketing extends object
{
    /*var $remotedb;
	  var $student;
	  var $module;checking to use these compared to
	  var $objUser;*/     


    function init(){
	 	   parent::init();
	//	   $name = 'http://172.16.65.134/s3m5d3v/studentinfo4.php?wsdl';
 //      $this->objSoapClient2 = new SoapClient($name);
	 }
/*------------------------------------------------------------------------------*/	 
	 //example of using we services
	 function getlimitSTDET($field,$field,$start,$end){
      $this->NAMESPACE2 = 'http://172.16.65.134/s3m5d3v/studentinfo4.php?wsdl';
      $this->objSoapClient2 = new SoapClient($this->NAMESPACE2);
      $STAUX = $this->objSoapClient2->getlimitSTDET("STDNUM", "2127721",0,1);
      return $STAUX;
   }
   
/*------------------------------------------------------------------------------*/   
   function getlimitAREAA($field,$value,$start,$end){         //returns array(1) { [0]=>  object(stdClass)#180 (2) { ["ARECDE"]=>  string(1) "2" ["LNGDSC"]=>  string(12) "EASTERN CAPE" } } 
      
      $this->NAMESPACE2 = 'http://172.16.65.134/s3m5d3v/studentinfo4.php?wsdl';
      $this->objSoapClient2 = new SoapClient($this->NAMESPACE2);
      $STAUX = $this->objSoapClient2->getlimitAREAA("ARECDE","2",0,10);
      return $STAUX;
   
   }
/*------------------------------------------------------------------------------*/   
   function getlimitCRSRE($field,$value,$start,$end){
      $this->NAMESPACE2 = 'http://172.16.65.134/s3m5d3v/studentinfo4.php?wsdl';
      $this->objSoapClient2 = new SoapClient($this->NAMESPACE2);
      $STAUX = $this->objSoapClient2->getlimitCRSRE("YEAR","2006",0,10);
      return $STAUX;
      /*array(10) { [0]=>  object(stdClass)#180 (7) { ["CRSCDE"]=>  string(4) "8411" ["CRSCDE2"]=>  string(1) "0" ["FTE"]=>  string(1) "0" ["RFSNBR"]=>  string(1) "2" ["SBJFTE"]=>  string(2) "95" ["STYTYP"]=>  string(1) "F" ["YEAR"]=>  string(4) "2006" } [1]=>  object(stdClass)#185 (7) { ["CRSCDE"]=>  string(4) "8412" ["CRSCDE2"]=>  string(1) "0" ["FTE"]=>  string(1) "0" ["RFSNBR"]=>  string(1) "2" ["SBJFTE"]=>  string(3) "220" ["STYTYP"]=>  string(1) "F" ["YEAR"]=>  string(4) "2006" } [2]=>  object(stdClass)#187 (7) { ["CRSCDE"]=>  string(4) "8421" ["CRSCDE2"]=>  string(1) "0" ["FTE"]=>  string(1) "0" ["RFSNBR"]=>  string(1) "2" ["SBJFTE"]=>  string(4) "65.5" ["STYTYP"]=>  string(1) "F" ["YEAR"]=>  string(4) "2006" } [3]=>  object(stdClass)#183 (7) { ["CRSCDE"]=>  string(4) "8422" ["CRSCDE2"]=>  string(1) "0" ["FTE"]=>  string(1) "0" ["RFSNBR"]=>  string(1) "2" ["SBJFTE"]=>  string(3) "175" ["STYTYP"]=>  string(1) "F" ["YEAR"]=>  string(4) "2006" } [4]=>  object(stdClass)#181 (7) { ["CRSCDE"]=>  string(4) "8423" ["CRSCDE2"]=>  string(1) "0" ["FTE"]=>  string(1) "0" ["RFSNBR"]=>  string(1) "2" ["SBJFTE"]=>  string(3) "290" ["STYTYP"]=>  string(1) "F" ["YEAR"]=>  string(4) "2006" } [5]=>  object(stdClass)#178 (7) { ["CRSCDE"]=>  string(4) "8424" ["CRSCDE2"]=>  string(1) "0" ["FTE"]=>  string(1) "0" ["RFSNBR"]=>  string(1) "3" ["SBJFTE"]=>  string(3) "400" ["STYTYP"]=>  string(1) "F" ["YEAR"]=>  string(4) "2006" } [6]=>  object(stdClass)#176 (7) { ["CRSCDE"]=>  string(4) "8425" ["CRSCDE2"]=>  string(1) "0" ["FTE"]=>  string(1) "0" ["RFSNBR"]=>  string(1) "2" ["SBJFTE"]=>  string(2) "40" ["STYTYP"]=>  string(1) "P" ["YEAR"]=>  string(4) "2006" } [7]=>  object(stdClass)#191 (7) { ["CRSCDE"]=>  string(4) "8426" ["CRSCDE2"]=>  string(1) "0" ["FTE"]=>  string(1) "0" ["RFSNBR"]=>  string(1) "3" ["SBJFTE"]=>  string(3) "100" ["STYTYP"]=>  string(1) "P" ["YEAR"]=>  string(4) "2006" } [8]=>  object(stdClass)#192 (7) { ["CRSCDE"]=>  string(4) "8427" ["CRSCDE2"]=>  string(1) "0" ["FTE"]=>  string(1) "0" ["RFSNBR"]=>  string(1) "2" ["SBJFTE"]=>  string(3) "100" ["STYTYP"]=>  string(1) "F" ["YEAR"]=>  string(4) "2006" } [9]=>  object(stdClass)#193 (7) { ["CRSCDE"]=>  string(4) "8431" ["CRSCDE2"]=>  string(1) "0" ["FTE"]=>  string(1) "0" ["RFSNBR"]=>  string(1) "2" ["SBJFTE"]=>  string(2) "90" ["STYTYP"]=>  string(1) "F" ["YEAR"]=>  string(4) "2006" } } */
   }
/*------------------------------------------------------------------------------*/   
   function getlimitSTAUX($field,$value,$start,$end){
      $this->NAMESPACE2 = 'http://172.16.65.134/s3m5d3v/studentinfo4.php?wsdl';
      $this->objSoapClient2 = new SoapClient($this->NAMESPACE2);
      $STAUX = $this->objSoapClient2->getlimitSTAUX("ARECDE","1",0,1);
      return $STAUX;
      /*array(1) { [0]=>  object(stdClass)#180 (34) { ["APLNUM"]=>  string(7) "3417234" ["APLSCNIDB"]=>  string(1) "B" ["ARECDE"]=>  string(1) "1" ["CCHCDE"]=>  string(1) "I" ["DSC1"]=>  string(11) "WORK AT UWC" ["DSR"]=>  string(1) " " ["FAMINC"]=>  string(1) "0" ["HOW1"]=>  string(1) " " ["HOW10"]=>  string(1) " " ["HOW11"]=>  string(1) " " ["HOW12"]=>  string(1) " " ["HOW13"]=>  string(1) "Y" ["HOW2"]=>  string(1) " " ["HOW3"]=>  string(1) " " ["HOW4"]=>  string(1) " " ["HOW5"]=>  string(1) " " ["HOW6"]=>  string(1) " " ["HOW7"]=>  string(1) " " ["HOW8"]=>  string(1) " " ["HOW9"]=>  string(1) " " ["MEDDSC"]=>  string(1) " " ["MLPRINTED"]=>  string(1) " " ["NAT"]=>  string(1) "C" ["PAYMTHIND"]=>  string(1) "0" ["PHYDSBIND"]=>  string(1) "N" ["PNTEDU"]=>  string(1) "0" ["PRVACT"]=>  string(1) "8" ["PRVINS"]=>  string(1) "0" ["PRVNME"]=>  string(1) " " ["SCTSTS"]=>  string(1) " " ["UNIRESIND"]=>  string(1) "N" ["URBRURIND"]=>  string(1) "U" ["WRKCTGFTH"]=>  string(1) "0" ["WRKCTGMTH"]=>  string(1) "0" } } */
    }
/*------------------------------------------------------------------------------*/
    /* Method to get a list of Faculties
    * @param string $server
    * @returns array $result
    */
    function getlimitFCLTY($field,$start,$end)
    {
       $this->NAMESPACE2 = 'http://172.16.65.134/s3m5d3v/studentinfo4.php?wsdl';
       $this->objSoapClient2 = new SoapClient($this->NAMESPACE2);
       $result = $this->objSoapClient2->getlimitFCLTY("Medium Descrip",0,10);
       return $result;
                    
    }

/*------------------------------------------------------------------------------*/    
}
?>
