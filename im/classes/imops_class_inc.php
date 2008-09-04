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
	
	// public $objXMPP;
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
		}
		catch(customException $e) {
			echo customException::cleanUp();
			die();
		}
	}
	
	
	
}
?>