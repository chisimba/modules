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
			$this->sysConfig = $this->getObject('dbsysconfig', 'sysconfig');
			$this->conn = new XMPPHP_XMPP('talk.google.com', 5222, 'fsiu123', 'fsiu2008', 'xmpphp', 'gmail.com', $printlog=FALSE, $loglevel=XMPPHP_Log::LEVEL_ERROR );
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