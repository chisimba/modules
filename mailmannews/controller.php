<?php
/**
 * Mailman Newsletter Controller
 * 
 * controller class for mailman newsletter package
 * 
 * PHP version 5
 * 
 * The license text...
 * 
 * @category  Chisimba
 * @package   mailmannews
 * @author    Paul Scott <pscott@uwc.ac.za>
 * @copyright 2008 Pual Scott
 * @license   gpl
 * @version   CVS: $Id$
 * @link      http://avoir.uwc.ac.za
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
// end security check


/**
 * Controller class for the mailman newsletter module
 * 
 * Controller class for the mailman newsletter module
 * 
 * @category  Chisimba
 * @package   mailmannews
 * @author    Paul Scott <pscott@uwc.ac.za>
 * @copyright 2008 Paul Scott
 * @license   gpl
 * @version   Release: @package_version@
 * @link      http://avoir.uwc.ac.za
 */
class mailmannews extends controller
{

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
	
	public $objMailmanSignup;
	public $objUser;
	
	/**
     * Constructor method to instantiate objects and get variables
     */
    public function init()
    {
        try {
            $this->objLanguage = $this->getObject('language', 'language');
            $this->objConfig = $this->getObject('altconfig', 'config');
            $this->objMailmanSignup = $this->getObject('mailmansignup');
            $this->objUser = $this->getObject('user', 'security');
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
            	$this->requiresLogin(FALSE);
            	return 'subscribe_tpl.php';
            	break;
            	
            case 'subscribe':
            	$this->requiresLogin(FALSE);
            	$email = $this->getParam('email');
            	if($this->objMailmanSignup->subscribeToMailman($email) === TRUE)
            	{
            		return 'welcome_tpl.php';
            	}
            	else {
            		return 'error_tpl.php';
            	}
            	//echo $email; die();
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