<?php

/**
 * @package finanacialaid
 * @filesource
 */

/**
 * Financial Aid module to allow viewing of students applying for financial aid
 *
 * @access public
 * @author Siphiwo Dzingwe, Serge Meunier
 *
 */
class financialaid extends controller
{
    var $personid;
    var $objUser;
    var $objLanguage;
    var $studentinfo;
    var $objDBApplication;
    var $objDBNextofkin;
    var $objDBDependants;
    var $objDBParttimejobs;
    var $objDBStudentFamily;
    var $objDBFinancialAidWS;
    
    var $objLeftBar;
    var $objRightBar;

	function init(){
		$this->objUser =& $this->getObject('user','security');
    	$this->objLanguage = &$this->getObject('language','language');
		$this->studentinfo =& $this->getObject('dbstudentinfo','studentenquiry');
		$this->finaid =& $this->getObject('dbfinaid');
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
		$this->loadclass('radio','htmlelements');
		$this->objDBApplication =& $this->getObject('dbapplication');
		$this->objDBNextofkin =& $this->getObject('dbnextofkin');
		$this->objDBDependants =& $this->getObject('dbdependants');
		$this->objDBParttimejobs =& $this->getObject('dbparttimejobs');
		$this->objDBStudentFamily =& $this->getObject('dbstudentfamily');
        $this->objDBFinancialAidWS = & $this->getObject('dbfinancialaidws');

        $this->objLeftBar =& $this->getObject('financialaidleftblock');
        $this->objRightBar =& $this->getObject('applicationblocksearchbox');;
		$this->module = $this->getParam('module');

	}

	function dispatch(){

		$this->setLayoutTemplate('financialaid_layout.php');

		$action = $this->getParam('action');
		switch($action){
			case null:
            //----------------
            //Default action
			case 'ok':
               // $this->setVarByRef('stdinfo',$this->studentinfo->search());
				return 'studentapplicationlist_tpl.php';

            //----------------
            //Student details actions
			case 'info':
				$this->studentInformation();
				return 'studentinfo_tpl.php';

			case 'account':
				$this->studentInfo();
				return 'studentaccount_tpl.php';

   			case 'matric':
				$this->studentInfo();
				return 'studentschool_tpl.php';

			case 'results':
				$this->studentResults();
				return 'studentresults_tpl.php';


			case 'search':
				$this->setVarByRef('stdinfo',$this->studentinfo->search());
				return 'studentlist_tpl.php';

			case 'searchapplications':
				return 'studentapplicationlist_tpl.php';
			case 'searchmarkrange':
				return 'markrangesearch_tpl.php';
			case 'listmarkrange':
				return 'markrangelist_tpl.php';

			case 'applicationinfo':
                $this->setVar('appid', $this->getParam('appid', NULL));
				return 'studentapplicationinfo_tpl.php';

			case 'shownextofkin':
				return 'applicationnextofkin_tpl.php';
			case 'showdependants':
				return 'applicationdependants_tpl.php';
			case 'showparttimejob':
				return 'applicationparttimejobs_tpl.php';
			case 'showstudentfamily':
				return 'applicationstudentfamily_tpl.php';
            //----------------
            //Application actions
            case 'addapplication':
                return 'addapplication_tpl.php';
            case 'addnextofkin':
                return 'addnextofkin_tpl.php';
            case 'adddependant':
                return 'adddependant_tpl.php';
            case 'addparttimejob':
                return 'addparttimejob_tpl.php';
            case 'addstudentfamily':
                return 'addstudentfamily_tpl.php';
                 
            case 'saveapplication':
                $fields = array('id' => $this->getParam('appid', ''),
                            'studentNumber' => $this->getParam('stdnum', ''),
                            'idNumber' => $this->getParam('idNum', ''),
                            'surname' => $this->getParam('surname', ''),
                            'firstNames' => $this->getParam('firstname', ''),
                            'gender' => $this->getParam('gender', ''),
                            'saCitizen' => $this->getParam('saCitizen', ''),
                            'supportingSelf' => $this->getParam('supportingself', ''),
                            'dateCreated' => date("Y-m-d H:i:s"),
                            'creatorId' => $this->objUser->userId());
                $this->objDBFinancialAidWS->saveApplication('add', $fields);
                $this->setVar('appid', $this->getParam('appid', NULL));

                return 'studentapplicationinfo_tpl.php';
            case 'savenextofkin':
                $fields = array('appId' => $this->getParam('appid', ''),
                            'idNumber' => $this->getParam('idnum', ''),
                            'surname' => $this->getParam('surname', ''),
                            'firstNames' => $this->getParam('firstname', ''),
                            'relationship' => $this->getParam('gender', ''),
                            'strAddress' => $this->getParam('straddress', ''),
                            'suburb' => $this->getParam('suburb', ''),
                            'city' => $this->getParam('city', ''),
                            'postcode' => $this->getParam('postcode', ''),
                            'maritalStatus' => $this->getParam('maritalstatus', ''),
                            'spouse' => $this->getParam('spouse', ''),
                            'occupation' => $this->getParam('occupation', ''),
                            'employersDetails' => $this->getParam('employerdetails', ''),
                            'employersTelNo' => $this->getParam('employertelno', ''),
                            'dateCreated' => date("Y-m-d H:i:s"),
                            'creatorId' => $this->objUser->userId());
                $this->objDBFinancialAidWS->saveNextofkin('add', $fields);
                $this->setVar('appid', $this->getParam('appid', NULL));
                return 'studentapplicationinfo_tpl.php';
            case 'savedependant':
                $fields = array('appId' => $this->getParam('appid', ''),
                            'firstName' => $this->getParam('firstname', ''),
                            'relationship' => $this->getParam('relationship', ''),
                            'dependantReason' => $this->getParam('dependantreason', ''),
                            'category' => $this->getParam('category', ''),
                            'hasIncome' => $this->getParam('hasIncome', ''),
                            'incomeType' => $this->getParam('incomeType', ''),
                            'incomeAmount' => $this->getParam('incomeAmount', ''),
                            'dateCreated' => date("Y-m-d H:i:s"),
                            'creatorId' => $this->objUser->userId());
                $this->objDBFinancialAidWS->saveDependant('add', $fields);
                $this->setVar('appid', $this->getParam('appid', NULL));
                return 'studentapplicationinfo_tpl.php';
            case 'saveparttimejob':
                $fields = array('appId' => $this->getParam('appid', ''),
                            'jobTitle' => $this->getParam('jobtitle', ''),
                            'employersDetails' => $this->getParam('employersdetails', ''),
                            'employersTelNo' => $this->getParam('employerstelno', ''),
                            'dateCreated' => date("Y-m-d H:i:s"),
                            'creatorId' => $this->objUser->userId());
                $this->objDBFinancialAidWS->saveParttimejob('add', $fields);
                $this->setVar('appid', $this->getParam('appid', NULL));
                return 'studentapplicationinfo_tpl.php';
            case 'savestudentfamily':
                $fields = array('appId' => $this->getParam('appid', ''),
                            'firstName' => $this->getParam('firstname', ''),
                            'institution' => $this->getParam('institution', ''),
                            'course' => $this->getParam('course', ''),
                            'yearOfStudy' => $this->getParam('year', ''),
                            'studentNumber' => $this->getParam('stdnum', ''),
                            'dateCreated' => date("Y-m-d H:i:s"),
                            'creatorId' => $this->objUser->userId());
                $this->objDBFinancialAidWS->saveStudentInFamily('add', $fields);
                $this->setVar('appid', $this->getParam('appid', NULL));
                return 'studentapplicationinfo_tpl.php';
            //----------------
            //Test action
            case 'test':
                return 'test_tpl.php';

    	}
	}


 	function studentInformation(){
		$studentinfo = $this->studentinfo->getPersonInfo($this->getParam('id'));
 		$this->setvarByRef('stdinfo', $studentinfo);
		$address = $this->getParam('address');
        $staddr = $this->studentinfo->studentAddress($studentinfo[0]->STDNUM);
		if(!is_null($address)){
		    $this->setvarByRef('stdaddress', $staddr);
		}else{
			$this->setVar('stdaddress',false);
		}
	}

  	function studentInfo(){
		$studentinfo = $this->studentinfo->getPersonInfo($this->getParam('id'));
 		$this->setvarByRef('stdinfo', $studentinfo);
	}

	function studentResults(){
		$year = $this->getParam('year');
		if(is_null($year)){
			$year = date('Y');
		}

		$this->setvarByRef('stdinfo',$this->studentinfo->getPersonInfo($this->getParam('id')));
		$this->setvarByRef('results',$this->studentinfo->getCourseInfo($this->getParam('id'), $year));
	}

}
