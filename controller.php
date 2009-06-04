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
         * link to requirements test
         * @var String
         */
    public $reqTest;

        /**
         *unique session id
         * @var String
         */
    public $sessionId;

        /**
         * This is the session title
         * @var String
         */
    public $sessionTitle;

    /**
     *Need a room, everything rotates around a room
     * @var <type>
     */
    public $room='default';


    public $ispresenter='yes';

    public $passwordrequired='no';

    public $jnlpId="";

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

        $this->sessionId="default";
        $this->sessionTitle="Default Session";
        $this->jnlpId=$this->realtimeManager->randomString(20);
    }

        /**
        * Method to process actions to be taken
        *
        * @param string $action String indicating action to be taken
        */
    function dispatch($action = Null) {

 $this->setVarByRef('title', 'Under Maintenance');
                            $this->setVarByRef('desc','Under Maintenance');
                            $this->setVarByRef('content', '<h2>Under Maintenance</h2>');
                            return "dump_tpl.php";


        $this->contextCode = $this->objContext->getContextCode();
        switch ($action)   {
            case 'startSlideServer':{
                    $this->sessionId=$this->getParam('id');
                    $this->sessionTitle=$this->getParam('agenda');
                    $this->startSlideServer("popopopo",$this->getParam('agenda'));
                    return "";
                }

                case 'classroom' :{
                        $this->sessionId=$this->getParam('id');
                        $this->sessionTitle=$this->getParam('agenda');
                        $this->room=$this->getParam('room');
                        $this->ispresenter=$this->getParam('presenter');
                        $this->passwordrequired=$this->getParam('needpassword');
                        return $this->showClassRoom();
                    }
                    case 'classroombeta' :{
                            $id=$this->getParam('id');
                            $title=$this->getParam('agenda');
                            $this->room=$this->getParam('room');
                            $this->ispresenter=$this->getParam('presenter');
                            return $this->showClassRoomBeta($id,$title);
                        }
                        case 'showstartlinks' :
                            $this->sessionId=$this->getParam('id');
                            $this->sessionTitle=$this->getParam('agenda');
                            $this->room=$this->getParam('room');
                            $this->ispresenter=$this->getParam('presenter');
                            $this->passwordrequired=$this->getParam('needpassword');
                            return $this->showStartLinks();

                            default :{
                                            return $this->showClassRoom();
                                }
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
           /**
         *This starts a slide server
         * @return String
         */
                        public function generateJNLP($xsessionId,$xsessionTitle,$xroom,$ispresenter,$passwordrequired){
                            $chatLogPath = $filePath.'/chat/'.date("Y-m-d-H-i");
                            $modPath=$this->objAltConfig->getModulePath();
                            $replacewith="";
                            $docRoot=$_SERVER['DOCUMENT_ROOT'];
                            $appletPath=str_replace($docRoot,$replacewith,$modPath);
                            $appletCodeBase="http://" . $_SERVER['HTTP_HOST']."/".$appletPath.'/realtime/resources/';
                            $objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
                            $supernodeHost=$objSysConfig->getValue('SUPERNODE_HOST', 'realtime');
                            $supernodePort=$objSysConfig->getValue('SUPERNODE_PORT', 'realtime');
                            $mediaServerHost=$objSysConfig->getValue('MEDIA_SERVER_HOST', 'realtime');
                            $audioMICPort=$objSysConfig->getValue('AUDIO_MIC_PORT', 'realtime');
                            $audioSpeakerPort=$objSysConfig->getValue('AUDIO_SPEAKER_PORT', 'realtime');

                            $openfireHost=$objSysConfig->getValue('OPENFIRE_HOST', 'realtime');
                            $openfirePort=$objSysConfig->getValue('OPENFIRE_CLIENT_PORT', 'realtime');
                            $openfireHttpBindUrl=$objSysConfig->getValue('OPENFIRE_HTTP_BIND', 'realtime');


                            $siteRoot=$this->objAltConfig->getSiteRoot();
                           // $siteRoot=$_SERVER['HTTP_REFERER'];
                            //if($siteRoot == ''){
                              //  $siteRoot=$this->objAltConfig->getSiteRoot().'?module=realtime&action=classroom&id='.$xsessionId.'&agenda=Default&room='.$xroom.'&needpassword='+$passwordrequired;
                            //}
                            $username=$this->objUser->userName();
                            $fullnames=$this->objUser->fullname();
                            $email=$this->objUser->email();
                            $userDetails=$fullnames.' '.$username;
                            $userImagePath='imagepath';//".'.$this->objUser->getSmallUserImage().'"';
                            $isLoggedIn =$this->objUser->isLoggedIn();
                            $fileBase=$modPath.'/realtime/resources/';
                            $resourcesPath =$modPath.'/realtime/resources';
                            $desc= $this->objLanguage->code2Txt('mod_realtime_aboutrealtime', 'realtime');
                            $filePath=$this->objConfig->getContentBasePath().'/webpresent/'.$xsessionId;

                            //generate for presenter
                            $webpresent=$xsessionId == "default"?"false":"true";
                            $this->objStarter->generateJNLP('presenter',$fileBase,$appletCodeBase,$openfireHost,
                                $openfirePort,$openfireHttpBindUrl,$username,$filePath, $xroom,
                                $ispresenter,$xsessionId,$xsessionTitle,$webpresent,$fullnames,$email,
                                $siteRoot,$passwordrequired,$this->jnlpId);

                        }
                        public function showClassroom(){
                     
                            $username=$this->objUser->userName();
                            $modPath=$this->objAltConfig->getModulePath();
                            $replacewith="";
                            $docRoot=$_SERVER['DOCUMENT_ROOT'];
                            $appletPath=str_replace($docRoot,$replacewith,$modPath);
                            $appletCodeBase="http://" . $_SERVER['HTTP_HOST']."/".$appletPath.'/realtime/resources/';

                            $presenterimage=$this->newObject('image','htmlelements');
                            $presenterimage->src='skins/_common/icons/webpresent/btn_START.jpg';
                            $presenterimage->width="200";
                            $presenterimage->height="80";

                            $joinimage=$this->newObject('image','htmlelements');
                            $joinimage->src='skins/_common/icons/webpresent/btn_JOIN.jpg';
                            $joinimage->width="200";
                            $joinimage->height="80";
                            $presentationLink = new link ($this->uri(array('action'=>'view', 'id'=>$this->sessionId),"webpresent"));
                            $presentationLink->link=   $this->objLanguage->languageText('mod_realtime_backtopresentation', 'realtime');

                            $siteRoot=$this->objAltConfig->getSiteRoot();
                            $presenterLink='<img src="'.$siteRoot.'skins/_common/icons/webpresent/btn_START.jpg" width="200" height="80">';
                            $joinLink='<img src="'.$siteRoot.'skins/_common/icons/webpresent/btn_JOIN.jpg" width="200" height="80">';

                            $this->reqTest= 'Verify that your system meets the <a href="'.$appletCodeBase.'/sysreq.php">minimum requirements</a><br><br>';
                            $desc='<li>Add Live interactions to your presentation</li>';
                            $desc.='<li>Communicate in realtime through audio/video conferencing.</li>';
                            $tip=$this->objLanguage->languageText('mod_realtime_openwith', 'realtime');

                            $this->setVarByRef('desc', $desc);
                            $this->setVarByRef('sessionId', $this->sessionId);
                            $this->setVarByRef('sessionTitle', $this->sessionTitle);

                            if($this->getSession('javatest') == 'done'){
                                return $this->nextAction ( 'showStartLinks', array ('id'=>$this->sessionId,'agenda'=>$this->sessionTitle,'room'=>$this->room,
                                     'presenter'=>$this->ispresenter,'needpassword'=>$this->passwordrequired ) );
                            }else{
                                $javaTest = $this->getObject('sysreqs', 'webpresent');
                                $testPage=$javaTest->show($this->sessionId,$this->sessionTitle,$this->room,$this->ispresenter,$this->passwordrequired);
                                $this->setVarByRef('content',$testPage);
                                $this->setSession('javatest','done');
                            }
                            return "dump_tpl.php";
                        }


                        public function showClassroomBeta($id,$title){

                            $slideServerId=$this->realtimeManager->randomString(32);//'gen19Srv8Nme50';
                            //$this->realtimeManager->startBetaSlidesServer($slideServerId);

                            $chatLogPath = $filePath.'/chat/'.date("Y-m-d-H-i");
                            $modPath=$this->objAltConfig->getModulePath();
                            $replacewith="";
                            $docRoot=$_SERVER['DOCUMENT_ROOT'];
                            $appletPath=str_replace($docRoot,$replacewith,$modPath);
                            $appletCodeBase="http://" . $_SERVER['HTTP_HOST']."/".$appletPath.'/realtime/resources/';
                            $this->reqTest= 'Verify that your system meets the <a href="'.$appletCodeBase.'/sysreq.php">minimum requirements</a><br><br>';

                            $objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
                            $supernodeHost=$objSysConfig->getValue('SUPERNODE_HOST', 'realtime');
                            $supernodePort=22225;//$objSysConfig->getValue('SUPERNODE_PORT', 'realtime');
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
                            $presenterimage->src='skins/_common/icons/webpresent/btn_START.jpg';
                            $presenterimage->width="200";
                            $presenterimage->height="80";

                            $joinimage=$this->newObject('image','htmlelements');
                            $joinimage->src='skins/_common/icons/webpresent/btn_JOIN.jpg';
                            $joinimage->width="200";
                            $joinimage->height="80";
                            $presentationLink = new link ($this->uri(array('action'=>'view', 'id'=>$id),"webpresent"));
                            $presentationLink->link=   $this->objLanguage->languageText('mod_realtime_backtopresentation', 'realtime');

                            $siteRoot=$this->objAltConfig->getSiteRoot();
                            $presenterLink='<img src="'.$siteRoot.'skins/_common/icons/webpresent/btn_START.jpg" width="200" height="80">';
                            $joinLink='<img src="'.$siteRoot.'skins/_common/icons/webpresent/btn_JOIN.jpg" width="200" height="80">';

                            $desc="Beta 1.0.2<br><hr>";
                            $desc.='<li>Add Live interactions to your presentation</li>';
                            $desc.='<li>Communicate in realtime through audio/video conferencing.</li>';

                            //generate for presenter

                            $this->objStarter->generateJNLP1_0_2Beta('presenter',$fileBase,$appletCodeBase,$supernodeHost,
                                $supernodePort,$username,$fullnames,'true',$id,$title,$userDetails,$userImagePath,
                                $isLoggedIn,$siteRoot,$resourcesPath,$this->userLevel,$chatLogPath,
                                $filePath,$slideServerId,'true');
                            //generate for participant
                            $this->objStarter->generateJNLP1_0_2Beta('audience',$fileBase,$appletCodeBase,$supernodeHost,
                                $supernodePort,$username,$fullnames,'false',$id,$title,$userDetails,$userImagePath,
                                $isLoggedIn,$siteRoot,$resourcesPath,$this->userLevel,$chatLogPath,
                                $filePath,$slideServerId,'true');
                            $tip=$this->objLanguage->languageText('mod_realtime_openwith', 'realtime');

                            $this->setVarByRef('title',  $title);
                            $this->setVarByRef('desc', $desc);
                            $this->setVarByRef('content', $this->reqTest.'<a href="'.$appletCodeBase.'/presenter_'.$username.'_chisimba_classroom.jnlp">'.$presenterLink.'</a>-----<a href="'.$appletCodeBase.'/audience_'.$username.'_chisimba_classroom.jnlp">'.$joinLink.'</a> <br><br><h4>'.$tip.'</h4><br><br><h2>'.$presentationLink->show().'</h2>');
                            return "dump_tpl.php";
                        }
                        /**
                         * this displays link from which to launch the jnlp file
                         * @return void
                         */
                        public function showStartLinks(){
                            $this->generateJNLP($this->sessionId, $this->sessionTitle,$this->room,$this->ispresenter,$this->passwordrequired);
                            $modPath=$this->objAltConfig->getModulePath();
                            $replacewith="";
                            $docRoot=$_SERVER['DOCUMENT_ROOT'];
                            $appletPath=str_replace($docRoot,$replacewith,$modPath);
                            $appletCodeBase="http://" . $_SERVER['HTTP_HOST']."/".$appletPath.'/realtime/resources/';

                            $username=$this->objUser->userName();

                            $desc='<li>Add Live interactions to your presentation</li>';
                            $desc.='<li>Communicate in realtime through audio/video conferencing.</li>';
                            $tip=$this->objLanguage->languageText('mod_realtime_openwith', 'realtime');
                            $presentationLink = new link ($this->uri(array('action'=>'view', 'id'=>$this->sessionId),"webpresent"));
                            $presentationLink->link= $this->sessionId=='default'?"":  $this->objLanguage->languageText('mod_realtime_backtopresentation', 'realtime');

                            $siteRoot=$this->objAltConfig->getSiteRoot();
                            $presenterLink='<img src="'.$siteRoot.'skins/_common/icons/webpresent/btn_ENTER.jpg" width="200" height="80">';

                            $this->setVarByRef('desc', $desc);
                            $this->setVarByRef('sessionId', $this->sessionId);
                            $this->setVarByRef('sessionTitle', $this->sessionTitle);
                            $this->setVarByRef('content', '<a href="'.$appletCodeBase.'/avoir_'.$this->jnlpId.'_realtime.jnlp">'.$presenterLink.'</a><br/><br/><h4>'.$tip.'</h4><br><br><h2>'.$presentationLink->show().'</h2>');

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
                            $this->reqTest= 'Verify that your system meets the <a href="'.$appletCodeBase.'/sysreq.php">minimum requirements</a><br><br>';

                            $objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
                            $supernodeHost=$objSysConfig->getValue('SUPERNODE_HOST', 'realtime');
                            $supernodePort=$objSysConfig->getValue('SUPERNODE_PORT', 'realtime');
                            $mediaServerHost=$objSysConfig->getValue('MEDIA_SERVER_HOST', 'realtime');
                            $audioMICPort=$objSysConfig->getValue('AUDIO_MIC_PORT', 'realtime');
                            $audioSpeakerPort=$objSysConfig->getValue('AUDIO_SPEAKER_PORT', 'realtime');
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
                            $presenterimage->src='skins/_common/icons/webpresent/btn_START.jpg';
                            $presenterimage->width="200";
                            $presenterimage->height="80";
                            $title=$this->objLanguage->languageText('mod_realtime_title', 'realtime');

                            $joinimage=$this->newObject('image','htmlelements');
                            $joinimage->src='skins/_common/icons/webpresent/btn_JOIN.jpg';
                            $joinimage->width="200";
                            $joinimage->height="80";
                            $presentationLink = new link ($this->uri(array('action'=>'view', 'id'=>$id),"webpresent"));
                            $presentationLink->link=   $this->objLanguage->languageText('mod_realtime_backtopresentation', 'realtime');

                            $siteRoot=$this->objAltConfig->getSiteRoot();
                            $presenterLink='<img src="'.$siteRoot.'skins/_common/icons/webpresent/btn_START.jpg" width="200" height="80">';
                            $joinLink='<img src="'.$siteRoot.'skins/_common/icons/webpresent/btn_JOIN.jpg" width="200" height="80">';

                            $desc='<li>'.$this->objLanguage->languageText('mod_realtime_addlivepresentation', 'realtime').'</li>';
                            $desc.='<li>'.$this->objLanguage->languageText('mod_realtime_addaudiovideo', 'realtime').'</li>';
                            $mediaServerHost=$objSysConfig->getValue('MEDIA_SERVER_HOST', 'realtime');
                            $audioMICPort=$objSysConfig->getValue('AUDIO_MIC_PORT', 'realtime');
                            $audioSpeakerPort=$objSysConfig->getValue('AUDIO_SPEAKER_PORT', 'realtime');
                            //generate for presenter
                            $this->objStarter->generateJNLP('presenter',$fileBase,$appletCodeBase,$supernodeHost,
                                $supernodePort,$username,$fullnames,'true','wbb',$this->sessionTitle,$userDetails,$userImagePath,
                                $isLoggedIn,$siteRoot,$resourcesPath,$this->userLevel,$chatLogPath,
                                $filePath,$slideServerId,$mediaServerHost,$audioMICPort,$audioSpeakerPort,'false');
                            //generate for participant
                            $this->objStarter->generateJNLP('audience',$fileBase,$appletCodeBase,$supernodeHost,
                                $supernodePort,$username,$fullnames,'false','wbb',$this->sessionTitle,$userDetails,$userImagePath,
                                $isLoggedIn,$siteRoot,$resourcesPath,$this->userLevel,$chatLogPath,
                                $filePath,$slideServerId,$mediaServerHost,$audioMICPort,$audioSpeakerPort,'false');
                            $tip=$this->objLanguage->languageText('mod_realtime_openwith', 'realtime');
                            $this->setVarByRef('title',  $title);
                            $this->setVarByRef('desc', $desc);
                            $this->setVarByRef('content', $this->reqTest.'<a href="'.$appletCodeBase.'/presenter_'.$username.'_chisimba_classroom.jnlp">'.$presenterLink.'</a>-----<a href="'.$appletCodeBase.'/audience_'.$username.'_chisimba_classroom.jnlp">'.$joinLink.'</a> <br><br><h3>'.$tip.'</h3><br><br><h2>'.$presentationLink->show().'</h2>');
                            return "dump_tpl.php";

                        }

                    }
                    ?>
