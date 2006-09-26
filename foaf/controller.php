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
			//LOAD UP THE USER OBJECT
			$this->objUser = $this->getObject('user', 'security');
			//hook up the database models
			$this->dbFUsers = $this->getObject('dbfoafusers');

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
				//set the path where we will save the users foaf rdf file for publishing
				$this->savepath = $this->objConfig->getContentBasePath() . "users/" . $this->objUser->userId() . "/";
				//get the users userId
				$userid = $this->objUser->userId();
				//get the users full name
				$fullname = $this->objUser->fullname();
				//retrieve what ever other info about the user we can get from tbl_users
				$uarr = $this->dbFUsers->getRecordSet($userid);
				//set the user details to an array that we can use
				$userdetails = $uarr[0];
				//get some of the foaf info
				//title
				$title = $userdetails['title'];
				//users first name
				$firstname = $userdetails['firstname'];
				//users surname
				$surname = $userdetails['surname'];
				//users email address
				$email = $userdetails['emailaddress'];
				//we need a usrimage as well for the foaf depiction
				$depiction = $this->objUser->getUserImageNoTags();

				//var_dump($this->objFoaf);
				$this->objFoaf->newAgent('person');
				$this->objFoaf->setName($fullname);
				$this->objFoaf->setTitle($title);
				$this->objFoaf->setFirstName($firstname);
				$this->objFoaf->setSurname($surname);
				$this->objFoaf->setGeekcode('GCS/S d-- s++:+ a- C+++$ UBL+$ P+ L+++$ E- !W+++$ N+ !o !K-- !w-- O M V PS+++ PE++ Y+ PGP+ t+ 5 X R tv-- b+++ DI-- D++ G e++ h---- r+++ y++++');
				$this->objFoaf->addMbox('mailto:'.$email,TRUE); // see also: XML_FOAF::setMboxSha1Sum();
				$this->objFoaf->addHomepage('http://fsiu.uwc.ac.za');
				$this->objFoaf->addWeblog('http://fsiu.uwc.ac.za/index.php?module=blog');
				$this->objFoaf->addImg('http://fsiu.uwc.ac.za/usr_images/pscott.jpg');
				$this->objFoaf->addPage('http://fsiu.uwc.ac.za/kinky/','Stuff about me','Paul Scotts project homepage');
				$this->objFoaf->addPage('http://www.php-mag.net/itr/online_artikel/psecom,id,484,nodeid,114.html','Sticking The Fork In','Creating Daemons in PHP');
				$this->objFoaf->addPage('http://pawscon.com/', 'PHP and Web Standards Conference UK 2004', 'A Conference dedicated to PHP, Web Standards and the Semantic Web');
				$this->objFoaf->addPhone('0834360955');
				$this->objFoaf->addJabberID('paul@fsiu.uwc.ac.za');
				$this->objFoaf->addTheme('http://php.net');
				$this->objFoaf->addOnlineAccount('Paul','http://freenode.info','http://xmlns.com/foaf/0.1/OnlineChatAccount');
				$this->objFoaf->addOnlineGamingAccount('Paul_S','http://www.there.com');
				$this->objFoaf->addWorkplaceHomepage('http://fsiu.uwc.ac.za');
				$this->objFoaf->addSchoolHomepage('http://www.uwc.ac.za/');
				$this->objFoaf->addInterest('http://xmlns.com/foaf/0.1/');
				$this->objFoaf->addFundedBy('http://www.idrc.ca');
				$this->objFoaf->addLogo('http://www.php.net/image.jpg');
				$this->objFoaf->setBasedNear(32.565475,-25.162895);
				$this->objFoaf->addDepiction($depiction);
				$this->objFoaf->addDepiction('http://example.org/depiction/2');

				//var_dump($this->objFoaf->foaftree);
				//who do we know?
				$mcd = $this->newObject('foafcreator');
				$mcd->newAgent('Organization');
    			$mcd->setName('McDonalds');
    			$mcd->addHomepage('http://www.mcdonalds.com/');

    			$matti = $this->newObject('foafcreator');
				$matti->newAgent('Person');
    			$matti->setName('Matthew Scott');
    			$matti->addHomepage('http://www.flickr.com/photos/scott06/');

    			$chicken = $this->newObject('foafcreator');
				$chicken->newAgent('Person');
    			$chicken->setName('Catherine Scott');
    			$chicken->addMbox('clscott@telkomsa.net', FALSE);
    			$chicken->addHomepage('http://www.flickr.com/photos/scott06/');


    			//var_dump($mcd->foaftree);

    			$this->objFoaf->addKnows($mcd);
    			$this->objFoaf->addKnows($matti);
    			$this->objFoaf->addKnows($chicken);

			//	echo "<pre>" .htmlentities($this->objFoaf->get()). "</pre>";
    	//echo "<hr />";
    	//echo $this->objConfig->getSiteRootPath();
    	header('Content-Type: text/xml');
    	$this->objFoaf->toFile($this->savepath, $this->objUser->userId() . '.rdf', $this->objFoaf->get());
    	//$foaf->dump();
    	$this->objFoaf->dump();
				break;

			case 'parsefoaf':
				$this->objFoafParser->setup();
				$fp = $this->objFoafParser->parseFromUri('/var/www/5ive/app/usrfiles/paulfoaf.rdf');
				//var_dump($this->objFoafParser->rdf_parser);
				//print_r($this->objFoafParser->foaf_data);
				var_dump($this->objFoafParser->toObject($this->objFoafParser->foaf_data));
		}
	}
}
?>