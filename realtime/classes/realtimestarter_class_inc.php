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
        public function generateURL($id,$agenda,$resourcesPath,$appletCodeBase,$slidesDir,$username,$fullnames,$userLevel,$slideServerId){
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
    }
?>