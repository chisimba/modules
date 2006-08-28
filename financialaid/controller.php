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
		$this->objDBApplication =& $this->getObject('dbapplication');
		$this->objDBNextofkin =& $this->getObject('dbnextofkin');
		$this->objDBDependants =& $this->getObject('dbdependants');
		$this->objDBParttimejobs =& $this->getObject('dbparttimejobs');
		$this->objDBStudentFamily =& $this->getObject('dbstudentfamily');

		$this->module = $this->getParam('module');

	}

	function dispatch(){
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
    
			case 'applicationinfo':
				return 'studentapplicationinfo_tpl.php';
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
                 $this->objDBApplication->saveRecord('add', $this->objUser->userId());
                 return 'addapplication_tpl.php';
            case 'savenextofkin':
                 $this->objDBNextofkin->saveRecord('add', $this->objUser->userId());
                 return 'addnextofkin_tpl.php';
            case 'savedependant':
                 $this->objDBDependants->saveRecord('add', $this->objUser->userId());
                 return 'adddependant_tpl.php';
            case 'saveparttimejob':
                 $this->objDBParttimejobs->saveRecord('add', $this->objUser->userId());
                 return 'addparttimejob_tpl.php';
            case 'savestudentfamily':
                 $this->objDBStudentFamily->saveRecord('add', $this->objUser->userId());
                 return 'addstudentfamily_tpl.php';
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
