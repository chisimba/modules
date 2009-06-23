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
 * @author    Charl van Niekerk <charlvn@charlvn.za.net>
 * @copyright 2008 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id: twitterlib_class_inc.php 13185 2009-04-14 14:41:58Z paulscott $
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
        $this->upw = 'elearn2009';//$this->_objSysConfig->getValue('password', 'turnitin');       
        
       
      //  $this->uem= 'wesleynitsckie@gmail.com';
		$this->ufn='Wesley';
		$this->uln='Nitsckie';
	//	$this->upw='364t648y';
		
        //setup defaults
       	$this->gmtime = $this->getGMT();
       	$this->encrypt=0;
		$this->diagnostic=1;
    }
    
	/**
	 * Method to generate a an MD5 string
	 *
	 * @return unknown
	 */
	public function getMD5(){
		$md5string = $this->aid.$this->assign.$this->assignid.$this->cid.$this->cpw.$this->ctl.$this->diagnostic.$this->dis.$this->dtdue.$this->dtstart.$this->encrypt.$this->fcmd.$this->fid.$this->gmtime.$this->newassign.$this->newupw.$this->oid.$this->pfn.$this->pln.$this->ptl.$this->ptype.$this->said.$this->tem.$this->uem.$this->ufn.$this->uid.$this->uln.$this->upw.$this->utp.$this->shared_secret_key;
		error_log($md5string);			
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
		$xml = new SimpleXMLElement($xmlStr);
		$message = $xml->returndata->message;
		$rcode = $xml->returndata->rcode;
		
		return array('message' => $message, 'rcode' => $rcode );
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
	public function doPost(){
	  	$params = $this->getParams();
	  	$user_agent = "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)";
			
	  	 //get the proxy info if set
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
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
			curl_setopt($ch, CURLOPT_PROXYPORT, "8080");
			curl_setopt($ch, CURLOPT_PROXY, "http://cache.uwc.ac.za");
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
    public function APILogin()
    {
    	var_dump($this->aid);
    	$this->fcmd = 1;
    	$this->fcmd = 2;
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
    	$this->uid = $params['username'];    	
    	$this->ufn = $params['firstname'];
    	$this->uln = $params['lastname'];
    	$this->uem = $params['email'];    	
    	
    	return $this->doPost();
    }
    ///////////////////////// 
    /// LECTURER FUNCTIONS///
    /////////////////////////
    
    public function createClass($params)
    {
    	$this->fid = 2;
    	$this->fcmd = 2;
    	$this->utp = 2;
    	$this->ctl = $para['contexttitle'];
    	//$this->cpw = $para['contexttitle'];
    	$this->uid = $para['username'];
    	$this->cid = $para['contextcode'];
    	
    	return $this->doPost();
    }
    
    /**
     * Method to create an assessment
     *
     * @param array $params
     */
    public function createAssessment($params)
    {
    	
    }
    
    
    /**
     * Method to get a list of assessments
     *
     * @param array $params
     */
    public function listSubmissions($params)
    {
    	
    }
    
    
    public function checkForSubmission($params)
    {
    	
    }
    
    ///////////////////////// 
    /// STUDENT FUNCTIONS////
    /////////////////////////
    /**
     * Method to submit an assessment
     *
     * @param array $params
     */
    public function submitAssessment($params)
    {
    	
    }
    
    public function getReport($params)
    {
    	
    }
    
    public function deleteSubmission($params)
    {
    	
    }
    
    public function viewSubmission($param)
    {
    	
    }
    
}