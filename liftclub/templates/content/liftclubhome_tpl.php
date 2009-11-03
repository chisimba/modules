<?php
$this->loadclass('link','htmlelements');

echo '<h1>'.$this->objLanguage->languageText("mod_liftclub_welcomemessage","liftclub").' '.$this->objConfig->getSitename().'</h1>';
if($this->objUser->userId() !== NULL){
$objBizCard = $this->getObject('userbizcard', 'useradmin');
$objBizCard->setUserArray($user);

echo $objBizCard->show();
}
echo '<br /><p>Karibu! Welcome!</p><br />';
//$registerLink=new link();
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

if($this->objUser->userId()!==null){
echo $modifyLink->show()." | ".$offerLink->show()." | ".$findLink->show();
}else{
echo $registerLink->show();
}
?>
