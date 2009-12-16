<?php
// security check - must be included in all scripts
if (!
       /**
        * Description for $GLOBALS
        * @global string $GLOBALS['kewl_entry_point_run']
        * @name   $kewl_entry_point_run
        */
    $GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
class sessionmanager extends object{
        /**
         * Setup display to ask the user to enter email address for the participant
         * who are to be invited.
         * Added by David Wafula
         * @return <type>
         */
            /**
        *
        * @var $objLanguage String object property for holding the
        * language object
        * @access private
        *
        */
    public $objLanguage;

        /**
        *
        * @var $objUser String object property for holding the
        * user object
        * @access private
        *
        */
    public $objUser;

        /**
        *
        * @var $objUser String object property for holding the
        * cobnfiguration object
        * @access private
        *
        */
    public $objConfig;

    public function init()
    {
        // Instantiate the language object.
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objDbScheduleMembers=$this->getObject('dbschedulemembers');
        // Instantiate the user object.
        $this->objUser = $this->getObject("user", "security");
        // Instantiate the config object
        $this->objConfig = $this->getObject('altconfig', 'config');
        $this->objAltConfig = $this->getObject('altconfig','config');
        $this->objDbSchedules=$this->getObject('dbschedules');
        // scripts
       // $extbase = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/adapter/ext/ext-base.js','htmlelements').'" type="text/javascript"></script>';
       // $extalljs = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0.3/ext-all.js','htmlelements').'" type="text/javascript"></script>';
       // $extallcss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('ext-3.0.3/resources/css/ext-all.css','htmlelements').'"/>';


       $extjs=$this->getObject('extjs','htmlelements');
       $extjs->show();
       $maincss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('css/session.css').'"/>';
        $schedulejs = '<script language="JavaScript" src="'.$this->getResourceUri('js/schedule.js').'" type="text/javascript"></script>';

        $this->appendArrayVar('headerParams', $extbase);
        $this->appendArrayVar('headerParams', $extalljs);
        $this->appendArrayVar('headerParams', $extallcss);
        $this->appendArrayVar('headerParams', $maincss);
        $this->appendArrayVar('headerParams', $schedulejs);
    }
    public function showSessionList($presentationId,$presentationName,$xxslidesDir)
    {
        if(!$this->objUser->isLoggedIn()){
                        // Create an instance of the css layout class
            $cssLayout =$this->getObject('csslayout', 'htmlelements');// Set columns to 2
            $security=$this->getObject('security');
            $this->loadclass('link','htmlelements');
            $objBlocks = $this->getObject('blocks', 'blocks');
            $cssLayout->setNumColumns(2);
            $registerLink=new link();
            
            $registerLink->link($this->uri(array('action'=>'showregister'),'userregistration'));
            $registerLink->link="New user? Register here";
            $rightSideColumn .=  '<h1>Please Login</h1>In order to use live presentations, please login first<br/>
            '.$registerLink->show();
            $cssLayout->setLeftColumnContent( $objBlocks->showBlock('login', 'security'));

            // Add Right Column
            $cssLayout->setMiddleColumnContent( $rightSideColumn);

            //Output the content to the page
            return $cssLayout->show();
            
            
        }
        //where we render the 'popup' window
        $renderSurface='<div id="addsession-win" class="x-hidden">
        <div class="x-window-header">Add Session</div>
        </div>';
        $scheduleTitle='<h2>Live Sessions</h2>';
        $scheduleTitle.='
          <p>Here you will find a listing of live sessions owned by you or of
          which you are a member.<br/>
          
         Select one to join. You can start you own sessions by clicking on the
         <font color="green"><b>Add Session</b></font> button.
         </p>
         ';
        $registerLink2=new link();
         $registerLink2->link($this->uri(array('action'=>'signinagain')));
         $registerLink2->link='<h4><font color="red">Seeing blank page? Sign in again here</font></h4>';
         // $scheduleTitle.=$registerLink2->show();

        //load class
        $this->loadclass('link','htmlelements');
        $objIcon= $this->newObject('geticon','htmlelements');


        $modPath=$this->objAltConfig->getModulePath();
        $replacewith="";
        $docRoot=$_SERVER['DOCUMENT_ROOT'];
        $resourcePath=str_replace($docRoot,$replacewith,$modPath);
        $codebase="http://" . $_SERVER['HTTP_HOST']."/".$resourcePath.'/realtime/resources/';

        $objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
        $servletURL=$objSysConfig->getValue('SERVLETURL', 'realtime');
        $openfireHost=$objSysConfig->getValue('OPENFIRE_HOST', 'realtime');
        $openfirePort=$objSysConfig->getValue('OPENFIRE_CLIENT_PORT', 'realtime');
        $openfireHttpBindUrl=$objSysConfig->getValue('OPENFIRE_HTTP_BIND', 'realtime');
        $skinclass=$objSysConfig->getValue('SKINCLASS', 'realtime');
        $skinjars=$objSysConfig->getValue('SKINJAR', 'realtime');
        $username=$this->objUser->userName();
        $fullnames=$this->objUser->fullname();
        $email=$this->objUser->email();
        $inviteUrl=$this->objAltConfig->getSiteRoot();


        $addButton = new button('add','Add Session');
        //$addButton->setOnClick("showAddSessionWindow();");
        $addButton->setId('add-session-btn');

        //prints out add comment message
        if ($this->addCommentMessage){
            $message = "<span id=\"commentSuccess\">".$this->objLanguage->languageText('mod_ads_commentSuccess', 'ads')."</span><br />";
            $this->addCommentMessage = false;
        } else $message = "";

        $content = $message;
        $content= '<div id="grouping-grid">'.$scheduleTitle.$addButton->show().$renderSurface.'<br /><br /></div>';

        $slidesDir=$filePath=$this->objAltConfig->getContentBasePath().'/webpresent/'.$presentationId;
        $data='';

        //data grid from db
        $dbdata=$this->objDbScheduleMembers->getSessionsThatAmAMember();
        $total=count($dbdata);
        $index=1;
        $prevTitle='';
        foreach($dbdata as $row){
            $xsessionData=$this->objDbSchedules->getSchedule($row['sessionid']);

            foreach($xsessionData as $sessionData){
                $sessionTitle=addslashes($sessionData['title']);
                $sessionOwner=addslashes($sessionData['owner']);
                $creationDate=$sessionData['creation_date'];
            }

           if($prevTitle != $sessionTitle){

            $prevTitle=$sessionTitle;
            $deleteLink=new link();
            $editLink=new link();
            $detailsLink=new link();
            $roomUrl='';
            $roomUrl.=$servletURL.'?';
            $roomUrl.='port='.$openfirePort.'&';
            $roomUrl.='host='.$openfireHost.'&';
            $roomUrl.='username='.$username.'&';
            $roomUrl.='roomname='.$sessionTitle.'&';
            $roomUrl.='audiovideourl='.$openfireHttpBindUrl.'&';
            $roomUrl.='slidesdir='.$slidesDir.'&';
            $roomUrl.=$this->objDbSchedules->isScheduleOwner($row['sessionid'])?'ispresenter=yes&':'ispresenter=no&';

            $roomUrl.='presentationid='.$presentationId.'&';
            $roomUrl.='presentationName='.$presentationName.'&';
            $roomUrl.='names='.$fullnames.'&';
            $roomUrl.='email='.$email.'&';
            $roomUrl.='inviteurl='.$inviteUrl.'&';
            $roomUrl.='useec2=false&';
            $roomUrl.='joinid=none&';
            $roomUrl.='codebase='.$codebase.'&';
            $roomUrl.='skinclass='.$skinclass.'&';
            $roomUrl.='skinjar='.$skinjars;

            $deleteLink->link($this->uri(array('action'=>'deleteschedule','id'=>$row['id'])));
            $objIcon->setIcon('delete');
            $deleteLink->link=$objIcon->show();


            $editLink->link($this->uri(array('action'=>'editschedule','id'=>$row['id'])));
            $objIcon->setIcon('edit');
            $editLink->link=$objIcon->show();

            $detailsLink->link($this->uri(array('action'=>'showdetails','id'=>$row['sessionid'])));
            $detailsLink->link='Details';

            $editDeleteLink=$this->objDbSchedules->isScheduleOwner($row['id'])? $editLink->show().$deleteLink->show():"N/A";
            $detailsL=$this->objDbSchedules->isScheduleOwner($row['sessionid'])?$detailsLink->show():"Details";
            $data.="[";
            $data.= "'<a href=\"".$roomUrl."\">".$sessionTitle."</a>',";
            $data.="'".$creationDate."',";
            $data.="'".addslashes($detailsL)."',";
            $data.="'".addslashes($this->objUser->fullname($sessionOwner))."'";

            $data.="]";

            
            if($index < $total){
                $data.=',';
            }
         }
          $index++;
        }
     
        $lastChar = $data[strlen($data)-1];
        $len=strlen($data);
         if($lastChar == ','){
             $data=substr($data, 0, (strlen ($data)) - (strlen (strrchr($data,','))));
         }
        $submitUrl = $this->uri(array('action' => 'saveschedule'));

        $title='Title';
        $dateCreated='Date Created';
        $details='Details';

        $owner='Owner';
        $edit='Edit';


        $mainjs = "/*!realtime
                 * Ext JS Library 3.0.0
                 * Copyright(c) 2006-2009 Ext JS, LLC
                 * licensing@extjs.com
                 * http://www.extjs.com/license
                 */
                Ext.onReady(function(){

                    Ext.QuickTips.init();
                       var data=[$data];
                        var url='".str_replace("amp;", "", $submitUrl)."';
                        initAddScheduleFrame(url);
                       showSessions(data);
                   });
";

        $content.= "<script type=\"text/javascript\">".$mainjs."</script>";


        return $content;
    }


    public function showSessionMembersList($sessionid)
    {
        if(!$this->objUser->isLoggedIn()){
            $objBlocks = $this->getObject('blocks', 'blocks');
            return $objBlocks->showBlock('login', 'security');
        }
        //where we render the 'popup' window
        $renderSurface='<div id="addsession-win" class="x-hidden">
        <div class="x-window-header">Add Member</div>
        </div>';
        $scheduleTitle='<h4>Session Details</h4>';
        //load class
        $this->loadclass('link','htmlelements');
        $objIcon= $this->newObject('geticon','htmlelements');


        $modPath=$this->objAltConfig->getModulePath();
        $replacewith="";
        $docRoot=$_SERVER['DOCUMENT_ROOT'];
        $resourcePath=str_replace($docRoot,$replacewith,$modPath);
        $codebase="http://" . $_SERVER['HTTP_HOST']."/".$resourcePath.'/realtime/resources/';

        $objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
        $servletURL=$objSysConfig->getValue('SERVLETURL', 'realtime');
        $openfireHost=$objSysConfig->getValue('OPENFIRE_HOST', 'realtime');
        $openfirePort=$objSysConfig->getValue('OPENFIRE_CLIENT_PORT', 'realtime');
        $openfireHttpBindUrl=$objSysConfig->getValue('OPENFIRE_HTTP_BIND', 'realtime');

        $username=$this->objUser->userName();
        $fullnames=$this->objUser->fullname();
        $email=$this->objUser->email();
        $ispresenter=$this->objDbSchedules->isScheduleOwner($me) ? 'yes':'no';
        $inviteUrl=$this->objAltConfig->getSiteRoot();


        $listButton = new button('add','Back to Session List');
        $returnUrl = $this->uri(array('action' => 'home'));
        $listButton->setOnClick("window.location='$returnUrl'");
        //prints out add comment message
        if ($this->addCommentMessage){
            $message = "<span id=\"commentSuccess\">".$this->objLanguage->languageText('mod_ads_commentSuccess', 'ads')."</span><br />";
            $this->addCommentMessage = false;
        } else $message = "";

        $content = $message;
        $content= '<div id="form-panel">'.$scheduleTitle.$listButton->show().'<br/></div>';
        $content.= $renderSurface.'</br><div id="grouping-grid"></div>';


        $data='';
        $sessionTitle='y';
        $meetingDate='x';
        $timeFrom='v';
        $timeTo='b';
        $xsessionData=$this->objDbSchedules->getSchedule($sessionid);
       
        $editLink=new link();
        $editLink->link($this->uri(array('action'=>'editschedule','id'=>$sessionid)));
        $objIcon->setIcon('edit');
        $editLink->link=$objIcon->show();
        $editLink->extra='onClick="showEditSessionWin();return false;"';

        $deleteLink=new link();
        $deleteLink->link($this->uri(array('action'=>'deleteschedule','id'=>$sessionid)));
        $objIcon->setIcon('delete');
        $deleteLink->link=$objIcon->show();
        $vars="
<script language=\"JavaScript\">
         var sessionid='$sessionid';
</script>
         ";
        $this->appendArrayVar('headerParams', $vars);
        $deleteLink->extra='onClick="deleteSchedule(sessionid);return false;"';

        $showEdit=$this->objDbSchedules->isScheduleOwner($sessionid)?$editLink->show().$deleteLink->show():"";

        foreach($xsessionData as $sessionData){
            $sessionTitle=$sessionData['title'];
            $meetingDate=$sessionData['meeting_date'];
            $timeFrom=$sessionData['start_time'];
            $timeTo=$sessionData['end_time'];
            $sessiontype=$sessionData['session_type'];
        }
        //$sessionTitle.=$showEdit;
        //data grid from db
        $dbdata=$this->objDbScheduleMembers->getScheduleMembers($sessionid);
        $total=count($dbdata);
        $index=0;
        foreach($dbdata as $row){
            $deleteLink=new link();

            $deleteLink->link($this->uri(array('action'=>'deleteroommember','userid'=>$row['userid'],'sessionid'=>$sessionid)));
            $objIcon->setIcon('delete');
            $deleteLink->link=$objIcon->show();

            $data.="[";
            $data.="'".$this->objUser->fullname($row['userid'])."',";
            $data.="'Default',";
            $data.="'".$deleteLink->show()."'";
            $data.="]";
            $index++;
            if($index <= $total-1){
                $data.=',';
            }
        }

        $usrdata=$this->objDbScheduleMembers->getUsers();
        $total=count($usrdata);
        $index=0;
        $userlist="";
        foreach($usrdata as $row){
            $userlist.="[";
            $userlist.="'".$row['userid']."',";
            $userlist.="'".$row['surname']." ".$row['firstname']."'";
            $userlist.="]";
            $index++;
            if($index <= $total-1){
                $userlist.=',';
            }
        }

        $submitUrl2 = $this->uri(array('action' => 'saveroommember','sessionid'=>$sessionid));
        $updateSession = $this->uri(array('action' => 'updatesession','sessionid'=>$sessionid));
        $deleteSession = $this->uri(array('action' => 'deletesession','sessionid'=>$sessionid));
        $title='Name';
        $group='Group';
        $edit='Edit';

        $registerUrl = $this->uri(array('action' => 'showregister','sessionid'=>$sessionid));
        $registerUrl2 = $this->uri(array('action' => 'registerexisting','sessionid'=>$sessionid));

        $mainjs = "
                Ext.onReady(function(){
                var meetingDate='$meetingDate';
                var timeFrom='$timeFrom';
                var timeTo='$timeTo';
                var addmemberUrl='".str_replace("amp;", "", $submitUrl2)."';
                var editUrl='".str_replace("amp;", "", $updateSession)."';
                var sessionTitle='$sessionTitle';
                var deleteUrl='".str_replace("amp;", "", $deleteSession)."';
                var registerUrl='".str_replace("amp;", "", $registerUrl)."';
                var registerUrl2='".str_replace("amp;", "", $registerUrl2)."';
                var userlist=[$userlist];
                var sessiontype='$sessiontype';
                var showEdit='$showEdit';
                initAddMember(userlist,addmemberUrl);
                initEditScheduleFrame(meetingDate,timeFrom,timeTo,editUrl,sessionTitle,sessiontype,showEdit);

                var sessiondata=[meetingDate,timeFrom,timeTo,editUrl,sessionTitle,deleteUrl,registerUrl,registerUrl2,sessiontype];

                var membersdata=[$data];
                showSessionDetails(sessiondata,membersdata);
                });
               ";

        $content.= "<div id=\"buttons-layer\"></div><script type=\"text/javascript\">".$mainjs."</script>";


        return $content;
    }

}
?>
