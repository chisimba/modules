<?php
	 $userLevel;
         $isLoggedIn='false';

		if ($this->objUser->isAdmin())
		{
			$this->userLevel = 'admin';
		}
		elseif ($this->objUser->isLecturer())
		{
			$this->userLevel = 'lecturer';
		}
		elseif ($this->objUser->isStudent())
		{
			$this->userLevel = 'student';
		} else
		{
			$this->userLevel = 'guest';
		}
    $isLoggedIn =$this->objUser->isLoggedIn();
    $modPath=$this->objConfig->getModulePath();
    $siteRoot=$this->objConfig->getSiteRoot().'/usrfiles/webpresent/';
    $replacewith="";
    $docRoot=$_SERVER['DOCUMENT_ROOT'];
    $appletPath=str_replace($docRoot,$replacewith,$modPath);
    $appletCodeBase="http://" . $_SERVER['HTTP_HOST']."/".$appletPath.'/realtime/resources/';
    $objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
    $port=$objSysConfig->getValue('WHITEBOARDPORT', 'realtime');
    $rtpport=$objSysConfig->getValue('RTPPORT', 'realtime');
    $rtcpport=$objSysConfig->getValue('RTCPPORT', 'realtime');
    $sipport=$objSysConfig->getValue('SIP_PORT', 'realtime');

    $linuxJMFPathLib=$modPath.'/realtime/resources/jmf-linux-i586/lib/';
    $linuxJMFPathBin=$modPath.'/realtime/resources/jmf-linux-i586/bin/';
   // $uploadURL=$this->objAltConfig->getSiteRoot()."/index.php?module=realtime&action=upload";
    $uploadURL="http://" . $_SERVER['HTTP_HOST']."/".$appletPath.'/realtime/templates/content/uploadfile.php';
  
    $objMkdir = $this->getObject('mkdir', 'files');
    // Path for uploaded files
    $uploadPath = $this->objConfig->getcontentBasePath().'/realtime/'.$this->contextCode.'/'.date("Y-m-d-H-i");//.'/'.time();
    $objMkdir->mkdirs($uploadPath, 0777);
    $resourcesPath =$modPath.'/realtime/resources';
    $chatLogPath = $filePath.'/chat/'.date("Y-m-d-H-i");
    $objMkdir->mkdirs($chatLogPath, 0777);
    
    echo '<center>';
    echo '<applet codebase="'.$appletCodeBase.'"';
    echo 'code="avoir.realtime.tcp.launcher.RealtimeLauncher" name ="Avoir Realtime Applet"';
     
    echo 'archive="realtime-launcher-0.1.jar" width="75%" height="600">';
    echo '<param name=userName value="'.$this->objUser->userName().'">';
    echo '<param name=isLocalhost value="true">';
    echo '<param name=fullname value="'.$this->objUser->fullname().'">';
    echo '<param name=userLevel value="'.$this->userLevel.'">';
    echo '<param name=linuxJMFPathLib value="'.$linuxJMFPathLib.'">';    
    echo '<param name=linuxJMFPathBin value="'.$linuxJMFPathBin.'">';
    echo '<param name=uploadURL value="'.$uploadURL.'">';
    echo '<param name=chatLogPath value="'.$chatLogPath.'">';
    echo '<param name=siteRoot value="'.$siteRoot.'">';

   //echo '<param name=siteRoot value="http://chameleon.uwc.ac.za/app/usrfiles/webpresent/">';    
    echo '<param name=isWebPresent value="true">';
    echo '<param name=isLoggedIn value="'.$isLoggedIn.'">';
    echo '<param name=slidesDir value="'.$filePath.'">';
    echo '<param name=uploadPath value="'.$uploadPath.'">';
    echo '<param name=resourcesPath value="'.$resourcesPath.'">';
    echo '<param name=port value="'.$port.'">';
    echo '<param name=rtpport value="'.$rtpport.'">';
    echo '<param name=rtcpport value="'.$rtcpport.'">';
    echo '<param name=sipport value="'.$sipport.'">';
    echo '<param name=sessionId value="'.$sessionid.'">';
    echo '<param name=sessionTitle value="'.$title.'">';
    echo '<param name=slideServerId value="'.$slideServerId.'">';
    
// echo '<param name=sessionId value="gen12Srv15Nme3_4074_1208767420">';
    echo '<param name=isSessionPresenter value="'.$isPresenter.'">';
    echo '</applet>';
    echo '</center>';
?>
