<?php
/* ----------- data class extends dbTable for tbl_guestbook------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
    {
        die("You cannot view this page directly");
    }


/**
* Model class for the table  
*/

//require_once('../../remotedatasource/classes/remotedb_class_inc.php');

class dbfinancialaid extends object
{

	var $remotedb;
	var $student;
	var $module;
	var $objUser;

	function init(){
	 	parent::init();
		$this->objUser = & $this->newObject("user", "security");
    	//$this->NAMESPACE
		$name = 'http://172.16.65.134/webserviceDEV/studentinfo2.php?wsdl';
        // Pull in the NuSOAP code
		//require_once('lib/nusoap/nusoap.php');
		// Create the client instance
		$this->objSoapClient = new SoapClient($name);
		//parent::init();
		//$this->remotedb =& $this->newObject('remotedb','remotedatasource');
		//$this->objUser =& $this->newObject('user','security');
		//$this->student =& $this->newObject('student','studentmodule');
		//$this->module = $this->getParam('module');
	}
	
	function getMarks($field,$value)
	{
	$value = "'" . $value . "'";
	$results = $this->objSoapClient->getSTSBJ($field,$value);
	return $results;
	}
	
	function getAll($field){
	//$value = "'" . $value . "'";
	$results = $this->objSoapClient->getallSTDET($field);
	return $results;
	}

	function getAllStudents($field,$value,$start,$offset){
		$this->objUser = & $this->newObject("user", "security");
		$this->NAMESPACE2 = 'http://172.16.65.134/webserviceDEV/studentinfo4.php?wsdl';
        // Pull in the NuSOAP code
		// Create the client instance
		$this->objSoapClient2 = new SoapClient($this->NAMESPACE2);
		
        
       return $this->objSoapClient2->getlimitSTDET($field,$value,$start,$offset);
	}


	function getAdditionalInfo($field,$value)
	{
		$value = "'" . $value . "'";
  		$results = $this->objSoapClient->getSTADT($field, "$value");
		return $results;
	}

	function getPersonInfo($field,$value){
	  	$value = "'" . $value . "'";
  		$results = $this->objSoapClient->getSTDET($field, "$value");
		return $results;
	}
	
	function searchStudent($field, $value){
        $value = "'" . $value . "'";
  		$results = $this->objSoapClient->getSTDET($field, "$value");
		return $results;
	}
	
	
	function studentAddress($value){
		$value = "'" . $value . "'";
		$results = $this->objSoapClient->getSTADR('STDNUM', "$value");
		return $results;
	}
	
	function getStudentNumber($field,$value){
		$value = "'" . $value . "'";
  		$results = $this->objSoapClient->getSTCRS($field, "$value");
		return $results;
	}
	

	function getSTCRS($value){
	$value = "'" . $value . "'";
	$field = 'STDNUM';
	$results = $this->objSoapClient->getSTCRS($field,$value);
	return $results;
	}
	
	//CRSCDE ---> value
	function getCourseInfo($value){
	$value = "'" . $value . "'";
	$field = 'CRSCDE';
	$results = $this->objSoapClient->getCRSE($field,$value);
	return $results;
	}

	function getApplication($field,$value){
		$value = "'" . $value . "'";
  		$results = $this->objSoapClient->getSTDNT($field, "$value");
		return $results;
	}
	
	function getCoursedetails($value){
	$field = 'CRSCDE';
	$value = "'" . $value . "'";
  	$results = $this->objSoapClient->getCRSE($field, "$value");
	return $results;
	}

	
	function getCourseResuts($studentCourseID,$assessmentDate){
		$sql = "select * from tbl_results where studentCourseID = '$studentCourseID' and assessmentDate like '$assessmentDate%'";
		//echo $sql;
		return $this->student->runSql($sql,'tbl_student',$this->module);
	}

	
	//---------------Stundent End	
	function getAccountInformationHistory($field,$value){
		$value = "'" . $value . "'";
  		$results = $this->objSoapClient->getSTACH($field, "$value");
		return $results;
	}

	function getLoanInformation($field,$value){
		$value = "'" . $value . "'";
  		$results = $this->objSoapClient->getSTLON($field, "$value");
		return $results;
	}




	function getAccountInformation($field,$value){
		$value = "'" . $value . "'";
  		$results = $this->objSoapClient->getSTACC($field, "$value");
		return $results;
		//$year = $this->getParam('year');
		//$personid = $this->getParam('id');
		//$filter = "";
		//if(!is_null($year)){
		//	$filter = "where accountYear like '$year%' and personID = '$personid'";
		//}
		//else{
		//	$year = date('Y');
		//	$filter = "where accountYear like '$year%' and personID = '$personid'";
		//}
		//return $this->student->getInfo('tbl_studentAccount',$this->module,null,$filter);
	}

	//----------------Accounts End
	function getResidence($field,$value){
		//getResidence
		$value = "'" . $value . "'";
  		$results = $this->objSoapClient->getACCOM($field, "$value");
		return $results;
		//return $this->student->getInfo('tbl_residence',$this->module);
	}

	function getStudentRes($field,$value){
		//getStudentRes
		$value = "'" . $value . "'";
  		$results = $this->objSoapClient->getACCOM($field, "$value");
		return $results;
		/*$year1= $year+1;
		$filter = " where personID = '$personid' and (resyear = '$year' or resyear = '$year1')";
		//echo $filter;
		return $this->student->getInfo('tbl_studentResidence',$this->module,null,$filter);
		 */
	}
	
	function getACCOM($field,$value){
		//Accomadation
		$value = "'" . $value . "'";
  		$results = $this->objSoapClient->getACCOM($field, "$value");
		return $results;
		
	}

	

	function getStudentFamily($personid){
		$sql = "select * from tbl_person p inner join tbl_family f on p.personID = f.personID where f.studentID = '$personid'";
		//echo $sql;
		return $this->student->runSql($sql,'tbl_contactDetails',$this->module);
	}

	function studentEmployement($personid){
		$filter = " where personID = '$personid'";
		return $this->student->getInfo('tbl_employment',$this->module,null,$filter);
	}

	

	function editFinapplication(){
		$personid = $this->getParam('id');
		$applicationID =$this->getParam('txtApplicationID');
		$appstatus = $this->getParam('status');
		$comment = $this->getParam('txtComment');
		$fields = array('finApplicationStatus'=>$appstatus, 'finComment'=>$comment);
		var_dump($fields);
		return $this->student->updateInfo('applicationID', $applicationID, 'tbl_application', $fields,$this->module);
	}

	function editResapplication(){
		$personid = $this->getParam('id');
		$applicationID =$this->getParam('txtApplicationID');
		$appstatus = $this->getParam('status');
		$comment = $this->getParam('txtComment');
		$resid = $this->getParam('residence');
		$resyear = $this->getParam('resyear');
		$resappid = $this->getParam('txtResAppID');
		//echo $resappid;
		$fields = array('resApplicationStatus'=>$appstatus, 'resComment'=>$comment);
		//var_dump($fields);
		$update = $this->student->updateInfo('applicationID', $applicationID, 'tbl_application', $fields,$this->module);

		if(is_array($this->getStudentRes($personid,$resyear))){
			$fields = array('residenceID'=>$resid,'resyear'=>$resyear);
			//echo "'residenceID'=>$resid,'resyear'=>$resyear)";
			return $this->student->updateInfo('studentResidenceID', $resappid, 'tbl_studentResidence', $fields,$this->module);
		}
		else{
			$fields = array('resyear'=>$resyear,'residenceID'=>$resid,'personID'=>$personid);
			return $this->student->inputInfo('tbl_studentResidence',$fields,$this->module);
		}

		
	}
	
	function lookup($filter = null){
		$comma = " where ";
		$filtered = "";
		
		if(is_array($filter)){
			foreach($filter as $key=>$value){
				$filtered .= " $comma l.lookupID = '$value' ";
				
				$comma = " or ";
							
			}
			$filter = $filtered;
		}
	
		$sql = "select * from tbl_lookUp l inner join tbl_lookupType lt on l.lookupTypeID = lt.lookupTypeID $filter";
		//echo $sql;
		return $this->student->runSql($sql,'tbl_lookUp',$this->module);
	}

	function getLookupInfo($id){
	//	$filter = "where lookupID = '$id'";
	//	return $this->student->getInfo('tbl_lookUp',$this->module,null,$filter);

	}

	
		/*if(!is_null($year)){
			$year = " and yearOfStudy = '$year'";
		}
		
		$sql = "select * from tbl_studentCourse sc inner join tbl_course c on sc.courseid = c.courseID where sc.personID = '$id' $year";
		//echo $sql;
		return $this->student->runSql($sql,'tbl_student',$this->module);
	}
	
	*/

	function getStudyYears($field,$value){
		$value = "'" . $value . "'";
		$fields = 'STDNUM';
		$results = $this->objSoapClient->getSTCRS($field,$value);
		return $results;
		
	}

	function getPayments($value){
		$value = "'" . $value . "'";
		$fields = 'PMTDT';
		$results = $this->objSoapClient->getSTSBJ($field,$value);
		return $results;
		
		/*
		$year = $this->getParam('year');
		$personid = $this->getParam('id');
		if(!is_null($year)){
			$filter = "where dateOfPayment like '$year%' and personID = '$personid'";
		}
		else{
			$year = date('Y');
			$filter = "where dateOfPayment like '$year%' and personID = '$personid'";
		}
		return $this->student->getInfo('tbl_studentPayment',$this->module,null,$filter);
		*/
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
		//$personid = $this->getParam('id');
		$filter = " where personID = '$personid'";
		$arr = $this->student->getInfo('tbl_student',$this->module,null,$filter);
		return $this->getLookupInfo($arr[0]['studyTypeID']);
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

	/*function search(){
		$stdnum = $this->getParam('studentNumber');
		$applnum = $this->getParam('applicationNumber');
		$surname = $this->getParam('surname');
		$idnumber = $this->getParam('idNumber');
	
		$sql = "select * from tbl_person p inner join tbl_student cd on p.personID = cd.personID ";

		if(!$stdnum and !$applnum and !$surname and !$idnumber)//0
			$sql .= "";

		if(!$stdnum and !$applnum and !$surname and $idnumber)//1
			$sql .= "where idnumber = '$idnumber'";

		if(!$stdnum and !$applnum and $surname and !$idnumber)//2
			$sql .= "where surname like '$surname%'";

		if(!$stdnum and !$applnum and $surname and $idnumber)//3
			$sql .= " where surname like '$surname%' and idnumber = '$idnumber'";

		if(!$stdnum and $applnum and !$surname and !$idnumber)//4
			$sql .= " where applicationNumber = '$applnum'";

		if(!$stdnum and $applnum and !$surname and $idnumber)//5
			$sql .= " where applicationNumber = '$applnum' and idnumber = '$idnumber'";

		if(!$stdnum and $applnum and $surname and !$idnumber)//6
			$sql .= " where applicationNumber = '$applnum' and surname like '$surname%'";
		
		if(!$stdnum and $applnum and $surname and $idnumber)//7
			$sql .= " where applicationNumber = '$applnum' and surname like '$surname%' and idnumber = '$idnumber'";

		if($stdnum and !$applnum and !$surname and !$idnumber)//8
			$sql .= " where studentNumber = '$stdnum'";

		if($stdnum and !$applnum and !$surname and $idnumber)//9
			$sql .= " where studentNumber = '$stdnum' and idnumber = '$idnumber'"; 

		if($stdnum and !$applnum and $surname and !$idnumber)//10
			$sql .= " where studentNumber = '$stdnum' and surname like '$surname%'";

		if($stdnum and !$applnum and $surname and $idnumber)//11
			$sql .= " where studentNumber = '$stdnum' and surname like '$surname%' and idnumber = '$idnumber'";
		
		if($stdnum and $applnum and !$surname and !$idnumber)//12
			$sql .= "where studentNumber = '$stdnum and applicationNumber = '$applnum'";
		
		if($stdnum and $applnum and !$surname and $idnumber)//13
			$sql .= "where studentNumber = '$stdnum and applicationNumber = '$applnum' and idnumber = '$idnumber'";
	
		if($stdnum and $applnum and $surname and !$idnumber)//14
			$sql .= "where studentNumber = '$stdnum and applicationNumber = '$applnum' and idnumber = '$idnumber' and surname like '$surname%'";

		if($stdnum and $applnum and $surname and $idnumber)//15
			$sql .= "where studentNumber = '$stdnum and applicationNumber = '$applnum' and idnumber = '$idnumber' and surname like '$surname%' and idnumber = '$idnumber'";

		return $this->student->runSql($sql,'tbl_contactDetails',$this->module);
		
	}
	*/
	
	
	

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
		$module = $this->module;
		$sql = "select * from tbl_person p inner join tbl_studentFinancialAidEnquiry se on p.personID = se.personID where se.personID = '$personid' and se.module = '$module'";
		//echo $sql;
		return $this->student->runSql($sql,'tbl_contactDetails',$this->module);
	}

	function getEnquiryToEdit(){
		$queryid = $this->getParam('queryID');
		//$filter = " where enquiryID = '$queryid'";
		//return $this->student->getInfo('tbl_studentFinancialAidEnquiry',$this->module,null,$filter);
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
		//echo $sql;
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

	}

	
	
	//function getAllStudents($field,$value){
	/*	//$sql = "select * from tbl_person p inner join tbl_student s on p.personID = s.personID $filter";
		$action = $this->getParam('action');
		$filter = "";
		$sql ="";;
		if($action === null or $action === 'ok'){
			if($this->module === "residence"){
				$filter = " where resApplication = '1'";
			}
			if($this->module === "financialaid"){
				$filter = " where faidApplication = '1'";
			}
			$sql = "select * from tbl_person p inner join tbl_application a on p.personID = a.personID $filter";
		}
		else{
			$sql = "select * from tbl_person p inner join tbl_student s on p.personID = s.personID $filter";
		}
		//echo $sql;
		return $this->student->runSql($sql,'tbl_student',$this->module);
*/	
	
}
