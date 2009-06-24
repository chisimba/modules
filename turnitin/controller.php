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
	}
	
	/**
	 * The standard dispatch funtion
	 *
	 * @param unknown_type $action
	 */
	
	public function dispatch($action)
	{
		
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
			case 'submitpaper':
				break;
				
			case 'returnreport':
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
				
			default:
				//print $this->objTOps->APILogin();
				echo "invalid action";
				break;
				
			case 'callback':
				echo "This is the CALLBACK ...<BR>";
				$m = var_export($_REQUEST, true);
				error_log($m);
				var_dump($_REQUEST);
				break;
		}
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
			
			$params['password'] = 'nitsckie';//$username;    	
			$params['username'] = 'wesleynitsckie';
			$params['firstname'] = 'Wesley';
			$params['lastname'] = 'Nitsckie';
			$params['email'] = 'wesleynitsckie@gmail.com';			
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
		
		$params['assignmenttitle'] = "some assessment title";
    	$params['assignmentinstruct'] = " please complete the assessment";
    	$params['assignmentdatestart'] = "20090623";
    	$params['assignmentdatedue'] = "20090624";    	
    	$params['assignmendatedue'] = "theid";    	
    	
    	return $params;
	}
}