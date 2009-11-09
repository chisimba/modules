<?php
$this->loadclass('link','htmlelements');
$objBlocks = $this->getObject('blocks', 'blocks');
$cssLayout = $this->getObject('csslayout', 'htmlelements');

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
$pageLink = "<ul>";
if($this->objUser->userId()!==null){
 
 $pageLink .= "<li>&nbsp;&nbsp;&nbsp;".$modifyLink->show()."</li>";
 $pageLink .= "<li>&nbsp;&nbsp;&nbsp;".$offerLink->show()."</li>";
 $pageLink .= "<li>&nbsp;&nbsp;&nbsp;".$findLink->show()."</li>";
}else{
 $pageLink .= "<li>&nbsp;&nbsp;&nbsp;".$registerLink->show()."</li>";
 $pageLink .= "<li>&nbsp;&nbsp;&nbsp;".$offerLink->show()."</li>";
 $pageLink .= "<li>&nbsp;&nbsp;&nbsp;".$findLink->show()."</li>";
}
$pageLink .= "</ul>";
$objFeatureBox = $this->newObject ( 'featurebox', 'navigation' );
$fieldset = $this->newObject('fieldset', 'htmlelements');
$fieldset->contents = $pageLink;

$cssLayout->setLeftColumnContent($objFeatureBox->show ("Lift Club", $fieldset->show()."<br />","clubox", $blockType = NULL, $titleLength = 20, $wrapStr = TRUE, $showToggle = TRUE, $hidden = 'default', $showTitle = TRUE, $cssClass = 'featurebox', $cssId = '')."<br />".$objBlocks->showBlock('login', 'security'));
$cssLayout->setMiddleColumnContent($this->getContent());
echo $cssLayout->show();
?>
