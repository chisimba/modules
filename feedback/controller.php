<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
	die("You cannot view this page directly");
}
// end security check
class feedback extends controller
{
	public $objFb;
	public $objLog;
	public $objLanguage;
	public $objDbFb;

	/**
     * Constructor method to instantiate objects and get variables
     */
	public function init()
	{
		try {
			$this->objLanguage = $this->getObject('language', 'language');
			$this->objUser = $this->getObject('user', 'security');
			$this->objFb = $this->getObject('fbform');
			$this->objDbFb = $this->getObject('dbfeedback');
			//Get the activity logger class
			$this->objLog = $this->newObject('logactivity', 'logger');
			//Log this module call
			$this->objLog->log();
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
			case 'save':
				$this->requiresLogin(FALSE);
				try {
					$captcha = $this->getParam('request_captcha');
					$userid = $this->objUser->userId();
					$fbname = $this->getParam('fbname');
					$fbemail = $this->getParam('fbemail');
					$fbww = $this->getParam('fbww');
					$fbnw = $this->getParam('fbnw');
					$fblo = $this->getParam('fblo');
					$fbsp = $this->getParam('fbsp');
					$fbee = $this->getParam('fbee');
					$fbw = $this->getParam('fbw');
					
					if (md5(strtoupper($captcha)) != $this->getParam('request_captcha') || !isset($captcha))
					{
						$insarr = array('userid' => $userid, 'fbname' => $fbname, 'fbemail' => $fbemail, 'fbww' => $fbww, 'fbnw' => $fbnw, 'fblo' => $fblo, 'fbsp' => $fbsp, 'fbee' => $fbee, 'fbw' => $fbw);
						$msg = 'badcaptcha';
						$this->setVarByRef('msg', $msg);
						$this->setVarByRef('insarr', $insarr);
						return 'form_tpl.php';
					}
					
					elseif(!isset($fbname) && !isset($fbemail))
					{
						$insarr = array('userid' => $userid, 'fbname' => $fbname, 'fbemail' => $fbemail, 'fbww' => $fbww, 'fbnw' => $fbnw, 'fblo' => $fblo, 'fbsp' => $fbsp, 'fbee' => $fbee, 'fbw' => $fbw);
						$msg = 'nodata';
						$this->setVarByRef('msg', $msg);
						$this->setVarByRef('insarr', $insarr);
						return 'form_tpl.php';
					}
					else {
						//insert to db
						$insarr = array('userid' => $userid, 'fbname' => $fbname, 'fbemail' => $fbemail, 'fbww' => $fbww, 'fbnw' => $fbnw, 'fblo' => $fblo, 'fbsp' => $fbsp, 'fbee' => $fbee, 'fbw' => $fbw);
						$this->objDbFb->saveRecord($insarr);
						//return a thanks template
						$msg = 'save';
						$this->setVarByRef('msg', $msg);
						return 'thanks_tpl.php';
					}
				}
				catch(customException $e) {
					customException::cleanUp();
					exit;
				}
				break;
		}
	}
	
	    /**
     * Ovveride the login object in the parent class
     *
     * @param void
     * @return bool
     * @access public
     */
    public function requiresLogin()
    {
        return FALSE;
    }
}
?>