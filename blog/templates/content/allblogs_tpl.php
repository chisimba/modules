<?php
$this->loadClass('href', 'htmlelements');
$tt = $this->newObject('domtt', 'htmlelements');
$cssLayout = &$this->newObject('csslayout', 'htmlelements');
$objSideBar = $this->newObject('usermenu', 'toolbar');
$objFeatureBox = $this->newObject('featurebox', 'navigation');

// Set columns to 3
$cssLayout->setNumColumns(3);
$leftMenu = NULL;

$rightSideColumn = NULL;

$leftCol = NULL;
$middleColumn = NULL;

$rightSideColumn .= $objFeatureBox->show($this->objLanguage->languageText("mod_blog_bloglist", "blog"), $this->objLanguage->languageText("mod_blog_intro", "blog"));
$rightSideColumn .= $this->objblogOps->showBlogsLink(TRUE);


if(empty($ret))
{
	$middleColumn .= $this->objLanguage->languageText("mod_blog_noblogs", "blog");
	$uinfo[] = array();
}
else {
	foreach($ret as $users)
	{
		//grab the user info from the user object
		$id = $users['userid'];
		$name = $this->objUser->fullName($id);
		$laston = $this->objUser->getLastLoginDate($id);
		$img = $this->objUser->getUserImage($id, FALSE);

		$uinfo[] = array('id' => $id, 'name' => $name, 'laston' => $laston, 'img' => $img);
	}

	//print_r($uinfo);


	foreach ($uinfo as $blogger)
	{
		$middleColumn .= $this->objblogOps->buildBloggertable($blogger) . "<br />";
	}
}
//left menu section
$leftCol = NULL;
if($this->objUser->isLoggedIn())
{
	$leftCol .= $objSideBar->show();
	$rightSideColumn .= $this->objblogOps->showAdminSection(TRUE);
	//$rightSideColumn .=$this->objblogOps->quickPost($this->objUser->userId(), TRUE);
}
else {
	$leftCol = $this->objblogOps->loginBox(TRUE);
	//$rightSideColumn .= $this->objblogOps->showBlogsLink(TRUE);
}

//show the feeds section
//$leftCol .= $this->objblogOps->showFeeds(&$userid, TRUE);

$cssLayout->setMiddleColumnContent($middleColumn);
$cssLayout->setLeftColumnContent($leftCol); //$leftMenu->show());
$cssLayout->setRightColumnContent($rightSideColumn);
echo $cssLayout->show();
?>
