<?php
class realtimeutil extends object {
    function  init() {
        $this->objUser=$this->getObject('user','security');
    }


    function generateJNLP() {
        
        $objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
        $servletURL=$objSysConfig->getValue('SERVLETURL', 'realtime');
        $openfireHost=$objSysConfig->getValue('OPENFIRE_HOST', 'realtime');
        $openfirePort=$objSysConfig->getValue('OPENFIRE_CLIENT_PORT', 'realtime');
        $openfireHttpBindUrl=$objSysConfig->getValue('OPENFIRE_HTTP_BIND', 'realtime');
        $skinclass=$objSysConfig->getValue('SKINCLASS', 'realtime');
        $skinjars=$objSysConfig->getValue('SKINJAR', 'realtime');
        $objAltConfig = $this->getObject('altconfig','config');
        $modPath=$objAltConfig->getModulePath();
        $moduleUri=$objAltConfig->getModuleURI();
        $siteRoot=$objAltConfig->getSiteRoot();
        $codebase=$siteRoot."/".$moduleUri.'/realtime/resources/';
        $enableDraw=$this->objUser->isLecturer()?'true':'false';

        $str=
                '<jnlp spec="1.0+" codebase="'.$codebase.'">
    <information>
        <title>Realtime</title>
        <vendor>AVOIR</vendor>
        <description>EFL</description>
        <homepage href="http://www.chisimba.com"/>
        <description kind="short">Realtime</description>
        <icon href="'.$codebase.'/images/logo.png"/>
        <icon kind="splash" href="'.$codebase.'/images/splash_realtime.png"/>
        <offline-allowed/>
    </information>
    <resources>
        <j2se version="1.5+" />
        
        <jar href="commons-collections-3.1-rt.jar" />
        <jar href="commons-logging-api-rt.jar" />
        <jar href="quartz-all-1.6.0.jar" />
        <jar href="jta-rt.jar" />
        <jar href="PgsLookAndFeel.jar"/>
        <jar href="DJNativeSwing.jar" />
        <jar href="DJNativeSwing-SWT.jar" />
        <jar href="l2fprod-common-all.jar"/>
        <jar href="kunstsoff-rt.jar" />
        <jar href="smack.jar" />
        <jar href="smackx.jar" />
        <jar href="looks-2.3.0.jar" />
        <jar href="realtime2-chatmanager.jar" />
        <jar href="realtime2-presentations.jar" />
        <jar href="realtime2-core.jar" />
        <jar href="realtime2-usermanager.jar" />
        <jar href="realtime2-roommanager.jar" />
        <jar href="realtime2-whiteboard.jar" />
</resources>

    <resources os="Windows" arch="x86">
        <jar href="swt-win.jar" />
    </resources>

   <resources os="Linux">
        <jar href="swt-linux.jar" />
   </resources>

  <resources os="Mac OS X">
        <j2se version="1.5*" java-vm-args="-XstartOnFirstThread"/>
        <jar href="swt-mac.jar"/>
    </resources>
    
   <application-desc    main-class="org.avoir.realtime.core.Main">
    <argument>-slidesdir=/</argument>
    <argument>-maxstanzas=5</argument>
    <argument>-admin='.$enableDraw.'</argument>
    <argument>-debug=true</argument>
    <argument>-enabledraw='.$enableDraw.'</argument>
    <argument>-skinclass=null </argument>
    <argument>-audiovideourl='.$openfireHttpBindUrl.'</argument>
    <argument>-serverport='.$openfirePort.'</argument>
    <argument>-serverhost='.$openfireHost.'</argument>
    <argument>-mode=0</argument>
    <argument>-plugins=org.avoir.realtime.user.UserManager#org.avoir.realtime.presentations.PresentationManager#org.avoir.realtime.whiteboard.WhiteboardManager#org.avoir.realtime.roommanager.RoomManager#org.avoir.realtime.chat.ChatManager</argument>
      
     </application-desc>

    <security>
        <all-permissions/>
    </security>
</jnlp>

';
        //this ought to be in useriles
        $myFile = $modPath.'/realtime/resources/'.$this->objUser->userid().'.jnlp';
        //chmod($modPath.'/realtime/resources/', 0777);
        $fh = fopen($myFile, 'w') or die("can't open file");
        fwrite($fh, $str);
        fclose($fh);
        
        chmod($myFile, 0777);
    }
}
?>
