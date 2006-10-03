<?php
var_dump($tcont);
//var_dump($tcont->foaf['type']);
$this->setLayoutTemplate('flayout_tpl.php');

$this->loadClass('textinput', 'htmlelements');
$this->loadClass('textarea', 'htmlelements');
$this->loadClass('label', 'htmlelements');
$this->loadClass('link', 'htmlelements');
$pane = &$this->newObject('tabpane', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('dropdown','htmlelements');
$userMenu  = &$this->newObject('usermenu','toolbar');

// Create an instance of the css layout class
$cssLayout =& $this->newObject('csslayout', 'htmlelements');
// Set columns to 2
$cssLayout->setNumColumns(3);

// Add Post login menu to left column
$leftSideColumn ='';
$leftSideColumn = $userMenu->show();

$middleColumn = NULL;

$rightSideColumn = NULL;

$header = new htmlheading();
$header->type = 1;
$header->str = $this->objLanguage->languageText('mod_foaf_header', 'foaf');

$rightSideColumn = $this->objLanguage->languageText('mod_foaf_instructions', 'foaf');

$middleColumn = $header->show();

//set the userparams string that we get from tbl_users and should not be changed here...

//lets start the forms now. First we do tbl_foaf_myfoaf
//create the form
$myFoafForm = new form('myfoaf',$this->uri(array('action'=>'insertmydetails')));
$fieldset1 = $this->getObject('fieldset', 'htmlelements');
$fieldset1->setLegend($objLanguage->languageText('mod_foaf_detailsfor', 'foaf') . " <em>" .$tcont->foaf['title'] . " " . $tcont->foaf['name'] . "</em>" );
$table1 = $this->getObject('htmltable', 'htmlelements');
$table1->cellpadding = 5;

//homepage field
$table1->startRow();
$label1 = new label($objLanguage->languageText('mod_foaf_homepage', 'foaf').':', 'foaf_homepage');
$homepage = new textinput('homepage');
if(!isset($tcont->foaf['homepage']))
{
	$tcont->foaf['homepage'] = NULL;
}
$homepage->value= $tcont->foaf['homepage'];
$table1->addCell($label1->show(), 150, NULL, 'right');
$table1->addCell($homepage->show());
$table1->endRow();

//weblog field
$table1->startRow();
$label2 = new label($objLanguage->languageText('mod_foaf_weblog', 'foaf').':', 'foaf_weblog');
$weblog = new textinput('weblog');
if(!isset($tcont->foaf['weblog']))
{
	$tcont->foaf['weblog'] = NULL;
}
$weblog->value= $tcont->foaf['weblog'];
$table1->addCell($label2->show(), 150, NULL, 'right');
$table1->addCell($weblog->show());
$table1->endRow();

//phone field
$table1->startRow();
$label3 = new label($objLanguage->languageText('mod_foaf_phone', 'foaf').':', 'foaf_phone');
$phone = new textinput('phone');
if(!isset($tcont->foaf['phone']))
{
	$tcont->foaf['phone'] = NULL;
}
$phone->value= $tcont->foaf['phone'];
$table1->addCell($label3->show(), 150, NULL, 'right');
$table1->addCell($phone->show());
$table1->endRow();

//Jabber ID
$table1->startRow();
$label4 = new label($objLanguage->languageText('mod_foaf_jabberid', 'foaf').':', 'foaf_jabberid');
$jabberid = new textinput('jabberid');
if(!isset($tcont->foaf['jabberid']))
{
	$tcont->foaf['jabberid'] = NULL;
}
$jabberid->value= $tcont->foaf['jabberid'];
$table1->addCell($label4->show(), 150, NULL, 'right');
$table1->addCell($jabberid->show());
$table1->endRow();

//theme
$table1->startRow();
$label5 = new label($objLanguage->languageText('mod_foaf_theme', 'foaf').':', 'foaf_theme');
$theme = new textinput('theme');
if(!isset($tcont->foaf['theme']))
{
	$tcont->foaf['theme'] = NULL;
}
$theme->value= $tcont->foaf['theme'];
$table1->addCell($label5->show(), 150, NULL, 'right');
$table1->addCell($theme->show());
$table1->endRow();

//work homepage field
$table1->startRow();
$label6 = new label($objLanguage->languageText('mod_foaf_workhomepage', 'foaf').':', 'foaf_workhomepage');
$workhomepage = new textinput('workhomepage');
if(!isset($tcont->foaf['workplacehomepage']))
{
	$tcont->foaf['workplacehomepage'] = NULL;
}
$workhomepage->value= $tcont->foaf['workplacehomepage'];
$table1->addCell($label6->show(), 150, NULL, 'right');
$table1->addCell($workhomepage->show());
$table1->endRow();

//school homepage field
$table1->startRow();
$label7 = new label($objLanguage->languageText('mod_foaf_schoolhomepage', 'foaf').':', 'foaf_schoolhomepage');
$schoolhomepage = new textinput('schoolhomepage');
if(!isset($tcont->foaf['schoolhomepage']))
{
	$tcont->foaf['schoolhomepage'] = NULL;
}
$schoolhomepage->value= $tcont->foaf['schoolhomepage'];
$table1->addCell($label7->show(), 150, NULL, 'right');
$table1->addCell($schoolhomepage->show());
$table1->endRow();

//logo field
$table1->startRow();
$label8 = new label($objLanguage->languageText('mod_foaf_logo', 'foaf').':', 'foaf_logo');
$logo = new textinput('logo');
if(!isset($tcont->foaf['logo']))
{
	$tcont->foaf['logo'] = NULL;
}
$logo->value= $tcont->foaf['logo'];
$table1->addCell($label8->show(), 150, NULL, 'right');
$table1->addCell($logo->show());
$table1->endRow();

//basednear field
$table1->startRow();
$label9 = new label($objLanguage->languageText('mod_foaf_basednear', 'foaf').':', 'foaf_basednear');
$basednear = new textinput('basednear');
if(!isset($tcont->foaf['basednear']))
{
	$tcont->foaf['basednear'] = NULL;
}
$basednear->value= $tcont->foaf['basednear'];
$table1->addCell($label9->show(), 150, NULL, 'right');
$table1->addCell($basednear->show());
$table1->endRow();

//geekcode field
$table1->startRow();
$label10 = new label($objLanguage->languageText('mod_foaf_geekcode', 'foaf').':', 'foaf_geekcode');
$geekcode = new textarea('geekcode');
if(!isset($tcont->foaf['geekcode']))
{
	$tcont->foaf['geekcode'] = NULL;
}
$geekcode->value= $tcont->foaf['geekcode'];
$table1->addCell($label10->show(), 150, NULL, 'right');
$table1->addCell($geekcode->show());
$table1->endRow();

$fieldset1->addContent($table1->show());
$myFoafForm->addToForm($fieldset1->show());

$this->objButton1 = & new button($objLanguage->languageText('word_submit', 'system'));
$this->objButton1->setValue($objLanguage->languageText('word_submit', 'system'));
$this->objButton1->setToSubmit();
$myFoafForm->addToForm($this->objButton1->show());


//Tab names
$mydetails = $this->objLanguage->languageText('mod_foaf_mydetails', 'foaf');
$myfriends = $this->objLanguage->languageText('mod_foaf_myfriends', 'foaf');
$myorganizations = $this->objLanguage->languageText('mod_foaf_myorganizations', 'foaf');
$myfunders = $this->objLanguage->languageText('mod_foaf_myfunders', 'foaf');
$myinterests = $this->objLanguage->languageText('mod_foaf_myinterests', 'foaf');
$mydepictions = $this->objLanguage->languageText('mod_foaf_mydepictions', 'foaf');
$mypages = $this->objLanguage->languageText('mod_foaf_mypages', 'foaf');
$myaccounts= $this->objLanguage->languageText('mod_foaf_myaccounts', 'foaf');
$invite = $this->objLanguage->languageText('mod_foaf_invite', 'foaf');
$query = $this->objLanguage->languageText('mod_foaf_query', 'foaf');
$visualise = $this->objLanguage->languageText('mod_foaf_visualize', 'foaf');
$surprise = $this->objLanguage->languageText('mod_foaf_surprise', 'foaf');
$game = '<object width="550" height="400"><param name="movie" value="http://www.zipperfish.com/mediabase/cache/1456-184-blobs.swf" /><embed src="http://www.zipperfish.com/mediabase/cache/1456-184-blobs.swf" type="application/x-shockwave-flash" width="550" height="400"></embed></object>';

//start the tabbedpane
//$pane =new tabpane(100,500);
$pane->addTab(array('name'=>$mydetails,'content' => $myFoafForm->show()));
$pane->addTab(array('name'=>$myfriends,'content' => 'tbl_foaf_friends'));
$pane->addTab(array('name'=>$myorganizations,'content' => 'tbl_foaf_organizations'));
$pane->addTab(array('name'=>$myfunders,'content' => 'tbl_foaf_funders'));
$pane->addTab(array('name'=>$myinterests,'content' => 'tbl_foaf_interests'));
$pane->addTab(array('name'=>$mydepictions,'content' => 'tbl_foaf_depictions'));
$pane->addTab(array('name'=>$mypages,'content' => 'tbl_foaf_pages'));
$pane->addTab(array('name'=>$myaccounts,'content' => 'tbl_foaf_accounts'));
$pane->addTab(array('name'=>$invite,'content' => 'Invittaion'));
$pane->addTab(array('name'=>$query,'content' => 'Query the Network'));
$pane->addTab(array('name'=>$visualise,'content' => 'Visulalise the Network'));
$pane->addTab(array('name'=>$surprise,'content' => $game));


//$middleColumn .= $pane->show();
echo $pane->show();

//add left column
//$cssLayout->setLeftColumnContent($leftSideColumn);
//add middle column
//$cssLayout->setMiddleColumnContent($middleColumn);
//add right column
//$cssLayout->setRightColumnContent($rightSideColumn);

//echo $cssLayout->show();