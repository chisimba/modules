<?php
/* -------------------- eportflio class extends controller ----------------*/
                                                                                                                                             
// security check - must be included in all scripts

if (!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}
// end security check

 /**
 * Controller for the ePortfolio Module
 * @package eportfolio
 * @copyright 2008, University of Nairobi & AVOIR Project
 * @license GNU GPL
 * @author Paul Mungai
 **/

class eportfolio extends controller
{
    /**
    * @var groupadminmodel Object reference.
    */
    var $_objGroupAdmin;
     /*	
     * @var object $objFSContext : The File System Object for the context
     */
     public $objFSContext;

    public $objConfig;
    public $objLanguage;
    public $objButtons;
    public $objUserAdmin;
    public $objUser;
    public $isAdmin;

    
    /**
    * Constructor
    */
    public function init()
    {
        $this->objConfig =& $this->getObject('altconfig','config');
        $this->objLanguage =& $this->getObject('language','language');
        $this->objUserAdmin =& $this->getObject('useradmin_model2','security');
        $this->objUser =& $this->getObject('user', 'security');
        $this->objFile =& $this->getObject('dbfile', 'filemanager');
        $this->objCleanUrl = $this->getObject('cleanurl', 'filemanager');
	$this->objDate = &$this->newObject('dateandtime', 'utilities');
	$this->objUserContext = $this->getObject('utils', 'contextpostlogin');
	$this->objPopupcal = $this->newObject('datepickajax', 'popupcalendar');
	$this->objUrl = $this->getObject('url', 'strings');
	$this->_objGroupAdmin = &$this->newObject('groupadminmodel','groupadmin');
	$this->_objManageGroups = &$this->newObject('managegroups','contextgroups');
        $this->_objDBContext = &$this->newObject('dbcontext','context');
        $this->_objDBAssgnment = &$this->newObject('dbassignment','assignment');
        $this->_objDBEssay = &$this->newObject('dbessay_book','essay');
	$this->objFSContext=$this->newObject('fscontext','context');
//        $this->lectGroupId = $this->_objGroupAdmin->getLeafId( array( $this->_objDBContext->getContextCode(), 'Lecturers' ) );

	// Get the DB object.
	$this->objDbAddressList =& $this->getObject('dbeportfolio_address', 'eportfolio');
	$this->objDbContactList =& $this->getObject('dbeportfolio_contact', 'eportfolio');
	$this->objDbDemographicsList =& $this->getObject('dbeportfolio_demographics', 'eportfolio');
	$this->objDbActivityList =& $this->getObject('dbeportfolio_activity', 'eportfolio');
	$this->objDbAffiliationList =& $this->getObject('dbeportfolio_affiliation', 'eportfolio');
	$this->objDbTranscriptList =& $this->getObject('dbeportfolio_transcript', 'eportfolio');
	$this->objDbEmailList =& $this->getObject('dbeportfolio_email', 'eportfolio');
	$this->objDbQclList =& $this->getObject('dbeportfolio_qcl', 'eportfolio');
	$this->objDbGoalsList =& $this->getObject('dbeportfolio_goals', 'eportfolio');
	$this->objDbCompetencyList =& $this->getObject('dbeportfolio_competency', 'eportfolio');
	$this->objDbInterestList =& $this->getObject('dbeportfolio_interest', 'eportfolio');
	$this->objDbReflectionList =& $this->getObject('dbeportfolio_reflection', 'eportfolio');
	$this->objDbAssertionList =& $this->getObject('dbeportfolio_assertion', 'eportfolio');
	$this->objDbProductList =& $this->getObject('dbeportfolio_product', 'eportfolio');
	$this->objDbCategoryList =& $this->getObject('dbeportfolio_category', 'eportfolio');
	$this->objDbCategorytypeList =& $this->getObject('dbeportfolio_categorytypes', 'eportfolio');
	$this->objGetall =& $this->getObject('getall_eportfolio', 'eportfolio');
	$this->objExport =& $this->getObject('export_eportfolio', 'eportfolio');
	$this->userId=$this->objUser->userId(); //To pick user userid
	$this->setVarByRef('userId', $this->userId);
        $userPid = $this->objUser->PKId($this->objUser->userId());//To pick user id
        $this->setVarByRef('userPid', $this->userPid);

        
        $this->objUrl = $this->getObject('url', 'strings');

        // Create an array of words to abstract
         // Create an array of words to abstract
        $this->abstractionArray = array(
                'Lecturers'=>ucwords($this->objLanguage->code2Txt('word_lecturers')), 
                'Students'=>ucwords($this->objLanguage->code2Txt('word_students'))
            );
        $this->_arrSubGroups = array();
        $this->_arrSubGroups['Owner']['id'] = NULL;
        $this->_arrSubGroups['Owner']['members'] = array($userPid);

        $this->_arrSubGroups['Guest']['id'] = NULL;
        $this->_arrSubGroups['Guest']['members'] = array();

    }


    public function dispatch($action) 
    {
        //$this->setLayoutTemplate('eportfolioview_layout_tpl.php');
        
        $this->user = $this->objUserAdmin->getUserDetails($this->objUser->PKId($this->objUser->userId()));
        $this->userPid = $this->objUser->PKId($this->objUser->userId());
        $this->setVarByRef('user', $this->user);
        $this->setVarByRef('userPid', $this->userPid);
	
        switch ($action)
        { 
	   case 'export':
		$myid = $this->objUser->userId();
		$address = $this->objExport->exportAddress( $myid );
		return $address;	   
	   case 'makepdf':

		//$sectionid = $this->getParam('sectionid');
		$fullnames = $this->objUser->fullName()."'s ".$this->objLanguage->languageText("mod_eportfolio_wordEportfolio",'eportfolio');
		$type = $this->getParam('tables', NULL);
		$myid = $this->objUser->userId();
		$myPid = $this->objUser->PKId($this->objUser->userId());
		$address = $this->objGetall->getAddress( $myid );
		$contacts = $this->objGetall->getContacts( $myid );
		$emails = $this->objGetall->getEmail( $myid );
		$demographics = $this->objGetall->getDemographics( $myid );
		$activity = $this->objGetall->getActivity ($myid );
		$affiliation = $this->objGetall->getAffiliation ( $myid );
		$transcripts = $this->objGetall->getTranscripts( $myid );
		$qualification = $this->objGetall->getQualification( $myid );
		$goals = $this->objGetall->getGoals( $myid );
		$competency = $this->objGetall->getCompetency( $myid );
		$interests = $this->objGetall->getInterests( $myid );
		$reflections = $this->objGetall->getReflections( $myid );
		$assertions = $this->objGetall->getAssertions( $myPid );


		//get the pdfmaker classes
		$objPdf = $this->getObject('tcpdfwrapper','pdfmaker');


		$text = '<h1>'.$fullnames. "</h1><br></br>\r\n".$address.$contacts.$emails.$demographics;

		$otherText = $activity.$affiliation.$transcripts.$qualification.$goals.$competency.$interests.$reflections.$assertions;
/*
.$email.$demographics.$activity.$affiliation.$transcripts.$qualification.$goals.$competency.$interests.$reflections.$assertions

$text = '<h1>'.$fullnames. "</h1><br></br>\r\n".html_entity_decode($address). "\r\n".html_entity_decode($contacts). "\r\n".html_entity_decode($email). "\r\n".html_entity_decode($demographics). "\r\n".html_entity_decode($activity). "\r\n".html_entity_decode($affiliation). "\r\n".html_entity_decode($transcripts). "\r\n".html_entity_decode(strip_tags($qualification)). "\r\n".html_entity_decode($goals). "\r\n".html_entity_decode($competency). "\r\n".html_entity_decode($interests). "\r\n".html_entity_decode($reflections). "\r\n".html_entity_decode($assertions);
*/
		//Write pdf
		$objPdf->initWrite();
		$objPdf->partWrite($text);
		$objPdf->partWrite($activity);
		$objPdf->partWrite($affiliation);
		$objPdf->partWrite($transcripts);
		$objPdf->partWrite($qualification);
		$objPdf->partWrite($competency);
		$objPdf->partWrite($goals);
		$objPdf->partWrite($interests);
		$objPdf->partWrite($reflections);
		$objPdf->partWrite($assertions);
		return $objPdf->show();

		break;

	     //manage_group
             case 'addparts':
		$selectedParts=$this->getArrayParam('arrayList');
		$groupId = $this->getParam('groupId', NULL);
		$this->setVarByRef('groupId',$groupId);
		//Get user Groups
		$userGroups = $this->_objGroupAdmin->getUserDirectGroups( $groupId );		
		if(empty($selectedParts))
		{

			$this->deleteGroupUsers( $userGroups, $groupId );

		}else{
			// Get the added member ids
			$addList = array_diff( $selectedParts, $userGroups );
			// Get the deleted member ids
			$delList = array_diff( $userGroups, $selectedParts );
			// Delete these members
			foreach( $delList as $partPid ) {
				
				$this->_objGroupAdmin->deleteGroupUser( $partPid['group_id'], $groupId  );

			}
			// Add these members
	                 if (count($addList)>0) {
			         $this->manageEportfolioViewers($addList, $groupId);
				
	                 }
			//Empty array
			$selectedParts = array();
		}
		return 'allparts_tpl.php';

	    case "add_group":
		return "add_group_tpl.php";
		break;
            case 'manage_eportfolio' :
		//$this->setLayoutTemplate('eportfolio_layout_tpl.php');
		$groupId = $this->getParam('id', null);
		$this->setVarByRef('groupId',$groupId);
		return "allparts_tpl.php";		
		break;
            case 'view_others_eportfolio' :
		//$this->setLayoutTemplate('eportfolio_layout_tpl.php');
		$groupId = $this->getParam('id', null);
		$this->setVarByRef('groupId',$groupId);		
		return "others_eportfolio_tpl.php";		
		break;
            case 'manage_group' :
		$this->setLayoutTemplate('eportfolio_layout_tpl.php');
		$myid = $this->getParam('id', null);
		return $this->showManagegroup($myid);
		break;
            case 'manage_form' :
		$myid = $this->getParam('id', null);
		$this->setVarByRef('myid',$myid);
                return $this->processManagegroup( $myid );
		break;
            case 'manage_stud' :
		$this->setLayoutTemplate('eportfolio_layout_tpl.php');
		$myid = $this->getParam('id', null);
		return $this->showManage('Students',$myid);
		break;
            case 'manage_lect':
		$this->setLayoutTemplate('eportfolio_layout_tpl.php');
		$myid = $this->getParam('id', null);
		return $this->showManage('Lecturers',$myid);
		break;
            case 'students_form' :
		$myid = $this->getParam('id', null);
		$this->setVarByRef('myid',$myid);
                return $this->processManage( 'Students',$myid);
		break;
            case 'lecturers_form' :
		$myid = $this->getParam('id', null);
		$this->setVarByRef('myid',$myid);
                return $this->processManage( 'Lecturers',$myid);
		break;
           case "userdetails":
		
		return "userdetails_tpl.php";
		break;
            case "main":
		return "main_tpl.php";
		break;
	    case "add_address":
		$this->setLayoutTemplate('eportfolio_layout_tpl.php');
		return "add_address_tpl.php";
		break;
            case "view_transcript":	
		return 'main_tpl.php';	
		break;
	    case "add_interest":
		$this->setLayoutTemplate('eportfolio_layout_tpl.php');
		return "add_interest_tpl.php";
		break;
            case "view_interest":		
		return 'main_tpl.php';
		break;
           case "view_address":		
		return 'main_tpl.php';
		break;
	    case "view_contact":
		return 'main_tpl.php';
		break;
	    case "view_email":		
		return 'main_tpl.php';
		break;
	    case "view_activity":		
		return 'main_tpl.php';
		break;
	    case "view_qcl":		
		return 'main_tpl.php';
		break;
	    case "view_goals":		
		return 'main_tpl.php';
		break;
	    case "add_goals":
		$this->setLayoutTemplate('eportfolio_layout_tpl.php');
		return "add_goals_tpl.php";
		break;
	    case "add_contact":
		$this->setLayoutTemplate('eportfolio_layout_tpl.php');
		return "add_contact_tpl.php";
		break;
	    case "add_email":
		$this->setLayoutTemplate('eportfolio_layout_tpl.php');
		return "add_email_tpl.php";
		break;
	    case "add_activity":		
		$this->setLayoutTemplate('eportfolio_layout_tpl.php');
		return "add_activity_tpl.php";
		break;
	    case "add_reflection":		
		$this->setLayoutTemplate('eportfolio_layout_tpl.php');
		return "add_reflection_tpl.php";
		break;
	    case "add_assertion":		
		$this->setLayoutTemplate('eportfolio_layout_tpl.php');
		return "add_assertion_tpl.php";
		break;
	    case "view_assertion":		
		return 'main_tpl.php';
		break;
	    case "add_product":		
		$this->setLayoutTemplate('eportfolio_layout_tpl.php');
		return "add_product_tpl.php";
		break;
	    case "view_product":		
		return 'main_tpl.php';
		break;
	    case "add_category":		
		$this->setLayoutTemplate('eportfolio_layout_tpl.php');
		return "add_category_tpl.php";
		break;
	    case "view_category":		
		return 'main_tpl.php';
		break;
	    case "add_categorytype":		
		$this->setLayoutTemplate('eportfolio_layout_tpl.php');
		return "add_categorytype_tpl.php";
		break;
	    case "view_categorytype":		
		return 'main_tpl.php';
		break;

	    case "view_reflection":		
		return 'main_tpl.php';
		break;
	    case "view_demographics":		
		return 'main_tpl.php';
		break;
	    case "view_affiliation":		
		return 'main_tpl.php';
		break;
	    case "view_competency":		
		return 'main_tpl.php';
		break;
	    case "add_affiliation":
		$this->setLayoutTemplate('eportfolio_layout_tpl.php');
		return "add_affiliation_tpl.php";
		break;
	    case "add_demographics":
		$this->setLayoutTemplate('eportfolio_layout_tpl.php');
		return "add_demographics_tpl.php";
		break;
	    case "add_transcript":
		$this->setLayoutTemplate('eportfolio_layout_tpl.php');
		return "add_transcript_tpl.php";
		break;
	    case "add_qcl":
		$this->setLayoutTemplate('eportfolio_layout_tpl.php');
		return "add_qcl_tpl.php";
		break;
	    case "add_competency":
		$this->setLayoutTemplate('eportfolio_layout_tpl.php');
		return "add_competency_tpl.php";
		break;
	    case 'changeimage':
                return $this->changePicture();
		break;
            case 'resetimage':
                return $this->resetImage($this->getParam('id'));
		break;
            case 'updateuserdetails':
                return $this->updateUserDetails();
		break;
	    case "deleteconfirm":
		$this->nextAction(
		$id = $this->getParam('id', null),
		$this->objDbAddressList->deleteSingle($id));
		// After processing return to view main

		return $this->nextAction('main',NULL);
		break;
	    case "deletecontact":
		$this->nextAction(
		$id = $this->getParam('id', null),
		$this->objDbContactList->deleteSingle($id));
		// After processing return to view main

		return $this->nextAction('main',NULL);
		break;
	    case "deleteinterest":
		$this->nextAction(
		$id = $this->getParam('id', null),
		$this->objDbInterestList->deleteSingle($id));
		 // After processing return to view main

		return $this->nextAction('main',NULL);
		break;

	    case "deletedemographics":
		$this->nextAction(
		$id = $this->getParam('id', null),
		$this->objDbDemographicsList->deleteSingle($id));
		// After processing return to view main

		return $this->nextAction('main',NULL);
		break;
	    case "deleteemail":
		$this->nextAction(
		$myid = $this->getParam('myid', null),
		$this->objDbEmailList->deleteSingle($myid));
		// After processing return to view main

		return $this->nextAction('main',NULL);
		break;
	    case "deletetranscript":
		$this->nextAction(
		$id = $this->getParam('id', null),
		$this->objDbTranscriptList->deleteSingle($id));
		// After processing return to view main
		return $this->nextAction('main',NULL);
		break;
	    case "deleteqcl":
		$this->nextAction(
		$id = $this->getParam('id', null),
		$this->objDbQclList->deleteSingle($id));
		// After processing return to view main
		return $this->nextAction('main',NULL);
		break;
	    case "deletecompetency":
		$this->nextAction(
		$id = $this->getParam('id', null),
		$this->objDbCompetencyList->deleteSingle($id));
		return $this->nextAction('main',NULL);
		break;


	    case "deleteaffiliation":
		$this->nextAction(
		$myid = $this->getParam('id', null),
		$this->objDbAffiliationList->deleteSingle($myid));
		return $this->nextAction('main',NULL);
		break;
	    case "deletegoals":
		$this->nextAction(
		$myid = $this->getParam('id', null),
		$this->objDbGoalsList->deleteSingle($myid));
		return $this->nextAction('main',NULL);
		break;
	    case "deletereflection":
		$this->nextAction(
		$myid = $this->getParam('id', null),
		$this->objDbReflectionList->deleteSingle($myid));
		return $this->nextAction('main',NULL);
		break;

	    case "deleteassertion":
		$this->nextAction(
		$myid = $this->getParam('id', null),
		$this->objDbAssertionList->deleteSingle($myid));
		return $this->nextAction('main',NULL);
		break;
	    case "deleteactivity":
		$this->nextAction(
		$myid = $this->getParam('id', null),
		$this->objDbActivityList->deleteSingle($myid));		
		return $this->nextAction('main',NULL);
		break;
	    case "deleteproduct":
		$this->nextAction(
		$myid = $this->getParam('id', null),
		$this->objDbProductList->deleteSingle($myid));		
		return $this->nextAction('main',NULL);
		break;
	    case "deletecategory":
		$this->nextAction(
		$myid = $this->getParam('id', null),
		$this->objDbCategoryList->deleteSingle($myid));		
		return $this->nextAction('main',NULL);
		break;
	    case "deletecategorytype":
		$this->nextAction(
		$myid = $this->getParam('id', null),
		$this->objDbCategorytypeList->deleteSingle($myid));		
		return $this->nextAction('main',NULL);
		break;

	   case "addaddressconfirm":
	        //$link = $this->getParam('link', NULL);
	        $id = $this->objDbAddressList->insertSingle(
		$this->getParam('address_type', NULL),
		$this->getParam('street_no', NULL),
		$this->getParam('street_name', NULL),
		$this->getParam('locality', NULL),
		$this->getParam('city', NULL),
		$this->getParam('postcode', NULL),
		$this->getParam('postal_address', NULL)
		);
       		// After processing return to view main
		return $this->nextAction('main',NULL);
		break;
	   case "addqclconfirm":
	        //$link = $this->getParam('link', NULL);
	        $id = $this->objDbQclList->insertSingle(
		$this->getParam('qcl_type', NULL),
		$this->getParam('title', NULL),
		$this->getParam('organisation', NULL),
		$this->getParam('qcl_level', NULL),
		$this->getParam('award_date', NULL),
		$this->getParam('shortdescription', NULL),
		$this->getParam('longdescription', NULL)
		);
		return $this->nextAction('main',NULL);
		break;
	   case "addgoalsconfirm":
	        //$link = $this->getParam('link', NULL);
	        $id = $this->objDbGoalsList->insertSingle(
		$this->getParam('parentid', NULL),
		$this->getParam('goal_type', NULL),
		$this->getParam('start', NULL),
		$this->getParam('priority', NULL),
		$this->getParam('status', NULL),
		$this->getParam('status_date', NULL),
		$this->getParam('shortdescription', NULL),
		$this->getParam('longdescription', NULL)
		);
		return $this->nextAction('main',NULL);
		break;

	case "editqclconfirm":
		$myid = $this->getParam('id', null);
		$this->setVarByRef('id',$myid);
		$this->nextAction(
		$this->objDbQclList->updateSingle(
			$myid,
		$this->getParam('qcl_type', NULL),
		$this->getParam('title', NULL),
		$this->getParam('organisation', NULL),
		$this->getParam('qcl_level', NULL),
		$this->getParam('award_date', NULL),
		$this->getParam('shortdescription', NULL),
		$this->getParam('longdescription', NULL)
		));
		return $this->nextAction('main',NULL);
		break;
	case "editgoalsconfirm":
		$myid = $this->getParam('id', null);
		$this->setVarByRef('id',$myid);
		$this->nextAction(
		$this->objDbGoalsList->updateSingle(
			$myid,
		$this->getParam('parentid', NULL),
		$this->getParam('goal_type', NULL),
		$this->getParam('start', NULL),
		$this->getParam('priority', NULL),
		$this->getParam('status', NULL),
		$this->getParam('status_date', NULL),
		$this->getParam('shortdescription', NULL),
		$this->getParam('longdescription', NULL)
		));
		return $this->nextAction('main',NULL);
		break;


	case "editcompetencyconfirm":
		$myid = $this->getParam('id', null);
		$this->setVarByRef('id',$myid);
		$this->nextAction(
		$this->objDbCompetencyList->updateSingle(
			$myid,
		$this->getParam('competency_type', NULL),
		$this->getParam('award_date', NULL),
		$this->getParam('shortdescription', NULL),
		$this->getParam('longdescription', NULL)
		));
		return $this->nextAction('main',NULL);
		break;

	   case "addcompetencyconfirm":
	        $id = $this->objDbCompetencyList->insertSingle(
		$this->getParam('competency_type', NULL),
		$this->getParam('award_date', NULL),
		$this->getParam('shortdescription', NULL),
		$this->getParam('longdescription', NULL)
		);
		return $this->nextAction('main',NULL);
		break;

	    case "editcompetency":
		$this->setLayoutTemplate('eportfolio_layout_tpl.php');
		$id = $this->getParam('id', null);
		$this->setVarByRef('id',$id);
		$list = $this->objDbCompetencyList->listSingle($id);
		$competency_type = $list[0]['type'];
		$award_date = $list[0]['award_date'];
		$shortdescription = $list[0]['shortdescription'];
		$longdescription = $list[0]['longdescription'];
		$this->setVarByRef('competency_type',$competency_type);
		$this->setVarByref('award_date',$award_date);
		$this->setVarByRef('shortdescription',$shortdescription);
		$this->setVarByRef('longdescription',$longdescription);
		return "edit_competency_tpl.php";
		
		break;

	case "editassertionconfirm":
		$myid = $this->getParam('id', null);
		$this->setVarByRef('id',$myid);
		$this->nextAction(
		$this->objDbAssertionList->updateSingle(
			$myid,
		$this->getParam('rationale', NULL),
		$this->getParam('creation_date', NULL),
		$this->getParam('shortdescription', NULL),
		$this->getParam('longdescription', NULL)
		));
		return $this->nextAction('main',NULL);
		break;

	   case "addgroupconfirm":
	        $id = $this->addGroups($this->getParam('group', NULL));
		return $this->nextAction('main',NULL);
		break;			


	   case "addassertionconfirm":
	        $id = $this->objDbAssertionList->insertSingle(
		$this->getParam('rationale', NULL),
		$this->getParam('creation_date', NULL),
		$this->getParam('shortdescription', NULL),
		$this->getParam('longdescription', NULL)
		);

		//Create Assertion Folder
                //check if the folder exist
                if($this->objFSContext->folderExists($contextCode) == FALSE)
                { 
                    //create the folder
                    $this->objFSContext->createContextFolder($contextCode);
                    
                } else {
                    
                    return FALSE;
                }
		// After processing return to view assertion
	        //return $this->nextAction( 'view_assertion', array() );
		return $this->nextAction('main',NULL);
		break;			
	    case "editassertion":
		$this->setLayoutTemplate('eportfolio_layout_tpl.php');
		$id = $this->getParam('id', null);
		$this->setVarByRef('id',$id);
		$list = $this->objDbAssertionList->listSingle($id);
		$rationale = $list[0]['rationale'];
		$creation_date = $list[0]['creation_date'];
		$shortdescription = $list[0]['shortdescription'];
		$longdescription = $list[0]['longdescription'];
		$this->setVarByRef('rationale',$rationale);
		$this->setVarByref('creation_date',$creation_date);
		$this->setVarByRef('shortdescription',$shortdescription);
		$this->setVarByRef('longdescription',$longdescription);
		return "edit_assertion_tpl.php";
		break;

	    case "displayassertion":
		$this->setLayoutTemplate('eportfolio_layout_tpl.php');
		$thisid = $this->getParam('thisid', null);
		$this->setVarByRef('thisid',$thisid);
		$mylist = $this->objDbAssertionList->listSingle($thisid);
		$myinstructor = $mylist[0]['userid'];
		$myrationale = $mylist[0]['rationale'];
		$mycreation_date = $mylist[0]['creation_date'];
		$myshortdescription = $mylist[0]['shortdescription'];
		$mylongdescription = $mylist[0]['longdescription'];
		$this->setVarByRef('myinstructor',$myinstructor);
		$this->setVarByRef('myrationale',$myrationale);
		$this->setVarByref('mycreation_date',$mycreation_date);
		$this->setVarByRef('myshortdescription',$myshortdescription);
		$this->setVarByRef('mylongdescription',$mylongdescription);
		return "display_assertion_tpl.php";
		break;

	    case "displayothers_assertion":
		$this->setLayoutTemplate('eportfolio_layout_tpl.php');
		$thisid = $this->getParam('thisid', null);
		$myfilter = explode( ",", $thisid );
		$assertionId = $myfilter[0];
		$ownerId = $myfilter[1];
		$groupId = $myfilter[2];
		$this->setVarByRef('assertionId',$assertionId);
		$this->setVarByRef('ownerId',$ownerId);
		$this->setVarByRef('groupId',$groupId);
		$mylist = $this->objDbAssertionList->listSingle($assertionId);
		$myinstructor = $mylist[0]['userid'];
		$myrationale = $mylist[0]['rationale'];
		$mycreation_date = $mylist[0]['creation_date'];
		$myshortdescription = $mylist[0]['shortdescription'];
		$mylongdescription = $mylist[0]['longdescription'];
		$this->setVarByRef('myinstructor',$myinstructor);
		$this->setVarByRef('myrationale',$myrationale);
		$this->setVarByref('mycreation_date',$mycreation_date);
		$this->setVarByRef('myshortdescription',$myshortdescription);
		$this->setVarByRef('mylongdescription',$mylongdescription);
		return "displayothers_assertion_tpl.php";
		break;

	    case "displayothers_reflection":
		$this->setLayoutTemplate('eportfolio_layout_tpl.php');
		$thisid = $this->getParam('thisid', null);
		$myfilter = explode( ",", $thisid );
		$reflectionId = $myfilter[0];
		$ownerId = $myfilter[1];
		$groupId = $myfilter[2];
		$this->setVarByRef('reflectionId',$reflectionId);
		$this->setVarByRef('ownerId',$ownerId);
		$this->setVarByRef('groupId',$groupId);
		$list = $this->objDbReflectionList->listSingle($reflectionId);
		$rationale = $list[0]['rationale'];
		$creation_date = $list[0]['creation_date'];
		$shortdescription = $list[0]['shortdescription'];
		$longdescription = $list[0]['longdescription'];
		$this->setVarByRef('rationale',$rationale);
		$this->setVarByref('creation_date',$creation_date);
		$this->setVarByRef('shortdescription',$shortdescription);
		$this->setVarByRef('longdescription',$longdescription);
		return "display_reflection_tpl.php";
		break;

	    case "displayothers_interest":
		$this->setLayoutTemplate('eportfolio_layout_tpl.php');
		$thisid = $this->getParam('thisid', null);
		$myfilter = explode( ",", $thisid );
		$interestId = $myfilter[0];
		$ownerId = $myfilter[1];
		$groupId = $myfilter[2];
		$this->setVarByRef('interestId',$interestId);
		$this->setVarByRef('ownerId',$ownerId);
		$this->setVarByRef('groupId',$groupId);
		$list = $this->objDbInterestList->listSingle($interestId);
		$interesttype = $this->objDbCategorytypeList->listSingle($list[0]['type']);
		$interest_type = $interesttype[0]['type'];
		$creation_date = $list[0]['creation_date'];
		$shortdescription = $list[0]['shortdescription'];
		$longdescription = $list[0]['longdescription'];
		$this->setVarByRef('interest_type',$interest_type);
		$this->setVarByref('creation_date',$creation_date);
		$this->setVarByRef('shortdescription',$shortdescription);
		$this->setVarByRef('longdescription',$longdescription);
		return "display_interest_tpl.php";
		break;


	    case "displayothers_competency":
		$this->setLayoutTemplate('eportfolio_layout_tpl.php');
		$thisid = $this->getParam('thisid', null);
		$myfilter = explode( ",", $thisid );
		$competencyId = $myfilter[0];
		$ownerId = $myfilter[1];
		$groupId = $myfilter[2];
		$this->setVarByRef('competencyId',$competencyId);
		$this->setVarByRef('ownerId',$ownerId);
		$this->setVarByRef('groupId',$groupId);
		$list = $this->objDbCompetencyList->listSingle($competencyId);
		$competencytype = $this->objDbCategorytypeList->listSingle($list[0]['type']);
		$competency_type = $competencytype[0]['type'];
		$award_date = $list[0]['award_date'];
		$shortdescription = $list[0]['shortdescription'];
		$longdescription = $list[0]['longdescription'];
		$this->setVarByRef('competency_type',$competency_type);
		$this->setVarByref('award_date',$award_date);
		$this->setVarByRef('shortdescription',$shortdescription);
		$this->setVarByRef('longdescription',$longdescription);
		return "display_competency_tpl.php";
		
		break;


	    case "displayothers_transcript":
		$this->setLayoutTemplate('eportfolio_layout_tpl.php');
		$thisid = $this->getParam('thisid', null);
		$myfilter = explode( ",", $thisid );
		$transcriptId = $myfilter[0];
		$ownerId = $myfilter[1];
		$groupId = $myfilter[2];
		$this->setVarByRef('transcriptId',$transcriptId);
		$this->setVarByRef('ownerId',$ownerId);
		$this->setVarByRef('groupId',$groupId);
		$list = $this->objDbTranscriptList->listSingle($transcriptId);
		$shortdescription = $list[0]['shortdescription'];
		$longdescription = $list[0]['longdescription'];
		$this->setVarByRef('shortdescription',$shortdescription);
		$this->setVarByRef('longdescription',$longdescription);
		return "display_transcript_tpl.php";
		break;	

	    case "displayothers_activity":
		$this->setLayoutTemplate('eportfolio_layout_tpl.php');
		$objDbContext = &$this->getObject('dbcontext','context');
		$thisid = $this->getParam('thisid', null);
		$myfilter = explode( ",", $thisid );
		$activityId = $myfilter[0];
		$ownerId = $myfilter[1];
		$groupId = $myfilter[2];
		$this->setVarByRef('activityId',$activityId);
		$this->setVarByRef('ownerId',$ownerId);
		$this->setVarByRef('groupId',$groupId);
		$list = $this->objDbActivityList->listSingle($activityId);
		$activitytype = $this->objDbCategorytypeList->listSingle($list[0]['type']);
		$mycontextRecord = $objDbContext->getContextDetails($list[0]['contextid']);
		if(!empty($mycontextRecord)){
	         	$contexttitle = $mycontextRecord['title'];
		}else{
			$contexttitle = $list[0]['contextid'];
		}
		$activityType = $activitytype[0]['type'];
		$activityStart = $list[0]['start'];
		$activityFinish = $list[0]['finish'];
		$shortdescription = $list[0]['shortdescription'];
		$longdescription = $list[0]['longdescription'];
		$this->setVarByRef('contexttitle',$contexttitle);
		$this->setVarByRef('activityType',$activityType);
		$this->setVarByRef('activityStart',$activityStart);
		$this->setVarByRef('activityFinish',$activityFinish);
		$this->setVarByRef('shortdescription',$shortdescription);
		$this->setVarByRef('longdescription',$longdescription);
		return "display_activity_tpl.php";
		break;	


	case "editreflectionconfirm":
		$myid = $this->getParam('id', null);
		$this->setVarByRef('id',$myid);
		$this->nextAction(
		$this->objDbReflectionList->updateSingle(
			$myid,
		$this->getParam('rationale', NULL),
		$this->getParam('creation_date', NULL),
		$this->getParam('shortdescription', NULL),
		$this->getParam('longdescription', NULL)
		));
		return $this->nextAction('main',NULL);
		break;


	   case "addreflectionconfirm":
	        $id = $this->objDbReflectionList->insertSingle(
		$this->getParam('rationale', NULL),
		$this->getParam('creation_date', NULL),
		$this->getParam('shortdescription', NULL),
		$this->getParam('longdescription', NULL)
		);
		return $this->nextAction('main',NULL);
		break;

	    case "editreflection":
		$this->setLayoutTemplate('eportfolio_layout_tpl.php');
		$id = $this->getParam('id', null);
		$this->setVarByRef('id',$id);
		$list = $this->objDbReflectionList->listSingle($id);
		$rationale = $list[0]['rationale'];
		$creation_date = $list[0]['creation_date'];
		$shortdescription = $list[0]['shortdescription'];
		$longdescription = $list[0]['longdescription'];
		$this->setVarByRef('rationale',$rationale);
		$this->setVarByref('creation_date',$creation_date);
		$this->setVarByRef('shortdescription',$shortdescription);
		$this->setVarByRef('longdescription',$longdescription);
		return "edit_reflection_tpl.php";
		break;


	case "editproductconfirm":
		$myid = $this->getParam('id', null);
		$this->setVarByRef('id',$myid);
		$this->nextAction(
		$this->objDbProductList->updateSingle(
			$myid,
		$this->getParam('producttype', NULL),
		$this->getParam('comment', NULL),
		$this->getParam('referential_source', NULL),
		$this->getParam('referential_id', NULL),
		$this->getParam('assertion_id', NULL),
		$this->getParam('assignment_id', NULL),
		$this->getParam('essay_id', NULL),
		$this->getParam('creation_date', NULL),
		$this->getParam('shortdescription', NULL),
		$this->getParam('longdescription', NULL)
		));
		return $this->nextAction('main',NULL);
		break;


	   case "addproductconfirm":
	        $id = $this->objDbProductList->insertSingle(
		$this->getParam('producttype', NULL),
		$this->getParam('comment', NULL),
		$this->getParam('referential_source', NULL),
		$this->getParam('referential_id', NULL),
		$this->getParam('assertion_id', NULL),
		$this->getParam('assignment_id', NULL),
		$this->getParam('essay_id', NULL),
		$this->getParam('creation_date', NULL),
		$this->getParam('shortdescription', NULL),
		$this->getParam('longdescription', NULL)

		);
		return $this->nextAction('main',NULL);
		break;

	    case "editproduct":
		$this->setLayoutTemplate('eportfolio_layout_tpl.php');
		$id = $this->getParam('id', null);
		$this->setVarByRef('id',$id);
		$list = $this->objDbProductList->listSingle($id);
		$producttype = $list[0]['type'];
		$comment = $list[0]['comment'];
		$referential_source = $list[0]['referential_source'];
		$referential_id = $list[0]['referential_id'];
		$assertion_id = $list[0]['assertion_id'];
		$assignment_id = $list[0]['assignment_id'];
		$essay_id = $list[0]['essay_id'];
		$creation_date = $list[0]['creation_date'];
		$shortdescription = $list[0]['shortdescription'];
		$longdescription = $list[0]['longdescription'];
		$this->setVarByRef('producttype',$producttype);
		$this->setVarByref('comment',$comment);
		$this->setVarByRef('referential_source',$referential_source);
		$this->setVarByRef('referential_id',$referential_id);
		$this->setVarByref('assertion_id',$assertion_id);
		$this->setVarByRef('assignment_id',$assignment_id);
		$this->setVarByRef('essay_id',$essay_id);
		$this->setVarByref('creation_date',$creation_date);
		$this->setVarByRef('shortdescription',$shortdescription);
		$this->setVarByRef('longdescription',$longdescription);

		 // After processing return to view product
		return "edit_product_tpl.php";
		break;
	case "editinterestconfirm":
		$myid = $this->getParam('id', null);
		$this->setVarByRef('id',$myid);
		$this->nextAction(
		$this->objDbInterestList->updateSingle(
			$myid,
		$this->getParam('interest_type', NULL),
		$this->getParam('creation_date', NULL),
		$this->getParam('shortdescription', NULL),
		$this->getParam('longdescription', NULL)
		));
		return $this->nextAction('main',NULL);
		break;

	   case "addinterestconfirm":
	        $id = $this->objDbInterestList->insertSingle(
		$this->getParam('interest_type', NULL),
		$this->getParam('creation_date', NULL),
		$this->getParam('shortdescription', NULL),
		$this->getParam('longdescription', NULL)
		);
		return $this->nextAction('main',NULL);
		break;

	    case "editinterest":
		$this->setLayoutTemplate('eportfolio_layout_tpl.php');
		$id = $this->getParam('id', null);
		$this->setVarByRef('id',$id);
		$list = $this->objDbInterestList->listSingle($id);
		$interest_type = $list[0]['type'];
		$creation_date = $list[0]['creation_date'];
		$shortdescription = $list[0]['shortdescription'];
		$longdescription = $list[0]['longdescription'];
		$this->setVarByRef('interest_type',$interest_type);
		$this->setVarByref('creation_date',$creation_date);
		$this->setVarByRef('shortdescription',$shortdescription);
		$this->setVarByRef('longdescription',$longdescription);
		return "edit_interest_tpl.php";
		break;



	    case "editgoals":
		$this->setLayoutTemplate('eportfolio_layout_tpl.php');
		$id = $this->getParam('id', null);
		$this->setVarByRef('id',$id);
		$list = $this->objDbGoalsList->listSingle($id);
		$parentid = $list[0]['parentid'];
		$goal_type = $list[0]['type'];
		$start = $list[0]['start'];
		$priority = $list[0]['priority'];
		$status = $list[0]['status'];
		$status_date = $list[0]['status_date'];
		$shortdescription = $list[0]['shortdescription'];
		$longdescription = $list[0]['longdescription'];
		$this->setVarByRef('id',$id);
		$this->setVarByRef('parentid',$parentid);
		$this->setVarByRef('goal_type',$goal_type);
		$this->setVarByRef('start',$start);
		$this->setVarByRef('priority',$priority);
		$this->setVarByRef('status',$status);
		$this->setVarByref('status_date',$status_date);
		$this->setVarByRef('shortdescription',$shortdescription);
		$this->setVarByRef('longdescription',$longdescription);
		return "edit_goals_tpl.php";
		break;


	    case "editqcl":
		$this->setLayoutTemplate('eportfolio_layout_tpl.php');
		$id = $this->getParam('id', null);
		$this->setVarByRef('id',$id);
		$list = $this->objDbQclList->listSingle($id);
		$qcl_type = $list[0]['qcl_type'];
		$title = $list[0]['qcl_title'];
		$organisation = $list[0]['organisation'];
		$qcl_level = $list[0]['qcl_level'];
		$award_date = $list[0]['award_date'];
		$shortdescription = $list[0]['shortdescription'];
		$longdescription = $list[0]['longdescription'];
		$this->setVarByRef('qcl_type',$qcl_type);
		$this->setVarByRef('title',$title);
		$this->setVarByRef('organisation',$organisation);
		$this->setVarByRef('qcl_level',$qcl_level);
		$this->setVarByRef('award_date',$award_date);
		$this->setVarByref('shortdescription',$shortdescription);
		$this->setVarByref('longdescription',$longdescription);
		return "edit_qcl_tpl.php";
		break;

	   case "addaffiliationconfirm":
	        $id = $this->objDbAffiliationList->insertSingle(
		$this->getParam('affiliation_type', NULL),
		$this->getParam('classification', NULL),
		$this->getParam('role', NULL),
		$this->getParam('organisation', NULL),
		$this->getParam('start', NULL),
		$this->getParam('finish', NULL)
		);
		return $this->nextAction('main',NULL);
		break;
	    case "editaddress":
		$this->setLayoutTemplate('eportfolio_layout_tpl.php');
		$id = $this->getParam('id', null);
		$this->setVarByRef('id',$id);
		$list = $this->objDbAddressList->listSingle($id);
		$address_type = $list[0]['type'];
		$street_no = $list[0]['street_no'];
		$street_name = $list[0]['street_name'];
		$locality = $list[0]['locality'];
		$city = $list[0]['city'];
		$postcode = $list[0]['postcode'];
		$postal_address = $list[0]['postal_address'];
		$this->setVarByRef('address_type',$address_type);
		$this->setVarByRef('street_no',$street_no);
		$this->setVarByRef('street_name',$street_name);
		$this->setVarByRef('locality',$locality);
		$this->setVarByRef('city',$city);
		$this->setVarByref('postcode',$postcode);
		$this->setVarByref('postal_address',$postal_address);
		return "edit_address_tpl.php";
		break;
	    case "editaffiliation":
		$this->setLayoutTemplate('eportfolio_layout_tpl.php');
		$id = $this->getParam('id', null);
		$this->setVarByRef('id',$id);
		$list = $this->objDbAffiliationList->listSingle($id);
		$affiliation_type = $list[0]['type'];
		$classification = $list[0]['classification'];
		$role = $list[0]['role'];
		$organisation = $list[0]['organisation'];
		$start = $list[0]['start'];
		$finish = $list[0]['finish'];
		$this->setVarByRef('affiliation_type',$affiliation_type);
		$this->setVarByRef('classification',$classification);
		$this->setVarByRef('role',$role);
		$this->setVarByRef('organisation',$organisation);
		$this->setVarByRef('start',$start);
		$this->setVarByref('finish',$finish);
		return "edit_affiliation_tpl.php";
		break;	
	case "editaddressconfirm":
		$myid = $this->getParam('id', null);
		$this->setVarByRef('id',$myid);
		$this->nextAction(
		$this->objDbAddressList->updateSingle(
			$myid,
			$this->getParam('address_type', NULL),
			$this->getParam('street_no', NULL),
			$this->getParam('street_name', NULL),
			$this->getParam('locality', NULL),
			$this->getParam('city', NULL),
			$this->getParam('postcode', NULL),
			$this->getParam('postal_address', NULL)
		));
		return $this->nextAction('main',NULL);
		break;
	case "editaffiliationconfirm":
		$myid = $this->getParam('id', null);
		$this->setVarByRef('id',$myid);
		$this->nextAction(
		$this->objDbAffiliationList->updateSingle(
			$myid,
			$this->getParam('affiliation_type', NULL),
			$this->getParam('classification', NULL),
			$this->getParam('role', NULL),
			$this->getParam('organisation', NULL),
			$this->getParam('start', NULL),
			$this->getParam('finish', NULL)
		));
		return $this->nextAction('main',NULL);
		break;
	   case "addcontactconfirm":	        
	        $id = $this->objDbContactList->insertSingle(
		$this->getParam('contact_type', NULL),
		$this->getParam('contactType', NULL),
		$this->getParam('country_code', NULL),
		$this->getParam('area_code', NULL),
		$this->getParam('id_number', NULL)
		);
		return $this->nextAction('main',NULL);
		break;

	case "editemail":
		$this->setLayoutTemplate('eportfolio_layout_tpl.php');
		$id = $this->getParam('id', null);
		$this->setVarByRef('id',$id);
		$list = $this->objDbEmailList->listSingle($id);
		$email_type = $list[0]['type'];
		$email = $list[0]['email'];
		$this->setVarByRef('email_type',$email_type);
		$this->setVarByRef('email',$email);
		return "edit_email_tpl.php";
		break;

	   case "addemailconfirm":
	        $id = $this->objDbEmailList->insertSingle(
		$this->getParam('email_type', NULL),
		$this->getParam('email', NULL)
		);
		return $this->nextAction('main',NULL);
		break;
	   case "editemailconfirm":
		$myid = $this->getParam('id', null);
		$this->setVarByRef('id',$myid);
		$this->nextAction(
		$this->objDbEmailList->updateSingle(
			$myid,
			$this->getParam('email_type', NULL),
			$this->getParam('email', NULL)
		));
		return $this->nextAction('main',NULL);
		break;

	   case "editcategory":
		$this->setLayoutTemplate('eportfolio_layout_tpl.php');
		$id = $this->getParam('id', null);
		$this->setVarByRef('id',$id);
		$list = $this->objDbCategoryList->listSingle($id);
		$category = $list[0]['category'];
		$this->setVarByRef('category',$category);
		return "edit_category_tpl.php";
		break;

	   case "addcategoryconfirm":
	        $id = $this->objDbCategoryList->insertSingle(
		$this->getParam('category', NULL)
		);
		return $this->nextAction('main',NULL);
		break;
	   case "editcategoryconfirm":
		$myid = $this->getParam('id', null);
		$this->setVarByRef('id',$myid);
		$this->nextAction(
		$this->objDbCategoryList->updateSingle(
			$myid,
			$this->getParam('category', NULL)
		));
		return $this->nextAction('main',NULL);
		break;

	   case "editcategorytype":
		$this->setLayoutTemplate('eportfolio_layout_tpl.php');
		$id = $this->getParam('id', null);
		$this->setVarByRef('id',$id);
		$list = $this->objDbCategorytypeList->listSingle($id);
		$categoryid = $list[0]['categoryid'];
		$categorytype = $list[0]['type'];
		$this->setVarByRef('categoryid',$categoryid);
		$this->setVarByRef('categorytype',$categorytype);
		return "edit_categorytype_tpl.php";
		break;

	   case "addcategorytypeconfirm":
	        $id = $this->objDbCategorytypeList->insertSingle(
		$this->getParam('categoryid', NULL),
		$this->getParam('categorytype', NULL)
		);
		return $this->nextAction('main',NULL);
		break;
	   case "editcategorytypeconfirm":
		$myid = $this->getParam('id', null);
		$this->setVarByRef('id',$myid);
		$this->nextAction(
		$this->objDbCategorytypeList->updateSingle(
			$myid,
			$this->getParam('categoryid', NULL),
			$this->getParam('categorytype', NULL)

		));
		return $this->nextAction('main',NULL);
		break;


	    case "editcontact":
		$this->setLayoutTemplate('eportfolio_layout_tpl.php');
		$id = $this->getParam('id', null);
		$this->setVarByRef('id',$id);
		$list = $this->objDbContactList->listSingle($id);
		$contact_type = $list[0]['type'];
		$contactType = $list[0]['contact_type'];
		$country_code = $list[0]['country_code'];
		$area_code = $list[0]['area_code'];
		$id_number = $list[0]['id_number'];
		$this->setVarByRef('contact_type',$contact_type);
		$this->setVarByRef('contactType',$contactType);
		$this->setVarByRef('country_code',$country_code);
		$this->setVarByRef('area_code',$area_code);
		$this->setVarByRef('id_number',$id_number);
		return "edit_contact_tpl.php";
		break;	
	   case "editcontactconfirm":
		$myid = $this->getParam('id', null);
		$this->setVarByRef('id',$myid);
		$this->nextAction(
		$this->objDbContactList->updateSingle(
			$myid,
			$this->getParam('contact_type', NULL),
			$this->getParam('contactType', NULL),
			$this->getParam('country_code', NULL),
			$this->getParam('area_code', NULL),
			$this->getParam('id_number', NULL)
		));
		return $this->nextAction('main',NULL);
		break;
	   case "adddemographicsconfirm":
		/*
	        //Covert date to sql format
		$this->getParam('birth', NULL);
		$this->setVarByRef('birth', $this->birth);
		$birth = $this->objDate->sqlDate($birth);
		*/
		$id = $this->objDbDemographicsList->insertSingle(
		$this->getParam('demographics_type', NULL),
		$this->getParam('birth', NULL),
		$this->getParam('nationality', NULL)
		);
		return $this->nextAction('main',NULL);
		break;
	    case "editdemographics":
		$this->setLayoutTemplate('eportfolio_layout_tpl.php');
		$id = $this->getParam('id', null);
		$this->setVarByRef('id',$id);
		$list = $this->objDbDemographicsList->listSingle($id);
		$demographics_type = $list[0]['type'];
		$birth = $list[0]['birth'];
		$nationality = $list[0]['nationality'];
		$this->setVarByRef('demographics_type',$demographics_type);
		$this->setVarByRef('birth',$birth);
		$this->setVarByRef('nationality',$nationality);
		return "edit_demographics_tpl.php";
		break;	
	case "editdemographicsconfirm":
		$myid = $this->getParam('id', null);
		$this->setVarByRef('id',$myid);
	        //Covert date to sql format
		$birth = $this->getParam('birth', NULL);
		//$this->setVarByRef('birth', $this->birth);
		//$mybirth = $this->objDate->sqlDate($birth);
		$this->nextAction(
		$this->objDbDemographicsList->updateSingle(
			$myid,
			$this->getParam('demographics_type', NULL),
			$birth,
			$this->getParam('nationality', NULL)
		));
		return $this->nextAction('main',NULL);
		break;

	   case "addactivityconfirm":
	        // associate activity to a course
	        $associate = $this->getParam('contexttitle', NULL);
	        if (isset($associate) && !empty($associate)) {
			$contexttitle = $this->getParam('contexttitle', NULL);
		}else{
			$contexttitle = 'None';
		}
	        $id = $this->objDbActivityList->insertSingle(
		$contexttitle,
		$this->getParam('activity_type', NULL),
		$this->getParam('activityStart', NULL),
		$this->getParam('activityFinish', NULL),
		$this->getParam('shortdescription', NULL),
		$this->getParam('longdescription', NULL)
		);
		return $this->nextAction('main',NULL);
		break;
	   case "addtranscriptconfirm":
	        //$link = $this->getParam('link', NULL);
	        $id = $this->objDbTranscriptList->insertSingle(
		$this->getParam('shortdescription', NULL),
		$this->getParam('longdescription', NULL)
		);
		return $this->nextAction('main',NULL);
		break;
	    case "editactivity":
		$this->setLayoutTemplate('eportfolio_layout_tpl.php');
		$id = $this->getParam('id', null);
		$this->setVarByRef('id',$id);
		$list = $this->objDbActivityList->listSingle($id);
		$contexttitle = $list[0]['contextid'];
		$activityType = $list[0]['type'];
		$activityStart = $list[0]['start'];
		$activityFinish = $list[0]['finish'];
		$shortdescription = $list[0]['shortdescription'];
		$longdescription = $list[0]['longdescription'];
		$this->setVarByRef('contexttitle',$contexttitle);
		$this->setVarByRef('activityType',$activityType);
		$this->setVarByRef('activityStart',$activityStart);
		$this->setVarByRef('activityFinish',$activityFinish);
		$this->setVarByRef('shortdescription',$shortdescription);
		$this->setVarByRef('longdescription',$longdescription);
		return "edit_activity_tpl.php";
		break;	
	case "editactivityconfirm":
		$myid = $this->getParam('id', null);
		$this->setVarByRef('id',$myid);
	        // associate activity to a course
	        $associate = $this->getParam('contexttitle', NULL);
	        if (isset($associate) && !empty($associate)) {
			$contexttitle = $this->getParam('contexttitle', NULL);
		}else{
			$contexttitle = 'None';
		}
		$this->nextAction(
		$this->objDbActivityList->updateSingle(
			$myid,
			$contexttitle,
			$this->getParam('activityType', NULL),
			$this->getParam('activityStart', NULL),
			$this->getParam('activityFinish', NULL),
			$this->getParam('shortdescription', NULL),
			$this->getParam('longdescription', NULL)
		));
		return $this->nextAction('main',NULL);
		break;

	    case "edittranscript":
		$this->setLayoutTemplate('eportfolio_layout_tpl.php');
		$id = $this->getParam('id', null);
		$this->setVarByRef('id',$id);
		$list = $this->objDbTranscriptList->listSingle($id);
		$shortdescription = $list[0]['shortdescription'];
		$longdescription = $list[0]['longdescription'];
		$this->setVarByRef('shortdescription',$shortdescription);
		$this->setVarByRef('longdescription',$longdescription);
		return "edit_transcript_tpl.php";
		break;	
	case "edittranscriptconfirm":
		$myid = $this->getParam('id', null);
		$this->setVarByRef('id',$myid);
		$this->nextAction(
		$this->objDbTranscriptList->updateSingle(
			$myid,
			$this->getParam('shortdescription', NULL),
			$this->getParam('longdescription', NULL)
		));
		return $this->nextAction('main',NULL);
		break;


            default:
		return $this->showUserDetailsForm();
		break;
	}
    }
    
    private function showUserDetailsForm()
    {
        return 'main_tpl.php';
    }
    


    /**
    * Method to get a list of courses a user is registered for
    * @return array
    * @access public
    */

	public function getUserContexts()
	{
		$usercontextcodes = $this->objUserContext->getContextList();

		foreach ($usercontextcodes as $code)
		{
			$objDbContext = &$this->getObject('dbcontext','context');	 
			$mycontextRecord[] = $objDbContext->getContextDetails($code['contextcode']);
	
		}//End foreach
				
		return $mycontextRecord;
	}

    private function changePicture()
    {
        $fileId = $this->getParam('imageselect');
        
        if (isset($_POST['resetimage'])) {
            return $this->resetImage();
        }
        
        if ($fileId == '') {
            return $this->nextAction(NULL, array('change'=>'image', 'message'=>'nopicturegiven'));
        }
        
        $filepath = $this->objFile->getFullFilePath($fileId);
        
        if ($fileId == FALSE) {
            return $this->nextAction(NULL, array('change'=>'image', 'message'=>'imagedoesnotexist'));
        }
        
        $mimetype = $this->objFile->getFileMimetype($fileId);
        
        if (substr($mimetype, 0, 5) != 'image') {
            return $this->nextAction(NULL, array('change'=>'image', 'message'=>'fileisnotimage'));
        }
        
        $objImageResize = $this->getObject('imageresize', 'files');
        $objImageResize->setImg($filepath);
        
        //Resize to 100x100 Maintaining Aspect Ratio
        $objImageResize->resize(100, 100, TRUE);
        $storePath = 'user_images/'.$this->objUser->userId().'.jpg';
        $this->objCleanUrl->cleanUpUrl($storePath);
        $result = $objImageResize->store($storePath);
        
        //Resize to 100x100 Maintaining Aspect Ratio
        $objImageResize->resize(35, 35, TRUE);
        $storePath = 'user_images/'.$this->objUser->userId().'_small.jpg';
        $this->objCleanUrl->cleanUpUrl($storePath);
        $result = $objImageResize->store($storePath);
        
        $this->setSession('showconfirmation', TRUE);
        return $this->nextAction(NULL, array('change'=>'image', 'message'=>'imagechanged'));
    }
    
    private function updateUserDetails()
    {
        if (!$_POST) {
            return $this->nextAction(NULL);
        }
        
        // Get Details from Form
        $title = $this->getParam('eportfolio_title');
        $firstname = $this->getParam('eportfolio_othernames');
        $surname = $this->getParam('eportfolio_surname');
	$password = '';
        $userDetails = array(
            'title'=>$title,
            'firstname'=>$firstname,
            'surname'=>$surname,
            );
            
        $this->setSession('userDetails', $userDetails);
        $results['detailschanged']=TRUE;
        
        // Process Update
        $update = $this->objUserAdmin->updateUserDetails($this->user['id'], $this->user['username'], $firstname, $surname, $title, $this->user['emailaddress'], $this->user['sex'], $this->user['country'], $this->user['cellnumber'], $this->user['staffnumber'], $password);
        
        if (count($results) > 0) {
            $results['change'] = 'details';
        }
        
        $this->setSession('showconfirmation', TRUE);
        
        $this->objUser->updateUserSession();
        // Process Update Results
        if ($update) {
	
		// After processing return to view contact
	        return $this->nextAction( 'view_contact', array() );

        } else {
            return $this->nextAction(NULL, array('change'=>'details', 'error'=>'detailscouldnotbeupdated'));
        }
        
    }
    
    private function checkFields($checkFields)
    {
        $allFieldsOk = TRUE;
        $this->messages = array();
        
        foreach ($checkFields as $field)
        {
            if ($field == '') {
                $allFieldsOk = FALSE;
            }
        }
        
        return $allFieldsOk;
    }
    
     public function formatDate($date)
    {
        $ret = $this->objDate->formatDate($date);
        return $ret;
    }
    
    function resetImage()
    {
        $this->objUserAdmin->removeUserImage($this->objUser->userId());
        $this->setSession('showconfirmation', TRUE);
        return $this->nextAction(NULL, array('change'=>'image', 'message'=>'userimagereset', 'change'=>'image'));
    }
    /**
    * Method to process the request to manage a member group.
    * @param string the group id to be managed.
    */
    function processManagegroup( $myId )
    {
	
	$groupId = $myId;

        if ( $this->getParam( 'button' ) == 'save' && $groupId <> '' ) {
            // Get the revised member ids
            if(is_array($this->getParam( 'list2' )))
            {
            	$list = $this->getParam( 'list2' );
            } else {
            	$list = array();
            }
           
            // Get the original member ids
            $fields = array ( 'tbl_users.id' );
            $memberList = &$this->_objGroupAdmin->getGroupUsers( $groupId, $fields );
            $oldList = $this->_objGroupAdmin->getField( $memberList, 'id' );
            // Get the added member ids
            $addList = array_diff( $list, $oldList );
            // Get the deleted member ids
            $delList = array_diff( $oldList, $list );
            // Add these members
            foreach( $addList as $userId ) {
                $this->_objGroupAdmin->addGroupUser( $groupId, $userId );
            }
            // Delete these members
            foreach( $delList as $userId ) {
                $this->_objGroupAdmin->deleteGroupUser( $groupId, $userId );
            }
        }
        if ( $this->getParam( 'button' ) == 'cancel' && $groupId <> '' ) {
		
        }
        // After processing return to main
        return $this->nextAction( 'view_assertion', array() );

    }

    /**
    * Method to process the request to manage a member group.
    * @param string the group to be managed.
    */
    function processManage( $groupName, $myId )
    {
	$mygroupId = $this->_objGroupAdmin->getLeafId( array( $myId , $groupName) );
	
	$groupId = $this -> getchildId($mygroupId, $groupName);

        if ( $this->getParam( 'button' ) == 'save' && $groupId <> '' ) {
            // Get the revised member ids
            if(is_array($this->getParam( 'list2' )))
            {
            	$list = $this->getParam( 'list2' );
            } else {
            	$list = array();
            }
           
            // Get the original member ids
            $fields = array ( 'tbl_users.id' );
            $memberList = &$this->_objGroupAdmin->getGroupUsers( $groupId, $fields );
            $oldList = $this->_objGroupAdmin->getField( $memberList, 'id' );
            // Get the added member ids
            $addList = array_diff( $list, $oldList );
            // Get the deleted member ids
            $delList = array_diff( $oldList, $list );
            // Add these members
            foreach( $addList as $userId ) {
                $this->_objGroupAdmin->addGroupUser( $groupId, $userId );
            }
            // Delete these members
            foreach( $delList as $userId ) {
                $this->_objGroupAdmin->deleteGroupUser( $groupId, $userId );
            }
        }
        if ( $this->getParam( 'button' ) == 'cancel' && $groupId <> '' ) {
		
        }
        // After processing return to main
        return $this->nextAction( 'view_assertion', array() );

    }
    /**
    * Method to show the manage member group template.
    * @param string the group id to be managed.
    */
    function showManagegroup( $myid )
    {
        // The member list of this group
        $fields = array ( 'firstName', 'surname', 'tbl_users.id' );
        $memberList = $this->_objGroupAdmin->getGroupUsers($myid, $fields);
        $memberIds  = $this->_objGroupAdmin->getField( $memberList, 'id' );
        $filter = "'" . implode( "', '", $memberIds ) . "'";

        // Users list need the firstname, surname, and userId fields.
        $fields = array ( 'firstName', 'surname', 'id' );
        $usersList = $this->_objGroupAdmin->getUsers( $fields, " WHERE id NOT IN($filter)" );
        sort( $usersList );

        // Members list dropdown
        $lstMembers = $this->newObject( 'dropdown', 'htmlelements' );
        $lstMembers->name = 'list2[]';
        $lstMembers->extra = ' multiple="multiple" style="width:100pt" size="10" ondblclick="moveSelectedOptions(this.form[\'list2[]\'],this.form[\'list1[]\'],true); "';
        foreach ( $memberList as $user ) {
        	
            $fullName = $user['firstname'] . " " . $user['surname'];
            $userPKId = $user['id'];
//echo "<h1>userPKId ".$userPKId."</h1>";
            $lstMembers->addOption( $userPKId, $fullName );
        }

		$tblLayoutM= &$this->newObject( 'htmltable', 'htmlelements' );
		$tblLayoutM->row_attributes = 'align="center" ';
		$tblLayoutM->width = '100px';
		$tblLayoutM->startRow();
		$tblLayoutM->endRow();
		$tblLayoutM->startRow();
			$tblLayoutM->addCell( $lstMembers->show() );
		$tblLayoutM->endRow();
        $this->setVarByRef('lstMembers', $tblLayoutM);

        // Users list dropdown
        $lstUsers = $this->newObject( 'dropdown', 'htmlelements' );
        $lstUsers->name = 'list1[]';
        $lstUsers->extra = ' multiple="multiple" style="width:100pt"  size="10" ondblclick="moveSelectedOptions(this.form[\'list1[]\'],this.form[\'list2[]\'],true)"';
        foreach ( $usersList as $user ) {
            $fullName = $user['firstname'] . " " . $user['surname'];
            $userPKId = $user['id'];
            $lstUsers->addOption( $userPKId, $fullName );

        }
		$tblLayoutU= &$this->newObject( 'htmltable', 'htmlelements' );
		$tblLayoutU->row_attributes = 'align="center"';
		$tblLayoutU->width = '100px';
		$tblLayoutU->startRow();
			$tblLayoutU->addCell( $this->objLanguage->code2Txt('mod_contextgroups_ttlUsers','contextgroups'),'10%',null,null,'heading' );
		$tblLayoutU->endRow();
		$tblLayoutU->startRow();
			$tblLayoutU->addCell( $lstUsers->show() );
		$tblLayoutU->endRow();
        $this->setVarByRef('lstUsers', $tblLayoutU );

        // Link method
        $lnkSave = $this->newObject('link','htmlelements');
        $lnkSave->href  = '#';
        $lnkSave->extra = 'onclick="javascript:';
        $lnkSave->extra.= 'selectAllOptions( document.forms[\'frmManage\'][\'list2[]\'] ); ';
        $lnkSave->extra.= 'document.forms[\'frmManage\'][\'button\'].value=\'save\'; ';
        $lnkSave->extra.= 'document.forms[\'frmManage\'].submit(); "';
        $lnkSave->link  = $this->objLanguage->languageText( 'word_save' );

        $lnkCancel = $this->newObject('link','htmlelements');
        $lnkCancel->href  = '#';
        $lnkCancel->extra = 'onclick="javascript:';
        $lnkCancel->extra.= 'document.forms[\'frmManage\'][\'button\'].value=\'cancel\'; ';
        $lnkCancel->extra.= 'document.forms[\'frmManage\'].submit(); "';
        $lnkCancel->link  = $this->objLanguage->languageText( 'word_cancel' );

        $ctrlButtons = array();
        $ctrlButtons['lnkSave'] = $lnkSave->show();
        $ctrlButtons['lnkCancel'] = $lnkCancel->show();
        $this->setVar('ctrlButtons',$ctrlButtons);

        $navButtons = array();
        $navButtons['lnkRight']    = $this->navLink('>>','Selected',"forms['frmManage']['list1[]']", "forms['frmManage']['list2[]']");
        $navButtons['lnkRightAll'] = $this->navLink('All >>','All',"forms['frmManage']['list1[]']", "forms['frmManage']['list2[]']");
        $navButtons['lnkLeft']     = $this->navLink('<<','Selected',"forms['frmManage']['list2[]']", "forms['frmManage']['list1[]']");
        $navButtons['lnkLeftAll']  = $this->navLink('All <<','All',"forms['frmManage']['list2[]']", "forms['frmManage']['list1[]']");
        $this->setVar('navButtons',$navButtons);

        $frmManage = &$this->getObject( 'form', 'htmlelements' );
        $frmManage->name = 'frmManage';
        $frmManage->displayType = '3';
        $frmManage->action = $this->uri ( array( 'action' => 'manage_form', 'id'=>$myid ) );
	//$frmManage->action = $this->uri ( array( 'module'=>'eportfolio', 'action' => 'main', 'id'=>$myid) );
        $frmManage->addToForm("<input type='hidden' name='button' value='' />");
        
        
        
        $this->setVarByRef('frmManage', $frmManage );

        $title = $this->objLanguage->code2Txt(
            'mod_contextgroups_ttlManageMembers','contextgroups', array(
                'GROUPNAME'=>$groupName,
                'TITLE'=>$this->_objDBContext->getTitle() )
            );
        $this->setVar('title', $title );

        return 'manage_group_tpl.php';
    }

    /**
    * Method to show the manage member group template.
    * @param string the group to be managed.
    */
    function showManage( $groupName, $myid )
    {
	$mygroupId = $this->_objGroupAdmin->getLeafId( array( $myid, $groupName) );
//echo "<h1>mygroupId ".$mygroupId."</h1>";
	$groupId = $this -> getchildId($mygroupId, $groupName);
//echo "<h1>groupId ".$groupId."</h1>";
        // The member list of this group
        $fields = array ( 'firstName', 'surname', 'tbl_users.id' );
        $memberList = $this->_objGroupAdmin->getGroupUsers($groupId, $fields);
        $memberIds  = $this->_objGroupAdmin->getField( $memberList, 'id' );
        $filter = "'" . implode( "', '", $memberIds ) . "'";

        // Users list need the firstname, surname, and userId fields.
        $fields = array ( 'firstName', 'surname', 'id' );
        $usersList = $this->_objGroupAdmin->getUsers( $fields, " WHERE id NOT IN($filter)" );
        sort( $usersList );

        // Members list dropdown
        $lstMembers = $this->newObject( 'dropdown', 'htmlelements' );
        $lstMembers->name = 'list2[]';
        $lstMembers->extra = ' multiple="multiple" style="width:100pt" size="10" ondblclick="moveSelectedOptions(this.form[\'list2[]\'],this.form[\'list1[]\'],true); "';
        foreach ( $memberList as $user ) {
        	
            $fullName = $user['firstname'] . " " . $user['surname'];
            $userPKId = $user['id'];
//echo "<h1>userPKId ".$userPKId."</h1>";
            $lstMembers->addOption( $userPKId, $fullName );
        }

		$tblLayoutM= &$this->newObject( 'htmltable', 'htmlelements' );
		$tblLayoutM->row_attributes = 'align="center" ';
		$tblLayoutM->width = '100px';
		$tblLayoutM->startRow();
		$tblLayoutM->endRow();
		$tblLayoutM->startRow();
			$tblLayoutM->addCell( $lstMembers->show() );
		$tblLayoutM->endRow();
        $this->setVarByRef('lstMembers', $tblLayoutM);

        // Users list dropdown
        $lstUsers = $this->newObject( 'dropdown', 'htmlelements' );
        $lstUsers->name = 'list1[]';
        $lstUsers->extra = ' multiple="multiple" style="width:100pt"  size="10" ondblclick="moveSelectedOptions(this.form[\'list1[]\'],this.form[\'list2[]\'],true)"';
        foreach ( $usersList as $user ) {
            $fullName = $user['firstname'] . " " . $user['surname'];
            $userPKId = $user['id'];
            $lstUsers->addOption( $userPKId, $fullName );

        }
		$tblLayoutU= &$this->newObject( 'htmltable', 'htmlelements' );
		$tblLayoutU->row_attributes = 'align="center"';
		$tblLayoutU->width = '100px';
		$tblLayoutU->startRow();
			$tblLayoutU->addCell( $this->objLanguage->code2Txt('mod_contextgroups_ttlUsers','contextgroups'),'10%',null,null,'heading' );
		$tblLayoutU->endRow();
		$tblLayoutU->startRow();
			$tblLayoutU->addCell( $lstUsers->show() );
		$tblLayoutU->endRow();
        $this->setVarByRef('lstUsers', $tblLayoutU );

        // Link method
        $lnkSave = $this->newObject('link','htmlelements');
        $lnkSave->href  = '#';
        $lnkSave->extra = 'onclick="javascript:';
        $lnkSave->extra.= 'selectAllOptions( document.forms[\'frmManage\'][\'list2[]\'] ); ';
        $lnkSave->extra.= 'document.forms[\'frmManage\'][\'button\'].value=\'save\'; ';
        $lnkSave->extra.= 'document.forms[\'frmManage\'].submit(); "';
        $lnkSave->link  = $this->objLanguage->languageText( 'word_save' );

        $lnkCancel = $this->newObject('link','htmlelements');
        $lnkCancel->href  = '#';
        $lnkCancel->extra = 'onclick="javascript:';
        $lnkCancel->extra.= 'document.forms[\'frmManage\'][\'button\'].value=\'cancel\'; ';
        $lnkCancel->extra.= 'document.forms[\'frmManage\'].submit(); "';
        $lnkCancel->link  = $this->objLanguage->languageText( 'word_cancel' );

        $ctrlButtons = array();
        $ctrlButtons['lnkSave'] = $lnkSave->show();
        $ctrlButtons['lnkCancel'] = $lnkCancel->show();
        $this->setVar('ctrlButtons',$ctrlButtons);

        $navButtons = array();
        $navButtons['lnkRight']    = $this->navLink('>>','Selected',"forms['frmManage']['list1[]']", "forms['frmManage']['list2[]']");
        $navButtons['lnkRightAll'] = $this->navLink('All >>','All',"forms['frmManage']['list1[]']", "forms['frmManage']['list2[]']");
        $navButtons['lnkLeft']     = $this->navLink('<<','Selected',"forms['frmManage']['list2[]']", "forms['frmManage']['list1[]']");
        $navButtons['lnkLeftAll']  = $this->navLink('All <<','All',"forms['frmManage']['list2[]']", "forms['frmManage']['list1[]']");
        $this->setVar('navButtons',$navButtons);

        $frmManage = &$this->getObject( 'form', 'htmlelements' );
        $frmManage->name = 'frmManage';
        $frmManage->displayType = '3';
        $frmManage->action = $this->uri ( array( 'action' => $groupName.'_form', 'id'=>$myid ) );
	//$frmManage->action = $this->uri ( array( 'module'=>'eportfolio', 'action' => 'main', 'id'=>$myid) );
        $frmManage->addToForm("<input type='hidden' name='button' value='' />");
        
        
        
        $this->setVarByRef('frmManage', $frmManage );

        $title = $this->objLanguage->code2Txt(
            'mod_contextgroups_ttlManageMembers','contextgroups', array(
                'GROUPNAME'=>$groupName,
                'TITLE'=>$this->_objDBContext->getTitle() )
            );
        $this->setVar('title', $title );

        return 'manage_group_tpl.php';
    }
    /**
    * Method to create a navigation button link
    */
    function navLink( $linkText, $moveType, $from, $to )
    {
        $link = $this->newObject('link','htmlelements');
        $link->href  = '#';
        $link->extra = 'onclick="javascript:';
        $link->extra.= 'move'.$moveType.'Options';
        $link->extra.= '( document.'.$from;
        $link->extra.= ', document.'.$to;
        $link->extra.= ', true );"';
        $link->link  = htmlspecialchars( $linkText );
        return $link->show();
    }
    /**
    * Method to get the child id where name = $groupName
    */
 
    function getchildId($parentid, $groupName)
    {
	$thisgroupId = $this->_objGroupAdmin->getChildren($parentid);
	//Get the id for the child that corresponds to $groupName
	foreach($thisgroupId as $item){
		
		$mygroupName = $item['name'];
		
		if($mygroupName == $groupName){
			$groupId = $item['id'];
			
		}
		
	}
	return $groupId;	
    }	

    /**
    * Method to create the groups for a new eportfolio user
    * @param string The user id.
    * @param string The Title of a new context.
    */
    function createGroups( $userid, $title )
    {
        // Context node
        $eportfolioGroupId = $this->_objGroupAdmin->addGroup($userid,$title,NULL);
        // For each subgroup
        foreach( $this->_arrSubGroups as $groupName=>$groupId ) {

            $newGroupId = $this->_objGroupAdmin->addGroup(
                $groupName,
                $this->objUser->PKId($this->objUser->userId()).' '.$groupName,
                $eportfolioGroupId);
            $this->_arrSubGroups[$groupName]['id'] = $newGroupId;
        } // End foreach subgroup

        // Add groupMembers
        $this->addGroupMembers();

        // Now create the ACLS

	$this->_objManageGroups->createAcls( $userid, $title );

    } // End createGroups

    /**
    * Method to create more groups for an eportfolio user
    * @param string The user id.
    * @param string The Title of a new context.
    */
    function addGroups( $title )
    {
        // user Pk id
	$userPid = $this->objUser->PKId($this->objUser->userId());
        $usergroupId = $this->_objGroupAdmin->getId( $userPid, $pkField = 'name' );  
        // Add subgroup
        $newGroupId = $this->_objGroupAdmin->addGroup($title,$userPid.' '.$groupName,$usergroupId);

        // Add groupMembers
        $this->addGroupMembers();

        // Now create the ACLS

	$this->_objManageGroups->createAcls( $userPid, $title );

    } // End createGroups

    /**
    * Method to add members to the groups for a new eportfolio user
    */
    function addGroupMembers( )
    {
        foreach( $this->_arrSubGroups as $groupName=>$row ) {
            foreach( $row['members'] as $userPKId ){
                $this->_objGroupAdmin->addGroupUser( $row['id'], $userPKId );
            } // End foreach member
        } // End foreach subgroup
    } // End addGroupMembers
        /**
        * Method to get the eportfolio users
        * @return string
        */
        public function getEportfolioUsers()
        {

            //manage eportfolio users
            $objLink =  new link();
	    $objLanguage = &$this->getObject('language', 'language');
            $icon =  & $this->newObject('geticon', 'htmlelements');
            $table = & $this->newObject('htmltable' , 'htmlelements');
            $linkstable = & $this->newObject('htmltable' , 'htmlelements');
            $objGroups = & $this->newObject('managegroups', 'contextgroups');
            $mngfeatureBox =  & $this->newObject('featurebox', 'navigation');
            $table->width = '40%';
            $linkstable->width = '40%';
	    $str = '';

	    //Add Group Link
            $iconAdd = $this->getObject('geticon','htmlelements');
            $iconAdd->setIcon('add');	
	    $iconAdd->alt = $objLanguage->languageText("mod_eportfolio_add", 'eportfolio');			
	    $addlink = new link($this->uri(array('module'=>'eportfolio','action'=>'add_group')));
	    $addlink->link = $objLanguage->languageText("mod_eportfolio_add",'eportfolio').' '.$objLanguage->languageText("mod_eportfolio_wordGroup",'eportfolio').' '.$iconAdd->show();
	    //$addlink->link = 'Add Group'.' '.$iconAdd->show();
	    $linkAdd = $addlink->show();     
            $linkstableRow = array('<hr/>'.$linkAdd);
	    $linkstable->addRow($linkstableRow);	 
	//	$str .= $mngfeatureBox->show(NULL,$linkstable->show());	    

	    //Get group members
	    //Get group id
	    $userPid = $this->objUser->PKId($this->objUser->userId());
	    $this->setVarByRef('userPid', $this->userPid);
	    $usergroupId = $this->_objGroupAdmin->getId( $userPid, $pkField = 'name' );  

	    //get the descendents.


	    $usersubgroups = $this->_objGroupAdmin->getChildren($usergroupId);
 	    foreach ($usersubgroups as $subgroup)
	    {

		        // The member list of this group
		        $fields = array ( 'firstName', 'surname', 'tbl_users.id' );
		        $membersList = $this->_objGroupAdmin->getGroupUsers($subgroup['id'], $fields);
		        foreach ( $membersList as $users ) {
				if ($users)
				{
			
			            $fullName = $users['firstname'] . " " . $users['surname'];
			            $userPKId = $users['id'];
			
		                    $tableRow = array($fullName);

		                    $table->addRow($tableRow);

				}else{
		                   $tableRow = array('<div align="center" style="font-size:small;font-weight:bold;color:#CCCCCC;font-family: Helvetica, sans-serif;">'.$this->objLanguage->languageText('mod_eportfolio_wordManage','eportfolio').'</div>');
	  	                    $table->addRow($tableRow);
				}
		       

			}
			

		        //Add Users 
	                $iconManage = $this->getObject('geticon','htmlelements');
            		$iconManage->setIcon('add_icon');	
	    		$iconManage->alt = $objLanguage->languageText("mod_eportfolio_add",'eportfolio').' / '.$objLanguage->languageText("word_edit").' '.$subgroup['name'];
	    		$mnglink = new link($this->uri(array(
				'module'=>'eportfolio',
				'action'=>'manage_group', 
				'id' => $subgroup["id"]
			)));
//	    		$mnglink->link = $objLanguage->languageText("mod_eportfolio_wordManage",'eportfolio').' '.$subgroup['name'].' '.$iconManage->show();
			$mnglink->link = $iconManage->show();
			$linkManage = $mnglink->show();     
			//Manage Group

	                $iconShare = $this->getObject('geticon','htmlelements');
            		$iconShare->setIcon('fileshare');	
	    		$iconShare->alt = $objLanguage->languageText("mod_eportfolio_configure",'eportfolio').' '.$subgroup['name'].' '.$this->objLanguage->code2Txt("mod_eportfolio_view",'eportfolio');
	    		$mnglink = new link($this->uri(array(
				'module'=>'eportfolio',
				'action'=>'manage_eportfolio', 
				'id' => $subgroup["id"]
			)));
//	    		$mnglink->link = $objLanguage->languageText("mod_eportfolio_wordManage",'eportfolio').' '.$this->objLanguage->code2Txt("mod_eportfolio_wordEportfolio",'eportfolio').' '.$iconShare->show();
	    		$mnglink->link = $iconShare->show();

			$linkMng = $mnglink->show();     


		        $tableRow = array('<hr/>'.$linkManage.'   '.$linkMng);
		        $table->addRow($tableRow);	 


			$textinput = new textinput("groupname",$subgroup['name']);
		        $str .= $mngfeatureBox->show($subgroup['name'],$table->show());
		        $table = & $this->newObject('htmltable' , 'htmlelements');
			$managelink = new link();          
			

		}//end foreach
	        $str .= $mngfeatureBox->show(NULL,$linkstable->show());

		
		return $str;
		unset($users);

	  }//end method
	//Function for managing eportfolio group items/parts
	public function manageEportfolioViewers($selectedParts, $groupId)
	{
		// user Pk id
		$userPid = $this->objUser->PKId($this->objUser->userId());
		foreach($selectedParts as $partId){
			
			$thisId = $this->_objGroupAdmin->getId( $partId, $pkField = 'name' );		
			
			$partList = $this->_objGroupAdmin->getId( $partId, $pkField = 'name' );
							
			if(empty($partList)){
			        $partGroupsId=$this->_objGroupAdmin->addGroup($partId, $partId, $userPid);

				$groupUser=$this->_objGroupAdmin->addGroupUser( $partGroupsId, $groupId );
			}else{
				$isGroupMember=$this->_objGroupAdmin->isGroupMember( $groupId, $partList );
						
				if(empty($isGroupMember)){
					$this->_objGroupAdmin->addGroupUser( $partList, $groupId );
				}
			}

		}


	}//end function
	public function checkIfExists($partId,$groupId){
		// user Pk id
		$userPid = $this->objUser->PKId($this->objUser->userId());
		//Get group PidisGroupMember
		$partPid = $this->_objGroupAdmin->getId( $partId, $pkField = 'name' );
		
		//Is Member?
		$isGroupMbr=$this->_objGroupAdmin->isGroupMember( $groupId, $partPid );
		
		return $isGroupMbr;
	}//End Function

	public function deleteGroupUsers( $users, $groupId ){
		// Delete these members
		foreach( $users as $partId ) {

			$this->_objGroupAdmin->deleteGroupUser( $partId['group_id'], $groupId );

		}
		//Empty array
		$selectedParts = array();
	}//end function

}

?>
