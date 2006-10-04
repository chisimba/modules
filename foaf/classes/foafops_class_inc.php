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
			//print_r($foafdetails);
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

			$logo = $foafdetails['logo'];
			$basednear = $foafdetails['basednear'];
			if(isset($basednear))
			{
				$basednear = explode(",",$basednear);
				$basednearlat = $basednear[0];
				$basednearlong = $basednear[1];
			}
			else {
				$basednear = NULL;
			}
			$geekcode = $foafdetails['geekcode'];


			//add the details to the foaf xml tree
			$this->objFoaf->addHomepage($homepage);
			$this->objFoaf->addWeblog($weblog);
			if(isset($phone))
			{
				$this->objFoaf->addPhone($phone);
			}
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
			if(isset($basednearlat) && isset($basednearlong))
			{
				$this->objFoaf->setBasedNear($basednearlat, $basednearlong);
			}

			//funded by from funded by table
			$this->_getFunders($userId);
			//depictions from depictions table
			$this->_getDepictions($userId);
			//organizations from organisations table
			$this->_getOrganizations($userId);
			//Get all the pages that we are interested in...
			//A page is a document about the thing
			$this->_getpages($userId);
			//interests from interests table
			$this->_getInterests($userId);
			//get the people we know...
			$this->_getFriends($userId);
			//var_dump($this->objFoaf->foaftree);
		}


	}

	private function _getFriendFoaf($fuserid)
	{
		//switch tables to tbl_foaf_myfoaf
		$farr = $this->dbFoaf->getRecordSet($fuserid, 'tbl_foaf_myfoaf');
		//get the info from dbFmyfoaf and set up all the fields
		//set the user details to an array that we can use
		if(empty($farr))
		{
			$foafdetails = array();
		}
		else {

			$foafdetails = $farr[0];
		}
		return $foafdetails;
	}

	private function _getInterests($userId, $friend = FALSE)
	{
		$iarr = $this->dbFoaf->getRecordSet($userId,'tbl_foaf_interests');
		if(empty($iarr))
		{
			$interests = array();
		}
		else {
			foreach($iarr as $interests)
			{
				if($friend == FALSE)
				{
					$this->objFoaf->addInterest($interests['interesturl']);
				}
				else {
					$this->friend->addInterest($interests['interesturl']);
				}
			}
		}
	}

	private function _getFunders($userId, $friend = FALSE)
	{
		$funarr = $this->dbFoaf->getRecordSet($userId,'tbl_foaf_fundedby');
		if(empty($funarr))
		{
			$funds = array();
		}
		else {
			foreach($funarr as $funds)
			{
				if($friend == FALSE)
				{
					$this->objFoaf->addFundedBy($funds['funderurl']);
				}
				else {
					$this->friend->addFundedBy($funds['funderurl']);
				}
			}
		}
	}

	private function _getDepictions($userId, $friend = FALSE)
	{
		$darr = $this->dbFoaf->getRecordSet($userId,'tbl_foaf_depiction');
		if(empty($darr))
		{
			$deps = array();
		}
		else {
			foreach($darr as $deps)
			{
				if($friend == FALSE)
				{
					$this->objFoaf->addDepiction($deps['depictionurl']);
				}
				else {
					$this->friend->addDepiction($deps['depictionurl']);
				}

			}
		}
	}

	private function _getOrganizations($userId, $friend = FALSE)
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
    			if($friend == FALSE)
    			{
					$this->objFoaf->addKnows($org);
    			}
    			else {
    				$this->friend->addKnows($org);
    			}
			}
		}
	}

	private function _getpages($userId, $friend = FALSE)
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
				if($friend == FALSE)
				{
					$this->objFoaf->addPage($docuri, $title, $description);
				}
				else {
					$this->friend->addPage($docuri, $title, $description);
				}
			}
		}
	}

	private function _getFriends($userId)
	{
		$frarr = $this->dbFoaf->getRecordSet($userId,'tbl_foaf_friends');

		//print_r($frarr);
		if(empty($frarr))
		{
			$frarr = array();
		}

		else {
			foreach($frarr as $friends)
			{

				$fuserid = $friends['fuserid'];
				$fimage = $this->objUser->getUserImageNoTags($fuserid);
				$frfoaf = $this->_getFriendFoaf($fuserid);

				//go and get all our friends details
				$uarr = $this->dbFoaf->getRecordSet($fuserid, 'tbl_users');
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
				$fullname = $firstname . " " . $surname;
				//echo "<h1>$fullname</h1><br><br>";


				$friend = $this->newObject('foafcreator');
				$friend->newAgent('person');
				$friend->setName($fullname);
				$friend->setTitle($title);
				$friend->setFirstName($firstname);
				$friend->setSurname($surname);
				$friend->addMbox('mailto:'.$email,TRUE);
				if(!empty($frfoaf))
				{
					//add the details to the foaf xml tree
					if(isset($frfoaf['homepage']))
					{
						$friend->addHomepage($frfoaf['homepage']);
					}
					if(isset($frfoaf['weblog']))
					{
						$friend->addWeblog($frfoaf['weblog']);
					}
					if(isset($frfoaf['phone']))
					{
						$friend->addPhone($frfoaf['phone']);
					}
					if(isset($frfoaf['jabberid']))
					{
						$friend->addJabberID($frfoaf['jabberid']);
					}
					if(isset($frfoaf['geekcode']))
					{
						$friend->setGeekcode($frfoaf['geekcode']);
					}
					if(isset($frfoaf['theme']))
					{
						$friend->addTheme($frfoaf['theme']);
					}

					/**
			 		* @todo check out the accounts bit, they need a service homepage as well as a username
			 		*/

					if(isset($frfoaf['workhomepage']))
					{
						$friend->addWorkplaceHomepage($frfoaf['workhomepage']);
					}
					if(isset($frfoaf['schoolhomepage']))
					{
						$friend->addSchoolHomepage($frfoaf['schoolhomepage']);
					}
					if(isset($frfoaf['logo']))
					{
						$friend->addLogo($frfoaf['logo']);
					}
					if(isset($frfoaf['basednearlat']) && isset($frfoaf['basednearlong']))
					{
						$friend->setBasedNear($frfoaf['basednearlat'], $frfoaf['basednearlong']);
					}

				}
				$friend->addImg($fimage);
				$this->objFoaf->addKnows($friend);
			}
		}
	}



	public function writeFoaf()
	{
		//write the file so that we can edit it later
		//var_dump($this->objFoaf->foaftree->get());
		//var_dump($this->objFoaf->foaftree);
		$this->objFoaf->toFile($this->savepath, $this->objUser->userId() . '.rdf', $this->objFoaf->get());
		//die();
	}

	public function foaf2html($userId)
	{
		$this->objFoafParser->setup();
		$fp = $this->objFoafParser->parseFromUri($this->savepath . $userId . '.rdf');
		return $this->objFoafParser->toHtml($this->objFoafParser->foaf_data);
	}

	public function foaf2array($userId)
	{
		$this->objFoafParser->setup();
		$fp = $this->objFoafParser->parseFromUri($this->savepath . $userId . '.rdf');
		return $this->objFoafParser->toArray();
	}

	public function foaf2Object($userId)
	{
		$this->objFoafParser->setup();
		$fp = $this->objFoafParser->parseFromUri($this->savepath . $userId . '.rdf');
		return $this->objFoafParser->toObject();
	}

}
?>