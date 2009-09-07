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
class turnitinops extends object
{
	
	//required
	public $gmtime, $encrypt, $md5, $aid, $diagnostic, $uem, $ufn, $uln, $utp;
	
	//optional
	public $said, $upw, $dis;
	
	//unique ids
	public $uid, $cid, $assignid;
	
	//function specific
	public $fid, $fcmd;
	public $ctl, $cpw, $tem, $assign, $dtstart, $dtdue, $ainst, $newassign, $ptl, $pdata, $ptype, $pfn, $pln;
	public $oid, $newupw, $username;
	
	
	//config
	public $remote_host, $shared_secret_key;
	
	 /**
     * Constructor for the twitterlib class
     * @access public
     * @return VOID
     */
    public function init()
    {
        // Retrieve system configuration
        $this->_objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
        $this->aid = $this->_objSysConfig->getValue('accountid', 'turnitin');
        $this->remote_host = $this->_objSysConfig->getValue('apihost', 'turnitin');
        $this->shared_secret_key = $this->_objSysConfig->getValue('sharedkey', 'turnitin');       
        $this->uem = $this->_objSysConfig->getValue('email', 'turnitin');       
        $this->upw = $this->_objSysConfig->getValue('password', 'turnitin');       
        
      
        //setup defaults
       	$this->gmtime = $this->getGMT();
       	$this->encrypt=0;
		$this->diagnostic=0;
		
		//success codes
		$this->successCodes = array();
		$this->successCodes['2'] = array(20, 21, 22);
		$this->successCodes['1'] = array(10, 11);
    }
    
	/**
	 * Method to generate a an MD5 string
	 *
	 * @return unknown
	 */
	public function getMD5(){
		$md5string = 	$this->aid.
						$this->assign.
						$this->assignid.
						$this->cid.
						$this->cpw.
						$this->ctl.
						$this->diagnostic.
						$this->dis.
						$this->dtdue.
						$this->dtstart.
						$this->encrypt.
						$this->fcmd.
						$this->fid.
						$this->gmtime.
						$this->newassign.
						$this->newupw.
						$this->oid.
						$this->pfn.
						$this->pln.
						$this->ptl.
						$this->ptype.
						$this->said.
						$this->tem.
						$this->uem.
						$this->ufn.
						$this->uid.
						$this->uln.
						$this->upw.
						$this->utp.
						$this->shared_secret_key;
		//error_log($md5string);			
		return md5($md5string);
	}		
	
	/**
	 *Get the time in a formatted GMT sting
	 *
	 * @return string
	 */
	public function getGMT(){
		return substr(gmdate('YmdHi'), 0, -1);
	}	
	
	/**
	 * Method to get the returned xml result
	 * and format it into a readable message
	 * 
	 * @param string $xml
	 * @return array
	 */
	public function getXMLResult($xmlStr)
	{
		if($this->diagnostic == 0)
		{
			$xml = new SimpleXMLElement($xmlStr);
			$message = $xml->rmessage;
			$rcode = $xml->rcode;
			
			return array('message' => $message, 'code' => $rcode );
		}else {
			return $xmlStr;
		}
	}
	
	/**
	 * Method to get all the parameters for
	 * the url
	 *
	 * @return string
	 */
	public function getParams(){
		$url = "gmtime=".$this->gmtime;
		$url .= "&fid=".$this->fid;
		$url .= "&fcmd=".$this->fcmd;
		$url .= "&encrypt=".$this->encrypt;
		$url .= "&md5=".$this->getMD5();
		$url .= "&aid=".$this->aid;
		$url .= "&said=".$this->said;
		$url .= "&diagnostic=".$this->diagnostic;
		$url .= "&uem=".$this->uem;
		$url .= "&upw=".urlencode($this->upw);
		$url .= "&ufn=".urlencode($this->ufn);
		$url .= "&uln=".urlencode($this->uln);
		$url .= "&utp=".$this->utp;
		$url .= "&ctl=".urlencode($this->ctl);
		$url .= "&cpw=".urlencode($this->cpw);
		$url .= "&tem=".$this->tem;
		$url .= "&oid=".$this->oid;
		$url .= "&newupw=".urlencode($this->newupw);
		$url .= "&assign=".urlencode($this->assign);
		$url .= "&dis=".$this->dis;
		$url .= "&uid=".urlencode($this->uid);
		$url .= "&cid=".urlencode($this->cid);
		$url .= "&assignid=".urlencode($this->assignid);
		$url .= "&dtstart=".urlencode($this->dtstart);
		$url .= "&dtdue=".urlencode($this->dtdue);
		$url .= "&ptl=".urlencode($this->ptl);
		$url .= "&ptype=".urlencode($this->ptype);
		$url .= "&pdata=".urlencode($this->pdata);
		
		error_log($url);
		return $url;
	}
	
	/**
	 * Method to redirect the url
	 *
	 * @return unknown
	 */
	public function getRedirectUrl(){
		return $this->remote_host.'?'.$this->getParams();
	}
	
	/**
	 * Method to get the results from turnitin
	 *
	 */
	public function doGet(){
		header('location:'.$this->remote_host.'?'.$this->getParams());
	}
		
		
	/**
	 * To use the doPost function as written, CURL must be installed.
	 *
	 * @return result
	 */
	public function doPost($headers=""){
	  	
	  	$user_agent = "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)";
			
	  	 //get the proxy info if set
        $objProxy = $this->getObject('proxyparser', 'utilities');
        $proxyArr = $objProxy->getProxy();
            
		$ch = curl_init();
		//curl_setopt($ch, CURLOPT_POST,1);
		
		curl_setopt($ch, CURLOPT_URL,$this->remote_host);
		curl_setopt($ch, CURLOPT_POST, 1 );
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  2);
		curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);		
	
		if(!empty($proxyArr) && $proxyArr['proxy_protocol'] != '')
        {
			//setup proxy
			//curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
			curl_setopt($ch, CURLOPT_PROXYPORT, $proxyArr['proxy_port']);
			curl_setopt($ch, CURLOPT_PROXY, $proxyArr['proxy_protocol'].'://'.$proxyArr['proxy_host']);
        }
        $params = $this->getParams();
       // print $params;
        //die($params);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$params);
        ob_start();
		$result=curl_exec ($ch);
		curl_close ($ch);
			
		return $this->getXMLResult($result);		
	}
	
	
	/**
	 * Method to post an assessment to Turnitin
	 *
	 * @return boolean
	 */
	public function doPostAssessment($params)
	{
		$objProxy = $this->getObject('proxyparser', 'utilities');
        $proxyArr = $objProxy->getProxy();
            
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$params);
		curl_setopt($ch, CURLOPT_URL,$this->remote_host);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  2);
		curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		if(!empty($proxyArr) && $proxyArr['proxy_protocol'] != '')
        {
			//setup proxy
			//curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
			curl_setopt($ch, CURLOPT_PROXYPORT, $proxyArr['proxy_port']);
			curl_setopt($ch, CURLOPT_PROXY, $proxyArr['proxy_protocol'].$proxyArr['proxy_host']);
        }
		$result=curl_exec ($ch);
		curl_close ($ch);
	
		//return $this->getXMLResult($result);
		print $params;
		return $result;
	}
		    
    /**
     * Do a login with the details provided
     *
     * @return boolean
     */
    public function APILogin($params)
    {    	
    	$this->fcmd = 1;
    	$this->fcmd = 2;

		$this->uem= $params['email'];
		$this->ufn=$params['firstname'];
		$this->uln=$params['lastname'];
		$this->upw=$params['password'];		
    	return $this->doPost();
    	
    }
    
    /**
     * Method to create a Lecturer on Turnitin
     *
     * @param array $params
     */
    function createLecturer($params)
    {    
    	return $this->createUser($params, 2);
    }
    
     /**
     * Method to create a user on Turnitin
     *
     * @param array $params
     */
    function createStudent($params)
    {       	
    	return $this->createUser($params, 1);
    }
        
    /**
     * Method to create a user on Turnitin
     *
     * @param array $params
     * @param integer $type
     * 
     */
    function createUser($params, $type = 1)
    {
    	$this->fid = 1;
    	$this->fcmd = 2;
    	$this->utp = $type;
    	
    	$this->upw = $params['password'];    	
    	//$this->uid = $params['username'];    	
    	$this->ufn = $params['firstname'];
    	$this->uln = $params['lastname'];
    	$this->uem = $params['email'];    	
    	
    	return $this->doPost();
    }
    
    /**
     * Method for a student to be 
     * assigned to a class
     *
     * @param array $params
     * @return boolean
     */
    public function joinClass($params)
    {
    	$this->fid = 3;
    	$this->fcmd = 2;
    	$this->utp = 1;
    	    	
    	$this->upw = $params['password'];    	
    	//$this->uid = $params['username'];    	
    	$this->ufn = $params['firstname'];
    	$this->uln = $params['lastname'];
    	$this->uem = $params['email'];   
    	$this->tem = $params['instructoremail'];
    	$this->ctl = $params['classtitle'];    	
    	
    	return $this->doPost();
    }
    
    /**
     * Method to get the admin stats
     *
     * @param array $params
     * @return array
     */
    public function adminStats($params)
    {
    	$this->fid=12;
    	$this->fcmd = 1;
    	$this->utp = 3;
    	
    	return $this->doGet();
    }
    
    ///////////////////////// 
    /// LECTURER FUNCTIONS///
    /////////////////////////
    
    public function createClass($params)
    {
    	$this->fid = 2;
    	$this->fcmd = 2;
    	$this->utp = 2;
    	
    	$this->ctl = $params['classtitle'];
    	$this->cpw = $params['classpassword'];
    	//$this->uid = $params['username'];
    	//$this->cid = $params['classid'];
    	
    	//$this->upw = $params['password'];    	
    	//$this->uid = $params['username'];    	
    	//$this->ufn = $params['firstname'];
    	//$this->uln = $params['lastname'];
    	//$this->uem = $params['email'];   
    	//$this->tem = $params['instructoremail'];
    	
    	//var_dump($params);die;
    	return $this->doPost();
    }
    
    /**
     * Method to create an assessment
     *
     * @param array $params
     */
    public function createAssessment($params)
    {
    	$this->fid = 4;
    	$this->fcmd = 2;
    	$this->utp = 2;
    	    	
    	$this->ctl = $params['classtitle'];
    	//$this->cpw = $params['classpassword'];
    	//$this->uid = $params['username'];
    	//$this->cid = 2758586;//$params['classid'];
    	
    	
    		//$this->upw = $params['password'];    	
    	//$this->uid = $params['username'];    	
    	$this->ufn = $params['firstname'];
    	$this->uln = $params['lastname'];
    	$this->uem = $params['email'];   
    	$this->tem = $params['email'];
    	
    	$this->assign = $params['assignmenttitle'];
    	$this->ainst = $params['assignmentinstruct'];
    	$this->dtstart = $params['assignmentdatestart'];
    	$this->dtdue = $params['assignmentdatedue'];
  
    	$ret = $this->doPost();
    	
    	return $ret;
    }
    
    
    /**
     * Method to get a list of assessments
     *
     * @param array $params
     */
    public function listSubmissions($params)
    {
    	$this->fid = 10;
    	$this->fcmd = 2;
    	$this->utp = 2;
    	
    	$this->oid = '100461236';
    	$this->ctl = $params['classtitle'];
    	$this->assign = $params['assignmenttitle'];
    	//$this->assignid = $params['assignmentid'];
    	
    	//$this->upw = $params['password'];    	
    	//$this->uid = $params['username'];    	
    	$this->ufn = $params['firstname'];
    	$this->uln = $params['lastname'];
    	//$this->uem = $params['email'];  
    	
    	//var_dump($params);die;
    	return $this->doPost();
    }
    
    
    public function checkForSubmission($params)
    {
    	
    }
    
    ///////////////////////// 
    /// STUDENT FUNCTIONS////
    /////////////////////////
    
    public function subA($params)
    {
    	$this->fid = 1;
    	$this->fcmd = 2;
    	$this->utp = 1; //student
    	$this->diagnostic = 0;
    	
    	$this->upw = 'student';//$params['password'];    	
    	//$this->uid = $params['username'];    	
    	$this->ufn = 'Student';//$params['firstname'];
    	$this->uln = 'student';//$params['lastname'];
    	$this->uem = 'student@uwc.ac.za';//$params['email'];	
    	
    	$this->tem = 'elearning@uwc.ac.za';//$params['instructoremail'];
    	$this->assign = $params['assignmenttitle'];
    	$this->ctl = $params['classtitle'];
    	
    	
    	//$result = $this->doPost();
    
    	//if ($result->rcode == 11)
    	//{
    		$file = "/home/wesley/Downloads/ext-core-3.0/LICENSE.txt";
    		if (file_exists($file))
    		{   		
	    		print "logging success - doing the submit now";
	    		$this->fid = 5;
	    		$this->ptl = "new title";
	    		$this->ptype = 1;	
	    		$filename = "/home/wesley/Downloads/API_Manual.pdf";

				$content = shell_exec('pdftotext '.$filename.' -');    		
	    		$this->pdata = $content;
	    		
	    		$result = $this->doPost();
	    		print '<br>'.$result;
	    		//print '<br>'.$result->rmessage;
	    		//print '<br>'.$result->rcode;
    		} else {
    			print "file dont exist";
    		}
    	//} else {
    	//	print $result->rcode.'<br>'.$result->rmessage;
    	//}
    	
    	print '<br>Done!';
    	//$die($code);
    }
    
    public function getCode($xml)
    {    	
    	$tiixml = new SimpleXMLElement($xml);    	
    	return $tiixml->rcode;
    }
    /**
     * Method to submit an assessment
     *
     * @param array $params
     */
    public function submitAssessment($params)
    {/*
    	$this->fid = 5;
    	$this->fcmd = 2;
    	$this->utp = 1; //student
    	
    	$this->upw = $params['password'];    	
    	//$this->uid = $params['username'];    	
    	$this->ufn = $params['firstname'];
    	$this->uln = $params['lastname'];
    	$this->uem = $params['email'];	
    	
    	$this->tem = 'elearning@uwc.ac.za';//$params['instructoremail'];
    	$this->assign = $params['assignmenttitle'];
    	$this->ctl = $params['classtitle'];
    	
    	$this->ptl = 'my paper';//$params['papertitle'];
    	//$this->ptype = 1;//$params['papertype'];
    	//$this->pdata = $params['paperdata'];
    	//var_dump($params);die;
    	
    	//var_dump($this->getParams());die;
    	return $this->doPost();
    	*/
    	//var_dump($params);die;
    	$postData = array();

		//simulates <input type="file" name="file_name">
		//$postData[ 'file_name' ] = " [at] test.txt";
		//$postData[ 'submit' ] = "UPLOAD";
		$postData[ 'ufn' ] = $params['firstname'];
		$postData[ 'uem' ] = $params['email'];
		$postData[ 'uln' ] = $params['lastname'];
		$postData[ 'gmtime' ] = "";//gmdate();
		
		$postData[ 'ptl' ] = "my paper";
		$postData[ 'ptype' ] = "1";
		$postData[ 'pdata' ] = " this is sthe content of the paper";
		
		
		
		
		//$this->ptl = 'my paper';//$params['papertitle'];
    	//$this->ptype = 1;//$params['papertype'];
    	//$this->pdata = $params['paperdata'];
		print 'tryinto curl it';
		$url = "http://www.turnitin.com/api.asp";
		$user_agent = "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)";
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, $url );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POST, 1 );
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  2);
		curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		//seems no need to tell it enctype='multipart/data' it already knows
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData );
		
		if(!empty($proxyArr) && $proxyArr['proxy_protocol'] != '')
        {
			//setup proxy
			//curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
			curl_setopt($ch, CURLOPT_PROXYPORT, $proxyArr['proxy_port']);
			curl_setopt($ch, CURLOPT_PROXY, $proxyArr['proxy_protocol'].'://'.$proxyArr['proxy_host']);
        }
		
		$response = curl_exec( $ch );
		
		curl_close ($ch);
	print 'curl done';
		//return $this->getXMLResult($result);
		var_dump($response);
		return $response;
		//where test.txt is a file in the same directory!"
    }
    
    public function getReport($params)
    {
    	$this->fid = 6;
    	$this->fcmd = 1;
    	$this->diagnostic = 1;
    	$this->oid = $this->getParam('objectid');
    	$this->utp = 1; //student
    	$this->diagnostic = 0;
    	
    	$this->upw = 'student';//$params['password'];    	
    	//$this->uid = $params['username'];    	
    	$this->ufn = 'Student';//$params['firstname'];
    	$this->uln = 'student';//$params['lastname'];
    	$this->uem = 'student@uwc.ac.za';//$params['email'];	
    	
    	$this->tem = 'elearning@uwc.ac.za';//$params['instructoremail'];
    	$this->assign = $params['assignmenttitle'];
    	$this->ctl = $params['classtitle'];
    	
    	return $this->doGet();
    	//print $this->doPost();
    	$result = $this->doPost();
    	print $result;//->rmessage;
    }
    
    public function deleteSubmission($params)
    {
    	
    }
    
    public function viewSubmission($param)
    {
    	
    }
    
    
    public function isSuccess($fid, $code)
    {
    	return in_array($code, $this->successCodes[$fid]);
    }
    
}
