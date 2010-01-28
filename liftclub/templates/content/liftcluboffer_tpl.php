<?php
$this->loadclass('link','htmlelements');
$objSysConfig  = $this->getObject('altconfig','config');
$this->appendArrayVar('headerParams', '
<script type="text/javascript">
var pageSize = 25;
var uri = "'.str_replace('&amp;','&',$this->uri(array('module' => 'liftclub', 'action' => 'jsongetlifts', 'userneed' => 'offer'))).'"; 
var usrneed = "offer";
var liftitle= "Lifts on Offer";
var lang = new Array();
lang["triporigin"] =   "'.ucWords($this->objLanguage->code2Txt('mod_liftclub_triporigin', 'liftclub', NULL, 'Origin(Suburb)')).'";
lang["tripdestiny"] =   "'.ucWords($this->objLanguage->code2Txt('mod_liftclub_tripdestiny', 'liftclub', NULL, 'Destiny(Suburb)')).'";
lang["findoffer"] =   "'.ucWords($this->objLanguage->code2Txt('mod_liftclub_findoffer', 'liftclub', NULL, 'Find/Offer')).'";
lang["datecreated"] =   "'.ucWords($this->objLanguage->code2Txt('mod_liftclub_datecreated', 'liftclub', NULL, 'Date')).'";
lang["needtype"] =   "'.ucWords($this->objLanguage->code2Txt('mod_liftclub_needtype', 'liftclub', NULL, 'Type')).'";
lang["tripdays"] =   "'.ucWords($this->objLanguage->code2Txt('mod_liftclub_tripdays', 'liftclub', NULL, 'Trip Days')).'";
lang["wordview"] =   "'.ucWords($this->objLanguage->code2Txt('mod_liftclub_view', 'liftclub', NULL, 'View Lift')).'";
lang["wordcreated"] =   "'.ucWords($this->objLanguage->code2Txt('mod_liftclub_wordcreated', 'liftclub', NULL, 'Created')).'";
lang["wordof"] =   "'.ucWords($this->objLanguage->code2Txt('mod_liftclub_wordof', 'liftclub', NULL, 'of')).'";
lang["displayingpage"] =   "'.ucWords($this->objLanguage->code2Txt('mod_liftclub_displayingpage', 'liftclub', NULL, 'Displaying Page')).'";
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

echo '<div id="find-grid"></div>';
?>
