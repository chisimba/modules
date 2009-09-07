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
		//return "lectmain_tpl.php";
		$this->setVar('errorMessage','');
		$objContextGroups = $this->getObject('managegroups', 'contextgroups');	
		if ($this->objUser->isAdmin () || $objContextGroups->isContextLecturer()) 
		{
			//create user on TII if he does not exist
			$res = $this->objTOps->createLecturer($this->getUserParams());

			//create the course on TII if it does not exist and 
			//make this user the instructor					
			
			$res =  $this->objTOps->createClass(array_merge(
													$this->getUserParams(), 
													$this->getClassParams()));
			
			if(is_array($res))
			{
				if(!$this->objTOps->isSuccess(2, $res['code']))
				{
					$this->setVar('errorMessage',$res['message']);
					error_log($res['message']);
				}
			}
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
		$params = array();
		$params['password'] = 'nitsckie';//$username;    	
		$params['username'] = $this->objUser->userName();
		$params['firstname'] =  $this->objUser->getFirstname(); 
		$params['lastname'] =  $this->objUser->getSurname();
		$params['email'] = $this->objUser->email();
		
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
			$params['classpassword'] = 'classpass';
			$params['instructoremail'] = '';//$this->objUser->email();
		}
		
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
		error_log(var_export($assParams, true));
		//$rcode = array('rcode'=>46, 'message' => 'bogus message');
		$res = $this->objTOps->createAssessment(array_merge(
														$this->getUserParams(), 
														$this->getClassParams(),
														$assParams));
		error_log(var_export($res, true));
		if(in_array($res['code'], $successcodes ))
		{
			//add to local database
			if($this->objTAssDB->addAssignment($this->objDBContext->getContextCode(), $assParams))
			{
				return json_encode(array('success' => 'true', 'msg' => 'A new assigment entitled <b>"'.$this->getParam('title').'"</b> was successfully created'));
			} else {
				return json_encode(array('success' => false, 'msg' => 'The assigment was create on Turnitin but an error occurred while inserting the details into the database'));
			}
		} else {
			
			return json_encode(array('success' => false, 'msg' => $res['message']));
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
	  
	public function doFileUpload()
	{
		//var_dump($_FILES);
		$allowedExtensions = array("txt","csv","htm","html","xml",
								    "css","doc","xls","rtf","ppt","pdf","swf","flv","avi",
								    "wmv","mov","jpg","jpeg","gif","png");
								    
								    
    	$file = $_FILES["file"];
		$allowedSize = $file['size'];
		if ($file["error"] > 0)
		{
			return json_encode(array('success' => 'false', 'msg' => 'Error: ' . $this->fileUploadErrorMessage($_FILES["file"]["error"]) ));
		} else {
			//if (!in_array(end(explode(".", strtolower($file['name']))),$allowedExtensions)) {} 
			$extension = end(explode(".", strtolower($file['name'])));
		//	var_dump($extension);
			$filename = $file['tmp_name'];
			switch ($extension){
				case 'pdf':
					$content = shell_exec('pdftotext '.$filename.' -'); 
					break;
				case 'doc':
				case 'docx':
					$content = shell_exec('antiword '.$filename.' -');
					break;
				case 'odt':
					$content = shell_exec('odt2txt '.$filename);
					break;
				default:
					$content = file_get_contents($filename, true);
					break;
			}
			//hand the content off to TII 
			//var_dump($content);
			$msg = "<br /><br />Upload: " . $file["name"] . "<br />";
		    //$msg .= "Type: " . $file["type"] . "<br />";
		    $msg .= "Size: " . ($file["size"] / 1024) . " Kb<br />";
			//$content = shell_exec('pdftotext '.$filename.' -'); 
			return '{"success":"true", "msg": "'.htmlentities($msg).'"}';
		}
	
	}
	
	/**
	 * Error codes explained
	 *
	 * @param unknown_type $error_code
	 * @return unknown
	 */
	function fileUploadErrorMessage($error_code) {
	    switch ($error_code) {
	        case UPLOAD_ERR_INI_SIZE:
	            return 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
	        case UPLOAD_ERR_FORM_SIZE:
	            return 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
	        case UPLOAD_ERR_PARTIAL:
	            return 'The uploaded file was only partially uploaded';
	        case UPLOAD_ERR_NO_FILE:
	            return 'No file was uploaded';
	        case UPLOAD_ERR_NO_TMP_DIR:
	            return 'Missing a temporary folder';
	        case UPLOAD_ERR_CANT_WRITE:
	            return 'Failed to write file to disk';
	        case UPLOAD_ERR_EXTENSION:
	            return 'File upload stopped by extension';
	        default:
	            return 'Unknown upload error';
	    } 
	}
	
	public function isTIIavailable()
	{		
		$result = $this->objTOps->APILogin(array('firstname' => $this->objUser->getFirstname(),
												'lastname' => $this->objUser->getSurname(),
												'password' => '123456',
												'email' => $this->objUser->email()							
												));				
		
		return true;
	}
	
	
}
