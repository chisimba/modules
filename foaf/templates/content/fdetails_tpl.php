<?php
//var_dump($tcont);
$dbFoaf = $this->getObject('dbfoaf');
$this->setLayoutTemplate('flayout_tpl.php');
$objmsg = &$this->getObject('timeoutmessage', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('textarea', 'htmlelements');
$this->loadClass('label', 'htmlelements');
$this->loadClass('link', 'htmlelements');
$this->loadClass('href', 'htmlelements');
$pane = &$this->newObject('tabpane', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$userMenu = &$this->newObject('usermenu', 'toolbar');
// Create an instance of the css layout class
$cssLayout = &$this->newObject('csslayout', 'htmlelements');
// Set columns to 2
$cssLayout->setNumColumns(3);
// Add Post login menu to left column
$leftSideColumn = '';
$leftSideColumn = $userMenu->show();
$middleColumn = NULL;
//echo $msg;
if ($msg == 'update') {
    $objmsg->message = $this->objLanguage->languageText('mod_foaf_recupdated', 'foaf');
    echo $objmsg->show();
} else {
    $msg = NULL;
}
$rightSideColumn = NULL;
$header = new htmlheading();
$header->type = 1;
$header->str = $this->objLanguage->languageText('mod_foaf_header', 'foaf');
$rightSideColumn = $this->objLanguage->languageText('mod_foaf_instructions', 'foaf');
$middleColumn = $header->show();
//set the userparams string that we get from tbl_users and should not be changed here...

//Tab names
$mydetails = $this->objLanguage->languageText('mod_foaf_mydetails', 'foaf');
$myfriends = $this->objLanguage->languageText('mod_foaf_myfriends', 'foaf');
$myorganizations = $this->objLanguage->languageText('mod_foaf_myorganizations', 'foaf');
$myfunders = $this->objLanguage->languageText('mod_foaf_myfunders', 'foaf');
$myinterests = $this->objLanguage->languageText('mod_foaf_myinterests', 'foaf');
$mydepictions = $this->objLanguage->languageText('mod_foaf_mydepictions', 'foaf');
$mypages = $this->objLanguage->languageText('mod_foaf_mypages', 'foaf');
$myaccounts = $this->objLanguage->languageText('mod_foaf_myaccounts', 'foaf');
$invite = $this->objLanguage->languageText('mod_foaf_invite', 'foaf');
$query = $this->objLanguage->languageText('mod_foaf_query', 'foaf');
$visualise = $this->objLanguage->languageText('mod_foaf_visualize', 'foaf');
$surprise = $this->objLanguage->languageText('mod_foaf_surprise', 'foaf');
$game = ''; //"<object width='550' height='400'><param name='movie' value='http://www.zipperfish.com/mediabase/cache/1456-184-blobs.swf' /><embed src='http://www.zipperfish.com/mediabase/cache/1456-184-blobs.swf' type='application/x-shockwave-flash' width='550' height='400'></embed></object>";
//start the tabbedpane
$pane->addTab(array(
    'name' => $mydetails,
    'content' => $this->objUi->myFoaf($tcont)
));
$pane->addTab(array(
    'name' => $myfriends,
    'content' => $this->objUi->foafFriends($tcont)
));
$pane->addTab(array(
    'name' => $myorganizations,
    'content' => $this->objUi->foafOrgs($tcont)
));
$pane->addTab(array(
    'name' => $myfunders,
    'content' => 'tbl_foaf_funders'
));
$pane->addTab(array(
    'name' => $myinterests,
    'content' => 'tbl_foaf_interests'
));
$pane->addTab(array(
    'name' => $mydepictions,
    'content' => 'tbl_foaf_depictions'
));
$pane->addTab(array(
    'name' => $mypages,
    'content' => 'tbl_foaf_pages'
));
$pane->addTab(array(
    'name' => $myaccounts,
    'content' => 'tbl_foaf_accounts'
));
$pane->addTab(array(
    'name' => $invite,
    'content' => 'Invittaion'
));
$pane->addTab(array(
    'name' => $query,
    'content' => 'Query the Network'
));
$pane->addTab(array(
    'name' => $visualise,
    'content' => 'Visulalise the Network'
));
//$pane->addTab(array('name'=>$surprise,'content' => $game));
echo $pane->show();
?>
