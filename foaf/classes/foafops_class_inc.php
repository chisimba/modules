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
			$this->loadClass('dropdown','htmlelements');

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

	/**
	 * Method to add the additional details to the FOAF of a particular user
	 *
	 * @param int $userId
	 * @return void
	 */
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

	/**
	 * Method to get the FOAF of a friend that you have added to your FOAF
	 *
	 * @param integer $fuserid
	 * @return array
	 */
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

	/**
	 * Method to get your FOAF interests
	 *
	 * @param integer $userId
	 * @param bool $friend
	 */
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

	/**
	 * Method to get the funders associated with your FOAF profile
	 *
	 * @param integer $userId
	 * @param bool $friend
	 */
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

	/**
	 * Method to get depiction URL's of yourself or a friend
	 *
	 * @param integer $userId
	 * @param bool $friend
	 */
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

	/**
	 * Method to get the organizations associated with your profile
	 *
	 * @param integer $userId
	 * @param bool $friend
	 */
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

	/**
	 * Method to get the FOAF:Pages associated with your or a friends profile
	 *
	 * @param integer $userId
	 * @param bool $friend
	 */
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

	/**
	 * Method to get all your friends
	 *
	 * @param integer $userId
	 */
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



	/**
	 * Method to write the foaf profile that is generated to a file
	 *
	 * @param void
	 * @return void
	 */
	public function writeFoaf()
	{
		//write the file so that we can edit it later
		//var_dump($this->objFoaf->foaftree->get());
		//var_dump($this->objFoaf->foaftree);
		if(!is_dir($this->savepath)
		{
			mkdir($this->savepath, 0777);
		}
		@chmod($this->savepath, 0777);
		$this->objFoaf->toFile($this->savepath, $this->objUser->userId() . '.rdf', $this->objFoaf->get());
		//die();
	}

	/**
	 * Method to parse a foaf profile and return it as HTML
	 *
	 * This should only really be used in debugging
	 *
	 * @param integer $userId
	 * @return string
	 */
	public function foaf2html($userId)
	{
		$this->objFoafParser->setup();
		$fp = $this->objFoafParser->parseFromUri($this->savepath . $userId . '.rdf');
		return $this->objFoafParser->toHtml($this->objFoafParser->foaf_data);
	}

	/**
	 * Method to parse the FOAF into an array that can be manipulated
	 *
	 * @param integer $userId
	 * @return array
	 */
	public function foaf2array($userId)
	{
		$this->objFoafParser->setup();
		$fp = $this->objFoafParser->parseFromUri($this->savepath . $userId . '.rdf');
		return $this->objFoafParser->toArray();
	}

	/**
	 * Method to create a stdClass Object from a FOAF profile
	 *
	 * @param integer $userId
	 * @return object
	 */
	public function foaf2Object($userId)
	{
		$this->objFoafParser->setup();
		$fp = $this->objFoafParser->parseFromUri($this->savepath . $userId . '.rdf');
		return $this->objFoafParser->toObject();
	}

	/**
	 * Method to create a the add a friend dropdown form
	 *
	 * @param void
	 * @return string
	 */
	public function addDD()
	{
		$myFriendsAddForm = new form('myfriends',$this->uri(array('action'=>'updatefriends')));
		$fieldset3 = $this->newObject('fieldset', 'htmlelements');
		//$fieldset3->setLegend($this->objLanguage->languageText('mod_foaf_addfriends', 'foaf'));
		$table3 = $this->newObject('htmltable', 'htmlelements');
		$table3->cellpadding = 5;

		//start the friends dropdowns
		$addarr = $this->dbFoaf->getAllUsers();
		foreach($addarr as $users)
		{
			$name = $users['firstname'] . " " . $users['surname'];
			$id = $users['userid'];
			$addusers[] = array('name' => $name, 'id' => $id);
		}
		//add in a dropdown to add/remove users as friends
		$addDrop = new dropdown('add');
		foreach($addusers as $newbies)
		{
			if($this->objUser->userId() != $newbies['id'])
			{
				$addDrop->addOption($newbies['id'], $newbies['name']);
			}
		}

		//add
		$table3->startRow();
		//$table3->addCell($this->objLanguage->languageText('mod_foaf_addfriends', 'foaf'));
		$table3->addCell($addDrop->show(), 150, NULL, 'left');
		$table3->endRow();

		$fieldset3->addContent($table3->show());
		$myFriendsAddForm->addToForm($fieldset3->show());

		$this->objButton3 = & new button('update_addfriends'); //$this->objLanguage->languageText('mod_foaf_update_friends', 'foaf'));
		$this->objButton3->setValue($this->objLanguage->languageText('mod_foaf_butaddfriends', 'foaf'));
		$this->objButton3->setToSubmit();
		$myFriendsAddForm->addToForm($this->objButton3->show());

		return $myFriendsAddForm;
	}

	/**
	 * Method to create the remove a friend dropdown
	 *
	 * @param void
	 * @return string
	 */
	public function remDD()
	{
		$myFriendsRemForm = new form('myfriendsrem',$this->uri(array('action'=>'updatefriends')));
		$fieldset4 = $this->newObject('fieldset', 'htmlelements');
		//$fieldset4->setLegend($this->objLanguage->languageText('mod_foaf_remfriends', 'foaf'));
		$table4 = $this->newObject('htmltable', 'htmlelements');
		$table4->cellpadding = 5;

		//remove dropdown
		$remarr = $this->dbFoaf->getFriends();
		//print_r($remarr);
		if(isset($remarr))
		{
			//add in a dropdown to add/remove users as friends
			$remDrop = new dropdown('remove');

			foreach($remarr as $removals)
			{
				$remDrop->addOption($removals['id'], $removals['name']);
			}
		}

		if(isset($remarr))
		{
			//delete
			$table4->startRow();
			//$table4->addCell($this->objLanguage->languageText('mod_foaf_remfriends', 'foaf'));
			$table4->addCell($remDrop->show());
			$table4->endRow();

			$fieldset4->addContent($table4->show());
			$myFriendsRemForm->addToForm($fieldset4->show());

			$this->objButton4 = & new button('update_remfriends'); //$this->objLanguage->languageText('mod_foaf_update_friends', 'foaf'));
			$this->objButton4->setValue($this->objLanguage->languageText('mod_foaf_butremfriends', 'foaf'));
			$this->objButton4->setToSubmit();
			$myFriendsRemForm->addToForm($this->objButton4->show());
		}
		return $myFriendsRemForm;

	}

	/**
	 * Method to create the featurebox to hold the organizations
	 *
	 * @param object $pals
	 * @return string
	 */
	function orgFbox($pals)
	{
		$pftype = $pals['type'];
		$pfbox = "<em>" . $pals['name'] . "</em><br />";
		if(isset($pals['homepage']))
		{
			$page = new href(htmlentities($pals['homepage'][0]),htmlentities($pals['homepage'][0]));
			$link = $page->show();
			return array($pfbox, $pftype, $link);
		}


	}

	/**
	 * Method to create the friends featurebox
	 *
	 * @param object $pals
	 * @return string
	 */
	function fFeatureBoxen($pals)
	{
		$pftype = $pals['type'];
		if(isset($pals['title']) && isset($pals['firstname']) && isset($pals['surname']))
		{
			$pfbox = "<em>" . $pals['title'] . " " . $pals['firstname'] . " " . $pals['surname'] . "</em><br />";
		}
		else {
			$pfbox = "<em>" . $pals['name'] . "</em><br />";
			$pfimg = NULL;
		}
		//build a table of values etc...
		//var_dump($pals);
		if(isset($pals['img']))
		{
			if(is_array($pals['img']))
			{
				$pimg = $pals['img'][0];
				$pimgv = new href($pimg,$pimg);
				$pfimg = '<img src="'.htmlentities($pimg).'" alt="user image" />' . "<br />";
			}
		}
		if(isset($pals['homepage']))
		{
			if(is_array($pals['homepage']))
			{
				$phomepage = $pals['homepage'][0];
				$plink = new href(htmlentities($phomepage),htmlentities($phomepage));
				$pfbox .= $this->objLanguage->languageText('mod_foaf_homepage', 'foaf') . ": " . $plink->show() . "<br />";
			}
		}
		if(isset($pals['jabberid']))
		{
			if(is_array($pals['jabberid']))
			{
				$pjabberid = $pals['jabberid'][0];
				$pfbox .= $this->objLanguage->languageText('mod_foaf_jabberid', 'foaf') . ": " . $pjabberid . "<br />";
			}
		}
		if(isset($pals['logo']))
		{
			if(is_array($pals['logo']))
			{
				$plogo = $pals['logo'][0];
				$plink2 = new href(htmlentities($plogo),htmlentities($plogo));
				$pfbox .= $this->objLanguage->languageText('mod_foaf_logo', 'foaf') . ": " . $plink2->show() . "<br />";
			}
		}
		if(isset($pals['phone']))
		{
			if(is_array($pals['phone']))
			{
				$pphone = $pals['phone'][0];
				$pfbox .= $this->objLanguage->languageText('mod_foaf_phone', 'foaf') . ": " . $pphone . "<br />";
			}
		}
		if(isset($pals['schoolhomepage']))
		{
			if(is_array($pals['schoolhomepage']))
			{
				$pschoolhomepage = $pals['schoolhomepage'][0];
				$plink3 = new href(htmlentities($pschoolhomepage),htmlentities($pschoolhomepage));
				$pfbox .= $this->objLanguage->languageText('mod_foaf_schoolhomepage', 'foaf') . ": " . $plink3->show() . "<br />";
			}
		}
		if(isset($pals['theme']))
		{
			if(is_array($pals['theme']))
			{
				$ptheme = $pals['theme'][0];
				$plink4 = new href(htmlentities($ptheme),htmlentities($ptheme));
				$pfbox .= $this->objLanguage->languageText('mod_foaf_theme', 'foaf') . ": " . $plink4->show() . "<br />";
			}
		}
		if(isset($pals['weblog']))
		{
			if(is_array($pals['weblog']))
			{
				$pweblog = $pals['weblog'][0];
				$plink5 = new href(htmlentities($pweblog),htmlentities($pweblog));
				$pfbox .= $this->objLanguage->languageText('mod_foaf_weblog', 'foaf') . ": " . $plink5->show() . "<br />";
			}
		}
		if(isset($pals['workplacehomepage']))
		{
			if(is_array($pals['workplacehomepage']))
			{
				$pworkplacehomepage = $pals['workplacehomepage'][0];
				$plink6 = new href(htmlentities($pworkplacehomepage),htmlentities($pworkplacehomepage));
				$pfbox .= $this->objLanguage->languageText('mod_foaf_workhomepage', 'foaf') . ": " . $plink6->show() . "<br />";
			}
		}
		if(isset($pals['geekcode']))
		{
			$pgeekcode = htmlentities($pals['geekcode'][0]);
			$pfbox .= $this->objLanguage->languageText('mod_foaf_geekcode', 'foaf') . ": " . $pgeekcode . "<br />";
		}

		return array($pfimg, $pfbox, $pftype);
	}

	/**
	 * Method to add a form to add an organization
	 *
	 * @param void
	 * @return string
	 */
	public function orgaAddForm()
	{
		$myOrgForm = new form('myorgform',$this->uri(array('action'=>'updateorgs')));
		$fieldseto = $this->newObject('fieldset', 'htmlelements');
		$fieldseto->setLegend($this->objLanguage->languageText('mod_foaf_addorg', 'foaf'));
		$tableo = $this->newObject('htmltable', 'htmlelements');
		$tableo->cellpadding = 5;

		$tableo->startRow();
		$labelo2 = new label($this->objLanguage->languageText('mod_foaf_oname', 'foaf').':', 'input_oname');
		$oname = new textinput('oname');
	    $tableo->addCell($labelo2->show(),150, NULL, 'right'); //label
		$tableo->addCell($oname->show()); //input box
		$tableo->endRow();

		$tableo->startRow();
		$labelo1 = new label($this->objLanguage->languageText('mod_foaf_ohomepage', 'foaf').':', 'input_ohomepage');
		$ohomepage = new textinput('ohomepage');
	    $tableo->addCell($labelo1->show(),150, NULL, 'right'); //label
		$tableo->addCell($ohomepage->show()); //input box
		$tableo->endRow();

		$fieldseto->addContent($tableo->show());
		$myOrgForm->addToForm($fieldseto->show());

		$this->objButtono = & new button('addorg');
		$this->objButtono->setValue($this->objLanguage->languageText('mod_foaf_addorg', 'foaf'));
		$this->objButtono->setToSubmit();
		$myOrgForm->addToForm($this->objButtono->show());

		return $myOrgForm->show();

	}

	/**
	 * Method to create the remove org form
	 *
	 * @param void
	 * @return string
	 */
	public function orgaRemForm()
	{
		$myOrgRemForm = new form('myorgsrem',$this->uri(array('action'=>'updateorgs')));
		$fieldsetor = $this->newObject('fieldset', 'htmlelements');
		$fieldsetor->setLegend($this->objLanguage->languageText('mod_foaf_remorgs', 'foaf'));
		$tableor = $this->newObject('htmltable', 'htmlelements');
		$tableor->cellpadding = 5;

		//remove dropdown
		$remarray = $this->dbFoaf->remOrg();
		if(isset($remarray))
		{
			//add in a dropdown to add/remove users as friends
			$remDrop = new dropdown('removeorg');
			foreach($remarray as $removal)
			{
				$remDrop->addOption($removal['id'], $removal['name']);
			}
			//delete
			$tableor->startRow();
			//$table4->addCell($this->objLanguage->languageText('mod_foaf_remfriends', 'foaf'));
			$tableor->addCell($remDrop->show());
			$tableor->endRow();

			$fieldsetor->addContent($tableor->show());
			$myOrgRemForm->addToForm($fieldsetor->show());

			$this->objButtonor = & new button('update_orgsrem'); //$this->objLanguage->languageText('mod_foaf_update_friends', 'foaf'));
			$this->objButtonor->setValue($this->objLanguage->languageText('mod_foaf_butremorgs', 'foaf'));
			$this->objButtonor->setToSubmit();
			$myOrgRemForm->addToForm($this->objButtonor->show());

			return $myOrgRemForm->show();
		}
	}
}
?>
