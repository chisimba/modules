<?php
    class realtimestarter extends object{

        public  $objConfig;
        /**
         * Constructor
         */
        public function init()
        {
            $this->objLanguage = $this->getObject('language', 'language');
            $this->objUser = $this->getObject('user', 'security');
            $this->objConfig = $this->getObject('altconfig', 'config');

        }

        /**
         * Generates a URL to be used to replace the filter
         * @param <type> $id
         * @param <type> $agenda
         * @return <type>
         */
        public function generateURL($id,$agenda,$resourcesPath,$appletCodeBase,$slidesDir,$username,$fullnames,$userLevel,$slideServerId
        ){
            $url='<center>';
            $url.='<applet codebase="'.$appletCodeBase.'"';
            $url.='code="avoir.realtime.tcp.launcher.RealtimeLauncher" name ="Avoir Realtime Applet"';

            $url.='archive="realtime-launcher-0.1.jar" width="100%" height="600">';
            $url.='<param name=userName value="'.$username.'">';
            $url.='<param name=isLocalhost value="false">';
            $url.='<param name=fullname value="'.$fullnames.'">';
            $url.='<param name=userLevel value="'.$userLevel.'">';
            $url.='<param name=uploadURL value="'.$uploadURL.'">';
            $url.='<param name=chatLogPath value="'.$chatLogPath.'">';
            $url.='<param name=siteRoot value="'.$siteRoot.'">';

            $url.='<param name=isWebPresent value="false">';
            $url.='<param name=isLoggedIn value="'.$isLoggedIn.'">';
            $url.='<param name=slidesDir value="'.$slidesDir.'">';
            $url.='<param name=uploadPath value="'.$uploadPath.'">';
            $url.='<param name=resourcesPath value="'.$resourcesPath.'">';
            $url.='<param name=sessionId value="'.$id.'">';
            $url.='<param name=sessionTitle value="'.$agenda.'">';
            $url.='<param name=slideServerId value="'.$slideServerId.'">';

            $url.='<param name=isSessionPresenter value="false">';
            $url.='</applet>';

            $url.='</center>';

            return $url;
        }


        public function generateJNLP(
            $type,
            $fileBase,
            $appletCodeBase,
            $supernodeHost,
            $superNodePort,
            $username,$fullnames,
            $isPresenter,
            $sessionId,
            $sessionTitle,
            $userDetails,
            $userImagePath,
            $isLoggedIn,
            $siteRoot,
            $resourcesPath,
            $userLevel,
            $chatLogPath,
            $filePath,
            $slideServerId,
            $mediaServerHost,
            $audioMICPort,
            $audioSpeakerPort
           ){

            $jnlpFile = $fileBase.'/'.$type.'_'.$username.'_chisimba_classroom.jnlp';
            $fh = fopen($jnlpFile, 'w') or die("can't open file");

            fwrite($fh,'<?xml version="1.0" encoding="utf-8"?>');
            fwrite($fh,'<jnlp spec="1.0+" ');
            fwrite($fh,      'codebase="'.$appletCodeBase.'" ');
            fwrite($fh,     ' href="'.$type.'_'.$username.'_chisimba_classroom.jnlp">');
            fwrite($fh,   '<information>');
            fwrite($fh,    ' <title>Realtime Classroom</title>');
            fwrite($fh,    ' <vendor>AVOIR</vendor>');
            fwrite($fh,    ' <description>Realtime Classroom</description>');
            fwrite($fh,   ' <homepage href="http://avoir.uwc.ac.za"/>');
            fwrite($fh,    ' <description kind="short">Realtime Virtual Classroom</description>');
            fwrite($fh,    ' <icon href="images/logo.png"/> ');
            fwrite($fh,    ' <icon kind="splash" href="images/splash_realtime.png"/> ');
            fwrite($fh,    ' <offline-allowed/>');
            fwrite($fh,   '</information>');
            fwrite($fh, '<resources>     ');
            fwrite($fh,  	'<jar href="realtime-launcher-1.0.2.jar"/>   ');
            fwrite($fh,	'<j2se version="1.5+"');
            fwrite($fh,	 '     href="http://java.sun.com/products/autodl/j2se"/>');
            fwrite($fh,   '</resources>');
            fwrite($fh,  '<application-desc main-class="avoir.realtime.tcp.launcher.RealtimeLauncher">');
            fwrite($fh,   ' <argument>'.$supernodeHost.'</argument>');
            fwrite($fh,   ' <argument>'.$superNodePort.'</argument>');
            fwrite($fh,   ' <argument>'.$username.'</argument>');
            fwrite($fh,   ' <argument>'.$fullnames.'</argument>');
            fwrite($fh,   ' <argument>'.$isPresenter.'</argument>');
            fwrite($fh,   ' <argument>'.$sessionId.'</argument>');
            fwrite($fh,   ' <argument>'.$sessionTitle.'</argument>');
            fwrite($fh,   ' <argument>'.$userDetails.'</argument>');
            fwrite($fh,   ' <argument>'.$userImagePath.'</argument>');
            fwrite($fh,   ' <argument>'.$isLoggedIn.'</argument>');
            fwrite($fh,   ' <argument>'.$siteRoot.'</argument>');
            fwrite($fh,   ' <argument>'.$resourcesPath.'</argument>');
            fwrite($fh,   ' <argument>'.$userLevel.'</argument>');
            fwrite($fh,   ' <argument>'.$chatLogPath.'</argument>');
            fwrite($fh,   ' <argument>'.$filePath.'</argument>');
            fwrite($fh,   ' <argument>'.$slideServerId.'</argument>');
            fwrite($fh,   ' <argument>'.$mediaServerHost.'</argument>');
            fwrite($fh,   ' <argument>'.$audioMICPort.'</argument>');
            fwrite($fh,   ' <argument>'.$audioSpeakerPort.'</argument>');

            fwrite($fh,   '</application-desc>');
            fwrite($fh,'<security>');
            fwrite($fh,'  <all-permissions/>');
            fwrite($fh,'</security> ');
            fwrite($fh,'</jnlp>');
            fclose($fh);
        }

        public function generateJNLP1_0_2Beta(
            $type,
            $fileBase,
            $appletCodeBase,
            $supernodeHost,
            $superNodePort,
            $username,$fullnames,
            $isPresenter,
            $sessionId,
            $sessionTitle,
            $userDetails,
            $userImagePath,
            $isLoggedIn,
            $siteRoot,
            $resourcesPath,
            $userLevel,
            $chatLogPath,
            $filePath,
            $slideServerId){


            $jnlpFile = $fileBase.'/'.$type.'_'.$username.'_chisimba_classroom.jnlp';
            $fh = fopen($jnlpFile, 'w') or die("can't open file");

            fwrite($fh,'<?xml version="1.0" encoding="utf-8"?>');
            fwrite($fh,'<jnlp spec="1.0+" ');
            fwrite($fh,      'codebase="'.$appletCodeBase.'" ');
            fwrite($fh,     ' href="'.$type.'_'.$username.'_chisimba_classroom.jnlp">');
            fwrite($fh,   '<information>');
            fwrite($fh,    ' <title>Realtime Classroom</title>');
            fwrite($fh,    ' <vendor>AVOIR</vendor>');
            fwrite($fh,    ' <description>Realtime Classroom</description>');
            fwrite($fh,   ' <homepage href="http://avoir.uwc.ac.za"/>');
            fwrite($fh,    ' <description kind="short">Realtime Virtual Classroom</description>');
            fwrite($fh,    ' <icon href="images/logo.png"/> ');
            fwrite($fh,    ' <icon kind="splash" href="images/splash_realtime.png"/> ');
            fwrite($fh,    ' <offline-allowed/>');
            fwrite($fh,   '</information>');
            fwrite($fh, '<resources>     ');
            fwrite($fh,  	'<jar href="realtime-launcher-1.0.2.jar"/>   ');
            fwrite($fh,	'<j2se version="1.5+"');
            fwrite($fh,	 '     href="http://java.sun.com/products/autodl/j2se"/>');
            fwrite($fh,   '</resources>');
            fwrite($fh,  '<application-desc main-class="avoir.realtime.tcp.launcher.RealtimeLauncher">');
            fwrite($fh,   ' <argument>'.$supernodeHost.'</argument>');
            fwrite($fh,   ' <argument>'.$superNodePort.'</argument>');
            fwrite($fh,   ' <argument>'.$username.'</argument>');
            fwrite($fh,   ' <argument>'.$fullnames.'</argument>');
            fwrite($fh,   ' <argument>'.$isPresenter.'</argument>');
            fwrite($fh,   ' <argument>'.$sessionId.'</argument>');
            fwrite($fh,   ' <argument>'.$sessionTitle.'</argument>');
            fwrite($fh,   ' <argument>'.$userDetails.'</argument>');
            fwrite($fh,   ' <argument>'.$userImagePath.'</argument>');
            fwrite($fh,   ' <argument>'.$isLoggedIn.'</argument>');
            fwrite($fh,   ' <argument>'.$siteRoot.'</argument>');
            fwrite($fh,   ' <argument>'.$resourcesPath.'</argument>');
            fwrite($fh,   ' <argument>'.$userLevel.'</argument>');
            fwrite($fh,   ' <argument>'.$chatLogPath.'</argument>');
            fwrite($fh,   ' <argument>'.$filePath.'</argument>');
            fwrite($fh,   ' <argument>'.$slideServerId.'</argument>');
            fwrite($fh,   '</application-desc>');
            fwrite($fh,'<security>');
            fwrite($fh,'  <all-permissions/>');
            fwrite($fh,'</security> ');
            fwrite($fh,'</jnlp>');
            fclose($fh);

            }
        }
    ?>