<?php
/**
 * Methods which intergrates the Turnitin API
 * into the Chisimba framework
 * 
 * This module requires a valid Turnitin account/license which can 
 * purhase at http://www.turnitin.com
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
 * @category  Chisimba
 * @package   turnitin
 * @author    Wesley Nitsckie
 * @copyright 2008 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @link      http://avoir.uwc.ac.za
 */

// security check - must be included in all scripts
if (!
/**
 * The $GLOBALS is an array used to control access to certain constants.
 * Here it is used to check if the file is opening in engine, if not it
 * stops the file from running.
 *
 * @global entry point $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 *
 */
$GLOBALS['kewl_entry_point_run'])
{
        die("You cannot view this page directly");
}
// end security check


/**
 * Class to supply an easy API for use from this module or even other modules.
 * @author Wesley Nitsckie
 * @package turnitin
 */
class utilities extends object
{
	
	/**
	 * The constructor
	 *
	 */
	public function init()
	{
		 try{		
			
			$this->objTOps = $this->getObject('turnitinops');
			$this->objUser = $this->getObject('user', 'security');
			$this->objDBContext = $this->getObject('dbcontext', 'context');
			$this->objForms = $this->getObject('forms');
			$this->objTAssDB = $this->getObject('turnitindbass');
			
			// Supressing Prototype and Setting jQuery Version with Template Variables
			$this->setVar('SUPPRESS_PROTOTYPE', true); //Can't stop prototype in the public space as this might impact blocks
			//$this->setVar('SUPPRESS_JQUERY', true);
			//$this->setVar('JQUERY_VERSION', '1.3.2');
		
		}catch(Exception $e){
            throw customException($e->getMessage());
            exit();
        }
	}
	
	
	public function formmatStudentAssessments($recs)
	{
		if (!$recs)
		{
			return false;
		}
		
		$cnt = 0;
		foreach($recs as $rec)
		{
			$assRec[] = array("title" => $rec['title'],
							"score" => $rec['score'],
							"duedate" => $rec['duedate'],
							"assid" => $rec['duedate'],
							"contextcode" => $rec['contextcode'],
							"assid" => $rec['assid']);
			$cnt++;
		}
		
		$arr['totalCount'] = $cnt;
		$arr['assignments'] = $assRec;
		return json_encode($arr);
	}
	
	/**
	 * Format the submissions
	 *
	 * @return array
	 */
	public function formatSubmissions(){
		
		$submission = array("username" => "nitsckie",
							"firstname" => "Wesley",
							"lastname" => "Nitsckie",
							"title" => "The title of the paper",
							"score" => "50",
							"dateposted" => "12-12-2009"
							);
		$submissions = array();
		
		//for($i=0; $i++;$i>10)
		$i=0;
		while ($i<100) 
		{
			//array_push($submissions, $submission);
			$submissions[$i] =  array("username" => "nitsckie$i",
							"firstname" => "Wesley",
							"lastname" => "Nitsckie",
							"title" => "The title of the paper",
							"score" => $i,
							"dateposted" => "12-12-2009"
							);
			$i++;
		}
		
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
