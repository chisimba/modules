<?php
$modPath=$this->objAltConfig->getModulePath();
$moduleUri=$this->objAltConfig->getModuleURI();
$siteRoot=$this->objAltConfig->getSiteRoot();
$codebase=$siteRoot."/".$moduleUri.'/realtime/resources/';
$imgLink='<img src="'.$siteRoot.'/'.$moduleUri.'/realtime/resources/images/realtime.png" width="200" height="80">';

$objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
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

$roomUrl='';
$roomUrl.=$servletURL.'?';
$roomUrl.='port='.$openfirePort.'&';
$roomUrl.='host='.$openfireHost.'&';
$roomUrl.='username='.$username.'&';
$roomUrl.='roomname='.$roomName.'&';
$roomUrl.='audiovideourl='.$openfireHttpBindUrl.'&';
$roomUrl.='slidesdir=/&';
$roomUrl.=$this->objUser->isLecturer()?'ispresenter=yes&':'ispresenter=no&';

$presentationId="0";
$presentationName="0";
$roomUrl.='presentationid='.$presentationId.'&';
$roomUrl.='presentationName='.$presentationName.'&';
$roomUrl.='names='.$fullnames.'&';
$roomUrl.='email='.$email.'&';
$roomUrl.='inviteurl='.$inviteUrl.'&';
$roomUrl.='useec2=false&';
$roomUrl.='joinid=none&';
$roomUrl.='codebase='.$codebase.'&';
$roomUrl.='skinclass='.$skinclass.'&';
$roomUrl.='skinjar='.$skinjars.'&';
$roomUrl.='contextcode='.$this->objContext->getContextCode();

// Create an instance of the css layout class
$cssLayout = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
$cssLayout->setNumColumns(2);
$title=$this->objLanguage->languageText('mod_realtime_filtertitle','realtime','To join Virtual Classroom, click on image below.');
$str.='<hr class="realtime-hr"><center><p class="realtime-title">'.$title.'</p><p class="realtime-img"><a href = "'.$roomUrl.'">'.$imgLink.'</a></p></center><hr class="realtime-hr">';

$toolbar = $this->getObject('contextsidebar', 'context');
$cssLayout->setLeftColumnContent( $toolbar->show());

// Add Right Column
$cssLayout->setMiddleColumnContent( $str);

//Output the content to the page
echo $cssLayout->show();


?>
