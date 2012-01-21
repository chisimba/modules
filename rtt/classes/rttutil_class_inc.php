<?php

class rttutil extends object {

    function init() {
        $this->objUser = $this->getObject('user', 'security');
        $this->objContext = $this->getObject('dbcontext', 'context');
        $this->objDBRttJnlp = $this->getObject("dbrttjnlp");
        $this->objDbRttUser = $this->getObject('dbrttusers', 'rtt');
    }

    function genRandomString() {
        $length = 32;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $string = '';
        for ($p = 0; $p < $length; $p++) {
            $string .= $characters[mt_rand(0, strlen($characters) - 1)];
        }
        return $string;
    }

    function runJNLP() {

        $objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
        $title = $objSysConfig->getValue('TITLE', 'rtt');
        $vendor = $objSysConfig->getValue('VENDOR', 'rtt');
        $description = $objSysConfig->getValue('DESCRIPTION', 'rtt');
        $homePageRef = $objSysConfig->getValue('HOMEPAGE', 'rtt');

        $openfireXMPPHost = $objSysConfig->getValue('OPENFIRE_XMPP_HOST', 'rtt');
        $openfireXMPPPort = $objSysConfig->getValue('OPENFIRE_XMPP_PORT', 'rtt');
        $openfireHTTPHost = $objSysConfig->getValue('OPENFIRE_HTTP_HOST', 'rtt');
        $openfireHTTPPort = $objSysConfig->getValue('OPENFIRE_HTTP_PORT', 'rtt');

        $sipDomain = $objSysConfig->getValue('SIP_DOMAIN', 'rtt');
        $outboundProxy = $objSysConfig->getValue('OUTBOUND_PROXY', 'rtt');
        $conferenceNumber = $objSysConfig->getValue('CONFERENCE_NUMBER', 'rtt');
        $sipPort = $objSysConfig->getValue('SIP_PORT', 'rtt');
        $rtpPort = $objSysConfig->getValue('RTP_PORT', 'rtt');
        $chatWelcomeMessage = $objSysConfig->getValue('CHAT_WELCOME_MESSAGE', 'rtt');
        $debug = $objSysConfig->getValue('DEBUG', 'rtt');
        $jnlpPath = $objSysConfig->getValue('JNLP_PATH', 'rtt');
        $baseUrl = $objSysConfig->getValue('BASE_URL', 'rtt');
        $isDemo = $objSysConfig->getValue('IS_DEMO', 'rtt');
        $roomName = $objSysConfig->getValue('DEFAULT_ROOM', 'rtt');
        $defaultSIPPwd=$objSysConfig->getValue('DEFAULT_SIP_PWD', 'rtt');
        
        
        $this->objContext = $this->getObject('dbcontext', 'context');
        if ($this->objContext->isInContext()) {
            $roomName =  $this->objContext->getContextCode();
        }
        $paramsBaseUrl = $objSysConfig->getValue('PARAMS_BASE_URL', 'rtt');

        $videoBroadcastUrl = $objSysConfig->getValue('VIDEO_BROADCAST_URL', 'rtt');
        $videoFeedUrl = $objSysConfig->getValue('VIDEO_FEED_URL', 'rtt');

        $objAltConfig = $this->getObject('altconfig', 'config');
        $modPath = $objAltConfig->getModulePath();
        $moduleUri = $objAltConfig->getModuleURI();
        $siteRoot = $objAltConfig->getSiteRoot();
        $codebase = $siteRoot . "/" . $moduleUri . '/rtt/resources/';
        $admin = $this->objUser->isAdmin() ? 'true' : 'false';
        $userId = $this->genRandomString();
        $password = $this->genRandomString();
        $user = array("userid" => $userId, "password" => $password, "createdon" => strftime('%Y-%m-%d %H:%M:%S', mktime()));
        $this->objDbRttUser->saveRttUser($user);

        $properties = array(
            //array("jnlp_key" => "-params_baseurl", "jnlp_value" => $paramsBaseUrl,"userid"=>$userId,"createdon"=>strftime('%Y-%m-%d %H:%M:%S', mktime())),
            array("jnlp_key" => "-maxstanzas", "jnlp_value" => '5', "userid" => $userId, "createdon" => strftime('%Y-%m-%d %H:%M:%S', mktime())),
            array("jnlp_key" => "-baseurl", "jnlp_value" => $baseUrl, "userid" => $userId, "createdon" => strftime('%Y-%m-%d %H:%M:%S', mktime())),
            array("jnlp_key" => "-broadcastvideourl", "jnlp_key" => $videoBroadcastUrl, "userid" => $userId, "createdon" => strftime('%Y-%m-%d %H:%M:%S', mktime())),
            array("jnlp_key" => "-receivervideourl", "jnlp_value" => $videoFeedUrl, "userid" => $userId, "createdon" => strftime('%Y-%m-%d %H:%M:%S', mktime())),
            array("jnlp_key" => "-debug", "jnlp_value" => $debug, "userid" => $userId, "createdon" => strftime('%Y-%m-%d %H:%M:%S', mktime())),
            array("jnlp_key" => "-admin", "jnlp_value" => $admin, "userid" => $userId, "createdon" => strftime('%Y-%m-%d %H:%M:%S', mktime())),
            array("jnlp_key" => "-enabledraw", "jnlp_value" => "true", "userid" => $userId, "createdon" => strftime('%Y-%m-%d %H:%M:%S', mktime())),
            array("jnlp_key" => "-skinclass", "jnlp_value" => "null", "userid" => $userId, "createdon" => strftime('%Y-%m-%d %H:%M:%S', mktime())),
            array("jnlp_key" => "-httpbindhost", "jnlp_value" => $openfireHTTPHost, "userid" => $userId, "createdon" => strftime('%Y-%m-%d %H:%M:%S', mktime())),
            array("jnlp_key" => "-httpbindport", "jnlp_value" => $openfireHTTPPort, "userid" => $userId, "createdon" => strftime('%Y-%m-%d %H:%M:%S', mktime())),
            array("jnlp_key" => "-serverport", "jnlp_value" => $openfireXMPPPort, "userid" => $userId, "createdon" => strftime('%Y-%m-%d %H:%M:%S', mktime())),
            array("jnlp_key" => "-serverhost", "jnlp_value" => $openfireXMPPHost, "userid" => $userId, "createdon" => strftime('%Y-%m-%d %H:%M:%S', mktime())),
            array("jnlp_key" => "-plugins", "jnlp_value" => "null", "userid" => $userId, "createdon" => strftime('%Y-%m-%d %H:%M:%S', mktime())),
            array("jnlp_key" => "-username", "jnlp_value" => $this->objUser->username(), "userid" => $userId, "createdon" => strftime('%Y-%m-%d %H:%M:%S', mktime())),
            array("jnlp_key" => "-names", "jnlp_value" => $this->objUser->fullname(), "userid" => $userId, "createdon" => strftime('%Y-%m-%d %H:%M:%S', mktime())),
            array("jnlp_key" => "-rtpPort", "jnlp_value" => $rtpPort, "userid" => $userId, "createdon" => strftime('%Y-%m-%d %H:%M:%S', mktime())),
            array("jnlp_key" => "-sipPort", "jnlp_value" => $sipPort, "userid" => $userId, "createdon" => strftime('%Y-%m-%d %H:%M:%S', mktime())),
            array("jnlp_key" => "-isdemo", "jnlp_value" => $isDemo, "userid" => $userId, "createdon" => strftime('%Y-%m-%d %H:%M:%S', mktime())),
            array("jnlp_key" => "-outboundProxy", "jnlp_value" => $outboundProxy, "userid" => $userId, "createdon" => strftime('%Y-%m-%d %H:%M:%S', mktime())),
            array("jnlp_key" => "-password", "jnlp_value" => $defaultSIPPwd, "userid" => $userId, "createdon" => strftime('%Y-%m-%d %H:%M:%S', mktime())),
            array("jnlp_key" => "-domain", "jnlp_value" => $sipDomain, "userid" => $userId, "createdon" => strftime('%Y-%m-%d %H:%M:%S', mktime())),
            array("jnlp_key" => "-userpart", "jnlp_value" => "1000", "userid" => $userId, "createdon" => strftime('%Y-%m-%d %H:%M:%S', mktime())),
            array("jnlp_key" => "-conferencenumber", "jnlp_value" => $conferenceNumber, "userid" => $userId, "createdon" => strftime('%Y-%m-%d %H:%M:%S', mktime())),
            array("jnlp_key" => "-roomname", "jnlp_value" => $roomName, "userid" => $userId, "createdon" => strftime('%Y-%m-%d %H:%M:%S', mktime())),
            array("jnlp_key" => "-chatwelcomemessage", "jnlp_value" => $chatWelcomeMessage, "userid" => $userId, "createdon" => strftime('%Y-%m-%d %H:%M:%S', mktime())),
            array("jnlp_key" => "-startupcomponent", "jnlp_value" => "startup", "userid" => $userId, "createdon" => strftime('%Y-%m-%d %H:%M:%S', mktime())),
            array("jnlp_key" => "-processid", "jnlp_value" => "-1", "userid" => $userId, "createdon" => strftime('%Y-%m-%d %H:%M:%S', mktime()))
        );

        foreach ($properties as $property) {
            $this->objDBRttJnlp->saveParams($property);
        }
        $iconPath = $codebase . 'images/splash_rtt.png';
        $jnlpPath = $objAltConfig->getModulePath() . '/rtt/resources';

        $content =
                "<jnlp spec=\"1.0+\" codebase=\"" . $codebase . "\">\n"
                . "    <information>\n"
                . "        <title>" . $title . "</title>\n"
                . "        <vendor>" . $vendor . "</vendor>\n"
                . "        <description>" . $description . "</description>\n"
                . "        <homepage href=\"" . $homePageRef . "\"/>\n"
                . "        <description kind=\"short\">rtt</description>\n"
                . "        <icon href=\"" . $iconPath . "\"/>\n"
                . "        <icon kind=\"splash\" href=\"" . $iconPath . "\"/>\n"
                . "        <offline-allowed/>\n"
                . "    </information>\n";


        $content .= "<resources os=\"Windows\" arch=\"x86\">\n"
                . "     <jar href=\"swt-win.jar\" />\n"
                . "</resources>\n"
                . " <resources os=\"Linux\">\n"
                . "   <jar href=\"swt-linux.jar\" />\n"
                . "  </resources>\n"
                . " <resources os=\"Mac OS X\">\n"
                . "   <j2se version=\"1.6*\" java-vm-args=\"-XstartOnFirstThread\"/>\n"
                . "   <jar href=\"swt-mac.jar\"/>\n"
                . " </resources>";

        $content .= "<resources>\n";


        if (is_dir($jnlpPath)) {
            if ($dh = opendir($jnlpPath)) {
                while (($file = readdir($dh)) !== false) {
                    $path_info = pathinfo($jnlpPath . '/' . $file);
                    $ext = $path_info['extension'];
                    $startsWith = substr($file, 0, 3);
                    if ($ext == 'jar' && $startsWith != 'swt') {
                        $content .= "<jar href=\"" . $file . "\" />\n";
                    }
                }
                closedir($dh);
            }
        }


        $rawbasecode = $siteRoot . 'index.php?module=rtt&action=restservice&args=';
        $content .= "</resources>\n"
                . "   <application-desc    main-class=\"org.avoir.rtt.core.Main\">\n"
                . "    <argument>" . $userId . "</argument>\n"
                . "    <argument>" . $password . "</argument>\n"
                . "    <argument>" . $rawbasecode . "</argument>\n"
                . "    <argument>" . $paramsBaseUrl . "</argument>\n"
                . "    </application-desc>\n"
                . "    <security>\n"
                . "        <all-permissions/>\n"
                . "    </security>\n"
                . "</jnlp>\n";


        header("Pragma: public"); // required 
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private", false); // required for certain browsers 
        header('Content-Type: application/x-java-jnlp-file');
        header('Content-Disposition: attachment;filename="meeting.jnlp"');
        header('Cache-Control: max-age=0');
        $fp = fopen('php://output', 'w');
        fwrite($fp, $content);
        fclose($fp);
    }

    function generateJNLP($roomname='') {
        $objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
        $servletURL = $objSysConfig->getjnlp_value('SERVLETURL', 'rtt');
        $plugins = $objSysConfig->getjnlp_value('PLUGINS', 'rtt');
        $openfireHost = $objSysConfig->getjnlp_value('OPENFIRE_HOST', 'rtt');
        $openfirePort = $objSysConfig->getjnlp_value('OPENFIRE_CLIENT_PORT', 'rtt');
        $openfireHttpBindUrl = $objSysConfig->getjnlp_value('OPENFIRE_HTTP_BIND', 'rtt');
        $skinclass = $objSysConfig->getjnlp_value('SKINCLASS', 'rtt');
        $skinjars = $objSysConfig->getjnlp_value('SKINJAR', 'rtt');
        $rtpPort = $objSysConfig->getjnlp_value('RTPPORT', 'rtt');
        $sipPort = $objSysConfig->getjnlp_value('SIPPORT', 'rtt');
        $outboundProxy = $objSysConfig->getjnlp_value('OUTBOUNDPROXY', 'rtt');
        $sipDomain = $objSysConfig->getjnlp_value('SIPDOMAIN', 'rtt');

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
        <jar href="l2fprod-common-all.jar"/>
        <jar href="kunstsoff-rt.jar" />
        <jar href="smack.jar"/>
        <jar href="smackx.jar"/>
        <jar href="smackx-debug.jar"/>
        <jar href="smack-bosh-3.2.0-SNAPSHOT-jar-with-dependencies.jar"/>
        <jar href="xmlpull_1_1_3_4c.jar"/>
         <jar href="google-api-translate-java-0.94.jar" />
        <jar href="proxy-vole_20100914.jar" />
        <jar href="looks-2.3.0.jar" />
        <jar href="rtt-2.0.0.jar" />
        <jar href="jspeex.jar" />
        <jar href="microba-0.4.4.3.jar" />
       
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

    function generateDemoJNLP($nickname, $username) {

        $objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
        $servletURL = $objSysConfig->getjnlp_value('SERVLETURL', 'rtt');
        $plugins = $objSysConfig->getjnlp_value('PLUGINS', 'rtt');
        $openfireHost = $objSysConfig->getjnlp_value('OPENFIRE_HOST', 'rtt');
        $openfirePort = $objSysConfig->getjnlp_value('OPENFIRE_CLIENT_PORT', 'rtt');
        $openfireHttpBindUrlHost = $objSysConfig->getjnlp_value('OPENFIRE_HTTP_BIND_HOST', 'rtt');
        $openfireHttpBindUrlPort = $objSysConfig->getjnlp_value('OPENFIRE_HTTP_BIND_PORT', 'rtt');
        $skinclass = $objSysConfig->getjnlp_value('SKINCLASS', 'rtt');
        $skinjars = $objSysConfig->getjnlp_value('SKINJAR', 'rtt');
        $rtpPort = $objSysConfig->getjnlp_value('RTPPORT', 'rtt');
        $sipPort = $objSysConfig->getjnlp_value('SIPPORT', 'rtt');
        $outboundProxy = $objSysConfig->getjnlp_value('OUTBOUNDPROXY', 'rtt');
        $sipDomain = $objSysConfig->getjnlp_value('SIPDOMAIN', 'rtt');
        $languageBundles = $objSysConfig->getjnlp_value('LANGUAGE_BUNDLES', 'rtt');
        $objAltConfig = $this->getObject('altconfig', 'config');
        $modPath = $objAltConfig->getModulePath();
        $moduleUri = $objAltConfig->getModuleURI();
        $siteRoot = $objAltConfig->getSiteRoot();
        $codebase = $siteRoot . "/" . $moduleUri . '/rtt/resources/';
        $enableDraw = 'true';
        $roomname = 'chisimba';
        $password = '1234';
        $callnumber = '1000';
        $str =
                '<jnlp spec="1.0+" codebase="' . $codebase . '">
    <information>
        <title>Realtime Communication Tools</title>
        <vendor>AVOIR</vendor>
        <description>Realtime Communication Tools</description>
        <homepage href="http://www.chisimba.com"/>
        <description kind="short">rtt</description>';
        /* <icon href="' . $codebase . '/images/logo.png"/>
          <icon kind="splash" href="' . $codebase . '/images/splash_rtt.png"/>
         */ $str.='
<offline-allowed/>
    </information>
    <resources>
       <j2se version="1.6+" />
        <jar href="commons-collections-3.1-rt.jar" />
        <jar href="commons-logging-api-rt.jar" />
        <jar href="quartz-all-1.6.0.jar" />
        <jar href="jta-rt.jar" />
        <jar href="PgsLookAndFeel.jar"/>
        <jar href="l2fprod-common-all.jar"/>
        <jar href="kunstsoff-rt.jar" />
        <jar href="smack.jar"/>
        <jar href="smackx.jar"/>
        <jar href="smackx-debug.jar"/>
        <jar href="httpcore-4.0.1.jar"/>
        <jar href="apache-mime4j-0.6.jar"/>
        <jar href="commons-codec-1.3.jar"/>
        <jar href="commons-logging-1.1.1.jar"/>
        <jar href="httpclient-4.0.3.jar"/>
        <jar href="httpmime-4.0.3.jar"/>
        <jar href="xmlpull_1_1_3_4c.jar"/>
        <jar href="xpp3-1.1.4c.jar"/>
         <jar href="google-api-translate-java-0.95.jar" />
        <jar href="proxy-vole_20100914.jar" />
        <jar href="looks-2.3.0.jar" />
        <jar href="json-org.jar" />
        <jar href="rtt-2.0.0.jar" />
        <jar href="jspeex.jar" />
        <jar href="microba-0.4.4.3.jar" />
        <jar href="icepdf-viewer.jar" />
        <jar href="icepdf-core.jar" />
        <jar href="serializable-doc-0.1.jar" />
</resources>
';


        $str.='
   <application-desc    main-class="org.avoir.rtt.core.Main">
    <argument>-slidesdir=/</argument>
    <argument>-maxstanzas=5</argument>
    <argument>-admin=true</argument>
    <argument>-debug=true</argument>
    <argument>-enabledraw=true</argument>
    <argument>-skinclass=null</argument>
    <argument>-httpbindhost=173.230.134.28</argument>
    <argument>-httpbindport=80</argument>
    <argument>-serverport=' . $openfirePort . '</argument>
    <argument>-serverhost=' . $openfireHost . '</argument>
    <argument>-mode=1</argument>
    <argument>-plugins=org.avoir.rtt.ruc.RucManager#org.avoir.rtt.whiteboard.WhiteboardManager</argument>
    <argument>-username=' . $username . '</argument>
    <argument>-names=' . $nickname . '</argument>
    <argument>-email=demo@chisimba.com</argument>
    <argument>-rtpPort=' . $rtpPort . '</argument>
    <argument>-sipPort=' . $sipPort . '</argument>
    <argument>-isdemo=true</argument>
    <argument>-outboundProxy=sip:173.230.134.28;lr</argument>
    <argument>-password=1234</argument>
    <argument>-domain=173.230.134.28</argument>
    <argument>-userpart=7000</argument>
    <argument>-conferencenumber=3000</argument>
    <argument>-roomname=' . $roomname . '</argument>
    <argument>-chatwelcomemessage=This is a custom chat welcome message</argument>
    </application-desc>
    <security>
        <all-permissions/>
    </security>
</jnlp>

';

        $myFile = $modPath . '/rtt/resources/' . $username . '.jnlp';
        //chmod($modPath.'/rtt/resources/', 0777);
        $fh = fopen($myFile, 'w') or die("can't open file");
        fwrite($fh, $str);
        fclose($fh);

        //chmod($myFile, 0777);
        return $myFile;
    }

}

?>
