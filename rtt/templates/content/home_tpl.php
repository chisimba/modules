<?php
$this->loadClass("link","htmlelements");
$objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
$moduleUri=$this->objAltConfig->getModuleURI();
$siteRoot=$this->objAltConfig->getSiteRoot();
$codebase=$siteRoot."/".$moduleUri.'/realtime/resources/';
$imgLink='<img src="'.$siteRoot.'/'.$moduleUri.'/rtt/resources/images/Virtual_Classroom_logo.png" width="200" height="80">';


$servletURL=$objSysConfig->getValue('SERVLETURL', 'realtime');
$openfireHost=$objSysConfig->getValue('OPENFIRE_HOST', 'realtime');
$openfirePort=$objSysConfig->getValue('OPENFIRE_CLIENT_PORT', 'realtime');
$openfireHttpBindUrl=$objSysConfig->getValue('OPENFIRE_HTTP_BIND', 'realtime');
$skinclass=$objSysConfig->getValue('SKINCLASS', 'realtime');
$skinjars=$objSysConfig->getValue('SKINJAR', 'realtime');
$username=$this->objUser->userName();
$fullnames=$this->objUser->fullname();
$email=$this->objUser->email();
$inviteUrl=$this->objAltConfig->getSiteRoot();
$roomName=$this->objContext->getTitle();

//$roomUrl=$siteRoot.'/'.$moduleUri.'/realtime/resources/'.$this->objUser->userid().'.jnlp';

$link=new link($this->uri(array("action"=>"runjnlp")));

// 
// Create an instance of the css layout class
$cssLayout = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
$cssLayout->setNumColumns(2);
$title=$this->objLanguage->languageText('mod_realtime_filtertitle','realtime','To join Virtual Classroom, click on image below.');
$str='<hr class="realtime-hr"><center><p class="realtime-title">'.$title.'</p><p class="realtime-img"><a href = "'.$link->href.'">'.$imgLink.'</a></p></center><hr class="realtime-hr">';

$toolbar = $this->getObject('contextsidebar', 'context');
$cssLayout->setLeftColumnContent('');

// Add Right Column
$cssLayout->setMiddleColumnContent( $str);

//Output the content to the page
echo $cssLayout->show();


?>
