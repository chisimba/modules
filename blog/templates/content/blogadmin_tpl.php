<?php

//initiate objects
$this->loadClass('label', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('form', 'htmlelements');
//$this->loadClass('heading', 'htmlelements');
$this->loadClass('href', 'htmlelements');
$this->loadClass('htmlarea', 'htmlelements');

$userid = $this->objUser->userId();
$profile = $this->objDbBlog->checkProfile($userid);
/*$dsnparse = $this->objblogOps->getMailDSN();
if($dsnparse == FALSE)
{
	//$mailsetup = $this->objblogOps->showMailSetup(TRUE);
}
else {
	//$mailsetup = $this->objblogOps->showMailSetup(TRUE, $dsnparse);
}*/

$tt = $this->newObject('domtt', 'htmlelements');
$pane = &$this->newObject('tabpane', 'htmlelements');

$cssLayout = &$this->newObject('csslayout', 'htmlelements');
$objSideBar = $this->newObject('sidebar', 'navigation');

// Set columns to 3
$cssLayout->setNumColumns(3);
$leftMenu = &$this->newObject('usermenu', 'toolbar');
$rightSideColumn = NULL;
$leftCol = NULL;
$middleColumn = NULL;

//left menu section
$leftCol = $leftMenu->show();
//$leftCol .= $this->objblogOps->rssBox('http://slashdot.org/slashdot.rdf', 'Slashdot');

$tabpane = $this->getObject('tabcontent', 'htmlelements');
$tabpane->width = '80%';

//write new post
$nplabel = $this->objLanguage->languageText("mod_blog_writepost", "blog");
$npcontent = $this->objblogOps->postEditor($userid, NULL);
$nplink = NULL;
$tabpane->addTab($nplabel, $npcontent, $nplink, TRUE);

//Profile editor
$prflabel = $this->objLanguage->languageText("mod_blog_setprofile", "blog");
$prfcontent = $this->objblogOps->profileEditor($userid, $profile);
$prflink = NULL;
$tabpane->addTab($prflabel, $prfcontent, $prflink, FALSE);

//blog importer
//$implabel = $this->objLanguage->languageText("mod_blog_blogimport", "blog");
//$impcontent = $this->objblogOps->showImportForm(FALSE);
//$implink = NULL;
//$tabpane->addTab($implabel, $impcontent, $implink, FALSE);

//edit posts
$eplabel = $this->objLanguage->languageText("mod_blog_word_editposts", "blog");
$epcontent = $this->objblogOps->managePosts($userid);
$eplink = NULL;
$tabpane->addTab($eplabel, $epcontent, $eplink, FALSE);

//edit/create cats
//$eclabel = $this->objLanguage->languageText("mod_blog_word_categories", "blog");
//$eccontent = $this->objblogOps->categoryEditor($userid);
//$eclink = NULL;
//$tabpane->addTab($eclabel, $eccontent, $eclink, FALSE);

//add delete rss feeds
$rslabel = $this->objLanguage->languageText("mod_blog_rssaddedit", "blog");
$rscontent = $this->objblogOps->rssEditor(FALSE);
$rslink = NULL;
$tabpane->addTab($rslabel, $rscontent, $rslink, FALSE);

//mail setup
//$maillabel = $this->objLanguage->languageText("mod_blog_setupmail", "blog");
//$mailcontent = $mailsetup;
//$maillink = NULL;
//$tabpane->addTab($maillabel, $mailcontent, $maillink, FALSE);

$middleColumn .= $tabpane->show();
//$middleColumn .= $this->objblogOps->showAdminSection(TRUE, TRUE);


$rightSideColumn .= $this->objblogOps->quickCats(TRUE);
$rightSideColumn .= $this->objblogOps->showAdminSection(TRUE);
$rightSideColumn .= $this->objblogOps->quickPost($userid, TRUE);
$cssLayout->setMiddleColumnContent($middleColumn);
$cssLayout->setLeftColumnContent($leftCol);
$cssLayout->setRightColumnContent($rightSideColumn);
echo $cssLayout->show();
?>