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

                default :
                return $this->showClassRoom($this->contextCode);
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

public function showClassroom($contextCode){
    $modPath=$this->objAltConfig->getModulePath();
    $replacewith="";
    $docRoot=$_SERVER['DOCUMENT_ROOT'];
    $appletPath=str_replace($docRoot,$replacewith,$modPath);
    $appletCodeBase="http://" . $_SERVER['HTTP_HOST']."/".$appletPath.'/realtime/resources/';
    $objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
    $supernodeHost=$objSysConfig->getValue('SUPERNODE_HOST', 'realtime');
    $supernodePort=$objSysConfig->getValue('SUPERNODE_PORT', 'realtime');
    $username=$this->objUser->userName();
    $fullnames=$this->objUser->userName();
    $isPresenter='true';
    $fileBase=$modPath.'/realtime/resources/';
    $title=$this->objLanguage->languageText('mod_realtime_title', 'realtime');
    $desc= $this->objLanguage->code2Txt('mod_realtime_aboutrealtime', 'realtime');

    $this->objStarter->generateJNLP($fileBase,$appletCodeBase,$supernodeHost,$supernodePort,$username,$fullnames,$isPresenter,'contextCode');
    $this->setVarByRef('title', $title);
    $this->setVarByRef('desc', $desc);
    $this->setVarByRef('content', '<a href="'.$appletCodeBase.'/chisimba_classroom.jnlp">Click here to launch realtime classroom</a>');
    return "dump_tpl.php";		
}

}
?>
