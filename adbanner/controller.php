<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
	die("You cannot view this page directly");
}
// end security check

/**
 * The controller for the adbanner module
 *
 * @package adbanner
 * @category chisimba
 * @copyright AVOIR
 * @license GNU GPL
 * @author Charl Mert
 */

class adbanner extends controller
{
	/**
	 * Class Constructor
	 *
	 * @access public
	 * @return void
	 */
	public function init()
	{
		try {       

		} catch (customException $e){
			throw customException($e->getMessage());
			exit();
		}	    
	}

	/**
	 *
	 * This is a method that overrides the parent class to stipulate whether
	 * the current module requires login. Having it set to false gives public
	 * access to this module including all its actions.
	 *
	 * @access public
	 * @return bool FALSE
	 */
	public function requiresLogin()
	{
		return FALSE;
	}


	/**
	 * Method to handle actions from templates
	 * 
	 * @access public
	 * @param string $action Action to be performed
	 * @return mixed Name of template to be viewed or function to call
	 */
	public function dispatch()
	{
		$action = $this->getParam('action');
		//$this->setLayoutTemplate('cms_layout_tpl.php');

		switch ($action) {

			default:

				return 'home_tpl.php';

		}
	}


}

?>
