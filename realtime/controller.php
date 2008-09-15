<?php

    // security check - must be included in all scripts
    if (!$GLOBALS['kewl_entry_point_run'])
    {
        die("You cannot view this page directly");
    }
    // end security check
    /**
    * Realtime Controller
    * This class controls all functionality to run the realtime module.
    * @package realtime
    * @author David Wafula
    * @version $Id$
    */
    class realtime extends controller
    {
        /**
        * @var object $objUser: The user class in the security module
        * @access public
        */
        public $objUser;

        /**
        * @var string $userId: The user id of the currently logged in user
        * @access public
        */
        public $userId;

        /**
        * @var string $userName: The username of the currently logged in user
        * @access public
        */
        public $userName;

        /**
        * @var string $userLevel: The user's access level
        * @access public
        */
        public $userLevel;

        /**
        * @var object $objConfig: The altconfig class in the config module
        * @access public
        */
        public $objConfig;

        /**
        * @var object $objLog: The logactivity class in the logger module
        * @access public
        */
        public $objLog;


        /**
         * @access public
         * @var contexctcode
         */
        public $contextCode;

        /**
         * This points to module root path
         * @var <type>
         */
        public $moduleRootPath;

        /**
         * config object
         * @var <type>
         */
        public $objAltConfig;

        /**
         * Link object
         * @var <type>
         */
        public $objLink;

        /**
         * JOD doc converter path
         * @var <type>
         */
        public $jodconverterPath;

        /**
         * Files object
         * @var <type>
         */
        public $objFiles;

        /**
         *  convert obj
         * @var <type>
         */
        public $converter;

        /**
         * Upload path
         * @var <type>
         */
        public $uploadPath;
        /**
         *For starting the slide server
         * @var <type>
         */
        public  $realtimeManager;

        /**
         * Constructor method to instantiate objects and get variables
         */
        function init()
        {
            $this->objLink= $this->getObject('link', 'htmlelements');
            //Get configuration class
            $this->objConfig =$this->getObject('config','config');

            $this->objAltConfig = $this->getObject('altconfig','config');

            //Get language class
            $this->objLanguage = $this->getObject('language', 'language');

            //Get the activity logger class
            $this->config = $this->getObject('config','config');
            $this->objLog = $this->getObject('logactivity', 'logger');

            //Log this module call
            $this->objLog->log();

            $this->objStarter= $this->getObject('realtimestarter');
            $this->realtimeManager = $this->getObject('realtimemanager','webpresent');
            // classes we need
            $this->objUser = $this->newObject('user', 'security');
            $this->userId = $this->objUser->userId();
            $this->userName = $this->objUser->username($this->userId);
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
            $this->objContext = $this->getObject('dbcontext', 'context');
            $this->objConfig = $this->getObject('altconfig', 'config');
            $location = "http://" . $_SERVER['HTTP_HOST'];

        }

        /**
        * Method to process actions to be taken
        *
        * @param string $action String indicating action to be taken
        */
        function dispatch($action = Null)
        {
            $this->contextCode = $this->objContext->getContextCode();
            switch ($action)
            {
                case 'classroom' :
                $id=$this->getParam('id');
                $title=$this->getParam('agenda');

                return $this->showClassRoom($id,$title);

                default :
                return $this->initClassRoom($this->contextCode);
            }
        }

       /**
         * shows classroom applet to user if the user first entered a context
         */
        public function explainRealtime()
        {

            $desc= $this->objLanguage->code2Txt('mod_realtime_aboutrealtime', 'realtime');
            $title=$this->objLanguage->languageText('mod_realtime_title', 'realtime');
            $this->setVarByRef('title', $title);
            $this->setVarByRef('desc', $desc);
            $this->setVarByRef('content', $desc);
            //$this->setVar('pageSuppressToolbar', FALSE);
            //$this->setVar('pageSuppressBanner', FALSE);
            return "dump_tpl.php";


        }

        public function showClassroom($id,$title){

            $slideServerId=$this->realtimeManager->randomString(32);//'gen19Srv8Nme50';
            $this->realtimeManager->startSlidesServer($slideServerId);

            $chatLogPath = $filePath.'/chat/'.date("Y-m-d-H-i");
            $modPath=$this->objAltConfig->getModulePath();
            $replacewith="";
            $docRoot=$_SERVER['DOCUMENT_ROOT'];
            $appletPath=str_replace($docRoot,$replacewith,$modPath);
            $appletCodeBase="http://" . $_SERVER['HTTP_HOST']."/".$appletPath.'/realtime/resources/';
            $objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
            $supernodeHost=$objSysConfig->getValue('SUPERNODE_HOST', 'realtime');
            $supernodePort=$objSysConfig->getValue('SUPERNODE_PORT', 'realtime');
            $username=$this->objUser->userName();
            $fullnames=$this->objUser->fullname();
            $userDetails=$fullnames.' '.$username;
            $userImagePath='imagepath';//".'.$this->objUser->getSmallUserImage().'"';
            $isLoggedIn =$this->objUser->isLoggedIn();
            $fileBase=$modPath.'/realtime/resources/';
            $resourcesPath =$modPath.'/realtime/resources';
            $desc= $this->objLanguage->code2Txt('mod_realtime_aboutrealtime', 'realtime');
            $filePath=$this->objConfig->getContentBasePath().'/webpresent/'.$id;
            $presenterimage=$this->newObject('image','htmlelements');
            $presenterimage->src='skins/_common/icons/webpresent/startpresentation.png';
            $presenterimage->width="200";
            $presenterimage->height="80";

            $joinimage=$this->newObject('image','htmlelements');
            $joinimage->src='skins/_common/icons/webpresent/joinpresent.png';
            $joinimage->width="200";
            $joinimage->height="80";
            $presentationLink = new link ($this->uri(array('action'=>'view', 'id'=>$id),"webpresent"));
            $presentationLink->link=   'Back To Presentation';

            $siteRoot=$this->objAltConfig->getSiteRoot();
            $presenterLink='<img src="'.$siteRoot.'skins/_common/icons/webpresent/startpresentation.png" width="200" height="80">';
            $joinLink='<img src="'.$siteRoot.'skins/_common/icons/webpresent/joinpresent.png" width="200" height="80">';

            $desc='<li>Add Live interactions to your presentation</li>';
            $desc.='<li>Communicate in realtime through audio/video conferencing.</li>';

            //generate for presenter
            $this->objStarter->generateJNLP('presenter',$fileBase,$appletCodeBase,$supernodeHost,
                $supernodePort,$username,$fullnames,'true',$id,$title,$userDetails,$userImagePath,
                $isLoggedIn,$siteRoot,$resourcesPath,$this->userLevel,$chatLogPath,
                $filePath,$slideServerId);
            //generate for participant
            $this->objStarter->generateJNLP('audience',$fileBase,$appletCodeBase,$supernodeHost,
                $supernodePort,$username,$fullnames,'false',$id,$title,$userDetails,$userImagePath,
                $isLoggedIn,$siteRoot,$resourcesPath,$this->userLevel,$chatLogPath,
                $filePath,$slideServerId);

            $this->setVarByRef('title',  $title);
            $this->setVarByRef('desc', $desc);
            $this->setVarByRef('content', '<a href="'.$appletCodeBase.'/presenter_'.$username.'_chisimba_classroom.jnlp">'.$presenterLink.'</a>-----<a href="'.$appletCodeBase.'/audience_'.$username.'_chisimba_classroom.jnlp">'.$joinLink.'</a> <br><br><h2>'.$presentationLink->show().'</h2>');
            return "dump_tpl.php";
        }
        public function initClassroom($contextCode){
            $slideServerId=$this->realtimeManager->randomString(32);//'gen19Srv8Nme50';
            $this->realtimeManager->startSlidesServer($slideServerId);

            $chatLogPath = $filePath.'/chat/'.date("Y-m-d-H-i");
            $modPath=$this->objAltConfig->getModulePath();
            $replacewith="";
            $docRoot=$_SERVER['DOCUMENT_ROOT'];
            $appletPath=str_replace($docRoot,$replacewith,$modPath);
            $appletCodeBase="http://" . $_SERVER['HTTP_HOST']."/".$appletPath.'/realtime/resources/';
            $objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
            $supernodeHost=$objSysConfig->getValue('SUPERNODE_HOST', 'realtime');
            $supernodePort=$objSysConfig->getValue('SUPERNODE_PORT', 'realtime');
            $username=$this->objUser->userName();
            $fullnames=$this->objUser->fullname();
            $userDetails=$fullnames.' '.$username;
            $userImagePath='imagepath';//".'.$this->objUser->getSmallUserImage().'"';
            $isLoggedIn =$this->objUser->isLoggedIn();
            $fileBase=$modPath.'/realtime/resources/';
            $resourcesPath =$modPath.'/realtime/resources';
            $desc= $this->objLanguage->code2Txt('mod_realtime_aboutrealtime', 'realtime');
            $filePath=$this->objConfig->getContentBasePath().'/webpresent/'.$id;
            $presenterimage=$this->newObject('image','htmlelements');
            $presenterimage->src='skins/_common/icons/webpresent/startpresentation.png';
            $presenterimage->width="200";
            $presenterimage->height="80";
            $title=$this->objLanguage->languageText('mod_realtime_title', 'realtime');

            $joinimage=$this->newObject('image','htmlelements');
            $joinimage->src='skins/_common/icons/webpresent/joinpresent.png';
            $joinimage->width="200";
            $joinimage->height="80";
            $presentationLink = new link ($this->uri(array('action'=>'view', 'id'=>$id),"webpresent"));
            $presentationLink->link=   'Back To Presentation';

            $siteRoot=$this->objAltConfig->getSiteRoot();
            $presenterLink='<img src="'.$siteRoot.'skins/_common/icons/webpresent/startpresentation.png" width="200" height="80">';
            $joinLink='<img src="'.$siteRoot.'skins/_common/icons/webpresent/joinpresent.png" width="200" height="80">';

            $desc='<li>'.$this->objLanguage->languageText('mod_realtime_addlivepresentation', 'realtime').'</li>';
            $desc.='<li>'.$this->objLanguage->languageText('mod_realtime_addaudiovideo', 'realtime').'</li>';

            //generate for presenter
            $this->objStarter->generateJNLP('presenter',$fileBase,$appletCodeBase,$supernodeHost,
                $supernodePort,$username,$fullnames,'true','wb',$title,$userDetails,$userImagePath,
                $isLoggedIn,$siteRoot,$resourcesPath,$this->userLevel,$chatLogPath,
                $filePath,$slideServerId);
            //generate for participant
            $this->objStarter->generateJNLP('audience',$fileBase,$appletCodeBase,$supernodeHost,
                $supernodePort,$username,$fullnames,'false','wb',$title,$userDetails,$userImagePath,
                $isLoggedIn,$siteRoot,$resourcesPath,$this->userLevel,$chatLogPath,
                $filePath,$slideServerId);

            $this->setVarByRef('title',  $title);
            $this->setVarByRef('desc', $desc);
            $this->setVarByRef('content', '<a href="'.$appletCodeBase.'/presenter_'.$username.'_chisimba_classroom.jnlp">'.$presenterLink.'</a>-----<a href="'.$appletCodeBase.'/audience_'.$username.'_chisimba_classroom.jnlp">'.$joinLink.'</a> <br><br><h2>'.$presentationLink->show().'</h2>');
            return "dump_tpl.php";

        }

    }
?>
