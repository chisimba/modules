<?php
$this->loadclass('link','htmlelements');
$objSysConfig  = $this->getObject('altconfig','config');
$this->appendArrayVar('headerParams', '
<script type="text/javascript">
var pageSize = 25;
var uri = "'.str_replace('&amp;','&',$this->uri(array('module' => 'liftclub', 'action' => 'jsongetlifts', 'userneed' => 'offer'))).'"; 
var userneed = "find";
var baseuri = "'.$objSysConfig->getsiteRoot().'index.php";
 </script>');

//Ext stuff
$ext =$this->getJavaScriptFile('ext-3.0-rc2/adapter/ext/ext-base.js', 'htmlelements');
$ext .=$this->getJavaScriptFile('ext-3.0-rc2/ext-all.js', 'htmlelements');
$ext .=$this->getJavaScriptFile('Ext.ux.grid.Search.js', 'liftclub');
$ext .=$this->getJavaScriptFile('searchlifts.js', 'liftclub');
$ext .=$this->getJavaScriptFile('ext-3.0-rc2/examples/grid/paging.js', 'htmlelements');
$ext .=$this->getJavaScriptFile('ext-3.0-rc2/examples/shared/examples.js', 'htmlelements');

$ext .= '<link rel="stylesheet" href="'.$this->getResourceUri('ext-3.0-rc2/resources/css/ext-all.css', 'htmlelements').'" type="text/css" />';
$ext .= '<link rel="stylesheet" href="'.$this->getResourceUri('ext-3.0-rc2/examples/grid/grid-examples.css', 'htmlelements').'" type="text/css" />';
$this->appendArrayVar('headerParams', $ext);


echo '<br /><p>Karibu! Welcome!</p><br />';

$registerLink =new link($this->uri(array('action'=>'startregister')));
$registerLink->link = $this->objLanguage->languageText("mod_liftclub_register","liftclub","Register");
$registerLink->title = $this->objLanguage->languageText("mod_liftclub_register","liftclub","Register");

$modifyLink =new link($this->uri(array('action'=>'modifydetails')));
$modifyLink->link = $this->objLanguage->languageText("mod_liftclub_modifyregister","liftclub","Modify Registration");
$modifyLink->title = $this->objLanguage->languageText("mod_liftclub_modifyregister","liftclub","Modify Registration");

//$homeLink =new link($this->uri(array('module' => 'liftclub', 'action' => 'jsongetlifts', 'userneed' => 'find')));
$homeLink =new link($this->uri(array('action'=>'liftclubhome')));
$homeLink->link = $this->objLanguage->languageText("mod_liftclub_liftclubhome","liftclub","Lift Club Home");
$homeLink->title = $this->objLanguage->languageText("mod_liftclub_liftclubhome","liftclub","Lift Club Home");

$findLink =new link($this->uri(array('action'=>'findlift')));
$findLink->link = $this->objLanguage->languageText("mod_liftclub_liftneeded","liftclub","Lifts Needed");
$findLink->title = $this->objLanguage->languageText("mod_liftclub_liftneeded","liftclub","Lifts Needed");

if($this->objUser->userId()!==null){
echo $modifyLink->show()." | ".$findLink->show()." | ".$homeLink->show();
}else{
echo $registerLink->show();
}
echo '<br /><br /><div id="find-grid"></div><br /><br />';
?>
