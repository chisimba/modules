<?php
//ini_set('error_reporting', 'E_ALL & ~E_NOTICE');
/**
 * TurnItIn controller class
 *
 * Class to control the IM module
 *
 * PHP version 5
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the
 * Free Software Foundation, Inc.,
 * 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 * @category  chisimba
 * @package   turnitin
 * @author    Wesley Nitsckie <wesleynitsckie@gmail.com>
 * @copyright 2009 Wesley Nitsckie
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License 
 * @link      http://avoir.uwc.ac.za 
 */

class turnitin extends controller 
{
	
	/**
	 * The constructor
	 *
	 */
	public function init()
	{
		$this->objTOps = $this->getObject('turnitinops');
		$this->objUser = $this->getObject('user', 'security');
		$this->objDBContext = $this->getObject('dbcontext', 'context');
		$this->objForms = $this->getObject('forms');
		$this->objTAssDB = $this->getObject('turnitindbass');
	}
	
	/**
	 * The standard dispatch funtion
	 *
	 * @param unknown_type $action
	 */
	
	public function dispatch($action)
	{
		// Set the layout template.
        $this->setLayoutTemplate("layout_tpl.php");
        
		switch ($action)
		{
			//creates a user profile (if one does not exist) and logs the user in (instructor or student)
			case 'createlecturer':
								
				print $this->objTOps->createLecturer($this->getUserParams());
				break;				
			
			case 'createstudent':				
				print $this->objTOps->createStudent($this->getUserParams());
				break;
				
			//create a class with a lecturer assigned to it	
			case 'createclass':				
				print $this->objTOps->createClass(array_merge(
													$this->getUserParams(), 
													$this->getClassParams()));
				break;
			
			//the student is assigned to a class
			case 'joinclass':				
				print $this->objTOps->joinClass(array_merge(
													$this->getUserParams(), 
													$this->getClassParams()));
				break;				
			
			//create an assessment: requires lecturer and class details
			case 'createassessment':	
				if($this->objDBContext->isInContext())
				{
					print $this->objTOps->createAssessment(array_merge(
														$this->getUserParams(), 
														$this->getClassParams(),  
														$this->getAssessmentParams()));
				} else {
					return false;
				}
				break;
			
			//student submit a paper
			case 'submitassessment':
				print $this->objTOps->submitAssessment(array_merge(
														$this->getUserParams(), 
														$this->getClassParams(),  
														$this->getAssessmentParams(),
														$this->getSubmissionInfo()));
				break;
			//student submit a paper
			case 'sub':				
				print $this->objTOps->subA(array_merge(
														$this->getUserParams(), 
														$this->getClassParams(),  
														$this->getAssessmentParams(),
														$this->getSubmissionInfo()));
				break;
			case 'ajax_returnreport':
			case 'returnreport':
				print $this->objTOps->getReport(array_merge(
														$this->getUserParams(), 
														$this->getClassParams(),  
														$this->getAssessmentParams(),
														$this->getSubmissionInfo()));
				break;
				
			case 'viewsubmission':
				break;
				
			case 'deletesubmission':
				break;
				
			case 'listsubmissions':
				
				print $this->objTOps->listSubmissions(array_merge(
														$this->getUserParams(), 
														$this->getClassParams(),  
														$this->getAssessmentParams()));
				break;
				
			case 'adminstats':
				$this->objTOps->adminStats($this->getUserParams());
				break;
				
			case 'apilogin':
				$params = array("firstname" => $this->getParam('firstname'),
								"lastname" => $this->getParam('lastname'),
								"password" => $this->getParam('password'),
								"email" => $this->getParam('email'),);
				
				print $this->objTOps->APILogin($params);
				break;
				
			
			case 'callback':
				echo "This is the CALLBACK ...<BR>";
				$m = var_export($_REQUEST, true);
				$this->send_alert($m);
				error_log($m);
				var_dump($_REQUEST);
				break;
			default:
			case 'main':	
				return $this->userTemplate();			
				return "main_tpl.php";
				break;
				
				
				
			//------- Ajax methods-------//
			case 'ajax_addassignment':
				//echo "{'success' : 'true', 'msg' : 'ja it works -> ".$this->getParam('title').$this->getParam('startdt').$this->getParam('duedt').$this->getParam('instructions')."'}"; 
				//echo "{'success' : false, 'errors' : ['clientCode': 'Client not found', 'portOfLoading' : 'This field must not be null']}";   
				
				echo $this->doAddAssignment();
				exit(0);
				break;
				
			case 'json_getassessments':
				echo $this->formatJsonAssignments($this->objTAssDB->getAssignments('11'));//$this->objDBContext-getContextCode()
				//echo $this->objForms->jsonGetAssessments();
				exit(0);
				break;
			case 'json_getsubmissions':
				echo $this->formatSubmissions();
				exit(0);
		}
	}
	
	
	public function formatSubmissions(){
		
		$submissions[] = array("username" => "nitsckie",
							"firstname" => "Wesley",
							"lastname" => "Nitsckie",
							"title" => "The title of the paper",
							"score" => "34",
							"dateposted" => "12-12-2009"
							);
		$arr['totalCount'] = "1";
		$arr['submissions'] = $submissions;
		
		return json_encode($arr);
	}
	/**
	 * Call the correct template
	 *
	 * @return unknown
	 */
	public function userTemplate()
	{
		return "lectmain_tpl.php";
		$objContextGroups = $this->getObject('managegroups', 'contextgroups');	
		if ($this->objUser->isAdmin () || $objContextGroups->isContextLecturer()) 
		{
			return "lectmain_tpl.php";
		} else {
			return "main_tpl.php";
		}
	
	}
	
	public function formatJsonAssignments($assigments)
	{
		//get extra info from turnitin
		//var_dump($assigments);
		if($assigments)
		{
			/*foreach ($assigments as $ass)
			{
				//array_push(array('submissions' => '12'),$assigments[$ass] );			
			}
			*/
			
			$arr['totalCount'] = strval(count($assigments));
			$arr['assignments'] = $assigments;
		} else {
			return false;
		}
		
		return trim(json_encode($arr));
	}
	
	/**
	 * Method to disable the login 
	 * feature 
	 */
	public function requiresLogin()
	{
		return FALSE;
	}
	
	/**
	 * MEthod to get the user parameters
	 *
	 * @return array
	 */
	public function getUserParams()
	{
		$username = $this->objUser->userName();
		$userDetails = $this->objUser->lookupData($username);
				
		$params = array();
		
		if ($userDetails)
		{
			
			$params['password'] = 'nitsckie';//$username;    	
			$params['username'] = $username;
			$params['firstname'] = $userDetails['firstname']; 
			$params['lastname'] = $userDetails['surname'];
			$params['email'] = $userDetails['emailaddress'];	
			
			
			//lect
			/*$params['password'] = 'nitsckie';//$username;    	
			$params['username'] = 'wesleynitsckie';
			$params['firstname'] = 'Wesley';
			$params['lastname'] = 'Nitsckie';
			$params['email'] = 'wesleynitsckie@gmail.com';			
			*/
			$params['password'] = 'student';    	
			$params['username'] = 'student';
			$params['firstname'] = 'Student';
			$params['lastname'] = 'student';
			$params['email'] = 'student@uwc.ac.za';			
		}
		
		return $params;
	}
	
	/**
	 * MEthod to get the class parameters
	 *
	 * @return array
	 */
	public function getClassParams()
	{
		$params = array();
		
		if($this->objDBContext->isInContext())
		{
			$params['classid'] = $this->objDBContext->getContextCode();
			$params['classtitle'] = $this->objDBContext->getTitle();
			$params['classpassword'] = 'password';
			$params['instructoremail'] = 'elearning@uwc.ac.za';
		}
		//var_dump($params);die;
		//$params['classid'] = $this->objDBContext->getContextCode();
		$params['classtitle'] = "Twitter Class";//$this->objDBContext->getTitle();
		//$params['classpassword']
		return $params;
	}
	
	/**
	 * Method to get the assessment parameters
	 *
	 * @return array
	 */
	public function getAssessmentParams()
	{
		$params = array();
		
		$params['assignmenttitle'] = $this->getParam('title');
    	$params['assignmentinstruct'] = $this->getParam('instructions');
    	$params['assignmentdatestart'] = $this->getParam('startdt');
    	$params['assignmentdatedue'] = $this->getParam('duedt');    	
    	//$params['assignmendatedue'] = "theid";    	
    	
    	return $params;
	}
	
	public function doAddAssignment()
	{
		$successcodes = array(40, 41, 42, 43);
		$assParams = $this->getAssessmentParams();
		$rcode = array('rcode'=>42);/*$this->objTOps->createAssessment(array_merge(
														$this->getUserParams(), 
														$this->getClassParams(),
														$assParams));*/
		if(in_array($rcode['rcode'], $successcodes ))
		{
			//add to local database
			if($this->objTAssDB->addAssignment($this->objDBContext->getContextCode(), $assParams))
			{
				return json_encode(array('success' => 'true', 'msg' => 'A new assigment entitled <b>"'.$this->getParam('title').'"</b> was successfully created'));
			} else {
				return json_encode(array('success' => false, 'msg' => 'The assigment was create on Turnitin but an error occurred while inserting the details into the database'));
			}
		} else {
			
			return json_encode(array('success' => false, 'msg' => $rcode['rmessage']));
		}
														
	}
	
	/**
	 * Get Submission data
	 *
	 * @return unknown
	 */
	public function getSubmissionInfo()
	{
		$params = array();
		
		$params['papertitle'] = "This is submmitted title";//$this->getParam('papertitle');
    	$params['papertype'] = 2;
    	$params['paperdata'] = $this->getParam("filedata");
    	
    	return $params;
	}
	
	function send_alert($message,$users = array('wesleynitsckie@gmail.com'))
	  {
	    // email or SMS code goes here
	    include ($this->getResourcePath('XMPPHP/XMPP.php', "im"));
	    include ($this->getResourcePath('XMPPHP/XMPPHP_Log.php', "im"));
	    //include ('../im/classes/XMPPHP/XMPPHP_Log.php' );
	
	    $jserver = "talk.google.com";
	    $jport = "5222";
	    $juser = "eteaching2009";
	    $jpass = "3t3ach1ng2009";
	    $jclient = "ChisimbaIM";
	    $jdomain = "gmail.com";
	    
	    $conn2 = new XMPPHP_XMPP ( $jserver, intval ( $jport ), $juser, $jpass, $jclient, $jdomain, $printlog = FALSE, $loglevel = XMPPHP_Log::LEVEL_ERROR );
	    $conn2->connect ();
	    $conn2->processUntil ( 'session_start' );
	    foreach ($users as $user){
	        $conn2->message ( $user, $message );
	    }
	    $conn2->disconnect ();  
	  }
}
