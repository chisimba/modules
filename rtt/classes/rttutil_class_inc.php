<?php

class rttutil extends object {

    function init() {
        $this->objUser = $this->getObject('user', 'security');
        $this->objContext = $this->getObject('dbcontext', 'context');
    }

    function generateJNLP($roomname='') {
        $objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
        $servletURL = $objSysConfig->getValue('SERVLETURL', 'rtt');
        $plugins = $objSysConfig->getValue('PLUGINS', 'rtt');
        $openfireHost = $objSysConfig->getValue('OPENFIRE_HOST', 'rtt');
        $openfirePort = $objSysConfig->getValue('OPENFIRE_CLIENT_PORT', 'rtt');
        $openfireHttpBindUrl = $objSysConfig->getValue('OPENFIRE_HTTP_BIND', 'rtt');
        $skinclass = $objSysConfig->getValue('SKINCLASS', 'rtt');
        $skinjars = $objSysConfig->getValue('SKINJAR', 'rtt');
        $rtpPort = $objSysConfig->getValue('RTPPORT', 'rtt');
        $sipPort = $objSysConfig->getValue('SIPPORT', 'rtt');
        $outboundProxy = $objSysConfig->getValue('OUTBOUNDPROXY', 'rtt');
        $sipDomain = $objSysConfig->getValue('SIPDOMAIN', 'rtt');

        $objAltConfig = $this->getObject('altconfig', 'config');
        $modPath = $objAltConfig->getModulePath();
        $moduleUri = $objAltConfig->getModuleURI();
        $siteRoot = $objAltConfig->getSiteRoot();
        $codebase = $siteRoot . "/" . $moduleUri . '/rtt/resources/';
        $enableDraw = $this->objUser->isCourseAdmin($this->objContext->getContextCode()) ? 'true' : 'false';
        if ($roomname == '') {
            $roomname = $this->objContext->getTitle($this->objContext->getContextCode());
        }
        $userDetails = $this->objUser->getUserDetails($this->objUser->userid());
        $password = '1234';
        $str =
                '<jnlp spec="1.0+" codebase="' . $codebase . '">
    <information>
        <title>Realtime Communication Tools</title>
        <vendor>AVOIR</vendor>
        <description>Realtime Communication Tools</description>
        <homepage href="http://www.chisimba.com"/>
        <description kind="short">rtt</description>
        <icon href="' . $codebase . '/images/logo.png"/>
        <icon kind="splash" href="' . $codebase . '/images/splash_rtt.png"/>
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
        <jar href="realtime2-audio.jar" />
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
    <argument>-admin=' . $enableDraw . '</argument>
    <argument>-debug=true</argument>
    <argument>-enabledraw=' . $enableDraw . '</argument>
    <argument>-skinclass=null </argument>
    <argument>-audiovideourl=' . $openfireHttpBindUrl . '</argument>
    <argument>-serverport=' . $openfirePort . '</argument>
    <argument>-serverhost=' . $openfireHost . '</argument>
    <argument>-mode=1</argument>
    <argument>-plugins=' . $plugins . '</argument>
    <argument>-username=' . $this->objUser->username() . '</argument>
    <argument>-names=' . $this->objUser->fullname() . '</argument>
    <argument>-email=' . $this->objUser->email() . '</argument>
    <argument>-rtpPort=' . $rtpPort . '</argument>
    <argument>-sipPort=' . $sipPort . '</argument>
    <argument>-outboundProxy=' . $outboundProxy . '</argument>
    <argument>-password=' . $password . '</argument>
    <argument>-domain=' . $sipDomain . '</argument>
    <argument>-userpart=' . $userDetails['cellnumber'] . '</argument>
    <argument>-roomname=' . $roomname . '</argument>
    </application-desc>

    <security>
        <all-permissions/>
    </security>
</jnlp>

';

//"rtpPort=8000" "sipPort=6060" "outboundProxy=sip:146.141.76.153;lr" "password=1234" "domain=146.141.76.153" "userpart=1000" "-slidesdir=/"  "-maxstanzas=5" "-admin=true" "-debug=true" "-enabledraw=true"  "-skinclass=null"  "-audiovideourl=localhost:7070"  "-serverport=5222" "-serverhost=localhost"   "-mode=0" "-plugins=org.avoir.realtime.user.UserManager#org.avoir.realtime.roommanager.RoomManager#org.avoir.realtime.chat.ChatManager#org.avoir.realtime.presentations.PresentationManager#org.avoir.realtime.whiteboard.WhiteboardManager#org.avoir.realtime.audio.AudioManager"
//this ought to be in useriles
        $myFile = $modPath . '/rtt/resources/' . $this->objUser->userid() . '.jnlp';
        //chmod($modPath.'/rtt/resources/', 0777);
        $fh = fopen($myFile, 'w') or die("can't open file");
        fwrite($fh, $str);
        fclose($fh);

        chmod($myFile, 0777);
    }

    function generateDemoJNLP($nickname) {
        $objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
        $servletURL = $objSysConfig->getValue('SERVLETURL', 'rtt');
        $plugins = $objSysConfig->getValue('PLUGINS', 'rtt');
        $openfireHost = $objSysConfig->getValue('OPENFIRE_HOST', 'rtt');
        $openfirePort = $objSysConfig->getValue('OPENFIRE_CLIENT_PORT', 'rtt');
        $openfireHttpBindUrl = $objSysConfig->getValue('OPENFIRE_HTTP_BIND', 'rtt');
        $skinclass = $objSysConfig->getValue('SKINCLASS', 'rtt');
        $skinjars = $objSysConfig->getValue('SKINJAR', 'rtt');
        $rtpPort = $objSysConfig->getValue('RTPPORT', 'rtt');
        $sipPort = $objSysConfig->getValue('SIPPORT', 'rtt');
        $outboundProxy = $objSysConfig->getValue('OUTBOUNDPROXY', 'rtt');
        $sipDomain = $objSysConfig->getValue('SIPDOMAIN', 'rtt');

        $objAltConfig = $this->getObject('altconfig', 'config');
        $modPath = $objAltConfig->getModulePath();
        $moduleUri = $objAltConfig->getModuleURI();
        $siteRoot = $objAltConfig->getSiteRoot();
        $codebase = $siteRoot . "/" . $moduleUri . '/rtt/resources/';
        $enableDraw = 'true';
        $roomname = 'chisimba';
        $password = '1234';
        $callnumber='1000';
        $str =
                '<jnlp spec="1.0+" codebase="' . $codebase . '">
    <information>
        <title>Realtime Communication Tools</title>
        <vendor>AVOIR</vendor>
        <description>Realtime Communication Tools</description>
        <homepage href="http://www.chisimba.com"/>
        <description kind="short">rtt</description>
        <icon href="' . $codebase . '/images/logo.png"/>
        <icon kind="splash" href="' . $codebase . '/images/splash_rtt.png"/>
        <offline-allowed/>
    </information>
    <resources>
        <j2se version="1.5+" />

        <jar href="commons-collections-3.1-rt.jar" />
        <jar href="commons-logging-api-rt.jar" />
        <jar href="quartz-all-1.6.0.jar" />
        <jar href="jta-rt.jar" />
        <jar href="PgsLookAndFeel.jar"/>
        <jar href="l2fprod-common-all.jar"/>
        <jar href="kunstsoff-rt.jar" />
        <jar href="smack.jar" />
        <jar href="smackx.jar" />
        <jar href="looks-2.3.0.jar" />
        <jar href="realtime2-chatmanager.jar" />
        <jar href="realtime2-presentations.jar" />
        <jar href="realtime2-audio.jar" />
        <jar href="realtime2-core.jar" />
        <jar href="realtime2-usermanager.jar" />
        <jar href="realtime2-roommanager.jar" />
        <jar href="realtime2-whiteboard.jar" />
</resources>


   <application-desc    main-class="org.avoir.realtime.core.Main">
    <argument>-slidesdir=/</argument>
    <argument>-maxstanzas=5</argument>
    <argument>-admin=' . $enableDraw . '</argument>
    <argument>-debug=true</argument>
    <argument>-enabledraw=' . $enableDraw . '</argument>
    <argument>-skinclass=null </argument>
    <argument>-audiovideourl=' . $openfireHttpBindUrl . '</argument>
    <argument>-serverport=' . $openfirePort . '</argument>
    <argument>-serverhost=' . $openfireHost . '</argument>
    <argument>-mode=1</argument>
    <argument>-plugins=' . $plugins . '</argument>
    <argument>-username=' . $nickname . '</argument>
    <argument>-names=' . $nickname . '</argument>
    <argument>-email=' . $nickname . '@chisimba.com</argument>
    <argument>-rtpPort=' . $rtpPort . '</argument>
    <argument>-sipPort=' . $sipPort . '</argument>
    <argument>-outboundProxy=' . $outboundProxy . '</argument>
    <argument>-password=' . $password . '</argument>
    <argument>-domain=' . $sipDomain . '</argument>
    <argument>-userpart=' . $callnumber . '</argument>
    <argument>-roomname=' . $roomname . '</argument>
    </application-desc>

    <security>
        <all-permissions/>
    </security>
</jnlp>

';

        $myFile = $modPath . '/rtt/resources/' . $nickname . '.jnlp';
        //chmod($modPath.'/rtt/resources/', 0777);
        $fh = fopen($myFile, 'w') or die("can't open file");
        fwrite($fh, $str);
        fclose($fh);

        //chmod($myFile, 0777);
        return $myFile;
    }

}

?>
