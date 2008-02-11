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
        $this->_objDBContext = &$this->newObject('dbcontext','context');
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

	$this->userId=$this->objUser->userId(); //To pick user userid
	$this->setVarByRef('userId', $this->userId);
        
        $this->objUrl = $this->getObject('url', 'strings');

        // Create an array of words to abstract
         // Create an array of words to abstract
        $this->abstractionArray = array(
                'Lecturers'=>ucwords($this->objLanguage->code2Txt('word_lecturers')), 
                'Students'=>ucwords($this->objLanguage->code2Txt('word_students'))
            );

    }


    public function dispatch($action) 
    {
	// Get the context
        $objDbContext = &$this->getObject('dbcontext','context');
        $contextCode = $objDbContext->getContextCode();
        // If we are not in a context...

	if ($contextCode == null) {
	    $this->contextId = "root";
	    $this->setVarByRef('contextId', $this->contextId);
	    $this->contextTitle = "Lobby";
	    $this->setVarByRef('contextTitle', $this->contextTitle);
	 }
	// ... we are in a context
        else {
            $this->contextId = $contextCode;
            $this->setVarByRef('contextId', $this->contextId);
            
            $contextRecord = $objDbContext->getContextDetails($contextCode);
            $this->contextTitle = $contextRecord['title'];
            $this->setVarByRef('contextTitle', $this->contextTitle);
        }
        $this->setLayoutTemplate('eportfolio_layout_tpl.php');
        
        $this->user = $this->objUserAdmin->getUserDetails($this->objUser->PKId($this->objUser->userId()));
        $this->setVarByRef('user', $this->user);
	
        //$this->userId = $this->objUser->PKId($this->objUser->userId()); //To pick user id
        //$this->userId = $this->objUser->userId(); //To pick user userid
	//$this->setVarByRef('userId',$this->userId);
        switch ($action)
        {
            case 'manage_stud' :
		$myid = $this->getParam('id', null);
		return $this->showManage('Students',$myid);
		break;
            case 'manage_lect' :
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
		return "add_address_tpl.php";
		break;
            case "view_transcript":		
		return "view_transcript_tpl.php"; 
		break;
	    case "add_interest":
		return "add_interest_tpl.php";
		break;
            case "view_interest":		
		return "view_interest_tpl.php"; 
		break;
           case "view_address":		
		return "view_address_tpl.php";
		break;
	    case "view_contact":
		return "view_contact_tpl.php";
		break;
	    case "view_email":		
		return "view_email_tpl.php";		
		break;
	    case "view_activity":		
		return "view_activity_tpl.php";
		break;
	    case "view_qcl":		
		return "view_qcl_tpl.php";
		break;
	    case "view_goals":		
		return "view_goals_tpl.php";
		break;
	    case "add_goals":
		return "add_goals_tpl.php";
		break;
	    case "add_contact":
		return "add_contact_tpl.php";
		break;
	    case "add_email":
		return "add_email_tpl.php";
		break;
	    case "add_activity":		
		return "add_activity_tpl.php";
		break;
	    case "add_reflection":		
		return "add_reflection_tpl.php";
		break;
	    case "add_assertion":		
		return "add_assertion_tpl.php";
		break;
	    case "view_assertion":		
		return "view_assertion_tpl.php";
		break;
	    case "view_reflection":		
		return "view_reflection_tpl.php";
		break;
	    case "view_demographics":		
		return "view_demographics_tpl.php";
		break;
	    case "view_affiliation":		
		return "view_affiliation_tpl.php";
		break;
	    case "view_competency":		
		return "view_competency_tpl.php";
		break;
	    case "add_affiliation":
		return "add_affiliation_tpl.php";
		break;
	    case "add_demographics":
		return "add_demographics_tpl.php";
		break;
	    case "add_transcript":
		return "add_transcript_tpl.php";
		break;
	    case "add_qcl":
		return "add_qcl_tpl.php";
		break;
	    case "add_competency":
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
		// After processing return to view contact
	        return $this->nextAction( 'view_contact', array() );
		break;
	    case "deletecontact":
		$this->nextAction(
		$id = $this->getParam('id', null),
		$this->objDbContactList->deleteSingle($id));
		// After processing return to view contact
	        return $this->nextAction( 'view_contact', array() );
		break;
	    case "deleteinterest":
		$this->nextAction(
		$id = $this->getParam('id', null),
		$this->objDbInterestList->deleteSingle($id));
		 // After processing return to view interest
	        return $this->nextAction( 'view_interest', array() );
		break;

	    case "deletedemographics":
		$this->nextAction(
		$id = $this->getParam('id', null),
		$this->objDbDemographicsList->deleteSingle($id));
		// After processing return to view contact
	        return $this->nextAction( 'view_contact', array() );
		break;
	    case "deleteemail":
		$this->nextAction(
		$myid = $this->getParam('myid', null),
		$this->objDbEmailList->deleteSingle($myid));
		// After processing return to view contact
	        return $this->nextAction( 'view_contact', array() );
		break;
	    case "deletetranscript":
		$this->nextAction(
		$id = $this->getParam('id', null),
		$this->objDbTranscriptList->deleteSingle($id));
		// After processing return to view transcript
	        return $this->nextAction( 'view_transcript', array() );
		break;
	    case "deleteqcl":
		$this->nextAction(
		$id = $this->getParam('id', null),
		$this->objDbQclList->deleteSingle($id));
		// After processing return to view qcl
	        return $this->nextAction( 'view_qcl', array() );
		break;
	    case "deletecompetency":
		$this->nextAction(
		$id = $this->getParam('id', null),
		$this->objDbCompetencyList->deleteSingle($id));
		 // After processing return to view competency
	        return $this->nextAction( 'view_competency', array() );
		break;


	    case "deleteaffiliation":
		$this->nextAction(
		$myid = $this->getParam('id', null),
		$this->objDbAffiliationList->deleteSingle($myid));
		// After processing return to view affiliation
	        return $this->nextAction( 'view_affiliation', array() );
		break;
	    case "deletegoals":
		$this->nextAction(
		$myid = $this->getParam('id', null),
		$this->objDbGoalsList->deleteSingle($myid));
		// After processing return to view goals
	        return $this->nextAction( 'view_goals', array() );
		break;
	    case "deletereflection":
		$this->nextAction(
		$myid = $this->getParam('id', null),
		$this->objDbReflectionList->deleteSingle($myid));
		 // After processing return to view reflection
	        return $this->nextAction( 'view_reflection', array() );
		break;

	    case "deleteassertion":
		$this->nextAction(
		$myid = $this->getParam('id', null),
		$this->objDbAssertionList->deleteSingle($myid));
		 // After processing return to view assertion
	        return $this->nextAction( 'view_assertion', array() );
		break;
	    case "deleteactivity":
		$this->nextAction(
		$myid = $this->getParam('id', null),
		$this->objDbActivityList->deleteSingle($myid));		
		// After processing return to view activity
	        return $this->nextAction( 'view_activity', array() );
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
       		// After processing return to view contact
	        return $this->nextAction( 'view_contact', array() );
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
		// After processing return to view qcl
	        return $this->nextAction( 'view_qcl', array() );
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
		// After processing return to view goals
	        return $this->nextAction( 'view_goals', array() );
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
		// After processing return to view qcl
	        return $this->nextAction( 'view_qcl', array() );
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
		// After processing return to view goals
	        return $this->nextAction( 'view_goals', array() );
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
		 // After processing return to view competency
	        return $this->nextAction( 'view_competency', array() );
		break;

	   case "addcompetencyconfirm":
	        $id = $this->objDbCompetencyList->insertSingle(
		$this->getParam('competency_type', NULL),
		$this->getParam('award_date', NULL),
		$this->getParam('shortdescription', NULL),
		$this->getParam('longdescription', NULL)
		);
		 // After processing return to view competency
	        return $this->nextAction( 'view_competency', array() );
		break;

	    case "editcompetency":
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
		 // After processing return to view assertion
	        return $this->nextAction( 'view_assertion', array() );
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
	        return $this->nextAction( 'view_assertion', array() );
		break;			
	    case "editassertion":
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
		 // After processing return to view reflection
	        return $this->nextAction( 'view_reflection', array() );
		break;


	   case "addreflectionconfirm":
	        $id = $this->objDbReflectionList->insertSingle(
		$this->getParam('rationale', NULL),
		$this->getParam('creation_date', NULL),
		$this->getParam('shortdescription', NULL),
		$this->getParam('longdescription', NULL)
		);
		 // After processing return to view reflection
	        return $this->nextAction( 'view_reflection', array() );
		break;

	    case "editreflection":
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
		 // After processing return to view interest
	        return $this->nextAction( 'view_interest', array() );
		break;

	   case "addinterestconfirm":
	        $id = $this->objDbInterestList->insertSingle(
		$this->getParam('interest_type', NULL),
		$this->getParam('creation_date', NULL),
		$this->getParam('shortdescription', NULL),
		$this->getParam('longdescription', NULL)
		);
		 // After processing return to view interest
	        return $this->nextAction( 'view_interest', array() );
		break;

	    case "editinterest":
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
	        //$link = $this->getParam('link', NULL);
	        $id = $this->objDbAffiliationList->insertSingle(
		$this->getParam('affiliation_type', NULL),
		$this->getParam('classification', NULL),
		$this->getParam('role', NULL),
		$this->getParam('organisation', NULL),
		$this->getParam('start', NULL),
		$this->getParam('finish', NULL)
		);
		// After processing return to view affiliation
	        return $this->nextAction( 'view_affiliation', array() );
		break;
	    case "editaddress":
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
	case "editemail":
		$id = $this->getParam('id', null);
		$this->setVarByRef('id',$id);
		$list = $this->objDbEmailList->listSingle($id);
		$email_type = $list[0]['type'];
		$email = $list[0]['email'];
		$this->setVarByRef('email_type',$email_type);
		$this->setVarByRef('email',$email);
		return "edit_email_tpl.php";
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
		// After processing return to view contact
	        return $this->nextAction( 'view_contact', array() );
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
		// After processing return to view affiliation
	        return $this->nextAction( 'view_affiliation', array() );
		break;
	   case "addcontactconfirm":	        
	        $id = $this->objDbContactList->insertSingle(
		$this->getParam('contact_type', NULL),
		$this->getParam('contactType', NULL),
		$this->getParam('country_code', NULL),
		$this->getParam('area_code', NULL),
		$this->getParam('id_number', NULL)
		);
		// After processing return to view contact
	        return $this->nextAction( 'view_contact', array() );
		break;
	   case "addemailconfirm":
	        $id = $this->objDbEmailList->insertSingle(
		$this->getParam('email_type', NULL),
		$this->getParam('email', NULL)
		);
		// After processing return to view contact
	        return $this->nextAction( 'view_contact', array() );
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
		// After processing return to view contact
	        return $this->nextAction( 'view_contact', array() );
		break;
	    case "editcontact":
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
		// After processing return to view contact
	        return $this->nextAction( 'view_contact', array() );
		break;
	   case "adddemographicsconfirm":
	        //Covert date to sql format
		$this->getParam('birth', NULL);
		$this->setVarByRef('birth', $this->birth);
		$birth = $this->objDate->sqlDate($birth);
		$id = $this->objDbDemographicsList->insertSingle(
		$this->getParam('demographics_type', NULL),
		$birth,
		$this->getParam('nationality', NULL)
		);
		// After processing return to view contact
	        return $this->nextAction( 'view_contact', array() );
		break;
	    case "editdemographics":
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
		// After processing return to view contact
	        return $this->nextAction( 'view_contact', array() );
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
		// After processing return to view activity
	        return $this->nextAction( 'view_activity', array() );
		break;
	   case "addtranscriptconfirm":
	        //$link = $this->getParam('link', NULL);
	        $id = $this->objDbTranscriptList->insertSingle(
		$this->getParam('shortdescription', NULL),
		$this->getParam('longdescription', NULL)
		);
		// After processing return to view transcript
	        return $this->nextAction( 'view_transcript', array() );
		break;
	    case "editactivity":
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
		// After processing return to view activity
	        return $this->nextAction( 'view_activity', array() );
		break;

	    case "edittranscript":
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
		// After processing return to view transcript
	        return $this->nextAction( 'view_transcript', array() );
		break;


            default:
		return $this->showUserDetailsForm();
		break;
	}
    }
    
    private function showUserDetailsForm()
    {
       // $this->setVar('mode', 'edit');
        
        
        //$confirmation = $this->getSession('showconfirmation', FALSE);
        //$this->setVar('showconfirmation', $confirmation);
        
        //$this->setSession('showconfirmation', FALSE);

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
    * @param string the group to be managed.
    */
    function showManage( $groupName, $myid )
    {
	$mygroupId = $this->_objGroupAdmin->getLeafId( array( $myid, $groupName) );
	$groupId = $this -> getchildId($mygroupId, $groupName);
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
        $frmManage->addToForm("<input type='hidden' name='button' value='' />");
        
        
        
        $this->setVarByRef('frmManage', $frmManage );

        $title = $this->objLanguage->code2Txt(
            'mod_contextgroups_ttlManageMembers','contextgroups', array(
                'GROUPNAME'=>$groupName,
                'TITLE'=>$this->_objDBContext->getTitle() )
            );
        $this->setVar('title', $title );

        return 'manage_assertion_tpl.php';
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


}

?>
