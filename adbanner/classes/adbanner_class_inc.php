<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
	die("You cannot view this page directly");
}
// end security check

/**
 * @package cmsadmin
 * @category chisimba
 * @copyright AVOIR
 * @license GNU GPL
 * @author Charl Mert
 */

class adbanner extends object
{
	/**
	 * The context  object
	 *
	 * @access private
	 * @var object
	 */
	protected $_objContext;	

	/**
	 * The inContextMode  object
	 *
	 * @access private
	 * @var object
	 */
	protected $inContextMode;	

	/**
	 * The sections  object
	 *
	 * @access private
	 * @var object
	 */
	protected $_objSections;

	/**
	 * The Content object
	 *
	 * @access private
	 * @var object
	 */
	protected $_objContent;

	/**
	 * The Skin object
	 *
	 * @access private
	 * @var object
	 */
	protected $objSkin;

	/**
	 * The Content Front Page object
	 *
	 * @access private
	 * @var object
	 */
	protected $_objFrontPage;

	/**
	 * The User object
	 *
	 * @access private
	 * @var object
	 */
	protected $_objUser;

	/**
	 * The user model
	 *
	 * @access private
	 * @var object
	 */
	protected $_objUserModel;

	/**
	 * The config object
	 *
	 * @access private
	 * @var object
	 */
	protected $_objConfig;

	/**
	 * The blocks object
	 *
	 * @access private
	 * @var object
	 */
	protected $_objBlocks;

	/**
	 * Feature box object
	 *
	 * @var object
	 */
	public $objFeatureBox;

	/**
	 * The security object
	 *
	 * @access public
	 * @var object
	 */
	public $_objSecurity;


	/**
	 * Class Constructor
	 *
	 * @access public
	 * @return void
	 */
	public function init()
	{
		try {
			$this->_objQuery =  $this->newObject('jquery', 'jquery');
			$this->_objConfig =$this->newObject('altconfig', 'config');
			$this->_objSysConfig =$this->newObject('dbsysconfig', 'sysconfig');
			$this->_objUser =$this->newObject('user', 'security');
			$this->objLanguage =$this->newObject('language', 'language');
			$this->objFeatureBox = $this->newObject('featurebox', 'navigation');
			$this->objModule=&$this->getObject('modules','modulecatalogue');
			$this->objDateTime = $this->getObject('dateandtime', 'utilities');

			$this->loadClass('textinput', 'htmlelements');
			$this->loadClass('checkbox', 'htmlelements');
			$this->loadClass('radio', 'htmlelements');
			$this->loadClass('dropdown', 'htmlelements');
			$this->loadClass('form', 'htmlelements');
			$this->loadClass('button', 'htmlelements');
			$this->loadClass('link', 'htmlelements');
			$this->loadClass('label', 'htmlelements');
			$this->loadClass('hiddeninput', 'htmlelements');
			$this->loadClass('textarea','htmlelements');
			$this->loadClass('htmltable','htmlelements');
			$this->loadClass('layer', 'htmlelements');

		} catch (Exception $e){
			throw customException($e->getMessage());
			exit();
		}
	}

}
