<?php
/* -------------------- foafops class ----------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
	die("You cannot view this page directly");
}
// end security check

class foafops extends object
{
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

	public $friend;

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

	//factory method
	//the object
	//new person/whatever
	//adds all the details as private methods
	//return foaf as an object (StdClass())


	/**
	 * Method to create a basic FOAF RDF file based on the info in tbl_users
	 *
	 * @param integer
	 * @return stdClass object
	 */
	public function newPerson($userId)
	{
		//set the path where we will save the users foaf rdf file for publishing
		$this->savepath = $this->objConfig->getContentBasePath() . "users/" . $this->objUser->userId() . "/";
		//get the users userId
		$userid = $this->objUser->userId();
		//get the users full name
		$fullname = $this->objUser->fullname();
		//retrieve what ever other info about the user we can get from tbl_users
		$uarr = $this->dbFoaf->getRecordSet($userid, 'tbl_users');
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
		$image = $this->objUser->getUserImageNoTags();

		$this->objFoaf->newAgent('person');
		$this->objFoaf->setName($fullname);
		$this->objFoaf->setTitle($title);
		$this->objFoaf->setFirstName($firstname);
		$this->objFoaf->setSurname($surname);
		$this->objFoaf->addMbox('mailto:'.$email,TRUE);
		$this->objFoaf->addImg($image);
	}

	public function myFoaf($userId)
	{
		//switch tables to tbl_foaf_myfoaf
		$farr = $this->dbFoaf->getRecordSet($userId, 'tbl_foaf_myfoaf');
		//get the info from dbFmyfoaf and set up all the fields
		//set the user details to an array that we can use
		if(empty($farr))
		{
			$foafdetails = array();
		}
		else {
			$foafdetails = $farr[0];
			//hook up the details to variables and put them into the XML Tree
			$homepage = $foafdetails['homepage'];
			$weblog = $foafdetails['weblog'];
			//page comes form a diff method
			$phone = $foafdetails['phone'];
			$jabberid = $foafdetails['jabberid'];
			$theme = $foafdetails['theme'];
			$onlineacc = $foafdetails['onlineacc'];
			$onlinegamoingacc = $foafdetails['onlinegamingacc'];
			$workhomepage = $foafdetails['workhomepage'];
			$schoolhomepage = $foafdetails['schoolhomepage'];

			//interests from interests table
			$this->_getInterests($userId);

			//funded by from funded by table
			$this->_getFunders($userId);

			$logo = $foafdetails['logo'];
			$basednear = $foafdetails['basednear'];
			$basednear = explode(",",$basednear);
			$basednearlat = $basednear[0];
			$basednearlong = $basednear[1];
			$geekcode = $foafdetails['geekcode'];

			//depictions from depictions table
			$this->_getDepictions($userId);

			//organizations from organisations table
			$this->_getOrganizations($userId);

			//Get all the pages that we are interested in...
			//A page is a document about the thing
			$this->_getpages($userId);

			//get the people we know...
			$this->_getFriends($userId);

			//add the details to the foaf xml tree
			$this->objFoaf->addHomepage($homepage);
			$this->objFoaf->addWeblog($weblog);
			$this->objFoaf->addPhone($phone);
			$this->objFoaf->addJabberID($jabberid);
			$this->objFoaf->setGeekcode($geekcode);
			$this->objFoaf->addTheme($theme);

			/**
			 * @todo check out the accounts bit, they need a service homepage as well as a username
			 */
			$this->objFoaf->addOnlineAccount('Paul','http://freenode.info','http://xmlns.com/foaf/0.1/OnlineChatAccount');
			$this->objFoaf->addOnlineGamingAccount('Paul_S','http://www.there.com');

			$this->objFoaf->addWorkplaceHomepage($workhomepage);
			$this->objFoaf->addSchoolHomepage($schoolhomepage);
			$this->objFoaf->addLogo($logo);
			$this->objFoaf->setBasedNear($basednearlat, $basednearlong);

		}

	}

	public function getfriends($userId)
	{
		$this->friend = $this->newObject('foafcreator');

		//set the path where we will save the users foaf rdf file for publishing
		$this->savepath = $this->objConfig->getContentBasePath() . "users/" . $this->objUser->userId() . "/";
		//get the users userId
		$userid = $this->objUser->userId();
		//get the users full name
		$fullname = $this->objUser->fullname();
		//retrieve what ever other info about the user we can get from tbl_users
		$uarr = $this->dbFoaf->getRecordSet($userid, 'tbl_users');
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
		$image = $this->objUser->getUserImageNoTags();

		$this->friend->newAgent('person');
		$this->friend->setName($fullname);
		$this->friend->setTitle($title);
		$this->friend->setFirstName($firstname);
		$this->friend->setSurname($surname);
		$this->friend->addMbox('mailto:'.$email,TRUE);
		$this->friend->addImg($image);

		//switch tables to tbl_foaf_myfoaf
		$farr = $this->dbFoaf->getRecordSet($userId, 'tbl_foaf_myfoaf');
		//get the info from dbFmyfoaf and set up all the fields
		//set the user details to an array that we can use
		if(empty($farr))
		{
			$foafdetails = array();
		}
		else {
			$foafdetails = $farr[0];
			//hook up the details to variables and put them into the XML Tree
			$homepage = $foafdetails['homepage'];
			$weblog = $foafdetails['weblog'];
			//page comes form a diff method
			$phone = $foafdetails['phone'];
			$jabberid = $foafdetails['jabberid'];
			$theme = $foafdetails['theme'];
			$onlineacc = $foafdetails['onlineacc'];
			$onlinegamoingacc = $foafdetails['onlinegamingacc'];
			$workhomepage = $foafdetails['workhomepage'];
			$schoolhomepage = $foafdetails['schoolhomepage'];

			//interests from interests table
			//$this->_getInterests($userId);

			//funded by from funded by table
			//$this->_getFunders($userId);

			$logo = $foafdetails['logo'];
			$basednear = $foafdetails['basednear'];
			$basednear = explode(",",$basednear);
			$basednearlat = $basednear[0];
			$basednearlong = $basednear[1];
			$geekcode = $foafdetails['geekcode'];

			//depictions from depictions table
			//$this->_getDepictions($userId);

			//organizations from organisations table
			//$this->_getOrganizations($userId);

			//Get all the pages that we are interested in...
			//A page is a document about the thing
			//$this->_getpages($userId);

			//get the people we know...
			//$this->_getFriends($userId);

			//add the details to the foaf xml tree
			$this->friend->addHomepage($homepage);
			$this->friend->addWeblog($weblog);
			$this->friend->addPhone($phone);
			$this->friend->addJabberID($jabberid);
			$this->friend->setGeekcode($geekcode);
			$this->friend->addTheme($theme);

			/**
			 * @todo check out the accounts bit, they need a service homepage as well as a username
			 */
			$this->friend->addOnlineAccount('Paul','http://freenode.info','http://xmlns.com/foaf/0.1/OnlineChatAccount');
			$this->friend->addOnlineGamingAccount('Paul_S','http://www.there.com');

			$this->friend->addWorkplaceHomepage($workhomepage);
			$this->friend->addSchoolHomepage($schoolhomepage);
			$this->friend->addLogo($logo);
			$this->friend->setBasedNear($basednearlat, $basednearlong);
		}
	}

	private function _getInterests($userId)
	{
		$iarr = $this->dbFoaf->getRecordSet($userId,'tbl_foaf_interests');
		if(empty($iarr))
		{
			$interests = array();
		}
		else {
			foreach($iarr as $interests)
			{
				$this->objFoaf->addInterest($interests['interesturl']);
			}
		}
	}

	private function _getFunders($userId)
	{
		$funarr = $this->dbFoaf->getRecordSet($userId,'tbl_foaf_fundedby');
		if(empty($funarr))
		{
			$funds = array();
		}
		else {
			foreach($funarr as $funds)
			{
				$this->objFoaf->addFundedBy($funds['funderurl']);
			}
		}
	}

	private function _getDepictions($userId)
	{
		$darr = $this->dbFoaf->getRecordSet($userId,'tbl_foaf_depiction');
		if(empty($darr))
		{
			$deps = array();
		}
		else {
			foreach($darr as $deps)
			{
				$this->objFoaf->addDepiction($deps['depictionurl']);
			}
		}
	}

	private function _getOrganizations($userId)
	{
		$oarr = $this->dbFoaf->getRecordSet($userId,'tbl_foaf_organization');
		if(empty($oarr))
		{
			$orgs = array();
		}
		else {
			foreach($oarr as $orgs)
			{
				$homepage = $orgs['homepage'];
				$name = $orgs['name'];

				$org = $this->newObject('foafcreator');
				$org->newAgent('Organization');
    			$org->setName($name);
    			$org->addHomepage($homepage);
				$this->objFoaf->addKnows($org);
			}
		}
	}

	private function _getpages($userId)
	{
		$parr = $this->dbFoaf->getRecordSet($userId,'tbl_foaf_pages');
		if(empty($parr))
		{
			$pages = array();
		}
		else {
			foreach($parr as $pages)
			{
				$docuri = $pages['page'];
				$title = $pages['title'];
				$description = $pages['description'];
				$this->objFoaf->addPage($docuri, $title, $description);
			}
		}
	}

	private function _getFriends($userId)
	{
		$frarr = $this->dbFoaf->getRecordSet($userId,'tbl_foaf_friends');
		if(empty($frarr))
		{
			$frarr = array();
		}
		else {
			foreach($frarr as $friends)
			{
				$fuserid = $friends['fuserid'];
				//go and get all our friends details
				$friend = $this->getfriends($fuserid);

				$this->objFoaf->addKnows($friend);
			}
		}
	}



	public function writeFoaf()
	{
		//write the file so that we can edit it later
		$this->objFoaf->toFile($this->savepath, $this->objUser->userId() . '.rdf', $this->objFoaf->get());
		//header('Content-Type: text/xml');
    	//$this->objFoaf->toFile($this->savepath, $this->objUser->userId() . '.rdf', $this->objFoaf->get());
    	//$this->objFoaf->dump();
	}

	public function foaf2html($userId)
	{
		$this->objFoafParser->setup();
		$fp = $this->objFoafParser->parseFromUri($this->savepath . $userId . '.rdf');
		return $this->objFoafParser->toHtml($this->objFoafParser->foaf_data);
	}

}
?>