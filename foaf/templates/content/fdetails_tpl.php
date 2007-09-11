<?php
//var_dump($foafAr);
//var_dump($tcont);
 
$script = '<script type="text/javascript">

//<![CDATA[

           // Initialize and render the menu when it is available in the DOM

            YAHOO.util.Event.onContentReady("userfields", function () {

                /*
                     Instantiate the menu.  The first argument passed to the 
                     constructor is the id of the element in the DOM that 
                     represents the menu; the second is an object literal 
                     representing a set of configuration properties for 
                     the menu.
                */

                var oMenu = new YAHOO.widget.Menu(
                                    "userfields", 
                                    {
                                        position: "static", 
                                        hidedelay: 750, 
                                        lazyload: true 
                                    }
                                );

                /*
                     Call the "render" method with no arguments since the 
                     markup for this menu already exists in the DOM.
                */

                oMenu.render();            
            
            });
 //]]>  
</script>';
//$this->setLayoutTemplate('flayout_tpl.php');
$objmsg = $this->getObject('timeoutmessage', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('textarea', 'htmlelements');
$this->loadClass('label', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('link', 'htmlelements');
$this->loadClass('href', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('form', 'htmlelements');



$pane = $this->newObject('tabpane', 'htmlelements');
$userMenu = $this->newObject('usermenu', 'toolbar');
$dbFoaf= $this->getObject('dbfoaf' , 'foaf');
// Create an instance of the css layout class
$cssLayout = $this->newObject('csslayout', 'htmlelements');

$orgs = $dbFoaf->getRecordSet($objUser->userId() , 'tbl_foaf_organization');
$funders = $dbFoaf->getFunders();
$pages = $dbFoaf->getPgs();
$accounts = $dbFoaf->getAccounts();
$interests = $dbFoaf->getInterests();
$images = $dbFoaf->getDepictions();
$friends = $dbFoaf->getFriends();

$fields = array('Organizations' => $orgs , 'Funders' => $funders , 'Pages' => $pages , 'Accounts' => $accounts ,
		'Interests' => $interests , 'Images' => $images);

foreach($fields as $field) {
	if(!is_array($field))
	{
		$field = array();
	}
}

if(!is_array($friends))
{
   $friends = array();
}


//echo "path>".$filePath.$foafFile."<br />";
//echo "predicate>".$predicate."<br />";
//echo "object>".$object."<br />";

$objmsg->timeout = 20000;
if ($msg == 'update') {
    $objmsg->message = $this->objLanguage->languageText('mod_foaf_recupdated', 'foaf');
    echo $objmsg->show();
} else {
    $objmsg->message = $msg;	
    echo $objmsg->show();
}


//set the userparams string that we get from tbl_users and should not be changed here...
//Tab names
$mydetails = $this->objLanguage->languageText('mod_foaf_mydetails', 'foaf');
$myfriends = $this->objLanguage->languageText('mod_foaf_myfriends', 'foaf');
$allfriends = $this->objLanguage->languageText('mod_foaf_allfriends', 'foaf');
$searchfriends = $this->objLanguage->languageText('mod_foaf_searchfriends', 'foaf');
$myorganizations = $this->objLanguage->languageText('mod_foaf_myorganizations', 'foaf');
$myfunders = $this->objLanguage->languageText('mod_foaf_myfunders', 'foaf');
$myinterests = $this->objLanguage->languageText('mod_foaf_myinterests', 'foaf');
$mydepictions = $this->objLanguage->languageText('mod_foaf_myimages', 'foaf');
$mypages = $this->objLanguage->languageText('mod_foaf_mypages', 'foaf');
$myaccounts = $this->objLanguage->languageText('mod_foaf_myaccounts', 'foaf');
$accountTypes = $this->objLanguage->languageText('mod_foaf_accounttypes', 'foaf');
$invite = $this->objLanguage->languageText('mod_foaf_invite', 'foaf');
$myevents = $this->objLanguage->languageText('mod_foaf_myevents', 'foaf');
$allevents = $this->objLanguage->languageText('mod_foaf_allevents', 'foaf');
$extras = $this->objLanguage->languageText('mod_foaf_extras', 'foaf');
$gallery = $this->objLanguage->languageText('mod_foaf_gallery', 'foaf');
$query = $this->objLanguage->languageText('mod_foaf_query', 'foaf');
$visualise = $this->objLanguage->languageText('mod_foaf_visualize', 'foaf');
$surprise = $this->objLanguage->languageText('mod_foaf_surprise', 'foaf');
$foafLinks = $this->objLanguage->languageText('mod_foaf_foaflinks', 'foaf');
$noresults = $this->objLanguage->code2Txt('mod_foaf_noresults' , 'foaf' , array('FIELD' => $predicate ,'VALUE' => $object));
//$noresults = $this->objLanguage->code2Txt('mod_foaf_noresults' , 'foaf' , array('NR'FIELD' => $predicate ,'VALUE' => $object));
$game = ''; //"<object width='550' height='400'><param name='movie' value='http://www.zipperfish.com/mediabase/cache/1456-184-blobs.swf' /><embed src='http://www.zipperfish.com/mediabase/cache/1456-184-blobs.swf' type='application/x-shockwave-flash' width='550' height='400'></embed></object>";

$matches = $this->objFoafParser->queryFoaf($foafFile , $predicate , $object , $noResultsMsg);
/*echo "<div style='color:white;'>";
//var_dump($matches);
echo "<h1>search field>".$predicate."</h1>";
echo "<h1>search value>".$object."</h1>";
echo "</div>";*/
//echo "<h1>foaf file>".$foafFile."</h1>";

//boxes
$box = $this->getObject('featurebox', 'navigation');
/*
$linksBox = $this->getObject('featurebox', 'navigation');
$profileBox = $this->getObject('featurebox', 'navigation'); 
$friendsBox = $this->getObject('featurebox', 'navigation'); 
$eventsBox = $this->getObject('featurebox', 'navigation');  
*/

//extras
//icons
$icon = $this->getObject('geticon', 'htmlelements');  
$icon->setIcon('rss', 'gif', 'icons/filetypes');
$icon->align = 'left';

$link1 = new href($this->uri(array('action' =>'fields', 'content' => 'gallery')) , $this->objLanguage->languageText('mod_foaf_gallery', 'foaf'), 'class="itemlink"');
$link2 = new href($this->uri(array('action' =>'fields', 'content' => 'links')) , 'Links', 'class="itemlink"');
$link3 = new href($this->uri(array('action' =>'fields', 'content' => 'seenet')) , $this->objLanguage->languageText('mod_foaf_seenet', 'foaf'), 'class="itemlink"');

//build invite friend form

$form = new form('inviteform', $this->uri(array(
            'action' => 'inviteform'
        )));
$textArea = new textarea('invitationtext',$this->objLanguage->languageText('mod_foaf_dear', 'foaf'),'4','18');
$label = new label($this->objLanguage->languageText('word_to').':', 'input_friendmail');
$mail = new textinput('friendmail','myfriend@foaf.com','text','25');
$button = new button('sendmail');
$button->setId('sendmail');
$button->setValue($this->objLanguage->languageText('mod_foaf_send', 'foaf'));
$button->setToSubmit();

$form->addToForm($label->show().$mail->show());
$form->addToForm($textArea->show());
$form->addToForm('<center>'.$button->show().'</center>');


$inviteBox = $box->show($invite, $form->show() , 'invitebox' ,'none',TRUE);

$table = NULL;
$table = $this->newObject('htmltable' , 'htmlelements');
$table->startRow();
$table->addCell($icon->show());
$table->addCell($link1->show());
$table->endRow();
$table->startRow();
$table->addCell($icon->show());
$table->addCell($link2->show());
$table->endRow();
$table->startRow();
$table->addCell($icon->show());
$table->addCell($link3->show());
$table->endRow();
$table->startRow();
$table->addCell('<br />'.$inviteBox,NULL,'top',null,null, 'colspan="2"' , '0');
$table->endRow();

$linksBox = $box->showContent('<a href="#" class="headerlink">'.$extras.'</a>',$table->show());

//friends

$table = NULL;
$table = $this->newObject('htmltable' , 'htmlelements');

foreach($friends as $key=>$friend){

//Display only the first three friends
if($key < 3){

$table->startRow();
$fLink = new href($this->uri(array('action' =>'fields', 'content' => 'friend' , 'friend' => $key)) , $friend['name'], 'class="itemlink" title="See '.$friend['name'].' profile" id="friend'.$key.'"');
$table->addCell('<h6>'.$fLink->show().'</h6>');
$table->endRow();

}

}
$friendsLink = new href($this->uri(array('action' =>'fields', 'content' => 'friends')) , $allfriends.'>>', 'class="itemlink"');
$table->startRow();
$table->addCell('<em>'.$friendsLink->show().'</em>');
$table->endRow();

$searchLink = new href($this->uri(array('action' =>'fields', 'content' => 'search')) , $searchfriends, 'class="itemlink"');
$table->startRow();
$table->addCell('<em>'.$searchLink->show().'</em>');
$table->endRow();


$friendsHeader = new href($this->uri(array('action' =>'fields', 'content' => 'friends')) , $myfriends, 'class="headerlink" title="'.$allfriends.'"');
$friendsBox = $box->showContent($friendsHeader->show() , $table->show());


//profilebox
$profileBox = $box->show('<a href="#">Profile</a>', 'Profile content' , 'profilebox' ,'none',TRUE);



//events
$eventsBox = $box->showContent('<a href="#" class="headerlink" title="'.$allevents.'">'.$myevents.'</a>','Events');


//Insert script for generating tree menu
$this->appendArrayVar('headerParams', $this->getJavascriptFile('yahoo/yahoo.js', 'yahoolib'));
$this->appendArrayVar('headerParams', $this->getJavascriptFile('event/event.js', 'yahoolib'));
$this->appendArrayVar('headerParams', $this->getJavascriptFile('dom/dom.js', 'yahoolib'));
$this->appendArrayVar('headerParams', $this->getJavascriptFile('container/container.js', 'yahoolib'));
$this->appendArrayVar('headerParams', $this->getJavascriptFile('menu/menu.js', 'yahoolib'));
$this->appendArrayVar('headerParams',$script);

$css = '<link rel="stylesheet" type="text/css" media="all" href="'.$this->getResourceURI("menu/assets/menu.css", 'yahoolib').'" />';
$this->appendArrayVar('headerParams', $css);

		$css = '<link rel="stylesheet" type="text/css" media="all" href="'.$this->getResourceURI("foaf.css", 'foaf').'" />';
		$this->appendArrayVar('headerParams', $css);




$userFields = '
               

                       <div id="userfields" class="yuimenu">
                            <div class="bd">
                                <ul class="first-of-type">
                                    <li class="yuimenuitem first-of-type"><a class="yuimenuitemlabel" href="http://communication.yahoo.com">'.$this->objLanguage->languageText('mod_foaf_myfoaf', 'foaf').'</a>';
                                                    $userFields.= '<div id="me" class="yuimenu">
                                                                     <div class="bd">
                                                                       <ul>';
//each field is a group of organizations or friends or interests , etc..
foreach($fields as $key => $field){
$userFields.= '<li class="yuimenuitem"><a class="yuimenuitemlabel" href="/chisimba_framework/app/index.php?module=foaf&amp;action=fields&amp;content='.$key.'" title="'.$this->objLanguage->languageText('mod_foaf_manage', 'foaf').' '.$key.'">'.$key.'</a>';
	$userFields.= ' <div id="'.$key.'" class="yuimenu">
                           <div class="bd">
			     <ul>';
//echo "<h1>".$key.'  '.$objUser->userId()."</h1>";
//var_dump($field);

//each fld is an element of a group i.e. a single organization or a single friend or a single interest, etc.
//display a usefull fld property i.e. display name (for organizations), urls(for funders), title (for pages) ,etc.
$url = NULL;
switch($key){
	case 'Organizations':
	$key = 'name';
	$url = 'homepage';
	break;
	case 'Funders':
	$key ='funderurl';
	$url = 'funderurl';
	break;
	case 'Pages':
	$key = 'title';
	$url = 'page';
	break;
	case 'Interests':
	$key ='interesturl' ;
	$url = 'interesturl';
	break;
	case 'Images':
	$key ='depictionurl' ;
	$url = 'depictionurl';
	break;

}

foreach($field as $fld){        
//As people use have only a name for several services it would be better
//to show the account type ($fld['type']) as well , so the user knows what
//service is the acoount refering to.
if($key=='Accounts')
{

	$userFields.= '<li class="yuimenuitem"><a class="yuimenuitemlabel" href="'.$fld['accountservicehomepage'].'" title="'.$this->objLanguage->languageText('mod_foaf_goto' , 'foaf').' '.$fld['accountservicehomepage'].'">'.$fld['accountname'].' ('.$fld['type'].')'.'</a></li>';

} else {
	$userFields.= '<li class="yuimenuitem"><a class="yuimenuitemlabel" href="'.$fld[$url].'" title="'.$this->objLanguage->languageText('mod_foaf_goto' , 'foaf').' '.$fld[$url].'">'.$fld[$key].'</a></li>';
}




}
$userFields.= '</ul></div></div></li>';
}
$userFields.= '</ul></div></div></li></ul></div></div>';
//end userfields (labeled My foaf in the interface) menu




//start the tabbedpane
$pane->addTab(array(
    'name' => $mydetails,
    'content' => $this->objUi->myFoaf($tcont),

));






//define the contenttab content
switch($content){
	case 'friends':
	$pane->addTab(array(
    		'name' => $myfriends,
    		'content' => $this->objUi->foafFriends($tcont)
	));
	break;

	case 'fndadmin':
	$pane->addTab(array(
    		'name' => $myfriends,
    		'content' => $this->objUi->manageFriends()
	));
	break;


	case 'friend':
	$pane->addTab(array(
    		'name' => $this->objLanguage->languageText('mod_foaf_friend','foaf') ,
    		'content' => $this->objUi->showFriend($tcont , $fIndex)
	));
	break;

	case 'Organizations':
	     $pane->addTab(array(
	    'name' => $myorganizations,
	    'content' => $this->objUi->foafOrgs($tcont)
	));
	break;

	case 'orgsadmin':
	     $pane->addTab(array(
	    'name' => $myorganizations,
	    'content' => $this->objUi->manageOrgs()
	));
	break;


	case 'Funders':
	    $pane->addTab(array(
    	    'name' => $myfunders,
	    'content' => $this->objUi->foafFunders($tcont)
	));
	break;

	case 'fnsadmin':
	    $pane->addTab(array(
    	    'name' => $myfunders,
	    'content' => $this->objUi->manageFunders()
	));
	break;


	case 'Interests':
	     $pane->addTab(array(
	    'name' => $myinterests,
	    'content' => $this->objUi->foafInterests($tcont)
	));
	break;


	case 'intadmin':
	     $pane->addTab(array(
	    'name' => $myinterests,
	    'content' => $this->objUi->manageInterests()
	));
	break;

	

	case 'Images':
		$pane->addTab(array(
	    'name' => $mydepictions,
	    'content' => $this->objUi->foafDepictions($tcont)
 	));
	break;


	case 'imgadmin':
		$pane->addTab(array(
	    'name' => $mydepictions,
	    'content' => $this->objUi->manageDepictions()
 	));
	break;



	case 'Pages':
		$pane->addTab(array(
	    'name' => $mypages,
	    'content' => $this->objUi->foafPages($tcont)
	));
	break;

	case 'pgsadmin':
		$pane->addTab(array(
	    'name' => $mypages,
	    'content' => $this->objUi->managePages()
	));
	break;
	
	
	case 'Accounts':
		$pane->addTab(array(
	    'name' => $myaccounts,
	    'content' =>  $this->objUi->foafAccounts($tcont)
	));
	break;

	case 'accadmin':
		$pane->addTab(array(
	    'name' => $myaccounts,
	    'content' =>  $this->objUi->manageAccounts()
	));
	break;



	case 'search':
		$pane->addTab(array(
	    'name' => $searchfriends,
	    'content' => $this->objUi->foafSearch()
	));
	break;
	
	case 'links':
		$pane->addTab(array(
	    'name' => $foafLinks,
	    'content' => $this->objUi->foafLinks()
	));
	break;

	case 'lnkadmin':
		$pane->addTab(array(
	    'name' => $foafLinks,
	    'content' => $this->objUi->linksAdmin()
	));
	break;


	case 'network':
		$pane->addTab(array(
	    'name' => $visualise,
	    'content' => 'Visulalise the Network'
	));
	break;

	case 'surprise':
	$pane->addTab(array('name'=>$surprise,'content' => $game));
	break;

	case 'gallery':
	$pane->addTab(array('name'=>$gallery,'content' => $this->objUi->foafGallery( $fields['Images'] , $page)));
	break;
	//account types
	case 'actypes':
		$pane->addTab(array(
    	'name' => $accountTypes,
    	'content' => $this->objUi->foafAccountTypes()
	));
	break;

	case 'profile':
	default:
		$pane->addTab(array(
		    'name' => 'FOAF',
    		    'content' => '<div id="about"><h2>'.$this->objLanguage->languageText('mod_foaf_welcometofoaf', 'foaf').'</h2>'.$this->objLanguage->languageText('mod_foaf_instructions','foaf').'</div>'
		));



}


$leftColumn = NULL;
$middleColumn = NULL;
$rightColumn = NULL;

$leftColumn = $userFields.$linksBox;
$rightColumn = $friendsBox.'<p>&nbsp;</p>'.$eventsBox;
$middleColumn = $pane->show();

$cssLayout->setNumColumns(3);
$cssLayout->setLeftColumnContent($leftColumn);
$cssLayout->setRightColumnContent($rightColumn);
$cssLayout->setMiddleColumnContent($middleColumn);
echo $cssLayout->show();


?>
