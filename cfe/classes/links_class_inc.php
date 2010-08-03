
<?php
/**
 /* This class create links to a specified module
 * 
 * PHP version 5
 * 
 * 
 * @category  Chisimba
 * @package   cfe
 * @author    JCSE <JCSE>
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

/*This class create a link to a specified module. Pass the name of the module and the text that should appear*/
class links extends object
{
    /**
     * The module to link to. This links to a template. The name should correspond to that written in controller
     * @var    string
     * @access public
     */
	public $linkModule;
    /**
     * The text that appear in the link
     * @var    string
     * @access public
     */
	public $texting;
   /**
     * The module i which the linkModule is found
     * @var    string
     * @access public
     */
	public $mod;

   /**
    * Constructor
    */
	public function init()
    	{
            	$this->loadClass('link', 'htmlelements');
         	$this->objModules = $this->getObject('modules', 'modulecatalogue');
		     
        }
    
    /**
     * Method to display the link
     * @var string $linkModule: The module to link to. This should correspond to the function written in controller
     * @var string $texting: The text to appear as a link
     * @var string $mod: The module in which linkModule exists
     * @return string
     */
	public function Link($linkModule, $texting, $mod)
	{
		//$this->buildLink();	
		$objUser = $this->getObject('user', 'security');
        	$userIsLoggedIn = $objUser->isLoggedIn();

        	$menuOptions = array(
            	array('action' => 'logoff', 'text' => 'Logout', 'actioncheck' => array(), 'module' => 'security', 'status' => 'loggedin'), );

        	$usedDefault = FALSE;
        	$str = '';

       		foreach ($menuOptions as $option) {
           	 // First Step, Check whether item will be added to menu
           	 // 1) Check Items to be Added whether user is logged in or not
           	 if ($option['status'] == 'both') {
                $okToAdd = TRUE;

                // 2) Check Items to be added only if user is not logged in
           	 } else if ($option['status'] == 'login' && !$userIsLoggedIn) {
                $okToAdd = TRUE;

                // 3) Check Items to be added only if user IS logged in
            	} else if ($option['status'] == 'loggedin' && $userIsLoggedIn) {
                $okToAdd = TRUE;

                // 4) Check if User is Admin
            	} else if ($option['status'] == 'admin' && $objUser->isAdmin() && $userIsLoggedIn) {
                $okToAdd = TRUE;
           	} else {
                $okToAdd = FALSE; // ELSE FALSE
           	}

           	 // IF Ok To Add
            	if ($okToAdd) {

                // Do a check if current action matches possible actions
                if (count($option['actioncheck']) == 0) {
                    $actionCheck = TRUE; // No Actions, set TRUE, to enable all actions and fo module check
                } else {
                    $actionCheck = in_array($this->getParam('action'), $option['actioncheck']);
                }

                // Check whether Module of Link Matches Current Module
                $moduleCheck = ($this->getParam('module') == $option['module']) ? TRUE : FALSE;

                // If Module And Action Matches, item will be set as current action
                $isDefault = ($actionCheck && $moduleCheck) ? TRUE : FALSE;

                if ($isDefault) {
                    $usedDefault = TRUE;
                }

                // Add to Navigation
                $str .= $this->generateItem($option['action'], $option['module'], $option['text'], $isDefault);
            	}
        	}

        	// Check whether Navigation has Current/Highlighted item
        	// Invert Result for Home Link
        	$usedDefault = $usedDefault ? FALSE : TRUE;
	
		$this->linkModule = $linkModule;
		$this->texting = $texting;
		$this->mod = $mod;
		$tbar = $this->generateItem($this->linkModule, $this->mod, $this->texting, $usedDefault);
                
        	return $tbar;
	
}

    /**
     * Method to generate the link
     */
	private function generateItem($action='', $module='webpresent', $text, $isActive=FALSE) {
        	switch ($module) {
        	    case '_default' : $isRegistered = TRUE;
			break;
case 'cfe' : $isRegistered = TRUE;
			break;
        	    default: $isRegistered = $this->objModules->checkIfRegistered($module);
        	        break;
        	}

        	if ($isRegistered) {
        	    $link = new link($this->uri(array('action' => $action), $module));
        	    $link->link = $text;
	
        	    //$isActive = $isActive ? ' id="createVenture"' : '';
	
        	    return $link->show();
        	} else {
        	    return '';
        	}
 }
	
}
?>
