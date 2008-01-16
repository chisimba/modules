<?php

/**
 * Short description for file
 * 
 * Long description (if any) ...
 * 
 * PHP version 5
 * 
 * The license text...
 * 
 * @category  Chisimba
 * @package   adm
 * @author    Administrative User <pscott@uwc.ac.za>
 * @copyright 2007 Administrative User
 * @license   gpl
 * @version   CVS: $Id$
 * @link      http://avoir.uwc.ac.za
 * @see       References to other sections (if any)...
 */
// security check - must be included in all scripts
if (!
/**
 * Description for $GLOBALS
 * @global unknown $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 */
$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
ini_set("max_execution_time", -1);
// end security check


/**
 * Short description for class
 * 
 * Long description (if any) ...
 * 
 * @category  Chisimba
 * @package   adm
 * @author    Administrative User <pscott@uwc.ac.za>
 * @copyright 2007 Administrative User
 * @license   gpl
 * @version   Release: @package_version@
 * @link      http://avoir.uwc.ac.za
 * @see       References to other sections (if any)...
 */
class adm extends controller
{

    /**
     * Description for public
     * @var    object
     * @access public
     */
	public $objAdmOps;

    /**
     * Description for public
     * @var    unknown
     * @access public 
     */
	public $objLanguage;

    /**
     * Description for public
     * @var    unknown
     * @access public 
     */
	public $objConfig;
	
	public $objrpc;
	public $method;
	public $objRpcClient;
	/**
     * Constructor method to instantiate objects and get variables
     */
    public function init()
    {
        try {
            $this->objLanguage = $this->getObject('language', 'language');
            $this->objConfig = $this->getObject('altconfig', 'config');
            $this->objAdmOps = $this->getObject('admops');
            $this->objDbAdm = $this->getObject('dbadm');
            $this->objrpc = $this->getObject('admrpcclient');
            $this->objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
            $this->objRpcClient = $this->getObject('admrpcclient');
            
            $reg = $this->objSysConfig->getValue('adm_servregistered', 'adm');
            if($reg == 'FALSE')
            {
            	// register the server
            	$this->objrpc->regServer();
            	// set the config to true
            	$this->objSysConfig->changeParam('adm_servregistered', 'adm', 'TRUE');
            }
            // grab an updated server list from the package server
            $data = $this->objrpc->updateServerList();
            $data = simplexml_load_string($data);
            $data = base64_decode($data->string);
            $list = $this->objConfig->getcontentBasePath().'adm/adm.xml';
            if(!file_exists($this->objConfig->getcontentBasePath().'adm/'))
            {
            	mkdir($this->objConfig->getcontentBasePath().'adm/');
            	chmod($this->objConfig->getcontentBasePath().'adm/',0777);
            }
            if(file_exists($list))
            {
            	unlink($list);
            	file_put_contents($list, $data);
            }
            else {
            	file_put_contents($list, $data);
            }
        }
        catch(customException $e) {
            echo customException::cleanUp();
            die();
        }
    }
    /**
     * Method to process actions to be taken
     *
     * @param string $action String indicating action to be taken
     */
    public function dispatch($action = Null)
    {
        switch ($action) {
            default:
            	$this->method = $this->objSysConfig->getValue('adm_transport', 'adm');
            	if($this->method == 'rpc')
            	{
            		$this->nextAction('rpcupdate');
            	}
            	elseif($this->method == 'email')
            	{
            		$this->nextAction('emailupdate');
            	}
            	else {
            		die($this->objLanguage->languageText("mod_adm_unknowntrans", "adm"));
            	}
            	echo "hello"; 
            	break;
            	
            case 'maillog':
            	$this->requiresLogin(FALSE);
            	echo $this->objAdmOps->sendLog();
            	break;
            	
            case 'parsemail':
            	$this->requiresLogin(FALSE);
            	// grab the mail off the mail server and parse the heck out of it
            	$status = $this->objAdmOps->parsemail();
            	//var_dump($status); die();
            	foreach($status as $filedata)
            	{
            		if(file_exists($filedata))
            		{
            			//echo $filedata;
            			$file = file($filedata);
            			// loop through the file array and do the inserts
            			//print_r($file);
            			foreach($file as $str)
            			{
            				preg_match_all('/\[SQLDATA\](.*)\[\/SQLDATA\]/U', $str, $results, PREG_PATTERN_ORDER);
        					$counter = 0;
        					foreach ($results[1] as $item)
        					{
            					$stmt = $item;
            					$counter++;
            				    //echo $stmt."<br />";
            				    // insert into the db
            				    $this->objDbAdm->insertSqldata($stmt);
            				                				    
        					}
            			}
            			// unlink the file as we are now done with it
            			unlink($filedata);
            		}
            	}
            	die();
            	
            case 'rpcupdate':
            	// echo "RPC method selected!";
            	$list = $this->objConfig->getcontentBasePath().'adm/adm.xml';
            	$xmlstr = file_get_contents($list);
				$xml = new SimpleXMLElement($xmlstr);
				foreach($xml->server as $server)
				{
					$url = $server->serverapiurl;
					$url = str_replace('index.php?module=api', '', $url);
					$endpoint = 'index.php?module=api';
					$comps = explode('/',$url);
					$comps = array_filter($comps);
					$apiurl = $comps[2];
					unset($comps[0]);
					unset($comps[2]);
					$endpoint = '/';
					foreach($comps as $com)
					{
						$endpoint .= $com."/";
					}
					// bang off a message asking for the sql log file for each server
					$endpoint = $endpoint."index.php?module=api";
					$lastup = $this->objrpc->getLastOn($endpoint, $apiurl, $server->servername);
					// ok so we have the last time we updated from this server.
					$lastup = strip_tags($lastup);
					$lastup = trim($lastup);
					if($lastup == 'never')
					{
						// go get a full update - this server has never been updated here!
						$data = $this->objRpcClient->getLog($endpoint, $apiurl, $server->servername);
						$data = strip_tags($data);
						$data = base64_decode($data);
						file_put_contents($this->objConfig->getcontentBasePath().'adm/'.$server->servername.'.log', $data);
					}
					
				}
            	
            	break;
            	
            case 'emailupdate':
            	echo 'email method in progress!';
            	break;
        }
    }
    
   /**
    * Overide the login object in the parent class
    *
    * @param  void  
    * @return bool  
    * @access public
    */
	public function requiresLogin($action)
	{
       return FALSE;
	}

}
?>