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
         * Starts the slide server with own id
         */
        function startSlidesServer()
        {
    
            $objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
            $slideServerId=$this->objConfig->serverName();
            $cmd = "java -Xms64m -Xmx128m -cp ".    
            $this->objConfig->getModulePath().
    "/realtime/resources/realtime-base-0.1.jar:".$this->objConfig->getModulePath().
    "/realtime/resources/realtime-launcher-0.1.jar avoir.realtime.tcp.base.SlidesServer ".$slideServerId."  >/dev/null &";
            system($cmd,$return_value);
        }

        /**
         * Generates a URL to be used to replace the filter
         * @param <type> $id
         * @param <type> $agenda
         * @return <type>
         */
        public function generateURL($id,$agenda){

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
            $resourcesPath="/var/www/app/chisimba_modules/realtime/resources";
            $this->startSlidesServer();
            $objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
            $slideServerId=$this->objConfig->serverName();
            $appletCodeBase='http://chameleon.uwc.ac.za/app/chisimba_modules/realtime/resources/';
            $slidesDir='http://chameleon.uwc.ac.za/app/usrfiles/webpresent/';
            $url='<center>';
            $url.='<applet codebase="'.$appletCodeBase.'"';
            $url.='code="avoir.realtime.tcp.launcher.RealtimeLauncher" name ="Avoir Realtime Applet"';
     
            $url.='archive="realtime-launcher-0.1.jar" width="100%" height="600">';
            $url.='<param name=userName value="'.$this->objUser->userName().'">';
            $url.='<param name=isLocalhost value="true">';
            $url.='<param name=fullname value="'.$this->objUser->fullname().'">';
            $url.='<param name=userLevel value="'.$this->userLevel.'">';
            $url.='<param name=uploadURL value="'.$uploadURL.'">';
            $url.='<param name=chatLogPath value="'.$chatLogPath.'">';
            $url.='<param name=siteRoot value="'.$siteRoot.'">';

            $url.='<param name=isWebPresent value="true">';
            $url.='<param name=isLoggedIn value="'.$isLoggedIn.'">';
            $url.='<param name=slidesDir value="'.$slidesDir.'">';
            $url.='<param name=uploadPath value="'.$uploadPath.'">';
            $url.='<param name=resourcesPath value="'.$resourcesPath.'">';
            $url.='<param name=sessionId value="'.$id.'">';
            $url.='<param name=sessionTitle value="'.$agenda.'">';
            $url.='<param name=slideServerId value="gen12Srv15Nme3">';
    
            $url.='<param name=isSessionPresenter value="false">';
            $url.='</applet>';
    

            return $url;
        } 
    }
?>