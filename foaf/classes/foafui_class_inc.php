<?php
/* -------------------- dbfoafusers class ----------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
	die("You cannot view this page directly");
}
// end security check

class foafui extends object {
	/**
	 * UI Elements (forms and stuff) for the FOAF module
	 * @author Paul Scott
	 * @access public
	 * @filesource
	 */
	public $objLanguage;

	public function init()
	{
		try {
			$this->loadClass('form', 'htmlelements');
			$this->loadClass('dropdown', 'htmlelements');
			$this->loadClass('textinput', 'htmlelements');
			$this->loadClass('fieldset', 'htmlelements');
			$this->objLanguage = $this->getObject('language', 'language');
			//the object needed to create FOAF files (RDF)
			$this->objFoaf = $this->getObject('foafcreator');
			//Object to parse and display FOAF RDF
			$this->objFoafParser = $this->getObject('foafparser');
			//load up the foaf factory class
			$this->objFoafOps = $this->getObject('foafops');
			$this->objUser = $this->getObject('user', 'security');
			$this->dbFoaf = $this->getObject('dbfoaf', 'foaf');
		}
		catch (customException $e)
		{
			customException::cleanUp();
			exit;
		}

	}

	/**
	 * Method to generate the myfoaf form
	 * 
	 * @access public
	 * @return string
	 */
	public function myFoaf($tcont)
	{
		//lets start the forms now. First we do tbl_foaf_myfoaf
		//create the form
		$myFoafForm = new form('myfoaf', $this->uri(array(
		'action' => 'insertmydetails'
		)));
		$fieldset1 = $this->getObject('fieldset', 'htmlelements');
		$fieldset1->setLegend($this->objLanguage->languageText('mod_foaf_detailsfor', 'foaf') ." ".$tcont->foaf['type']." <em>".$tcont->foaf['title']." ".$tcont->foaf['name']."</em>");
		$table1 = $this->getObject('htmltable', 'htmlelements');
		$table1->cellpadding = 5;
		//homepage field
		$table1->startRow();
		$label1 = new label($this->objLanguage->languageText('mod_foaf_homepage', 'foaf') .':', 'input_homepage');
		$homepage = new textinput('homepage');
		if (!isset($tcont->foaf['homepage'][0])) {
			$tcont->foaf['homepage'][0] = NULL;
		}
		$homepage->value = htmlentities($tcont->foaf['homepage'][0]);
		$table1->addCell($label1->show() , 150, NULL, 'right');
		$table1->addCell($homepage->show());
		$table1->endRow();
		//weblog field
		$table1->startRow();
		$label2 = new label($this->objLanguage->languageText('mod_foaf_weblog', 'foaf') .':', 'input_weblog');
		$weblog = new textinput('weblog');
		if (!isset($tcont->foaf['weblog'][0])) {
			$tcont->foaf['weblog'][0] = NULL;
		}
		$weblog->value = htmlentities($tcont->foaf['weblog'][0]);
		//echo $tcont->foaf['weblog'][0];
		$table1->addCell($label2->show() , 150, NULL, 'right');
		$table1->addCell($weblog->show());
		$table1->endRow();
		//phone field
		$table1->startRow();
		$label3 = new label($this->objLanguage->languageText('mod_foaf_phone', 'foaf') .':', 'input_phone');
		$phone = new textinput('phone');
		if (!isset($tcont->foaf['phone'][0])) {
			$tcont->foaf['phone'][0] = NULL;
		}
		$phone->value = $tcont->foaf['phone'][0];
		$table1->addCell($label3->show() , 150, NULL, 'right');
		$table1->addCell($phone->show());
		$table1->endRow();
		//Jabber ID
		$table1->startRow();
		$label4 = new label($this->objLanguage->languageText('mod_foaf_jabberid', 'foaf') .':', 'input_jabberid');
		$jabberid = new textinput('jabberid');
		if (!isset($tcont->foaf['jabberid'][0])) {
			$tcont->foaf['jabberid'][0] = NULL;
		}
		$jabberid->value = $tcont->foaf['jabberid'][0];
		$table1->addCell($label4->show() , 150, NULL, 'right');
		$table1->addCell($jabberid->show());
		$table1->endRow();
		//theme
		$table1->startRow();
		$label5 = new label($this->objLanguage->languageText('mod_foaf_theme', 'foaf') .':', 'input_theme');
		$theme = new textinput('theme');
		if (!isset($tcont->foaf['theme'][0])) {
			$tcont->foaf['theme'][0] = NULL;
		}
		$theme->value = $tcont->foaf['theme'][0];
		$table1->addCell($label5->show() , 150, NULL, 'right');
		$table1->addCell($theme->show());
		$table1->endRow();
		//work homepage field
		$table1->startRow();
		$label6 = new label($this->objLanguage->languageText('mod_foaf_workhomepage', 'foaf') .':', 'input_workhomepage');
		$workhomepage = new textinput('workhomepage');
		if (!isset($tcont->foaf['workplacehomepage'][0])) {
			$tcont->foaf['workplacehomepage'][0] = NULL;
		}
		$workhomepage->value = $tcont->foaf['workplacehomepage'][0];
		$table1->addCell($label6->show() , 150, NULL, 'right');
		$table1->addCell($workhomepage->show());
		$table1->endRow();
		//school homepage field
		$table1->startRow();
		$label7 = new label($this->objLanguage->languageText('mod_foaf_schoolhomepage', 'foaf') .':', 'input_schoolhomepage');
		$schoolhomepage = new textinput('schoolhomepage');
		if (!isset($tcont->foaf['schoolhomepage'][0])) {
			$tcont->foaf['schoolhomepage'][0] = NULL;
		}
		$schoolhomepage->value = $tcont->foaf['schoolhomepage'][0];
		$table1->addCell($label7->show() , 150, NULL, 'right');
		$table1->addCell($schoolhomepage->show());
		$table1->endRow();
		//logo field
		$table1->startRow();
		$label8 = new label($this->objLanguage->languageText('mod_foaf_logo', 'foaf') .':', 'input_logo');
		$logo = new textinput('logo');
		if (!isset($tcont->foaf['logo'][0])) {
			$tcont->foaf['logo'][0] = NULL;
		}
		$logo->value = $tcont->foaf['logo'][0];
		$table1->addCell($label8->show() , 150, NULL, 'right');
		$table1->addCell($logo->show());
		$table1->endRow();
		//basednear field
		/*
		$table1->startRow();
		$label9 = new label($this->objLanguage->languageText('mod_foaf_basednear', 'foaf').':', 'foaf_basednear');
		$basednear = new textinput('basednear');
		if(!isset($tcont->foaf['basednear'][0]))
		{
		$tcont->foaf['basednear'][0] = NULL;
		}
		$basednear->value= $tcont->foaf['basednear'][0];
		$table1->addCell($label9->show(), 150, NULL, 'right');
		$table1->addCell($basednear->show());
		$table1->endRow();
		*/
		//geekcode field
		$table1->startRow();
		$label10 = new label($this->objLanguage->languageText('mod_foaf_geekcode', 'foaf') .':', 'input_geekcode');
		$geekcode = new textarea('geekcode');
		if (!isset($tcont->foaf['geekcode'])) {
			$tcont->foaf['geekcode'] = NULL;
		}
		$geekcode->value = $tcont->foaf['geekcode'];
		$table1->addCell($label10->show() , 150, NULL, 'right');
		$table1->addCell($geekcode->show());
		$table1->endRow();
		$fieldset1->addContent($table1->show());
		$myFoafForm->addToForm($fieldset1->show());
		$this->objButton1 = &new button($this->objLanguage->languageText('word_update', 'system'));
		$this->objButton1->setValue($this->objLanguage->languageText('word_update', 'system'));
		$this->objButton1->setToSubmit();
		$myFoafForm->addToForm($this->objButton1->show());
		$myFoafForm = $myFoafForm->show();

		return $myFoafForm;
	}

	public function foafFriends($tcont)
	{
		//add/remove friends
		$addFriendsForm = $this->objFoafOps->addDD();
		$remFriendsForm = $this->objFoafOps->remDD();
		if (isset($tcont->foaf['knows'])) {
			if (is_array($tcont->foaf['knows'])) {
				foreach($tcont->foaf['knows'] as $pals) {
					$info[] = $this->objFoafOps->fFeatureBoxen($pals);
				}
			}
			//build the featurebox
			$mypfbox = NULL;
			$myFbox = NULL;
			foreach($info as $okes) {
				$objFeatureBox = $this->newObject('featurebox', 'navigation');
				//take the pfimage and the pfbox
				$table2 = $this->newObject('htmltable', 'htmlelements');
				$table2->cellpadding = 5;
				$table2->startRow();
				$table2->addCell($okes[0]);
				$table2->addCell($okes[1]);
				$table2->endRow();
				$mypfbox.= $table2->show() ."<br />";
				$myFbox.= $objFeatureBox->show($okes[2], $mypfbox) ."<br />";
				$mypfbox = NULL;
			}
		} else {
			$myFriendsForm = $this->objFoafOps->addDD();
			//$myFriendsForm.= $this->objFoafOps->remDD();
			$objFeatureBox = $this->newObject('featurebox', 'navigation');
			$myFbox = $objFeatureBox->show($this->objLanguage->languageText('mod_foaf_nofriends', 'foaf') , $this->objLanguage->languageText('mod_foaf_nofriendstxt', 'foaf'));
		}

		return $addFriendsForm->show() .$remFriendsForm->show() .$myFbox;
	}

	public function foafOrgs($tcont)
	{
		$myorgs = $this->objFoafOps->orgaRemForm() . $this->objFoafOps->orgaAddForm();
		//build the featureboxen for the orgs
		
		if (!array_key_exists('knows', $tcont->foaf)) {
			$tcont->foaf['knows'] = array();
		}
		if (!isset($tcont->foaf['knows'])) {
			$tcont->foaf['knows'] = array();
		}
		foreach($tcont->foaf['knows'] as $pal) {
			$orginfo[] = $this->objFoafOps->orgFbox($pal);
		}
		//print_r($orginfo); die();
		$myorgFbox = NULL;
		$myorgbox = NULL;
		if (!isset($orginfo)) {
			$orginfo = array();
		}
		foreach($orginfo as $orgas) {
			if ($orgas[1] == 'Organization') {
				$objFeatureBox = $this->newObject('featurebox', 'navigation');
				//take the pfimage and the pfbox
				$tableoo = $this->newObject('htmltable', 'htmlelements');
				$tableoo->cellpadding = 5;
				$tableoo->startRow();
				$tableoo->addCell($orgas[0]);
				$tableoo->addCell($orgas[2]);
				$tableoo->endRow();
				$myorgbox.= $tableoo->show() ."<br />";
				$myorgFbox.= $objFeatureBox->show($orgas[1], $myorgbox) ."<br />";
				$myorgbox = NULL;
			}
		}
		//add the featureboxen to the main output
		$myorgs.= $myorgFbox;
		return $myorgs;
	}






	public function foafFunders($tcont)
	{
		$myfunders = $this->objFoafOps->remFunderForm() . $this->objFoafOps->addFunderForm();
		//build the featureboxen for the funders

		if (!array_key_exists('fundedby', $tcont->foaf)) {
		  $tcont->foaf['fundedby'] = array();
		}
		if (!isset($tcont->foaf['fundedby'])) {
			$tcont->foaf['fundedby'] = array();
		}
		
	if(!empty($tcont->foaf['fundedby']))
	{
		$myfunFbox = NULL;
		$myfunbox = NULL;
       	$link = NULL;

          
		

		$objFeatureBox = $this->newObject('featurebox', 'navigation');
		$tablefuns = $this->newObject('htmltable', 'htmlelements');
		$tablefuns->cellpadding = 5;

		foreach($tcont->foaf['fundedby'] as $funder) {
			
  				$page = new href(htmlentities($funder) , htmlentities($funder));
    				$link = $page->show();					
				$tablefuns->startRow();
				$tablefuns->addCell("<em>".$funder."</em>");
				$tablefuns->addCell($link);
				$tablefuns->endRow();
			
		}

		$myfunbox.= $tablefuns->show() ."<br />";
		$myfunFbox.= $objFeatureBox->show($this->objLanguage->languageText('mod_foaf_funders', 'foaf'), $myfunbox) ."<br />";

		$myfunders.= $myfunFbox;
		
	 }	
		return $myfunders;
	}












	public function foafInterests($tcont)
	{
		$myints = $this->objFoafOps->remInterestForm() . $this->objFoafOps->addInterestForm();
		//build the featureboxen for user interests

		if (!array_key_exists('interest', $tcont->foaf)) {
		  $tcont->foaf['interest'] = array();
		}
		if (!isset($tcont->foaf['interest'])) {
			$tcont->foaf['interest'] = array();
		}
		
	if(!empty($tcont->foaf['interest']))
	{
		$myintFbox = NULL;
		$myintbox = NULL;
       	$link = NULL;

          
		

		$objFeatureBox = $this->newObject('featurebox', 'navigation');
		$table = $this->newObject('htmltable', 'htmlelements');
		$table->cellpadding = 5;

		foreach($tcont->foaf['interest'] as $interest) {
			
  				$page = new href(htmlentities($interest) , htmlentities($interest));
    				$link = $page->show();					
				$table->startRow();
				$table->addCell("<em>".$interest."</em>");
				$table->addCell($link);
				$table->endRow();
			
		}

		$myintbox.= $table->show() ."<br />";
		$myintFbox.= $objFeatureBox->show($this->objLanguage->languageText('mod_foaf_interests', 'foaf'), $myintbox) ."<br />";

		$myints.= $myintFbox;
		
	 }	
		return $myints;
	}







	public function foafDepictions($tcont)
	{
		$mydeps = $this->objFoafOps->remDepictionForm() . $this->objFoafOps->addDepictionForm();
		//build the featureboxen for user depictions

		if (!array_key_exists('depiction', $tcont->foaf)) {
		  $tcont->foaf['depiction'] = array();
		}
		if (!isset($tcont->foaf['depiction'])) {
			$tcont->foaf['depiction'] = array();
		}
		
	if(!empty($tcont->foaf['depiction']))
	{
		$mydepFbox = NULL;
		$mydepbox = NULL;
       	$link = NULL;

          
		

		$objFeatureBox = $this->newObject('featurebox', 'navigation');
		$table = $this->newObject('htmltable', 'htmlelements');
		$table->cellpadding = 5;

		foreach($tcont->foaf['depiction'] as $depiction) {
			
  				$page = new href(htmlentities($depiction) , htmlentities($depiction));
    				$link = $page->show();					
				$table->startRow();
				$table->addCell("<em>".$depiction."</em>");
				$table->addCell($link);
				$table->endRow();
			
		}

		$mydepbox.= $table->show() ."<br />";
		$mydepFbox.= $objFeatureBox->show($this->objLanguage->languageText('mod_foaf_depictions', 'foaf'), $mydepbox) ."<br />";

		$mydeps.= $mydepFbox;
		
	 }	
		return $mydeps;
	}



//pages



	public function foafPages($tcont)
	{
		$mypages = $this->objFoafOps->remPageForm() . $this->objFoafOps->addPageForm();
		//build the featureboxen for user pages

		if (!array_key_exists('page', $tcont->foaf)) {
		  $tcont->foaf['page'] = array();
		}
		if (!isset($tcont->foaf['page'])) {
			$tcont->foaf['page'] = array();
		}


		
		if (!array_key_exists('title', $tcont->foaf[0])) {
		  $tcont->foaf[0]['title'] = array();
		}
		if (!isset($tcont->foaf[0]['title'])) {
			$tcont->foaf[0]['title'] = array();
		}

		
		if (!array_key_exists('description', $tcont->foaf[0])) {
		  $tcont->foaf[0]['description'] = array();
		}
		if (!isset($tcont->foaf[0]['description'])) {
			$tcont->foaf[0]['description'] = array();
		}


		
		
	if(!empty($tcont->foaf['page']))
	{
		$mypageFbox = NULL;
		$mypagebox = NULL;
       		


		
		//builds pages feature box

		$objFeatureBox = $this->newObject('featurebox', 'navigation');
		$table = $this->newObject('htmltable', 'htmlelements');
		$table->cellpadding = 5;
		$table->cellspacing = 5;
		$table->startHeaderRow();
		$table->addHeaderCell($this->objLanguage->languageText('mod_foaf_title', 'foaf'), NULL, 'top' , 'center');
		$table->addHeaderCell($this->objLanguage->languageText('mod_foaf_page', 'foaf'), NULL, 'top' , 'center');
		$table->addHeaderCell($this->objLanguage->languageText('mod_foaf_description', 'foaf'), NULL, 'top' , 'center');
		$table->endHeaderRow();
		
		foreach($tcont->foaf['page'] as $pg)
 		{
			$pageTitle = NULL;
			$pageDescription = NULL;
			$link = NULL;	

			if(array_key_exists($pg, $tcont->foaf[0]['title']))
			{
				$pageTitle = "".$tcont->foaf[0]['title'][$pg];
			} else {
				$pageTitle = "<em>".$this->objLanguage->languageText('mod_foaf_notitle', 'foaf')."</em>";
			}
         	

		
			if(array_key_exists($pg, $tcont->foaf[0]['description']))
			{
				$pageDescription = "". $tcont->foaf[0]['description'][$pg];
			} else {
				$pageDescription = "<em>".$this->objLanguage->languageText('mod_foaf_nodescription', 'foaf')."</em>";
			}
         	
  				$page = new href(htmlentities($pg) , htmlentities($pg));
    				$link = $page->show();					
				$table->startRow();
				$table->addCell($pageTitle);
				$table->addCell("<em>".$link."</em>");
				$table->addCell($pageDescription);
				$table->endRow();
			
		}

		$mypagebox.= $table->show() ."<br />";
		$mypageFbox.= $objFeatureBox->show($this->objLanguage->languageText('mod_foaf_pages', 'foaf'), $mypagebox) ."<br />";

		$mypages.= $mypageFbox;
		
	 }	
		return $mypages;
	}

//accounts



	public function foafAccounts($tcont)
	{
		$myaccounts = $this->objFoafOps->remAccountForm() . $this->objFoafOps->addAccountForm();
		//build the featureboxen for user accounts

		if (!array_key_exists('holdsaccount', $tcont->foaf)) {
		  $tcont->foaf['holdsaccount'] = array();
		}
		if (!isset($tcont->foaf['holdsaccount'])) {
			$tcont->foaf['holdsaccount'] = array();
		}


		
		
		
	if(!empty($tcont->foaf['holdsaccount']))
	{
		$myaccountFbox = NULL;
		$myaccountbox = NULL;
       		


		
		//builds accountss feature box

		$objFeatureBox = $this->newObject('featurebox', 'navigation');
		$table = $this->newObject('htmltable', 'htmlelements');
		$table->cellpadding = 5;
		$table->cellspacing = 5;
		$table->startHeaderRow();
		$table->addHeaderCell($this->objLanguage->languageText('mod_foaf_account', 'foaf'));
		$table->addHeaderCell($this->objLanguage->languageText('mod_foaf_servicehomepage', 'foaf'));
		$table->addHeaderCell($this->objLanguage->languageText('mod_foaf_type', 'foaf'));
		$table->endHeaderRow();
		
		foreach($tcont->foaf['holdsaccount'] as $account)
 		{
			$accountName = NULL;
			$serviceHomepage = NULL;
			$type = NULL;		
			$link = NULL;	

			
         	
  				$page = new href(htmlentities($account['accountservicehomepage']) , htmlentities($account['accountservicehomepage']));
    				$link = $page->show();					
				$table->startRow();
				$table->addCell($account['accountname']);
				$table->addCell("<em>".$link."</em>");
				$table->addCell($account['type']);
				$table->endRow();
			
		}

		$myaccountbox.= $table->show() ."<br />";
		$myaccountFbox.= $objFeatureBox->show($this->objLanguage->languageText('mod_foaf_accounts', 'foaf'), $myaccountbox) ."<br />";

		$myaccounts.= $myaccountFbox;
		
	 }	
		return $myaccounts;
	}






	public function foafAccountTypes()
	{
		 return $this->objFoafOps->addAccountTypeForm() . $this->objFoafOps->remAccountTypeForm();
		 
	}

//links



	public function foafLinks()
	{
	
	$mylinks = " ";
	if($this->objUser->isAdmin())
	{
		$mylinks = $this->objFoafOps->remLinkForm() . $this->objFoafOps->addLinkForm();
	}
	//build the featureboxen for the links
	$links = $this->dbFoaf->getLinks();

	if(!isset($links))
	{	
	    $links = array();
	}
		
	if(!empty($links))
	{
		$mylinkFbox = NULL;
		$mylinkbox = NULL;
      	 	$link = NULL;

          
		

		$objFeatureBox = $this->newObject('featurebox', 'navigation');
		$tablelinks = $this->newObject('htmltable', 'htmlelements');
		$tablelinks->cellpadding = 5;
		$tablelinks->cellspacing = 2;
		$tablelinks->startHeaderRow();
		$tablelinks->addHeaderCell($this->objLanguage->languageText('mod_foaf_title', 'foaf'), NULL, 'top' , 'center');
		$tablelinks->addHeaderCell($this->objLanguage->languageText('mod_foaf_url', 'foaf'), NULL, 'top' , 'center');
		$tablelinks->addHeaderCell($this->objLanguage->languageText('mod_foaf_description', 'foaf'), NULL, 'top' , 'center');
		$tablelinks->endHeaderRow();
		

		foreach($links as $lnk) {
			
  				$page = new href(htmlentities($lnk['url']) , htmlentities($lnk['url']));
    				$link = $page->show();					
				$tablelinks->startRow();
				$tablelinks->addCell($lnk['title']);
				$tablelinks->addCell("<em>".$link."</em>");
				$tablelinks->addCell($lnk['description']);
				$tablelinks->endRow();
			
		}

		$mylinkbox.= $tablelinks->show() ."<br />";
		$mylinkFbox.= $objFeatureBox->show($this->objLanguage->languageText('mod_foaf_links', 'foaf'), $mylinkbox) ."<br />";

		$mylinks.= $mylinkFbox;
		
	 }	
		return $mylinks;
	}

}

?>
