<?php
$this->loadclass('link','htmlelements');
$objBlocks = $this->getObject('blocks', 'blocks');
$cssLayout = $this->getObject('csslayout', 'htmlelements');
$cssLayout->setNumColumns(2);

$homeLink =new link($this->uri(array('action'=>'liftclubhome')));
$homeLink->link = $this->objLanguage->languageText("word_home","system","Home");
$homeLink->title = $this->objLanguage->languageText("word_home","system","Home");

$exitLink =new link($this->uri(array('action'=>'liftclubsignout')));
$exitLink->link = $this->objLanguage->languageText("mod_liftclub_signout","liftclub","Sign Out");
$exitLink->title = $this->objLanguage->languageText("mod_liftclub_signout","liftclub","Sign Out");

$registerLink =new link($this->uri(array('action'=>'startregister')));
$registerLink->link = $this->objLanguage->languageText("mod_liftclub_register","liftclub","Register");
$registerLink->title = $this->objLanguage->languageText("mod_liftclub_register","liftclub","Register");

$modifyLink =new link($this->uri(array('action'=>'modifydetails')));
$modifyLink->link = $this->objLanguage->languageText("mod_liftclub_modifyregister","liftclub","Modify Registration");
$modifyLink->title = $this->objLanguage->languageText("mod_liftclub_modifyregister","liftclub","Modify Registration");

$findLink =new link($this->uri(array('action'=>'findlift')));
$findLink->link = $this->objLanguage->languageText("mod_liftclub_liftneeded","liftclub","Lifts Needed");
$findLink->title = $this->objLanguage->languageText("mod_liftclub_liftneeded","liftclub","Lifts Needed");

$offerLink =new link($this->uri(array('action'=>'offeredlifts')));
$offerLink->link = $this->objLanguage->languageText("mod_liftclub_liftonoffer","liftclub","Lifts On Offer");
$offerLink->title = $this->objLanguage->languageText("mod_liftclub_liftonoffer","liftclub","Lifts On Offer");

$favLink =new link($this->uri(array('action'=>'myfavourites')));
$favLink->link = $this->objLanguage->languageText("mod_liftclub_myfavourites","liftclub","My Favourites");
$favLink->title = $this->objLanguage->languageText("mod_liftclub_myfavourites","liftclub","My Favourites");

$msgLink =new link($this->uri(array('action'=>'messages')));
$msgLink->link = $this->objLanguage->languageText("mod_liftclub_receivedmessages","liftclub","Inbox");
$msgLink->title = $this->objLanguage->languageText("mod_liftclub_receivedmessages","liftclub","Inbox");

$msgOutLink =new link($this->uri(array('action'=>'outboxmessages')));
$msgOutLink->link = $this->objLanguage->languageText("mod_liftclub_sentmessages","liftclub","Outbox");
$msgOutLink->title = $this->objLanguage->languageText("mod_liftclub_sentmessages","liftclub","Outbox");

$msgTrashLink =new link($this->uri(array('action'=>'trashedmessages')));
$msgTrashLink->link = $this->objLanguage->languageText("mod_liftclub_trashedmessages","liftclub","Trash");
$msgTrashLink->title = $this->objLanguage->languageText("mod_liftclub_trashedmessages","liftclub","Trash");

$objFeatureBox = $this->newObject ( 'featurebox', 'navigation' );

$pageLink = "<ul>";
$mailFeatBox = "";
if($this->objUser->userId()!==null){ 
 $pageLink .= "<li>&nbsp;&nbsp;&nbsp;".$homeLink->show()."</li>";
 $pageLink .= "<li>&nbsp;&nbsp;&nbsp;".$offerLink->show()."</li>";
 $pageLink .= "<li>&nbsp;&nbsp;&nbsp;".$findLink->show()."</li>";
 $pageLink .= "<li>&nbsp;&nbsp;&nbsp;".$favLink->show()."</li>";
 $pageLink .= "<li>&nbsp;&nbsp;&nbsp;".$modifyLink->show()."</li>"; 
 $pageLink .= "<li>&nbsp;&nbsp;&nbsp;".$exitLink->show()."</li>";  
 $mailLink = "<ul>";
 $mailLink .= "<li>&nbsp;&nbsp;&nbsp;".$msgLink->show()."</li>";
 $mailLink .= "<li>&nbsp;&nbsp;&nbsp;".$msgOutLink->show()."</li>";
 $mailLink .= "<li>&nbsp;&nbsp;&nbsp;".$msgTrashLink->show()."</li>";
 $mailLink .= "</ul>";
	$mailfieldset = $this->newObject('fieldset', 'htmlelements');
	$mailfieldset->contents = $mailLink;
 $mailFeatBox = $objFeatureBox->show ($this->objLanguage->languageText("mod_liftclub_mailbox","liftclub","Mail Box"), $mailfieldset->show()."<br />","mailbox", $blockType = NULL, $titleLength = 20, $wrapStr = TRUE, $showToggle = TRUE, $hidden = 'default', $showTitle = TRUE, $cssClass = 'featurebox', $cssId = '');
 $mailFeatBox = $mailFeatBox."<br /><br /><br /><br />";
}else{
 $pageLink .= "<li>&nbsp;&nbsp;&nbsp;".$homeLink->show()."</li>";
 $pageLink .= "<li>&nbsp;&nbsp;&nbsp;".$registerLink->show()."</li>";
 $pageLink .= "<li>&nbsp;&nbsp;&nbsp;".$offerLink->show()."</li>";
 $pageLink .= "<li>&nbsp;&nbsp;&nbsp;".$findLink->show()."</li>";
}
$pageLink .= "</ul>";

$fieldset = $this->newObject('fieldset', 'htmlelements');
$fieldset->contents = $pageLink;

$cssLayout->setLeftColumnContent($objFeatureBox->show ($this->objLanguage->languageText("mod_liftclub_liftclubname","liftclub","Lift Club"), $fieldset->show()."<br />","clubox", $blockType = NULL, $titleLength = 20, $wrapStr = TRUE, $showToggle = TRUE, $hidden = 'default', $showTitle = TRUE, $cssClass = 'featurebox', $cssId = '')."<br />".$mailFeatBox.$objBlocks->showBlock('login', 'security'));
$cssLayout->setMiddleColumnContent($this->getContent());
echo $cssLayout->show();
?>
