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

class control extends controller
{
	var $remotedb;
	var $student;
	var $module;
	var $financialaid;
	var $personid;	

	function init(){
		parent::init();
		//$this->remotedb =& $this->getObject('remotedb','remotedatasource');
		$this->student =& $this->getObject('student','studentmodule');
		$this->module = $this->getParam('module');
		$this->financialaid =& $this->getObject('dbfinancialaid');
		$this->personid = $this->getParam('id');
		$this->module = $this->getParam('module');
	}

	function enquiry($id){
		if($this->getParam('id') and !$this->getParam('addenquiry') and !$this->getParam('ok')){
			//$this->setVarByRef('enquiry',$this->financialaid->getPersonInfo('STDNUM',$this->personid));
			//echo '<pre>';
			$enquiry = $this->financialaid->getPersonInfo('STDNUM',$id);
			$this->setVarByRef('enquiry',$enquiry);
			return 'enquiry_tpl.php';
		}

		if($this->getParam('ok')){
			$this->setVarByRef('stdinfo',$this->financialaid->getAllStudents());
			return 'studentlist_tpl.php';
		}

		if($this->getParam('addenquiry')){
			$this->setVarByRef('enquiry', $this->financialaid->getPersonInfo());
			//echo "add quiery";
			return 'addenquiry_tpl.php';
		}

		if($this->getParam('edit')){
			$this->setVarByRef('enquiry',$this->financialaid->getEnquiryToEdit());
			return 'editenquiry_tpl.php';
		}

		if($this->getParam('editquery')){
			$this->financialaid->editEnquiry();
			$this->setVarByRef('stdinfo',$this->financialaid->getAllStudents());
			return 'studentlist_tpl.php';
					
		}

		if($this->getParam('canceledit')){
			$this->setVarByRef('stdinfo',$this->financialaid->getAllStudents());
			return 'studentlist_tpl.php';

		}

		if($this->getParam('addquery')){
			$this->financialaid->addEnquiry();
			$this->setVarByRef('stdinfo',$this->financialaid->getAllStudents());
			return 'studentlist_tpl.php';
		}

		if($this->getParam('canceladd')){
			$this->setVarByRef('stdinfo',$this->financialaid->getAllStudents());
			return 'studentlist_tpl.php';

		}
	}

	function markrange(){
		if($this->getParam('ok')){
			//$this->setVarByRef('stdinfo',$this->financialaid->markRangeSearch());
			return 'studentlist_tpl.php';
		}
	
		if($this->getParam('cancel')){
			$this->setVarByRef('stdinfo',$this->financialaid->getAllStudents());
			return 'studentlist_tpl.php';

		}
	
		if(!$this->getParam('ok') and !$this->getParam('cancel')){
				return 'markrange_tpl.php';
		}
	}

	function family(){
		$this->setVarByRef('familyperson',$this->financialaid->getFamily());
		$this->setVarByRef('fpersonaddr',$this->financialaid->getFamilyAddress());
	}
	
	function editfamily(){
		if(!$this->getParam('ok') and !$this->getParam('cancel')){
			$this->family();
			$this->newFamily();
			return 'editfamily_tpl.php';
		}
		if($this->getParam('cancel')){
			$this->setVarByRef('stdinfo',$this->financialaid->getAllStudents());
			return 'studentlist_tpl.php';
		}

		if($this->getParam('ok')){
			$familypersonID = $this->getParam('fid');
			$studentpersonID = $this->getParam('id');
			//$stdid = $this->getParam('id');
			$familymemberid = $this->getParam('txtFamilymemberID');
			$contactID = $this->getParam('txtContactID');
			$contactTypeID = $this->getParam('txtContactTypeID');
	
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
			//$year = $this->getParam('txtYear');
			$year = date("Y");
			$occupation = $this->getParam('txtOccupation');
			$spousename = $this->getParam('txtSpousename');

			//update person table...
			$fields = array('name'=>$name, 'surname'=>$surname, 'genderID'=>$gender, 'raceID'=>$race, 'titleID'=>$title, 'maritalStatusID'=>$maritalstatus,'personTypeID'=>$relationship,'idnumber'=>$idnumber);
			//var_dump($fields);
			$personUpdate = $this->student->updateInfo('personID', $familypersonID,'tbl_person',$fields,$this->module);
			
			//update contactDetails table
			
			$contactFields = array('streetAddress' =>$streetaddress,'suburb'=>$suburb, 'town'=>$town, 'code'=>$code, 'email'=>$email, 'contactTypeID'=>$addresstype, 'telephone'=>$telephone, 'cellphone'=>$cellphone);
			//var_dump($contactFields);
			$contactUpdate = $this->student->updateInfo('contactID', $contactID,'tbl_contactDetails',$contactFields,$this->module);
			//echo "AND NOW HERE<BR>";
			
			$sql = "update tbl_family set occupation='$occupation', spousename='$spousename', employertelephone='$employertel', employerdetails='$employerdetails' ,annualgross = '$annual', monthlygross='$monthly',relationshipID='$relationship' where familymemberID ='$familymemberid'";
			//echo $sql;
			$famUpdate = $this->student->runSql($sql,'tbl_contactDetails',$this->module);

			/*$familyFields = array('occupation'=>$occupation,'spousename'=>$spousename, 'employertelephone'=>$employertel,'employerdetails'=>$employerdetails,'annualgross'=>$annual, 'monthlygross'=>$monthly,'relationshipID'=>$relationship);

			var_dump($familyFields);
			$familyUpdate = $this->student->updateInfo('familymemberID', $familymemberid,'tbl_family',$familyFields,$this->module);
			*/
			$this->setVarByRef('stdinfo',$this->financialaid->getAllStudents(" where p.personID = '".$this->getParam('id')."'"));
			$this->setVarByRef('stdfamily',$this->financialaid->getStudentFamily($this->personid));
			return 'stdfamily_tpl.php';	


			/*
			$updateContact = "UPDATE tbl_contactDetails set streetAddress = 'streetaddress',suburb = '$suburb', town='$town', code='$code', email='$email',contactTypeID='$addresstype',telephone='$telephone',cellphone='$cellphone' where contactID = $contactID and ";

			$this->setVarByRef('stdinfo',$this->financialaid->getAllStudents(" where p.personID = '".$this->getParam('id')."'"));
			$this->setVarByRef('stdfamily',$this->financialaid->getStudentFamily($this->personid));
			return 'stdfamily_tpl.php';
			*/

		}
	}

	function newFam(){
		$this->newFamily();
		return 'newfamily_tpl.php';
	}

	function newFamily(){
		$this->setVarByRef('stdinfo',$this->financialaid->getAllStudents(" where p.personID = '".$this->getParam('id')."'"));
		$this->setVarByRef('race',$this->financialaid->lookup(" where lookupType = 'Race'"));
		$this->setVarByRef('gender',$this->financialaid->lookup(" where lookupType = 'Gender'"));
		$this->setVarByRef('title',$this->financialaid->lookup(" where lookupType = 'Title'"));
		$this->setVarByRef('maritalstatus',$this->financialaid->lookup(" where lookupType = 'Marital Status'"));
		$this->setVarByRef('relationship',$this->financialaid->lookup(" where lookupType = 'Person Type'"));	
		$this->setVarByRef('addresstype',$this->financialaid->lookup(" where lookupType = 'Contact Type'"));

		$this->setVarByRef('monthlyincome',$this->financialaid->lookup(" where lookupType = 'Monthly Gross Income'"));
		$this->setVarByRef('annualincome',$this->financialaid->lookup(" where lookupType = 'Annual Gross Income'"));
	}
	
	function parttime(){
		$personid = $this->getParam('id');

		if(!$this->getParam('ok') and !$this->getParam('cancel')){
			$this->setVarByRef('stdinfo',$this->financialaid->getAllStudents(" where p.personID = '$personid'"));
			$this->setVarByRef('employment',$this->financialaid->studentEmployement($personid));
			return 'parttime_tpl.php';
		}
		
		if($this->getParam('cancel')){
			$this->setVarByRef('stdinfo',$this->financialaid->getAllStudents());
			return 'studentlist_tpl.php';
		}

		if($this->getParam('ok')){
			$emptel = $this->getParam('txtEmployertel');
			$empdetails = $this->getParam('txtEmployerdetails');
			$jobtitle = $this->getParam('txtJobtitle');
			
			/*
			$fields = array('jobtitle'=>$jobtitle,'employertelephone'=>$emptel,'employerdetails'=>$empdetails,'employmentyear'=>date("Y"),'personID'=>$personid);
			//var_dump($fields);
			$add = $this->student->inputInfo('tbl_employement',$fields,$this->module);
			*/
			
			$year = date("Y");
			$sql = "INSERT INTO tbl_employment (jobtitle,employertelephone,employerdetails ,employmentyear,personID) values ('$jobtitle', '$emptel', '$empdetails', '$year', '$personid')";
			//echo $sql;
			$arr = $this->student->runSql($sql,'tbl_employment',$this->module);

			$this->setVarByRef('stdinfo',$this->financialaid->getAllStudents(" where p.personID = '$personid'"));
			$this->setVarByRef('employment',$this->financialaid->studentEmployement($personid));
			return 'parttime_tpl.php';
		}
	}

	function financialaidApp(){
		$year = "";
		if(!$this->getParam('year'))$year = date("Y");
		else $year = $this->getParam('year');
		$personid = $this->getParam('id');
	
		$this->setVar('applicationYear',$year);
		$this->setVarByRef('finapplication',$this->financialaid->getApplication($personid,$year));
		$this->setVarByRef('studyYears',$this->financialaid->getStudyYears($personid,$year));
		return 'finapplication_tpl.php';
	}
	
	function residenceApp(){
		$year = "";
		if(!$this->getParam('year'))$year = date("Y");
		else $year = $this->getParam('year');
		$personid = $this->getParam('id');
		
		$personid = $this->getParam('id');
		$this->setVar('applicationYear',$year);
		$this->setVarByRef('finapplication',$this->financialaid->getApplication($personid,$year));
		$this->setVarByRef('studyYears',$this->financialaid->getStudyYears($personid,$year));
		return 'resapplication_tpl.php';
	}

	

	function finapplication(){
		$year = "";
		if(!$this->getParam('year'))$year = date("Y");
		else $year = $this->getParam('year');
		
		$personid = $this->getParam('id');
		
		
		
		if(!$this->getParam('edit') and !$this->getParam('cancel') and !$this->getParam('finish') and !$this->getParam('edit_now')){
			$this->setVar('applicationYear',$year);
			$this->setVarByRef('finapplication',$this->financialaid->getApplication($personid,$year));
			$this->setVarByRef('studyYears',$this->financialaid->getStudyYears($personid,$year));
			return 'finapplication_tpl.php';
		}
		
		if($this->getParam('edit')){
			$this->setVar('applicationYear',$year);
			$this->setVarByRef('finapplication',$this->financialaid->getApplication($personid,$year));
			$this->setVarByRef('studyYears',$this->financialaid->getStudyYears($personid,$year));
			$this->setVarByRef('applicationstatus',$this->financialaid->lookup(" where lookupType = 'Application Status'"));
			return 'finaidappedit_tpl.php';
		}
		
		if($this->getParam('cancel')){
			$this->setVarByRef('stdinfo',$this->financialaid->getAllStudents());
			return 'studentlist_tpl.php';

		}
	
		if($this->getParam('edit_now')){
			//echo "WHAT !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!";
			$edit = $this->financialaid->editFinapplication();
			$this->setVar('applicationYear',$year);
			$this->setVarByRef('finapplication',$this->financialaid->getApplication($personid,$year));
			$this->setVarByRef('studyYears',$this->financialaid->getStudyYears($personid,$year));
			return 'finapplication_tpl.php';
		}
	}

	

	function resapplication(){
		$year = "";
		//echo $this->getParam('txtApplicationID');
		if(!$this->getParam('year'))$year = date("Y");
		else $year = $this->getParam('year');

		$personid = $this->getParam('id');
		
		$personid = $this->getParam('id');
		$this->setVarByRef('residence',$this->financialaid->getResidence());
		$this->setVarByRef('stdres',$this->financialaid->getStudentRes($personid,$year));		
			
		if($this->getParam('edit')){
			$this->setVar('applicationYear',$year);
			$this->setVarByRef('finapplication',$this->financialaid->getApplication($personid,$year));
			$this->setVarByRef('studyYears',$this->financialaid->getStudyYears($personid,$year));
			$this->setVarByRef('applicationstatus',$this->financialaid->lookup(" where lookupType = 'Application Status'"));
			return 'resappedit_tpl.php';
		}
		
		if($this->getParam('cancel')){
			$this->setVarByRef('stdinfo',$this->financialaid->getAllStudents());
			return 'studentlist_tpl.php';

		}
	
		if($this->getParam('edit_now')){
			$edit = $this->financialaid->editResapplication();
			$this->setVar('applicationYear',$year);
			$this->setVarByRef('finapplication',$this->financialaid->getApplication($personid,$year));
			$this->setVarByRef('studyYears',$this->financialaid->getStudyYears($personid,$year));
			return 'resapplication_tpl.php';
		}

		if(!$this->getParam('edit') and !$this->getParam('cancel') and !!$this->getParam('edit_now')){
			$this->setVar('applicationYear',$year);
			$this->setVarByRef('finapplication',$this->financialaid->getApplication($personid,$year));
			$this->setVarByRef('studyYears',$this->financialaid->getStudyYears($personid,$year));
			return 'resapplication_tpl.php';
		}
		
	}

}
