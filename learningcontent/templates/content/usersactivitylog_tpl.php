<?php
$this->loadclass('link', 'htmlelements');
$objSysConfig = $this->getObject('altconfig', 'config');
$this->appendArrayVar('headerParams', '
<script type="text/javascript">
var pageSize = 15;
var uri = "' . str_replace('&amp;', '&', $this->uri(array(
    'module' => 'learningcontent',
    'action' => 'jsongetlogs'
))) . '"; 
var title= "'.ucWords($this->objLanguage->code2Txt('mod_learningcontent_useractivitieslog','learningcontent'))." ".ucWords($this->objLanguage->code2Txt('mod_learningcontent_wordfor', 'learningcontent'))." ".$this->objContext->getTitle( $this->contextCode ).'";
var lang = new Array();
lang["usernames"] =   "' . ucWords($this->objLanguage->code2Txt('mod_learningcontent_username', 'learningcontent')) . '";
lang["pagetitle"] =   "' . ucWords($this->objLanguage->code2Txt('mod_learningcontent_pageorchaptertitle', 'learningcontent')) . '";
lang["startime"] =   "' . ucWords($this->objLanguage->code2Txt('mod_learningcontent_startime', 'learningcontent')) . '";
lang["endtime"] =   "' . ucWords($this->objLanguage->code2Txt('mod_learningcontent_endtime', 'learningcontent')) . '";
lang["type"] =   "' . ucWords($this->objLanguage->code2Txt('mod_learningcontent_type', 'learningcontent')) . '";
lang["nologstodisplay"] =   "' . ucWords($this->objLanguage->code2Txt('mod_learningcontent_nologstodisplay', 'learningcontent')) . '";
var baseuri = "' . $objSysConfig->getsiteRoot() . 'index.php";
 </script>');
//Ext stuff
$objExtJs = $this->getObject('extjs', 'ext');
$objExtJs->show();
$ext = "";
$ext.= $this->getJavaScriptFile('Ext.ux.grid.Search.js', 'learningcontent');
$ext.= $this->getJavaScriptFile('getlogs.js', 'learningcontent');
$this->appendArrayVar('headerParams', $ext);
echo '<div id="lc-grid"></div>';
?>
