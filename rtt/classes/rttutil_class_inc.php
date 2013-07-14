<?php

class rttutil extends object {

    function init() {
        $this->objUser = $this->getObject('user', 'security');
        $this->objContext = $this->getObject('dbcontext', 'context');
        $this->objDBRttJnlp = $this->getObject("dbrttjnlp");
        $this->objDbRttUser = $this->getObject('dbrttusers', 'rtt');
        $this->objDbUserparamsadmin = & $this->getObject("dbuserparamsadmin", "userparamsadmin");
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

    function checkSipParams() {
        $userparamsObj = $this->objDbUserparamsadmin->readConfig();

        $userparams = $userparamsObj->toArray();
        $userparams = $userparams['root']['Settings'];
        $userpart = "-1";
        $userpartArray = $userparams[0];
        if (!key_exists("rtt_username", $userpartArray)) {

            $pname = "rtt_username";
            $ptag = "0000";
            $this->objDbUserparamsadmin->writeProperties("add", $this->objUser->userId(), $pname, $ptag);
        }
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
        $defaultSIPPwd = $objSysConfig->getValue('DEFAULT_SIP_PWD', 'rtt');
        $userparamsObj = $this->objDbUserparamsadmin->readConfig();

        $userparams = $userparamsObj->toArray();
        $userparams = $userparams['root']['Settings'];
        $userpartArray = $userparams[0];

        $userpart = "-1";
        if (key_exists("rtt_username", $userpartArray)) {
            $userpart = $userpartArray['rtt_username'];
        }

       
        $this->objContext = $this->getObject('dbcontext', 'context');
        if ($this->objContext->isInContext()) {

            $roomName = $this->objContext->getContextCode();
        }

        $paramsBaseUrl = $objSysConfig->getValue('PARAMS_BASE_URL', 'rtt');

        $videoBroadcastUrl = $objSysConfig->getValue('VIDEO_BROADCAST_URL', 'rtt');
        $videoFeedUrl = $objSysConfig->getValue('VIDEO_FEED_URL', 'rtt');

        $objAltConfig = $this->getObject('altconfig', 'config');
        $modPath = $objAltConfig->getModulePath();
        $moduleUri = $objAltConfig->getModuleURI();
        $siteRoot = $objAltConfig->getSiteRoot();
        $codebase = $siteRoot . "/" . $moduleUri . '/rtt/resources/';
        $admin = $this->objUser->isLecturer() ? 'true' : 'false';
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
            array("jnlp_key" => "-username", "jnlp_value" => $userId, "userid" => $userId, "createdon" => strftime('%Y-%m-%d %H:%M:%S', mktime())),
            array("jnlp_key" => "-names", "jnlp_value" => $this->objUser->fullname(), "userid" => $userId, "createdon" => strftime('%Y-%m-%d %H:%M:%S', mktime())),
            array("jnlp_key" => "-isdemo", "jnlp_value" => $isDemo, "userid" => $userId, "createdon" => strftime('%Y-%m-%d %H:%M:%S', mktime())),
            array("jnlp_key" => "-roomname", "jnlp_value" => $roomName, "userid" => $userId, "createdon" => strftime('%Y-%m-%d %H:%M:%S', mktime())),
            array("jnlp_key" => "-chatwelcomemessage", "jnlp_value" => $chatWelcomeMessage, "userid" => $userId, "createdon" => strftime('%Y-%m-%d %H:%M:%S', mktime())),
            array("jnlp_key" => "-sipOutboundProxy", "jnlp_value" => $outboundProxy, "userid" => $userId, "createdon" => strftime('%Y-%m-%d %H:%M:%S', mktime())),
            array("jnlp_key" => "-sipPassword", "jnlp_value" => $defaultSIPPwd, "userid" => $userId, "createdon" => strftime('%Y-%m-%d %H:%M:%S', mktime())),
            array("jnlp_key" => "-sipDomain", "jnlp_value" => $sipDomain, "userid" => $userId, "createdon" => strftime('%Y-%m-%d %H:%M:%S', mktime())),
            array("jnlp_key" => "-sipUsername", "jnlp_value" => $userpart, "userid" => $userId, "createdon" => strftime('%Y-%m-%d %H:%M:%S', mktime())),
            array("jnlp_key" => "-sipConferenceNumber", "jnlp_value" => $conferenceNumber, "userid" => $userId, "createdon" => strftime('%Y-%m-%d %H:%M:%S', mktime())),
        );

        $iconPath = $codebase . 'images/splash_rtt.png';
        $jnlpPath = $objAltConfig->getModulePath() . '/rtt/resources';

        $args = "";
        foreach ($properties as $property) {
            //    $this->objDBRttJnlp->saveParams($property);
            $args .= $property['jnlp_key'] . "=" . $property['jnlp_value'] . "!";
        }

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


        /*
          $content .= "<resources os=\"Windows\" arch=\"x86\">\n"
          . "     <jar href=\"swt-win.jar\" />\n"
          . "</resources>\n"
          . " <resources os=\"Linux\">\n"
          . "   <jar href=\"swt-linux.jar\" />\n"
          . "  </resources>\n"
          . " <resources os=\"Mac OS X\">\n"
          . "   <j2se version=\"1.6*\" java-vm-args=\"-XstartOnFirstThread\"/>\n"
          . "   <jar href=\"swt-mac.jar\"/>\n"
          . " </resources>"; */

        $content .= "<resources>\n";


        if (is_dir($jnlpPath)) {
            if ($dh = opendir($jnlpPath)) {
                while (($file = readdir($dh)) !== false) {
                    $path_info = pathinfo($jnlpPath . '/' . $file);
                    if (array_key_exists('extension', $path_info)) {
                        $ext = $path_info['extension'];
                        $startsWith = substr($file, 0, 3);
                        if ($ext == 'jar' && $startsWith != 'swt') {
                            $content .= "<jar href=\"" . $file . "\" />\n";
                        }
                    }
                }
                closedir($dh);
            }
        }


        $rawbasecode = $siteRoot . 'index.php?module=rtt&action=restservice&args=';
        $content .= "</resources>\n"
                . "   <application-desc    main-class=\"org.avoir.rtt.core.Main\">\n"
                . "    <argument>" . $args . "</argument>\n"
                /* . "    <argument>" . $password . "</argument>\n"
                  . "    <argument>" . $rawbasecode . "</argument>\n"
                  . "    <argument>" . $paramsBaseUrl . "</argument>\n" */
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

    /**
     * Generates the jnlp for running the softphone
     */
    function generateVoiceAppJNLP() {

        $objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
        $title = $objSysConfig->getValue('TITLE', 'rtt');
        $vendor = $objSysConfig->getValue('VENDOR', 'rtt');
        $description = $objSysConfig->getValue('DESCRIPTION', 'rtt');
        $homePageRef = $objSysConfig->getValue('HOMEPAGE', 'rtt');


        $sipDomain = $objSysConfig->getValue('SIP_DOMAIN', 'rtt');
        $outboundProxy = $objSysConfig->getValue('OUTBOUND_PROXY', 'rtt');
        $conferenceNumber = $objSysConfig->getValue('CONFERENCE_NUMBER', 'rtt');
        $sipPort = $objSysConfig->getValue('SIP_PORT', 'rtt');
        $rtpPort = $objSysConfig->getValue('RTP_PORT', 'rtt');
        $chatWelcomeMessage = $objSysConfig->getValue('CHAT_WELCOME_MESSAGE', 'rtt');
        $debug = $objSysConfig->getValue('DEBUG', 'rtt');
        $jnlpPath = $objSysConfig->getValue('SOFTPHONE_JNLP_PATH', 'rtt');
        $baseUrl = $objSysConfig->getValue('BASE_URL', 'rtt');
        $isDemo = $objSysConfig->getValue('IS_DEMO', 'rtt');
        $roomName = $objSysConfig->getValue('DEFAULT_ROOM', 'rtt');
        $defaultSIPPwd = $objSysConfig->getValue('DEFAULT_SIP_PWD', 'rtt');
        $userpart = $this->objUser->getStaffNumber();
        $objAltConfig = $this->getObject('altconfig', 'config');
        $modPath = $objAltConfig->getModulePath();
        $moduleUri = $objAltConfig->getModuleURI();
        $siteRoot = $objAltConfig->getSiteRoot();
        $codebase = $siteRoot . "/" . $moduleUri . '/rtt/resources/';

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




        $content .= " <resources>\n"
                . "   <jar href=\"l2fprod-common-all.jar\" />\n"
                . "   <jar href=\"looks-2.4.2.jar\" />\n"
                . "   <jar href=\"org.freeswitch.esl.client-0.9.3.jar\" />\n"
                . "   <jar href=\"PgsLookAndFeel.jar\" />\n"
                . "   <jar href=\"lico-softphone-0.1.0.jar\" />\n"
                . "   <jar href=\"netty-3.2.6.Final.jar\" />\n"
                . "   <jar href=\"peers-lib-0.5-SNAPSHOT.jar\" />\n"
                . "   <jar href=\"slf4j-api-1.6.4.jar\" />\n"
                . "  </resources>\n"
                . "   <application-desc    main-class=\" org.licosystems.softphone.LicoSoftphone\">\n"
                . "    <argument>" . $outboundProxy . "</argument>\n"
                . "    <argument>" . $defaultSIPPwd . "</argument>\n"
                . "    <argument>" . $sipDomain . "</argument>\n"
                . "    <argument>" . $conferenceNumber . "</argument>\n"
                . "    <argument>" . $userpart . "</argument>\n"
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
        header('Content-Disposition: attachment;filename="softphone.jnlp"');
        header('Cache-Control: max-age=0');
        $fp = fopen('php://output', 'w');
        fwrite($fp, $content);
        fclose($fp);
    }

}

?>
