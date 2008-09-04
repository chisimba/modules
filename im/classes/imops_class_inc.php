<?php
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
// end security check

/**
 * Class to handle im elements
 *
 * @author    Paul Scott
 * @copyright GNU/GPL, AVOIR
 * @package   blog
 * @access    public
 */
class imops extends object
{

	/**
     * Description for public
     * @var    object
     * @access public
     */
	public $objConfig;

	public $conn;
	// public $objXMPPLog;

	/**
     * Standard init function called by the constructor call of Object
     *
     * @param  void  
     * @return void  
     * @access public
     */
	public function init()
	{
		try {

			$this->objConfig = $this->getObject('altconfig', 'config');
			$this->objLanguage = $this->getObject('language', 'language');
			// Get the sysconfig variables for the Jabber user to set up the connection.
			$this->objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
            $this->jserver = $this->objSysConfig->getValue('jabberserver', 'im');
            $this->jport = $this->objSysConfig->getValue('jabberport', 'im');
			$this->juser = $this->objSysConfig->getValue('jabberuser', 'im');
			$this->jpass = $this->objSysConfig->getValue('jabberpass', 'im');
			$this->jclient = $this->objSysConfig->getValue('jabberclient', 'im');
			$this->jdomain = $this->objSysConfig->getValue('jabberdomain', 'im');
			
			$this->conn = new XMPPHP_XMPP($this->jserver, intval($this->jport), $this->juser, $this->jpass, $this->jclient, $this->jdomain, $printlog=FALSE, $loglevel=XMPPHP_Log::LEVEL_ERROR );
		}
		catch(customException $e) {
			echo customException::cleanUp();
			die();
		}
	}

	public function sendMessage($to, $message)
	{
		try {
			$this->conn->connect();
			$this->conn->processUntil('session_start');
			$this->conn->presence();
			// send the message
			$this->conn->message($to, $message);
			// disconnect
			$this->conn->disconnect();
		} catch(customException $e) {
			customException::cleanUp();
			exit;
		}
	}

}
?>