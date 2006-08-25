<?php
/* ----------- data class extends dbTable for tbl_guestbook------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
    {
        die("You cannot view this page directly");
    }


/**
* Model class for the student lists
*/


class dbstudentinfo extends object
{
	var $remotedb;
	var $student;
	var $module;
	var $objUser;
 //Web services variables
    var $NAMESPACE;
    var $server;
    var $test;
    var $test3;
    /**
    * Constructor Method for the class
    *
    * This method initialises the web service to be used
    */
    function init(){
        parent::init();
        //Uses NUSOAP
        $this->test = new SoapClient("http://172.16.65.134/webserviceDEV/studentinfo4.php?wsdl");
        //$this->test->getlimitSTACC($field, $value, 0, 0);
        
       // require_once("lib/nusoap/nusoap.php");
       // $this->NAMESPACE="http://172.16.65.134/webserviceDEV/studentinfo4.php?wsdl";

       // $this->server = new soapclient($this->NAMESPACE, true);
       // $this->test = $this->server->getProxy();
    }
    /**
    * Method to get the Student's Information
    *
    * @param string $stdno : provides the selected student's student number
    * @return string $psinfo: the array, containing the results of the query on the STDET table
    */
    function getPersonInfo($stdno){
        $psinfo = $this->test->getlimitSTDET('STDNUM', "$stdno",0,0);
        return $psinfo;
    }
    /**
    * Method to get the Student's Information
    *
    * @param string $stdno : provides the selected student's student number
    * @return string $psinfo: the array, containing the results of the query on the STDET table
    */
    function getAllStudents($stdno){
        $allSt = $this->test->getlimitSTDET('STDNUM',"$stdno",0,0);
        return $allSt;
    }

    /**
    * Method to get the Student's Address Information
    *
    * @param string $personid : provides the selected student's student number
    * @return string $addr: the array, containing the results of the query on the STADR table
    */
    function studentAddress($personid){
        $addr = $this->test->getlimitSTADR('STDNUM', "$personid",0,0);
        return $addr;
	}

    /**
    * Method to set the Student's Number
    *
    */
	function getStudentNumber(){
             $this->stdnum = $this->getParam('id');
	}


	function getStudentFamily($personid){

	}

	function studentEmployement($personid){

	}

	function getApplication($personid,$year){

	}

	function editFinapplication(){
		$personid = $this->getParam('id');
		$applicationID =$this->getParam('txtApplicationID');
		$appstatus = $this->getParam('status');
		$comment = $this->getParam('txtComment');
		
		$fields = array('finApplicationStatus'=>$appstatus, 'finComment'=>$comment);
		//var_dump($fields);
	//	return $this->student->updateInfo('applicationID', $applicationID, 'tbl_application', $fields,$this->module);
	}
	
	function lookup($filter = null){

	}

	function getLookupInfo(){

          $this->stdnum = $this->getParam('id');
       $lookinfo = $this->test->getlimitSTDET('STDNUM', "$this->stdnum",0,0);
        return $lookinfo;
	}

    /**
    * Method to get the Student's Race Status
    *
    * @param string $rce : provides the selected student's race id
    * @return string $srch: the Race value, according to the race id
    *     string value "UNDEFINED": the value returned if no race was defined
    */
    function getRace($rce)
    {  $srch = "";
       $raceSt = $this->test->getlimitPARAM('PRMIDN', '4',0,0);
       for($i = 0; $i < count($raceSt); $i++)
       {
           if ($raceSt[$i]->PRMCDE == "$rce")
           {
              $srch = $raceSt[$i]->LNGDSC;
           }
       }
       if($srch != "")
         return $srch;
       else
         return "UNDEFINED";
    }
    
    /**
    * Method to get the Student's Gender Status
    *
    * @param string $sex : provides the selected student's gender id
    * @return string value "MALE": the Gender value, according to the gender id
    * @return string value "FEMALE": the Gender value, according to the gender id
    *     string value "UNDEFINED": the value returned if no gender was defined
    */
    function getGender($sex)
    {
     if($sex == 'M')
     {   return "MALE";  }
     else if($sex == 'F')
     {   return "FEMALE";     }
     else
     {  return "UNDEFINED";}
    }

    /**
    * Method to get the Student's Marital Status
    *
    * @param string $msts : provides the selected student's Marital Status id
    * @return string value "MARRIED": the Marital Status value, according to the Marital Status id
    * @return string value "SINGLE": the Marital Status value, according to the Marital Status id
    *     string $msts: the value returned if no matching Marital Status was found
    */
    function getMarStatus($msts)
    {
     if("$msts" == 'M')
     {   return "MARRIED";  }
     else if("$msts" == 'S')
     {   return "SINGLE";     }
     else
     {  return $msts;}
    }
    
    /**
    * Method to get the Student's Course Information
    *
    * @param string $id : provides the selected student's student number
    * @param string $year : provides the selected student's curent study year
    * @return string $stcrsinfo: the array, containing the results of the query on the STSBJ table
    */
	function getCourseInfo($id,$year = null){

        $this->stdnum = $this->getParam('id');
        $stcrsinfo = $this->test->getlimitSTSBJ('STDNUM', "$id",0,0);
        return $stcrsinfo;
	}
	
	function getStudyYears($id,$year = null){

	}

     /**
    * Method to get the Student's Course Results
    *
    * @param string $studentCourseID : provides the selected student's student number
    * @param string $assessmentDate : provides the selected student's curent study year
    * @return string $stcrsinfo: the array, containing the results of the query on the STSBJ table
    */
	function getCourseResuts($studentCourseID,$assessmentDate){

       $stdnum = $this->getParam('id');
       $stresults = $this->test->getlimitSTSBJ('STDNUM', "$stdnum",0,0);
       return $stresults;
	}

    function getStudentCourse($id)
    {
       $stcrs1 = $this->test->getlimitSTCRS('STDNUM', "$id",0,0);
       return $stcrs1;
    }

    function getCourseDesc($stcrs)
    {
       $crse = $this->test->getlimitCRSE('CRSCDE', "$stcrs",0,0);
       return $crse;
    }
        
	function getAccountInformation(){

	}

	function getPayments(){

	}

	function makePayment($userid){
		$amount = $this->getParam('amount');
		$dateofpayment = $this->getParam('dateofpayment');
		$paidby = $this->getParam('paymentMadeBy');
		$personid = $this->getParam('id');
		
		$date = explode("-",$dateofpayment);
		$year = $date[0];
		$month = $date[1];
		$day = $date[2];

		$sql = "update tbl_studentAccount set currentAmount = currentAmount - '$amount' where personID = '$personid' and accountYear like '$year'";
		//echo $sql;
		if($this->student->runSql($sql,'tbl_student',$this->module)){
			$fields = array('personID'=>$personid,'amountPaid'=>$amount,'dateOfPayment'=>$dateofpayment,'paymentMadeBy'=>$paidby,'processedBy'=>$userid);
			return $this->student->inputInfo('tbl_studentPayment',$fields,$this->module);

		}
		else{

		?>
		<script>
			alert("The Payment Was not recorded.");
			alert("Please try again later");
		</script>
		<?

		}
		
	}
	
	function getStudyType($personid){
        $studySt = $this->test->getlimitSTCRS('STDNUM', '$this->stdnum',0,0);
        return $studySt[0]["STDTYP"];
        
	} 

	function getStudentSponsors($personid,$year){
		$filter = "where studentPersonid = '$personid' and yearOfSponsorship = '$year'";
	 return $this->student->getInfo('tbl_studentSponsor',$this->module,null,$filter);
	}
	
	function sponsorList(){
		$filter = "where personTypeID = '47'";
		return $this->student->getInfo('tbl_person',$this->module,null,$filter);
	}

	function sponsorStats($sponsorPersonid){
		$sql = "select count(spondorID) as numStdSponsored,sum(amountSponsored) as amount from tbl_studentSponsor where sponsorPersonid = '$sponsorPersonid'";
		return $this->student->runSql($sql,'tbl_studentSponsor',$this->module);

	}
	
	function getSponsorName($personid){
		$filter = " where personID = '$personid'";
		$arr = $this->student->getInfo('tbl_person',$this->module,null,$filter);
		return $arr[0]['name']."  ".$arr[0]['surname'];
	}

	function assignSponsor(){
		$amount = $this->getParam('amount');
		$year = $this->getParam('sponsorshipyear');
		$sponsorid = $this->getParam('sponsor');
		$studentid = $this->getParam('id');
		$fields = array('sponsorPersonid'=>$sponsorid,'studentPersonid'=>$studentid,'amountSponsored'=>$amount,'yearOfSponsorship'=>$year);
		return $this->student->inputInfo('tbl_studentSponsor',$fields,$this->module);

	}

	function getSponsoredStudents($sponsorid){		
		$sql = "select * from tbl_studentSponsor ss inner join tbl_person p on ss.studentPersonid = p.personID where ss.sponsorPersonid = '$sponsorid'";
		
		return $this->student->runSql($sql,'tbl_studentSponsor',$this->module);

	}

	function addNewSponsor(){
		$idnumber = $this->getParam('spIdnumber');
		$name = $this->getParam('spName');
		$surname = $this->getParam('spSurname');
		//default values for sponsors
		$raceid = 1;
		$genderid = 6;
		$maritalStatus = 11;
		$personTypeID = 47;
		$contactTypeID = 35;
	
		
		$streetaddress = $this->getParam('spStreetAddress');
		$suburb = $this->getParam('spSuburb');
		$town = $this->getParam('spTown');
		$code = $this->getParam('spCode');
		$telephone = $this->getParam('spTelephone');
		$cellphone = $this->getParam('spCellphone');
		$email = $this->getParam('spEmail');

		$personFields = array('idnumber'=>$idnumber,'name'=>$name,'surname'=>$surname,
				'raceID'=>$raceid,'genderID'=>$genderid,
				'maritalStatusID'=>$maritalStatus,'personTypeID'=>$personTypeID);

		if($add = $this->student->inputInfo('tbl_person',$personFields,$this->module)){
			//var_dump($add);
			$sql = "select * from tbl_person order by personID desc limit 1";
			$arr = $this->student->runSql($sql,'tbl_person',$this->module);

			$personid = $arr[0]['personID'];

			$contactFields = array('streetAddress'=>$streetaddress, 'suburb'=>$suburb, 'town'=>$town, 'code'=>$code ,'telephone'=>$telephone,'cellphone'=>$cellphone, 'email'=>$email, 'person','contactTypeID'=>$contactTypeID,'personID'=>$personid);
			//var_dump($contactFields);

			$sql = "insert into tbl_contactDetails (streetAddress,suburb,town,code, contactTypeID, telephone, cellphone, email, personID, residingYear) values ('$streetaddress', '$suburb', '$town', '$code', '35','$telephone', '$cellphone', '$email', '$personid','".date("Y")."')";
			
			return $this->student->runSql($sql,'tbl_contactDetails',$this->module);
		}
		else{
			return false;
		}
	}

	function editSponsor($personid){
		$sql = "select * from tbl_person p inner join tbl_contactDetails cd on p.personID = cd.personID where p.personID = '$personid'";
		//echo $sql;
		return $this->student->runSql($sql,'tbl_contactDetails',$this->module);
		
	}	

	function updateSponsor($personid){
		$idnumber = $this->getParam('spIdnumber');
		$name = $this->getParam('spName');
		$surname = $this->getParam('spSurname');
		$streetaddress = $this->getParam('spStreetAddress');
		$suburb = $this->getParam('spSuburb');
		$town = $this->getParam('spTown');
		$code = $this->getParam('spCode');
		$telephone = $this->getParam('spTelephone');
		$cellphone = $this->getParam('spCellphone');
		$email = $this->getParam('spEmail');
	
		$sql = "update tbl_person set idnumber = '$idnumber',name = '$name', surname = '$surname' where personID = '$personid'";

		if($this->student->runSql($sql,'tbl_contactDetails',$this->module)){
			$sql  = "update tbl_contactDetails set streetAddress = '$streetaddress', suburb = '$suburb', town = '$town', code = '$code', telephone = '$telephone', cellphone = '$cellphone', email = '$email'";
			return $this->student->runSql($sql,'tbl_contactDetails',$this->module);
			
		}	
		else{
			return false;
		}
	}
    /**
    * Method to get the results of te Student Search
    *
    * @return string $searchSt: the array, containing the results of the query on the STDET table
    *                            the returned array will depend on the search criteria.
    */
	function search(){
		$stdnum = strtoupper($this->getParam('studentNumber'));
		//$applnum = strtoupper($this->getParam('applicationNumber'));
		$surname = strtoupper($this->getParam('surname'));
		$idnumber = strtoupper($this->getParam('idNumber'));
		if(!$stdnum and !$surname and !$idnumber)//0
              $searchSt = "";

		if(!$stdnum and !$surname and $idnumber) {//1
              $searchSt = $this->test->getlimitSTDET('IDN', "$idnumber",0,0);
        }

		if(!$stdnum and $surname and !$idnumber){//2
              $searchSt = $this->test->getlimitSTDET('SURNAM', "$surname",0,0);
          }
		if(!$stdnum and $surname and $idnumber)//3
              $searchSt = $this->test->getlimitSTDET('IDN', "$idnumber",0,0);

		if(!$stdnum and !$surname and $idnumber)//5
            $searchSt = $this->test->getlimitSTDET('IDN', "$idnumber",0,0);

		if(!$stdnum and $surname and $idnumber){//7
             $searchSt = $this->test->getlimitSTDET('STDNUM', "$stdnum",0,0);
        }
		if($stdnum and !$surname and !$idnumber){//8
           $searchSt = $this->test->getlimitSTDET('STDNUM', "$stdnum",0,0);
       }
		if($stdnum and !$surname and $idnumber)//9
            $searchSt = $this->test->getlimitSTDET('STDNUM', "$stdnum",0,0);

		if($stdnum and $surname and !$idnumber)//10
            $searchSt = $this->test->getlimitSTDET('STDNUM', "$stdnum",0,0);

		if($stdnum and $surname and $idnumber)//11
            $searchSt = $this->test->getlimitSTDET('STDNUM', "$stdnum",0,0);
             

		if($stdnum and !$surname and !$idnumber)//12
            $searchSt = $this->test->getlimitSTDET('STDNUM', "$stdnum",0,0);

		if($stdnum and !$surname and $idnumber)//13
             $searchSt = $this->test->getlimitSTDET('STDNUM', "$stdnum",0,0);

		if($stdnum and $surname and !$idnumber)//14
            $searchSt = $this->test->getlimitSTDET('STDNUM', "$stdnum",0,0);

		if($stdnum and $surname and $idnumber)//15
            $searchSt = $this->test->getlimitSTDET('STDNUM', "$stdnum",0,0);
        return $searchSt;
	}

    /**
    * Method to get the page results of te Students based on a surname Search
    *
    * @param string $startl : provides the selected page's starting result value
    * @return string $searchSt: the array, containing the results of the query on the STDET table
    *                            the returned array will display all students from the starting value
    *                            the plus an additional 25.
    */
    function listsurn($startl=0)
    {
       $stdnum = strtoupper($this->getParam('studentNumber'));
       $surname = strtoupper($this->getParam('surname'));
       $idnumber = strtoupper($this->getParam('idNumber'));
       $endl = $startl + 25;
       $searchSt = $this->test->getlimitSTDET('SURNAM', "$surname",$startl,$endl);
       return $searchSt;
          
     }
        
	 function markRangeSearch(){
		$mark1 = $this->getParam('mark1');
		$mark2 = $this->getParam('mark2');
		$year = $this->getParam('resultyear');
		
		$result = "";
		$sql = "select * from tbl_person where personTypeID = '18'";
		$person = $this->student->runSql($sql,'tbl_person',$this->module);
		foreach($person as $student){
			$sql = "select avg(mark) as avgmark from tbl_studentCourse sc inner join tbl_results r on sc.studentCourseID = r.studentCourseID where sc.personid = '".$student['personID']."' and r.assessmentDate like '$year%'";
			//echo $sql."<br><br>";
			$avgmark = $this->student->runSql($sql,'tbl_person',$this->module);
			//echo "<p>$mark1 --- ".$avgmark[0]['avgmark']." --- $mark2</p>";
			if($avgmark[0]['avgmark'] >= $mark1 and $avgmark[0]['avgmark'] <= $mark2){
				$result[] = $student;
				
			}
			
		}
		return $result;
	}


	function getEnquiry(){
		$personid = $this->getParam('id');
        $stenquiry = $this->test->getlimitSTDET('STDNUM', "$personid",0,0);
        return $stenquiry;
	}

	function getEnquiryToEdit(){
		$queryid = $this->getParam('queryID');
		$filter = " where enquiryID = '$queryid'";
 		$sql = "select * from tbl_person p inner join tbl_studentFinancialAidEnquiry se on p.personID = se.personID where se.enquiryID = '$queryid'";
		return $this->student->runSql($sql,'tbl_contactDetails',$this->module);
		
	}


	function editEnquiry(){
		$enquiry = $this->getParam('enqQuery');
		$date = $this->getParam('enqDateTime');
		$comment = $this->getParam('enqComment');
		$enquiryID = $this->getParam('queryid');
		
		$sql = "UPDATE tbl_studentFinancialAidEnquiry SET enquiry = '$enquiry', dateOfEnquiry = '$date', comment = '$comment' where enquiryID = '$enquiryID'";
		return $this->student->runSql($sql,'tbl_contactDetails',$this->module);
		
	}

	function addEnquiry(){
		$enquiry = $this->getParam('enqQuery');
		$date = $this->getParam('enqDateTime');
		$comment = $this->getParam('enqComment');
		$person = $this->getParam('personid');	
		$module = $this->getParam('module');

		$sql = "INSERT INTO tbl_studentFinancialAidEnquiry (enquiry,dateOfEnquiry, comment, personID,userid,module) values ('$enquiry', '$date', '$comment','$person','".$this->objUser->userId()."','$module')";
		return $this->student->runSql($sql,'tbl_contactDetails',$this->module);
	}

	function addFamily(){
		$personid = "";
		$stdid = $this->getParam('id');
		$annual = $this->getParam('annual');
		$monthly = $this->getParam('monthly');
		$race = $this->getParam('race');
		$gender= $this->getParam('gender');
		$title = $this->getParam('title');
		$maritalstatus = $this->getParam('maritalstatus');
		$relationship = $this->getParam('relationship');
		$addresstype = $this->getParam('addresstype');
		$idnumber = $this->getParam('txtIdnumber');
		$name = $this->getParam('txtName');
		$surname = $this->getParam('txtSurname');
		$streetaddress = $this->getParam('txtStreetAddress');
		$suburb = $this->getParam('txtSuburb');
		$town = $this->getParam('txtTown');
		$code = $this->getParam('txtCode');
		$telephone = $this->getParam('txtTelephone');
		$cellphone = $this->getParam('txtCellphone');
		$email = $this->getParam('txtEmail');
		$employertel = $this->getParam('txtEmployertel');
		$employerdetails = $this->getParam('txtEmployerdetails');
		$year = $this->getParam('txtYear');
		$occupation = $this->getParam('txtOccupation');
		$spousename = $this->getParam('txtSpousename');


		$personFields = array('idnumber'=>$idnumber, 'name'=>$name , 'surname'=>$surname , 'raceID' => $race , 'genderID'=>$gender, 'maritalStatusID'=> $maritalstatus, 'personTypeID'=> $relationship , 'titleID'=>$title);
		//var_dump($personFields);
		//$personSql = "INSERT INTO tbl_person SET idnumber=$idnumber, name=$name, surname=$surname, raceID=$race, genderID=$gender, maritalStatusID=$maritalstatus, paersonTypeID=?, titleID=?";

		if($add = $this->student->inputInfo('tbl_person',$personFields,$this->module)){
			//var_dump($add);
			$sql = "select * from tbl_person order by personID desc limit 1";
			$arr = $this->student->runSql($sql,'tbl_person',$this->module);

			$personid = $arr[0]['personID'];
			
			$familyFields = array('personID'=>$personid , 'studentID'=>$stdid, 'whichYear'=>date("Y") ,'relationshipID'=>$relationship, 'spousename'=>$spousename , 'employerdetails'=>$employerdetails , 'employertelephone'=>$employertel, 'occupation'=>$occupation,'annualgross'=>$annual,'monthlygross'=>$monthly);
			$add = $this->student->inputInfo('tbl_family',$familyFields,$this->module);

			$addrFields = array('streetAddress'=>$streetaddress , 'suburb'=>$suburb, 'town'=>$town , 'code'=>$code , 'contactTypeID'=>$addresstype , 'telephone'=>$telephone , 'cellphone'=>$cellphone, 'email'=>$email , 'personID'=>$personid , 'residingYear'=>date("Y"));
			$add = $this->student->inputInfo('tbl_contactDetails',$addrFields,$this->module);
			
		
	}

	}

	function getFamily(){
		$personid = $this->getParam('familyid');
		$sql = "select * from tbl_person p inner join tbl_family f on p.personID = f.personID where p.personID = '$personid'";
		//echo $sql;
		return $this->student->runSql($sql,'tbl_contactDetails',$this->module);
			
	}
		
	function getFamilyAddress(){
		$personid = $this->getParam('familyid');
		$filter = " where personID = '$personid'";
		//echo $filter;
		return $this->student->getInfo('tbl_contactDetails',$this->module,null,$filter);
	}	

	function editFamily(){
		$personid = $this->getParam('fid');
		$stdid = $this->getParam('id');
		$annual = $this->getParam('annual');
		$monthly = $this->getParam('monthly');
		$race = $this->getParam('race');
		$gender= $this->getParam('gender');
		$title = $this->getParam('title');
		$maritalstatus = $this->getParam('maritalstatus');
		$relationship = $this->getParam('relationship');
		$addresstype = $this->getParam('addresstype');
		$idnumber = $this->getParam('txtIdnumber');
		$name = $this->getParam('txtName');
		$surname = $this->getParam('txtSurname');
		$streetaddress = $this->getParam('txtStreetAddress');
		$suburb = $this->getParam('txtSuburb');
		$town = $this->getParam('txtTown');
		$code = $this->getParam('txtCode');
		$telephone = $this->getParam('txtTelephone');
		$cellphone = $this->getParam('txtCellphone');
		$email = $this->getParam('txtEmail');
		$employertel = $this->getParam('txtEmployertel');
		$employerdetails = $this->getParam('txtEmployerdetails');
		$year = $this->getParam('txtYear');
		$occupation = $this->getParam('txtOccupation');
		$spousename = $this->getParam('txtSpousename');
		
		$personFields = array('idnumber'=>$idnumber, 'name'=>$name , 'surname'=>$surname , 'raceID' => $race , 'genderID'=>$gender, 'maritalStatusID'=> $maritalstatus, 'personTypeID'=> $relationship , 'titleID'=>$title);
		$person = $this->student->updateInfo('personID', $personid,'tbl_person',$personFields,$this->module);

		$familyFields = array('personID'=>$personid , 'studentID'=>$stdid, 'whichYear'=>date("Y") ,'relationshipID'=>$relationship, 'spousename'=>$spousename , 'employerdetails'=>$employerdetails , 'employertelephone'=>$employertel, 'occupation'=>$occupation,'annualgross'=>$annual,'monthlygross'=>$monthly);

		$family = $this->student->updateInfo('personID', $personid,'tbl_family',$familyFields,$this->module);

		/*
		problem with updating family and address details.

		what if the person has more than one address, how do you know which one is edited and which one is not...if you just run the query as it is there is achance of changing all the person's addresses to the same values

		$addrFields = array('streetAddress'=>$streetaddress , 'suburb'=>$suburb, 'town'=>$town , 'code'=>$code , 'contactTypeID'=>$addresstype , 'telephone'=>$telephone , 'cellphone'=>$cellphone, 'email'=>$email , 'personID'=>$personid , 'residingYear'=>date("Y"));

		$address = $this->student->updateInfo('personID', $personid,'tbl_family',$familyFields,$this->module);
		*/
	}

	

}
