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
            			$file = file($filedata);
            			// loop through the file array and do the inserts
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
            	
            case 'rcpsend':
            	
            	
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