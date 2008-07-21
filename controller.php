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
        * @var object $whiteboardURL: The URL for the whiteboard applet files
        * @access public
        */
        public $whiteboardURL;

        
        /**
        * @var object $voiceURL: The URL for the voice applet files
        * @access public
        */
        public $voiceURL;

        /**
         * @access public
         * @var contexctcode
         */
        public $contextCode;

        /**
         * The realtime controller URL
         */
        public $realtimeControllerURL;
        
        /**
         * Presentation URL
         * @var <type>
         */
        public $presentationsURL;
        
        /**
         * Classroom URL
         */
        public $classroomURL;

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
                return $this->showClassRoom($this->contextCode);

                case 'jmfinstall' :
                return "realtime-jmfinstall_tpl.php";

                case 'voice' :
                return $this->showVoiceApplet($this->contextCode);

                case 'audience_applet' :
                return $this->showAudienceApplet();
 
                case 'presenter_applet':
                return $this->showPresenterApplet();
                        
                case 'upload_presentation':
                return $this->uploadPresentation();
                         
                case 'show_upload_form':
                return "upload_presentation.php";
                        
                case 'whiteboard' :
                return "realtime-whiteboard_tpl.php";
                                
                case 'presentations' :
                return "realtime-presentations_tpl.php";
                        
                case 'requesttoken' :
                return $this->requestToken($this->userId, $this->userLevel, $this->contextCode);
                case 'releasetoken' :
                return $this->releaseToken($this->userId, $this->userLevel, $this->contextCode);
                case 'startconversation' :
                return $this->startConversation($this->userId, $this->userLevel, $this->contextCode);
                case 'stopconversation' :
                return $this->stopConversation($this->userId, $this->userLevel, $this->contextCode);
                case 'joinconversation' :
                return $this->joinConversation($this->userId, $this->userLevel, $this->contextCode);
                case 'leaveconversation' :
                return $this->leaveConversation($this->userId, $this->contextCode);
                case 'checktoken' :
                return $this->checkToken($this->userId, $this->userLevel, $this->contextCode);
                case 'checkturn' :
                return $this->checkToken($this->userId, $this->userLevel, $this->contextCode);
                case 'voicequeue' :
                return $this->makeQueue($this->userId, $this->userLevel, $this->contextCode);
                case 'assigntoken' :
                return $this->assignToken($this->getParam('id'), $this->contextCode);	
                default :
                //$this->startWhiteboardServer();
                //$this->startOpenOffice();
                //$this->validJavaVersion();
                return $this->explainRealtime();
            }
        }


 

        /**
         * Function to invoke the presenter applet 
         *
         */ 
        public function showAudienceApplet()
        {
            $this->startServer();
            $this->setVarByRef('id', $id);  
            return "realtime-presentations-audience-applet_tpl.php";
        }
    
        /**
         * check atleast of version of java running in server..min required is 1.5
         */
        public function validJavaVersion(){

            $result = array();
            $cmd='java -version';
            $needle='headless';
            echo exec("java '-version'");
            /*
                system( $cmd, $result);
                foreach ($result as $v ){
               echo 'before: '.$v;
                  $vers=explode(" ", $v);
                 $ver = $vers[2];
                 echo $ver;
               }
            */
        }
 
        /**
         * this checks if open office is running or not, then warns the user
         * @return <type>
         */
        public function openOfficeRunning(){

            $result = array();
            $cmd='ps aux | grep soffice';
            $needle='headless';// -accept=socket';
            exec( $cmd, &$result);
            foreach ($result as $v ){
       
                if($this->in_str($needle,$v)){
        
                    return true;
                }else{
                    return false;
                }
            }

        }

        /**
         * Given a haystack, look for a needle
         * @param <type> $needle
         * @param <type> $haystack
         * @return <type>
         */
        public function in_str($needle, $haystack){
            return (false !== strpos($haystack, $needle))  ? true : false;
    
        } 

   
        /**
         * Start the white board server
         */
        public function startWhiteboardServer()
        {
            $objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
            $port=$objSysConfig->getValue('WHITEBOARDPORT', 'realtime');
            $minMemory=$objSysConfig->getValue('MIN_MEMORY', 'realtime');
            $maxMemory=$objSysConfig->getValue('MAX_MEMORY', 'realtime');
   
            $cmd = "java -Xms".$minMemory."m -Xmx".$maxMemory."m -cp .:".
            $this->objConfig->getModulePath().
    "/documentconverter/resources/jodconverter-2.2.0/lib/commons-cli-1.0.jar:".
            $this->objConfig->getModulePath().
    "/documentconverter/resources/jodconverter-2.2.0/lib/jodconverter-2.2.0.jar:".
            $this->objConfig->getModulePath().
    "/documentconverter/resources/jodconverter-2.2.0/lib/commons-io-1.3.1.jar:".
            $this->objConfig->getModulePath().
        "/documentconverter/resources/jodconverter-2.2.0/lib/jodconverter-cli-2.2.0.jar:".
            $this->objConfig->getModulePath().
     "/documentconverter/resources/jodconverter-2.2.0/lib/juh-2.2.0.jar:".
            $this->objConfig->getModulePath().
    "/documentconverter/resources/jodconverter-2.2.0/lib/jurt-2.2.0.jar:".
            $this->objConfig->getModulePath().
    "/documentconverter/resources/jodconverter-2.2.0/lib/ridl-2.2.0.jar:".
            $this->objConfig->getModulePath().
    "/documentconverter/resources/jodconverter-2.2.0/lib/slf4j-api-1.4.0.jar:".
            $this->objConfig->getModulePath().
    "/documentconverter/resources/jodconverter-2.2.0/lib/slf4j-jdk14-1.4.0.jar:".
            $this->objConfig->getModulePath().
    "/documentconverter/resources/jodconverter-2.2.0/lib/unoil-2.2.0.jar:".
            $this->objConfig->getModulePath().
    "/documentconverter/resources/jodconverter-2.2.0/lib/xstream-1.2.2.jar:".
            $this->objConfig->getModulePath().
    "/realtime/resources/avoir-realtime-server-0.1.jar avoir.realtime.whiteboard.server.Server ".$port." >/dev/null &";
            //echo $cmd;
    
            system($cmd,$return_value);
    
        }


    
        /**
         * Start the realtime server
         */
        public function startServer()
        {
            $objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
            $port=$objSysConfig->getValue('WHITEBOARDPORT', 'realtime');
            $cmd = "java  -cp .:". $this->objConfig->getModulePath()."/realtime/resources/presentations/presentations-server.jar avoir.realtime.presentations.server.Server '.$port.' >/dev/null &";
            system($cmd,$return_value);
    
        }

        /**
         * automaticaly try to open office in headless mode
         */
        private function startOpenOffice()
        {
        
            //soffice -headless -display='0.0' -accept='socket,host=localhost,port=8100;urp;StarOffice.ServiceManager'
            //    $cmd=($this->objConfig->getModulePath()."realtime/resources/startOpenOffice.sh  > /dev/null 2>&1");
            //   exec($cmd); //"soffice -headless -accept='socket,port=8100;urp;' &";
            exec("soffice -headless -accept='socket,port=8100;urp;'&");
        }
       /**
        * This creates, if not existing, a folder where the presentations are to be stored.
        * In addition, an uploaded presentation is converted into .html and jpg formats
        *
        */
        function uploadPresentation()
        {
            //  if($this->checkOpenOfficeStatus()){
            //$this->startServer();
            $generatedid = $this->getParam('id');
            $filename = $this->getParam('filename');
                
            $id = $this->objFiles->autoCreateTitle();
            $id="test1";
            $objMkDir = $this->getObject('mkdir', 'files');

            $destinationDir = $this->objConfig->getcontentBasePath().'/realtime_presentations/'.$id;

            $objMkDir->mkdirs($destinationDir);

            @chmod($destinationDir, 0777);

            $objUpload = $this->newObject('upload', 'files');
            $objUpload->permittedTypes = array('ppt', 'odp', 'pps'); //'pps',
            $objUpload->overWrite = TRUE;
            $objUpload->uploadFolder = $destinationDir.'/';

            $result = $objUpload->doUpload(TRUE, $id);

            echo $generatedid;

            if ($result['success'] == FALSE) {
                $this->objFiles->removeAutoCreatedTitle($id);
                rmdir($this->objConfig->getcontentBasePath().'/realtime_presentations/'.$id);

                $filename = isset($_FILES['fileupload']['name']) ? $_FILES['fileupload']['name'] : '';

                return $this->nextAction('erroriframe', array('message'=>$result['message'], 'file'=>$filename, 'id'=>$generatedid));
            } else {

                $filename = $_FILES['fileupload']['name'];
                $mimetype = $_FILES['fileupload']['type'];

                $path_parts = pathinfo($_FILES['fileupload']['name']);
                $ext = $path_parts['extension'];


                $file = $this->objConfig->getcontentBasePath().'/realtime_presentations/'.$id.'/'.$id.'.'.$ext;

                if ($ext == 'pps')
                {
                    $rename = $this->objConfig->getcontentBasePath().'/realtime_presentations/'.$id.'/'.$id.'.ppt';

                    rename($file, $rename);

                    $filename = $path_parts['filename'].'.ppt';
                }


                if (is_file($file)) {
                    @chmod($file, 0777);
                }
                $inputFile=$this->objConfig->getcontentBasePath().'/realtime_presentations/'.$id.'/'.$id.'.'.$ext;
                $outputFile=$this->objConfig->getcontentBasePath().'/realtime_presentations/'.$id.'/'.$id.'.html';
                $this->converter->convert($inputFile,$outputFile);
                $this->setVarByRef('id', $id);       
                return "realtime-presentations-presenter-applet_tpl.php";
            }
       /* }else{
                $title=$this->objLanguage->languageText('mod_realtime_presentationtitle', 'realtime');
                $content='<p>'.$this->objLanguage->languageText('mod_realtime_tip1a', 'realtime').' '.$this->objLanguage->languageText('mod_realtime_presentations', 'realtime').' '.$this->objLanguage->languageText('mod_realtime_tip1b', 'realtime').' </p><p><b>'.$this->objLanguage->languageText('mod_realtime_presentations_tip1', 'realtime').'<br>'.$this->objLanguage->languageText('mod_realtime_presentations_tip2', 'realtime').'<br>'.$this->objLanguage->languageText('mod_realtime_presentations_tip3', 'realtime');
                $desc=$this->objLanguage->languageText('mod_realtime_officenotrunning', 'realtime');
                $this->setVarByRef('title', $title);
                $this->setVarByRef('desc', $desc);
                $this->setVarByRef('content', $content);
                return "dump_tpl.php";
        }*/
        }
       /**
        * Informs the server that a user is requesting a voice token, assigns token to User if the token is
        * available.
        */	
        public function requestToken($userid, $userlevel, $contextcode)
        {
            if (empty ($userid) || empty ($userlevel) || empty ($contextcode))
            {
                return "realtime_tpl.php";
            } else
            {
                $hasToken = $this->objrealtime->requestToken($userid, $userlevel, $contextcode);
                $this->setVar('hastoken', $hasToken);
                return "redirect_tpl.php";
            }
        }

        /**
         * Inform applet that User has released token.
         */
        public function releaseToken($userid, $userlevel, $contextcode)
        {
            $hasToken = $this->objrealtime->releaseToken($userid, $contextcode);
            $this->setVar('hastoken', $hasToken);
            return "redirect_tpl.php";

        }

        /**
         * Informs server that lecturer would like to start a conversation.
         */
        public function startConversation($userid, $userlevel, $contextcode)
        {
            if (empty ($userid) || empty ($userlevel) || empty ($contextcode))
            {
                $hasToken = "FAILURE";
                $this->setVar('hastoken', $hasToken);
                return "redirect_tpl.php";
            } else
            {
                $this->deleteFilesInServer();
                $hasToken = $this->objrealtime->startConversation($userid, $contextcode);
                $this->setVar('hastoken', $hasToken);
                return "redirect_tpl.php";
            }
        }

        /**
         * Informs the server that the lecturer is stopping a conversation.
         */
        public function stopConversation($userid, $userlevel, $contextcode)
        {
            $hasToken = $this->objrealtime->stopConversation($userid, $contextcode);
            $this->setVar('hastoken', $hasToken);
            return "redirect_tpl.php";
        }

        /**
         * Informs server that user wants to leave a conversation.
         */	
        public function leaveConversation($userid, $contextcode)
        {
            $hasToken = $this->objrealtime->leaveConversation($userid,  $contextcode);
            $this->setVar('hastoken', $hasToken);
            return "redirect_tpl.php";
        }

        /**
         * Informs server that a user wants to join a conversation.
         */
        public function joinConversation($userid, $userlevel, $contextcode)
        {
            $hasToken = $this->objrealtime->joinConversation($userid,  $contextcode);
            $this->setVar('hastoken', $hasToken);
            return "redirect_tpl.php";
        }

        /**
         *	checks availability of token
         */
        public function checkToken($userid, $userLevel, $contextcode)
        {
            $hasToken = $this->objrealtime->checkToken($userid, $contextcode);
            $this->setVar('hastoken', $hasToken);
            return "redirect_tpl.php";
        }
        
        /**
         * creates queue(list) of user's who request token
         */
        public function makeQueue($userId, $userLevel, $contextCode)
        {
                
            if(eregi("lecturer", $userLevel)){
                        
                $users = $this->objrealtime->makeQueue($userId, $userLevel, $contextCode);
                $this->setVar('hastoken', $users);

                return "redirect_tpl.php";
            }else { 
                $notAllowed = "You,are,not-authorised,to,view-this,page,aka-";
                $this->setVar('hastoken', $notAllowed);

                return "redirect_tpl.php";
            }
        }

        /**
         * Informs the server that the lecturer is assigning the token to a particular user.
         */	
        public function assignToken($userId, $contextcode)
        {
            $hasToken = $this->objrealtime->assignToken($userId, $contextcode);
            $this->setVar('hastoken', $hasToken);
            return "redirect_tpl.php";
        }
        
        /**
         *	deletes audio files of the previous conversation when the next conversation stars.
         */
        public function deleteFilesInServer(){
            $this->basePath = $this->objConfig->getModulePath();
            $this->modulePath = $this->basePath."realtime/resources/voice/audio";
            $this->file = "";
            if($this->handle = opendir($this->modulePath)){
                while(false !== ($this->file = readdir($this->handle))){
                    if(ereg(".gsm", $this->file))
                    unlink($this->modulePath.'/'.$this->file);
                }
                closedir($this->handle);
            }
        }
        
        /**
         * shows voice applet to user if user first entered a context.
         */
        public function showVoiceApplet($contextcode)
        {
            if(empty($contextcode)){
                $this->setVar("noContextCode", $this->objLanguage->languageText('mod_realtime_nocontextcode', 'realtime'));
            }
            return "realtime-voice_tpl.php";
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
    }
?>
