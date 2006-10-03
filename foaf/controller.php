<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
	die("You cannot view this page directly");
}
// end security check

/**
 * From Wikipedia, the free encyclopedia http://en.wikipedia.org/wiki/FOAF_%28software%29
 * FOAF (Friend of a Friend) is a project for machine-readable
 * modelling of homepage-like content and social networks founded by Libby Miller and Dan Brickley.
 * The heart of the project is its specification which defines
 * some terms that can be used in statements you can make about someone,
 * such as name, gender and various online attributes.
 *
 * To make linking possible, one includes uniquely identifiable properties of one's friends
 * (such as SHA1 checksums of their E-mail addresses, a Jabber ID, or a
 * URI to the homepage or weblog of the person).
 *
 * It is based on RDF, defined using OWL and was designed to be easily extended,
 * to allow data to be shared between varied computing environments.
 *
 * @author Paul Scott
 * @copyright AVOIR
 * @package foaf
 * @category chisimba
 */
class foaf extends controller
{
	/**
	 * The logger object
	 *
	 * @var object
	 */
	public $objLog;

	/**
	 * Config object - altconfig
	 *
	 * @var object
	 */
	public $objConfig;

	/**
	 * Language object
	 *
	 * @var object
	 */
	public $objLanguage;

	/**
	 * FOAF creation object
	 *
	 * @var object
	 */
	public $objFoaf;

	/**
	 * FOAF Parser class
	 *
	 * @var Object
	 */
	public $objFoafParser;

	/**
	 * User object
	 *
	 * @var object
	 */
	public $objUser;

	/**
	 * FOAF Model for the users table
	 *
	 * @var object
	 */
	public $dbFUsers;

	/**
	 * Foaf factory class
	 *
	 * @var object
	 */
	public $objFoafOps;

	/**
	 * Path to save RDF file to
	 *
	 * @var string
	 */
	public $savepath;

	/**
     * Constructor method to instantiate objects and get variables
     *
     * @param void
     * @return void
     * @access public
     */
	public function init()
	{
		try {
			//get the config object
			$this->objConfig = $this->getObject('altconfig', 'config');
			//instantiate the language system
			$this->objLanguage = $this->getObject('language', 'language');
			//the object needed to create FOAF files (RDF)
			$this->objFoaf = $this->getObject('foafcreator');
			//Object to parse and display FOAF RDF
			$this->objFoafParser = $this->getObject('foafparser');
			//load up the foaf factory class
			$this->objFoafOps = $this->getObject('foafops');
			//LOAD UP THE USER OBJECT
			$this->objUser = $this->getObject('user', 'security');
			//hook up the database models
			$this->dbFoaf = $this->getObject('dbfoaf');

			//Get the activity logger class
			$this->objLog = $this->newObject('logactivity', 'logger');
			//Log this module call
			$this->objLog->log();
		}
		//oops, one of the above is not being instantiated correctly
		catch(customException $e) {
			//handle the error gracefully
			echo customException::cleanUp();
			//kill the module now, its pointless going on...
			die();
		}
	}
	/**
     * Method to process actions to be taken
     *
     * @param string $action String indicating action to be taken
     * @return string template file
     * @access public
     */
	public function dispatch($action = Null)
	{
		switch ($action) {
			default:
			case 'createfoaf':
				//create the basic foaf profile from tbl_users
				$this->objFoafOps->newPerson($this->objUser->userId());
				//add in other details if they exist
				$this->objFoafOps->myFoaf($this->objUser->userId());
				$this->objFoafOps->writeFoaf();
				$midcontent = $this->objFoafOps->foaf2Object($this->objUser->userId());
				$this->setVarByRef('tcont', $midcontent);

				return 'fdetails_tpl.php';
				break;

			case 'parsefoaf':
				$this->objFoafParser->setup();
				$path = $this->objConfig->getContentBasePath() . "users/" . $this->objUser->userId() . "/" . $this->objUser->userId() . ".rdf";
				$fp = $this->objFoafParser->parseFromUri($path);

				echo $this->objFoafParser->toHtml($this->objFoafParser->foaf_data);
				break;

			case 'insertmydetails':
				$homepage = $this->getParam('homepage');
				$weblog = $this->getParam('weblog');
				$phone = $this->getParam('phone');
				$jabberid = $this->getParam('jabberid');
				$theme = $this->getParam('theme');
				$workhomepage = $this->getParam('workhomepage');
				$schoolhomepage = $this->getParam('schoolhomepage');
				$logo = $this->getParam('logo');
				$basednear = $this->getParam('basednear');
				$geekcode = $this->getParam('geekcode');

				$insarr =  array('userid' => 1, 'homepage' => $homepage, 'weblog' => $weblog, 'phone' => $phone, 'jabberid' => $jabberid, 'theme' => $theme, 'workhomepage' => $workhomepage, 'schoolhomepage' => $schoolhomepage, 'logo' => $logo, 'basednear' => $basednear, 'geekcode' => $geekcode);
				$this->dbFoaf->insertMyDetails($insarr);

				break;

		}
	}
}
?>