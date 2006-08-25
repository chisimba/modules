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
 * @author John Beneke, Siphiwo Dzingwe
 * 
 */
class studentenquiry extends controller
{
	var $personid;
    /**
    * var object Property to hold language object
    */
    var $objLanguage;
    
	function init(){
		$this->objUser =& $this->getObject('user','security');
		$this->studentinfo =& $this->getObject('dbstudentinfo');
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
		$this->module = $this->getParam('module');
		//$this->control =&$this->getObject('control');
        // Get an Instance of the language object
	    $this->objLanguage =& $this->getObject('language','language');
	}

	function dispatch(){
		$action = $this->getParam('action');
		switch($action){
			case null: 
			case 'ok':
                $this->setVarByRef('stdinfo',$this->studentinfo->search());
				return 'studentlist_tpl.php';
    //$this->setVarByRef('stdinfo',$this->studentinfo->getAllStudents());
				//return 'studentlist_tpl.php';

			case 'info':
				$this->studentInformation();
				return 'studentinfo_tpl.php';

			case 'results':
				$this->studentResults();
				return 'studentresults_tpl.php';
		

			case 'payment':
				$this->studentPayment();
				return 'studentpayment_tpl.php';

			case 'newpayment':
				$this->newPayment();
				return 'newstudentpayment_tpl.php';

			case 'pay':
				if(!is_null($this->getParam('ok'))){
					$this->studentinfo->makePayment($this->objUser->userId());
				}
				$this->studentPayment();
				return 'studentpayment_tpl.php';

			case 'sponsor':
				if($this->getParam('assignsponsor')){
					$this->studentinfo->assignSponsor();
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
				$this->setVarByRef('sponsorlist',$this->studentinfo->sponsorList());
				return 'sponsorlist_tpl.php';

			case 'studentssponsored':
				$this->setVarByRef('sponsoredStudents',$this->studentinfo->getSponsoredStudents($this->personid));
				$this->setVar('sponsorname',$this->studentinfo->getSponsorName($this->personid));
				return 'studentssponsored_tpl.php';
		
			case 'newsponsor':
				if($this->getParam('ok')){
					$arr = $this->studentinfo->addNewSponsor();
					$this->setVarByRef('sponsorlist',$this->studentinfo->sponsorList());
					return 'sponsorlist_tpl.php';
					
				}
				if($this->getParam('cancel')){
					$this->setVarByRef('sponsorlist',$this->studentinfo->sponsorList());
					return 'sponsorlist_tpl.php';
				}
				if(!$this->getParam('cancel') and !$this->getParam('ok')){
					return 'newsponsor_tpl.php';
				}

			case 'sponsorinfo':
				//$this->studentInformation();
				$this->setVarByRef('sponsorinfo',$this->studentinfo->editSponsor($this->personid));
				return 'editsponsor_tpl.php';

			case 'editsponsor':
				if($this->getParam('edit')){
					$updtate = $this->studentinfo->updateSponsor($this->personid);
				}
				$this->setVarByRef('sponsorlist',$this->studentinfo->sponsorList());
				return 'sponsorlist_tpl.php';
			case 'search':
				$this->setVarByRef('stdinfo',$this->studentinfo->search());
				return 'studentlist_tpl.php';	

			case 'markrange':
				//return $this->control->markrange();
				
				if($this->getParam('ok')){
					//echo "ok okokokok";
					$this->setVarByRef('stdinfo',$this->studentinfo->markRangeSearch());
					return 'studentlist_tpl.php';
				}
				if($this->getParam('cancel')){
					$this->setVarByRef('stdinfo',$this->studentinfo->getAllStudents());
					return 'studentlist_tpl.php';


				}
				if(!$this->getParam('ok') and !$this->getParam('cancel')){
					return 'markrange_tpl.php';
				}
				
			case 'enquiry':
                $this->setVarByRef('enquiry',$this->studentinfo->getEnquiry());
                return 'enquiry_tpl.php';
                //return $this->control->enquiry();
			case 'stenquiry':
                $this->setVarByRef('enquiry',$this->studentinfo->getEnquiry());
                return 'addenquiry_tpl.php';

			case 'nextofkin':
				$this->setVarByRef('stdinfo',$this->studentinfo->getAllStudents(" where p.personID = '".$this->getParam('id')."'"));
				$this->setVarByRef('stdfamily',$this->studentinfo->getStudentFamily($this->personid));
				return 'stdfamily_tpl.php';

			case 'newfamily':
				return $this->control->newFam();

			case 'addfamily':
				if($this->getParam('cancel')){
					$this->setVarByRef('stdinfo',$this->studentinfo->getAllStudents());
					return 'studentlist_tpl.php';

				}

				if($this->getParam('ok')){
					$this->studentinfo->addFamily();
					$this->setVarByRef('stdinfo',$this->studentinfo->getAllStudents(" where p.personID = '".$this->getParam('id')."'"));
					$this->setVarByRef('stdfamily',$this->studentinfo->getStudentFamily($this->personid));
					return 'stdfamily_tpl.php';
				}

		/*	case  'editfamily':
				return $this->control->editfamily();

			case 'parttime':
				return $this->control->parttime();
			
			case 'finapp':
				return $this->control->studentinfoApp();

			case 'resapp':
				return $this->control->residenceApp();
				

			case 'finapplication':
				return $this->control->finapplication();

			case 'resapplication':
				return $this->control->resapplication();  */
    	}
	}

	function studentInformation(){
		$studentinfo = $this->studentinfo->getPersonInfo($this->getParam('id'));
        //$filter = array_slice($studentinfo[0],4);
 		$this->setvarByRef('stdinfo', $studentinfo);
		$address = $this->getParam('address');
       // echo "<br>adress: " . $address ." (controller) snum: ". $studentinfo[0]['STDNUM'];
        $staddr = $this->studentinfo->studentAddress($studentinfo[0]->STDNUM);
        //$this->setVarByRef('student', $this->studentinfo->getAllStudents($stdnum));
        //$this->setvarByRef('stdlookup',$studentinfo);
		if(!is_null($address)){
			//echo "we are here";
		    $this->setvarByRef('stdaddress', $staddr);
		}
		else{
			$this->setVar('stdaddress',false);
		}
	}
	
	function studentPayment(){
		//$personid = $this->getParam('id');
		$filter = " where p.personid = '$this->personid'";
		
		$year = $this->getParam('year');
		if(is_null($year)){
			$year = date('Y');
		}

		$this->setVar('paymentYear',$year);
		$this->setVarByRef('stdinfo',$this->studentinfo->getPersonInfo($this->getParam('id')));
		//$this->setVarByRef('account',$this->studentinfo->getAccountInformation());
		//$this->setVarByRef('payments',$this->studentinfo->getPayments());
		$this->setVarByRef('studyYears',$this->studentinfo->getStudyYears($this->personid,$year));
	}

	function studentResults(){
		//$personid = $this->getParam('id');
		//$filter = " where p.personid = '$this->personid'";

		$year = $this->getParam('year');
		if(is_null($year)){
			$year = date('Y');
		}
		
		$this->setvarByRef('stdinfo',$this->studentinfo->getPersonInfo($this->getParam('id')));
		$this->setvarByRef('results',$this->studentinfo->getCourseInfo($this->getParam('id'), $year));
		//$this->setVar('resultsYear',$year);
		//$this->setVarByRef('studyYears',$this->studentinfo->getStudyYears($this->personid,$year));
		//$this->setVarByRef('studyType',$this->studentinfo->getStudyType($this->personid));
	}

	function newPayment(){
		//$personid = $this->getParam('id');
		$filter = " where p.personid = '$this->personid'";
		$this->setvarByRef('stdinfo',$this->studentinfo->getPersonInfo($filter));
		
	}
	
	function sponsor(){
		$filter = " where p.personid = '$this->personid'";

		$year = $this->getParam('year');
		if(is_null($year)){
			$year = date('Y');
		}
		$this->setVar('sponsorYear',$year);
		$this->setvarByRef('stdinfo',$this->studentinfo->getPersonInfo($filter));
		$this->setvarByRef('sponsors',$this->studentinfo->getStudentSponsors($this->personid,$year));
		$this->setVarByRef('studyYears',$this->studentinfo->getStudyYears($this->personid,$year));
	}

	function addSponsor(){
		$this->setVarByRef('stdinfo',$this->studentinfo->getPersonInfo(" where p.personid = '$this->personid'"));
		$this->setVarByRef('sponsorlist',$this->studentinfo->sponsorList());
	}

	/*function newFamily(){
		$this->setVarByRef('stdinfo',$this->studentinfo->getAllStudents(" where p.personID = '".$this->getParam('id')."'"));
		$this->setVarByRef('race',$this->studentinfo->lookup(" where lookupType = 'Race'"));
		$this->setVarByRef('gender',$this->studentinfo->lookup(" where lookupType = 'Gender'"));
		$this->setVarByRef('title',$this->studentinfo->lookup(" where lookupType = 'Title'"));
		$this->setVarByRef('maritalstatus',$this->studentinfo->lookup(" where lookupType = 'Marital Status'"));
		$this->setVarByRef('relationship',$this->studentinfo->lookup(" where lookupType = 'Person Type'"));
		$this->setVarByRef('addresstype',$this->studentinfo->lookup(" where lookupType = 'Contact Type'"));

		$this->setVarByRef('monthlyincome',$this->studentinfo->lookup(" where lookupType = 'Monthly Gross Income'"));
		$this->setVarByRef('annualincome',$this->studentinfo->lookup(" where lookupType = 'Annual Gross Income'"));
	}
	*/
	

			
}
