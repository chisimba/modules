<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
	die("You cannot view this page directly");
}
// end security check
class foaf extends controller
{
	public $objLog;
	public $objLanguage;
	public $objFoaf;

	/**
     * Constructor method to instantiate objects and get variables
     */
	public function init()
	{
		try {
			$this->objLanguage = $this->getObject('language', 'language');
			$this->objFoaf = $this->getObject('foafcreator');
			//Get the activity logger class
			$this->objLog = $this->newObject('logactivity', 'logger');
			//Log this module call
			$this->objLog->log();
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
		//$this->setLayoutTemplate('beautifier_layout_tpl.php');

		switch ($action) {
			default:
				//var_dump($this->objFoaf);
				$this->objFoaf->newAgent('person');
				$this->objFoaf->setName('Paul Scott');
				$this->objFoaf->setTitle('Mr');
				$this->objFoaf->setFirstName('Paul');
				$this->objFoaf->setSurname('Scott');
				$this->objFoaf->addMbox('mailto:pscott@uwc.ac.za',TRUE); // see also: XML_FOAF::setMboxSha1Sum();
				$this->objFoaf->addHomepage('http://fsiu.uwc.ac.za');
				$this->objFoaf->addWeblog('http://fsiu.uwc.ac.za/index.php?module=blog');
				$this->objFoaf->addImg('http://fsiu.uwc.ac.za/usr_images/pscott.jpg');
				$this->objFoaf->addPage('http://fsiu.uwc.ac.za/kinky/','Stuff about me','Paul Scotts passion homepage');
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
				$this->objFoaf->addFundedBy('http://synapticmedia.net');
				$this->objFoaf->addLogo('http://paws.davey.is-a-geek.com/images/paws.jpg');
				$this->objFoaf->setBasedNear(52.565475,-1.162895);
				$this->objFoaf->addDepiction('http://example.org/depiction/');
				$this->objFoaf->addDepiction('http://example.org/depiction/2');

				echo "<pre>" .htmlentities($this->objFoaf->get()). "</pre>";
    			echo "<hr />";
				break;
		}
	}
}
?>