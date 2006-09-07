<?php

/**
 * @package activitylog
 * @filesource
 */

/**
 * Activity log manages a log of activites between a postgraduate student and his/her supervisor/s
 * The activity log is part of the postgraduate supervision support tools.
 *
 * @access public
 * @author "Jameel Adam versoin: 1.wsdl" "Siphiwo Dzingwe version: 1.N0 wsdl"
 * 
 */
class residence extends controller
{
	var $personid;
	function init(){
		$this->objUser =& $this->getObject('user','security');
		$this->financialaid =& $this->getObject('dbfinancialaid','residence');
		//$this->misc =& $this->getObject('dbmiscinfo','studentfinance');
		$this->personid = $this->getParam('id');
		$this->loadClass('button', 'htmlelements');
		$this->loadClass('textinput','htmlelements');
		$this->loadClass('textarea','htmlelements');
		$this->loadClass('layer','htmlelements');
		$this->loadClass('form','htmlelements');
		$this->loadClass('multitabbedbox','htmlelements');
		$this->loadClass('href','htmlelements');
		$this->loadClass('tabbedbox', 'htmlelements');
		$this->loadclass('link','htmlelements');
		$this->loadclass('dropdown','htmlelements');

		//$this->student =& $this->getObject('student','studentmodule');
		//$this->dbstudent =& $this->getObject('dbstudents','residence');
		//$this->module = $this->getParam('module','residence');
		//$this->control =&$this->getObject('control','residence');
		
	}

	function dispatch(){
			$action = $this->getParam('action');
	switch($action)
	{
		case null: 
			return 'studentlist_tpl.php';
		
		case 'ok':
				$id = $this->getParam('id',null);
				if(!$id==null){
				$field = 'STDNUM';
				$value = $id ;
				$start = 0;
				$offset = 10 ;
				$stdinfo = $this->financialaid->getAllStudents($field,$value,$start,$offset);
				$this->setvarByRef('stdinfo',$stdinfo);
				return 'studentlist_tpl.php';
				}else{
				return 'studentlist_tpl.php';
				}
		case 'nextlist':
				$id   = $this->getParam('id',null);
				$list   = $this->getParam('nextlist',null);
				$field = 'SURNAM';
				$value = $id ;
				$start =  ($list *6)-6 ;
				$offset = $list * 6;
				$stdinfo = $this->financialaid->getAllStudents($field,$value,$start,$offset);
				$this->setvarByRef('stdinfo',$stdinfo);
				$this->setvarByRef('$id',$$id);
				return 'studentlist_tpl.php';
			break;
		case 'info':
				$value = $this->getParam('id',null);
				$field ='STDNUM';
				$studentdetails = $this->financialaid->getPersonInfo($field,$value);
				$studentaddress = $this->financialaid->studentAddress($value);
				$arrlength = count($studentaddress);
				$this->setvarByRef('stdinfo',$stdinfo);
				$this->setvarByRef('studentaddress',$studentaddress);
				$this->setvarByRef('studentdetails',$studentdetails);
				return 'studentinfo_tpl.php';
		case 'results':
				$studentNumber=$this->getParam('id',null);
				$field = 'STDNUM';
				$studentdetails = $this->financialaid->getPersonInfo($field,$studentNumber);
				$stdinfo= $this->financialaid->getSTCRS($studentNumber); //crsecode
				$marksarr = $this->financialaid->getMarks($field,$studentNumber);
				$studentNumber=$this->getParam('id',null);
				$this->setvarByRef('stdinfo',$stdinfo);
				$this->setvarByRef('marksarr',$marksarr);
				$this->setvarByRef('studentNumber',$studentNumber);
				$this->setvarByRef('studentdetails',$studentdetails);
				return 'studentresults_tpl.php';
		case 'payment':
				$this->studentPayment();
				return 'studentpayment_tpl.php';
		case 'newpayment':
				$this->newPayment();
				return 'newstudentpayment_tpl.php';
		case 'pay':
				if(!is_null($this->getParam('ok'))){
				$this->financialaid->makePayment($this->objUser->userId());
				}
				$this->studentPayment();
				return 'studentpayment_tpl.php';
		case 'sponsor':
				if($this->getParam('assignsponsor')){
					$this->financialaid->assignSponsor();
					$this->sponsor();
					return 'studentsponsor_tpl.php';
				}
				if($this->getParam('add_ok')){
					$this->addSponsor();
					return 'addstudentsponsor_tpl.php';
				}
				if($this->getParam('cancel') or $this->getParam('ok')){
					$this->studentPayment();
					return 'studentpayment_tpl.php';
				}
				else{
					$this->sponsor();
					return 'studentsponsor_tpl.php';
				}
		case 'addsponsor':
				return 'addstudentsponsor_tpl.php';
		case 'sponsorlist':
				$this->setVarByRef('sponsorlist',$this->financialaid->sponsorList());
				return 'sponsorlist_tpl.php';
		case 'studentssponsored':
				$this->setVarByRef('sponsoredStudents',$this->financialaid->getSponsoredStudents($this->personid));
				$this->setVar('sponsorname',$this->financialaid->getSponsorName($this->personid));
				return 'studentssponsored_tpl.php';
		case 'newsponsor':
				if($this->getParam('ok')){
					$arr = $this->financialaid->addNewSponsor();
					$this->setVarByRef('sponsorlist',$this->financialaid->sponsorList());
					return 'sponsorlist_tpl.php';
				}
				if($this->getParam('cancel')){
					$this->setVarByRef('sponsorlist',$this->financialaid->sponsorList());
					return 'sponsorlist_tpl.php';
				}
				if(!$this->getParam('cancel') and !$this->getParam('ok')){
					return 'newsponsor_tpl.php';
				}
		case 'sponsorinfo':
				$this->setVarByRef('sponsorinfo',$this->financialaid->editSponsor($this->personid));
				return 'editsponsor_tpl.php';
		case 'editsponsor':
				if($this->getParam('edit')){
					$updtate = $this->financialaid->updateSponsor($this->personid);
				}
				$this->setVarByRef('sponsorlist',$this->financialaid->sponsorList());
				return 'sponsorlist_tpl.php';
		case 'search':
			if(!$this->getParam('studentNumber')==null){
				$studentNumber = $this->getParam('studentNumber');
				$stdinfo = $this->financialaid->searchStudent('STDNUM',$studentNumber);
				}else{
				$surname = $this->getParam('surname');
				$surname = strtoupper($surname);
				$start=0;
				$offset=6;
				$stdinfo = $this->financialaid->getAllStudents('SURNAM',$surname,$start,$offset);
				$this->setVarByRef('surname',$surname);
				$this->setVarByRef('stdinfo',$stdinfo);
				return 'studentlist_tpl.php';	
				}
				$this->setVarByRef('stdinfo',$stdinfo);
				return 'studentlist_tpl.php';
		case 'markrange':
				if($this->getParam('ok')){
					//echo "ok okokokok";
					$this->setVarByRef('stdinfo',$this->financialaid->markRangeSearch());
					return 'studentlist_tpl.php';
				}
				if($this->getParam('cancel')){
					$this->setVarByRef('stdinfo',$this->financialaid->getAllStudents());
					return 'studentlist_tpl.php';


				}
				if(!$this->getParam('ok') and !$this->getParam('cancel')){
					return 'markrange_tpl.php';
				}
				
		case 'enquiry':
				return $this->control->enquiry($this->getParam('id',null));
							
		case 'nextofkin':
				$this->setVarByRef('stdinfo',$this->financialaid->getAllStudents(" where p.personID = '".$this->getParam('id')."'"));
				$this->setVarByRef('stdfamily',$this->financialaid->getStudentFamily($this->personid));
				return 'stdfamily_tpl.php';

		case 'newfamily':
				return $this->control->newFam();

		case 'addfamily':
				if($this->getParam('cancel')){
					$this->setVarByRef('stdinfo',$this->financialaid->getAllStudents());
					return 'studentlist_tpl.php';
				}

				if($this->getParam('ok')){
					$this->financialaid->addFamily();
					$this->setVarByRef('stdinfo',$this->financialaid->getAllStudents(" where p.personID = '".$this->getParam('id')."'"));
					$this->setVarByRef('stdfamily',$this->financialaid->getStudentFamily($this->personid));
					return 'stdfamily_tpl.php';
				}

		case  'editfamily':
				return $this->control->editfamily();

		case 'parttime':
				return $this->control->parttime();
			
		case 'finapp':
				return $this->control->financialaidApp();

		case 'resapp':
				return $this->control->residenceApp();
				

		case 'finapplication':
				return $this->control->finapplication();

		case 'resapplication':
				//return $this->control->resapplication();
				$field = 'STDNUM';
				$value = '2219065';
				echo '<pre>';
				print_r($this->financialaid->getResidence($field,$value));
				die;
		}

	}

	function studentInformation($field,$value)
	{
		$studentinfo = $this->financialaid->getPersonInfo($field,$value);
		$this->setvarByRef('stdinfo',$studentinfo);
		$address = $this->getParam('address');
		if(!is_null($address)){
			//echo "we are here";
			$this->setvarByRef('stdaddress',$this->financialaid->studentAddress($this->personid,$address));
		}
		else{
			$this->setVar('stdaddress',false);
		}
		$this->setvarByRef('stdlookup',$this->financialaid->lookup($filter));
	}
	
	function studentPayment(){
		$this->financialaid =& $this->getObject('dbfinancialaid');
		//$filter = " where p.personid = '$this->personid'";
		//$year = $this->getParam('year');
		//if(is_null($year)){
		//	$year = date('Y');
		//}
		$personid = $this->getParam('id');
		
		
		//$this->setVar('paymentYear',$year);
		$this->setVarByRef('stdinfo',$this->financialaid->getPersonInfo('STDNUM',$personid));
		//$this->setVarByRef('account',$this->financialaid->getAccountInformation('STDNUM',$personid));
		//$this->setVarByRef('payments',$this->financialaid->getPayments($personid));
		//$this->setVarByRef('studyYears',$this->financialaid->getStudyYears($personid));
	}

	
	function studentResults(){
		//$personid = $this->getParam('id');
		$filter = " where p.personid = '$this->personid'";

		$year = $this->getParam('year');
		if(is_null($year)){
			$year = date('Y');
		}
	
		$this->setvarByRef('stdinfo',$this->financialaid->getPersonInfo($personid));
		$this->setvarByRef('results',$this->financialaid->getCourseInfo($personid));
		$this->setVar('resultsYear',$year);
		$this->setVarByRef('studyYears',$this->financialaid->getStudyYears($personid));
		$this->setVarByRef('studyType',$this->financialaid->getStudyType($personid));
	}

	function newPayment(){
		//$personid = $this->getParam('id');
		$filter = " where p.personid = '$this->personid'";
		$this->setvarByRef('stdinfo',$this->financialaid->getPersonInfo($filter));
		
	}
	
	function sponsor(){
		$filter = " where p.personid = '$this->personid'";

		$year = $this->getParam('year');
		if(is_null($year)){
			$year = date('Y');
		}
		$this->setVar('sponsorYear',$year);
		$this->setvarByRef('stdinfo',$this->financialaid->getPersonInfo($filter));
		$this->setvarByRef('sponsors',$this->financialaid->getStudentSponsors($this->personid,$year));
		$this->setVarByRef('studyYears',$this->financialaid->getStudyYears($this->personid,$year));
	}

	function addSponsor(){
		$this->setVarByRef('stdinfo',$this->financialaid->getPersonInfo(" where p.personid = '$this->personid'"));
		$this->setVarByRef('sponsorlist',$this->financialaid->sponsorList());
	}

	/*function newFamily(){
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
	*/
	

			
}
