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
        $extbase = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/adapter/ext/ext-base.js','htmlelements').'" type="text/javascript"></script>';
        $extalljs = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/ext-all.js','htmlelements').'" type="text/javascript"></script>';
        $extallcss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('ext-3.0-rc2/resources/css/ext-all.css','htmlelements').'"/>';
        $maincss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('css/session.css').'"/>';

        $this->appendArrayVar('headerParams', $extbase);
        $this->appendArrayVar('headerParams', $extalljs);
        $this->appendArrayVar('headerParams', $extallcss);
        $this->appendArrayVar('headerParams', $maincss);
    }
    public function showSessionList($presentationId,$presentationName,$xxslidesDir)
    {
        if(!$this->objUser->isLoggedIn()){
                        // Create an instance of the css layout class
            $cssLayout =$this->getObject('csslayout', 'htmlelements');// Set columns to 2
            $security=$this->getObject('security');
            $objBlocks = $this->getObject('blocks', 'blocks');
            $cssLayout->setNumColumns(2);
            //Add the table to the centered layer
            $rightSideColumn .=  $security->getLoginErrorMessage();

            $postLoginMenu  = $this->newObject('postloginmenu','toolbar');
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
          which you are a member.</p>
          <p>
         Select one to join. You can start you own sessions by clicking on the
         <font color="green"><b>Add Session</b></font> button.
         </p>
         ';
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
        $inviteUrl=$this->objAltConfig->getSiteRoot();


        $addButton = new button('add','Add Session');
        //$addButton->setOnClick("showAddSessionWindow();");
        $addButton->setId('show-btn');

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
        $index=0;
        foreach($dbdata as $row){
            $xsessionData=$this->objDbSchedules->getSchedule($row['sessionid']);
            foreach($xsessionData as $sessionData){
                $sessionTitle=$sessionData['title'];
                $sessionOwner=$sessionData['owner'];
                $creationDate=$sessionData['creation_date'];
                
            }
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
            $roomUrl.='skinclass=null';

            $deleteLink->link($this->uri(array('action'=>'deleteschedule','id'=>$row['id'])));
            $objIcon->setIcon('delete');
            $deleteLink->link=$objIcon->show();


            $editLink->link($this->uri(array('action'=>'editschedule','id'=>$row['id'])));
            $objIcon->setIcon('edit');
            $editLink->link=$objIcon->show();

            $detailsLink->link($this->uri(array('action'=>'addroommember','id'=>$row['sessionid'])));
            $detailsLink->link='Details';

            $editDeleteLink=$this->objDbSchedules->isScheduleOwner($row['id'])? $editLink->show().$deleteLink->show():"N/A";
            $detailsL=$this->objDbSchedules->isScheduleOwner($row['sessionid'])?$detailsLink->show():"Details";
            $data.="[";
            $data.= "'<a href=\"".$roomUrl."\">".$sessionTitle."</a>',";
            $data.="'".$creationDate."',";
            $data.="'".$detailsL."',";
            $data.="'".$this->objUser->fullname($sessionOwner)."',";

            $data.="]";
            $index++;
            if($index <= $total-1){
                $data.=',';
            }
        }

        $submitUrl = $this->uri(array('action' => 'saveschedule'));

        $title='Title';
        $dateCreated='Date Created';
        $details='Details';

        $owner='Owner';
        $edit='Edit';



        $mainjs = "/*!
                 * Ext JS Library 3.0.0
                 * Copyright(c) 2006-2009 Ext JS, LLC
                 * licensing@extjs.com
                 * http://www.extjs.com/license
                 */
                Ext.onReady(function(){

                    Ext.QuickTips.init();



                    var xg = Ext.grid;

                    // shared reader
                    var reader = new Ext.data.ArrayReader({}, [
                       {name: 'title'},
                       {name: 'creationdate'},
                       {name: 'details'},
                       {name: 'owner'},


                    ]);


                   Ext.ToolTip.prototype.onTargetOver =
                        Ext.ToolTip.prototype.onTargetOver.createInterceptor(function(e) {
                            this.baseTarget = e.getTarget();
                        });
                    Ext.ToolTip.prototype.onMouseMove =
                        Ext.ToolTip.prototype.onMouseMove.createInterceptor(function(e) {
                            if (!e.within(this.baseTarget)) {
                                this.onTargetOver(e);
                                return false;
                            }
                        });

                    var grid = new xg.GridPanel({
                        store: new Ext.data.GroupingStore({
                            reader: reader,
                            data: xg.Data,
                            sortInfo:{field: 'title', direction: \"ASC\"},
                            groupField:'owner'
                        }),

                        columns: [
                            {id:'".$title."',header: \"".$title."\", width: 300, dataIndex: 'title'},
                            {header: \"".$dateCreated."\", width: 150, dataIndex: 'creationdate'},
                            {header: \"".$details."\", width: 50, dataIndex: 'details'},
                            {header: \"".$owner."\", width: 100, dataIndex: 'owner'}

                        ],

                        view: new Ext.grid.GroupingView({
                            forceFit:true,
                            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? \"Items\" : \"Item\"]})'
                        }),

                        frame:false,
                        width: 700,
                        height: 350,
                        x: 20,
                        collapsible: false,
                        animCollapse: false,

                        renderTo: 'grouping-grid',
                        listeners: {
                                    render: function(g) {
                                        g.on(\"beforetooltipshow\", function(grid, row, col) {
                                            var tipText=\"\";
                                            if(col == 0){
                                              tipText=\"Click here to enter live session.\";
                                            }
                                            if(col == 1){
                                              tipText=\"The date this session was created.\";
                                            }
                                            if(col == 2){
                                              tipText=\"Click here to view more details on the session.\";
                                            }
                                            if(col == 3){
                                              tipText=\"The owner of the session.\";
                                            }

                                            grid.tooltip.body.update(tipText);
                                        });
                                    }
                                },


                        onRender: function() {
                                Ext.grid.GridPanel.prototype.onRender.apply(this, arguments);
                                this.addEvents(\"beforetooltipshow\");
                                this.tooltip = new Ext.ToolTip({
                                    renderTo: Ext.getBody(),
                                    target: this.view.mainBody,
                                    listeners: {
                                        beforeshow: function(qt) {
                                            var v = this.getView();
                                            var row = v.findRowIndex(qt.baseTarget);
                                            var cell = v.findCellIndex(qt.baseTarget);
                                            this.fireEvent(\"beforetooltipshow\", this, row, cell);
                                        },
                                        scope: this
                                    }
                                });
                            }


                    });
                });

                // Array data for the grids
                Ext.grid.Data = [".$data."];
var hours= [
        ['00:00 am'],
        ['01:30 am'],
        ['02:00 am'],
        ['02:30 am'],
        ['03:00 am'],
        ['03:30 am'],
        ['04:00 am'],
        ['04:30 am'],
        ['05:00 am'],
        ['05:30 am'],
        ['06:00 am'],
        ['06:30 am'],
        ['07:00 am'],
        ['07:30 am'],
        ['08:00 am'],
        ['08:30 am'],
        ['09:00 am'],
        ['09:30 am'],
        ['10:00 am'],
        ['10:30 am'],
        ['11:00 am'],
        ['11:30 am'],
        ['12:00 pm'],
        ['12:30 pm'],
        ['01:00 pm'],
        ['01:30 pm'],
        ['02:00 pm'],
        ['02:30 pm'],
        ['03:00 pm'],
        ['03:30 pm'],
        ['04:00 pm'],
        ['04:30 pm'],
        ['05:00 pm'],
        ['05:30 pm'],
        ['06:00 pm'],
        ['06:30 pm'],
        ['07:00 pm'],
        ['07:30 pm'],
        ['08:00 pm'],
        ['08:30 pm'],
        ['09:00 pm'],
        ['09:30 pm'],
        ['10:00 pm'],
        ['10:30 pm'],
        ['11:00 pm'],
        ['11:30 pm']];
    var startDateField=new Ext.form.DateField(
    {
        fieldLabel:'Date',
        emptyText:'Select date ...',
        width: 200,
        format:'Y-m-d',
        allowBlank:false,
        editable:false,
        name: 'date'
    }
    );
   var timefromstore = new Ext.data.ArrayStore({
        fields: ['timefrom'],
        data : hours
    });
    var timeFromField = new Ext.form.ComboBox({
        store: timefromstore,
        displayField:'timefrom',
        fieldLabel:'Time',
        typeAhead: true,
        mode: 'local',
        editable:false,
        forceSelection: true,
        triggerAction: 'all',
        emptyText:'Select time from...',
        selectOnFocus:true,
        name : 'starttime'

    });
    var timetostore = new Ext.data.ArrayStore({
        fields: ['timeto'],
        data :hours
    });
    var timeToField = new Ext.form.ComboBox({
        store: timetostore,
        displayField:'timeto',
        fieldLabel:'Time',
        typeAhead: true,
        mode: 'local',
        forceSelection: true,
        editable:false,
        triggerAction: 'all',
        emptyText:'Select time to...',
        selectOnFocus:true,
        name: 'endtime'
    });


    var form = new Ext.form.FormPanel({
        baseCls: 'x-plain',
        labelWidth: 55,
        bodyStyle:'padding:5px 5px 0',
        standardSubmit: true,
        url:'".str_replace("amp;", "", $submitUrl)."',
        defaultType: 'textfield',

        items: [{
            fieldLabel: 'Title',
            name: 'title',
            text: 'Default',
            allowBlank:false,
            anchor:'100%'  // anchor width by percentage
        },

        startDateField,timeFromField, timeToField
        ]
    });

    var addSessionWin;
    var button = Ext.get('show-btn');

    button.on('click', function(){
       if(!addSessionWin){
            addSessionWin = new Ext.Window({
                applyTo:'addsession-win',
                layout:'fit',
                width:500,
                height:250,
                x:250,
                y:50,
                closeAction:'destroy',
                plain: true,

               items: form,
      listeners: {
            hide: function() {
               //alert('hide');
            },
            collapse: function() {
              // alert('collapse');
            }
         },

                buttons: [{
                    text:'Save',
                    handler: function(){
                        if (form.url)
                            form.getForm().getEl().dom.action = form.url;

                        form.getForm().submit();
                    }
                },{
                    text: 'Close',
                    handler: function(){
                       addSessionWin.hide();
                    }
                }]
            });
        }
        addSessionWin.show(this);
});
var detailsWin;
function showDetailsWindow(){
       if(!detailsWin){
            detailsWin = new Ext.Window({
                applyTo:'addsession-win',
                layout:'fit',
                width:400,
                height:250,
                x:250,
                y:50,
                closeAction:'destroy',
                plain: true,

               items: { html: \"My content was added during construction.\"},
      listeners: {
            hide: function() {
               //alert('hide');
            },
            collapse: function() {
              // alert('collapse');
            }
         },

                buttons: [{
                    text:'Save',
                    handler: function(){
                        if (form.url)
                            form.getForm().getEl().dom.action = form.url;

                        form.getForm().submit();
                    }
                },{
                    text: 'Close',
                    handler: function(){
                       detailsWin.hide();
                    }
                }]
            });
        }
         detailsWin.show(this);
}
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


        $addButton = new button('add','Add Member');
        $addButton->setId('add-btn');

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
        $content.='<hr><h3>Session Members</h3>'. $addButton->show().$renderSurface.'</br><div id="grouping-grid"></div>';


        $data='';
        $xsessionData=$this->objDbSchedules->getSchedule($sessionid);
        foreach($xsessionData as $sessionData){
            $sessionTitle=$sessionData['title'];
            $meetingDate=$sessionData['meeting_date'];
            $timeFrom=$sessionData['start_time'];
            $timeTo=$sessionData['end_time'];
        }
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
            $userlist.="'".$row['surname']." ".$row['firstname']."',";
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



        $mainjs = "
                Ext.onReady(function(){
                Ext.QuickTips.init();
                var xg = Ext.grid;

                var hours= [
                                    ['00:00 am'],
                                    ['01:30 am'],
                                    ['02:00 am'],
                                    ['02:30 am'],
                                    ['03:00 am'],
                                    ['03:30 am'],
                                    ['04:00 am'],
                                    ['04:30 am'],
                                    ['05:00 am'],
                                    ['05:30 am'],
                                    ['06:00 am'],
                                    ['06:30 am'],
                                    ['07:00 am'],
                                    ['07:30 am'],
                                    ['08:00 am'],
                                    ['08:30 am'],
                                    ['09:00 am'],
                                    ['09:30 am'],
                                    ['10:00 am'],
                                    ['10:30 am'],
                                    ['11:00 am'],
                                    ['11:30 am'],
                                    ['12:00 pm'],
                                    ['12:30 pm'],
                                    ['01:00 pm'],
                                    ['01:30 pm'],
                                    ['02:00 pm'],
                                    ['02:30 pm'],
                                    ['03:00 pm'],
                                    ['03:30 pm'],
                                    ['04:00 pm'],
                                    ['04:30 pm'],
                                    ['05:00 pm'],
                                    ['05:30 pm'],
                                    ['06:00 pm'],
                                    ['06:30 pm'],
                                    ['07:00 pm'],
                                    ['07:30 pm'],
                                    ['08:00 pm'],
                                    ['08:30 pm'],
                                    ['09:00 pm'],
                                    ['09:30 pm'],
                                    ['10:00 pm'],
                                    ['10:30 pm'],
                                    ['11:00 pm'],
                                    ['11:30 pm']];

    var startDateField=new Ext.form.DateField(
    {
        fieldLabel:'Date',
        emptyText:'Select date ...',
        width: 200,
        format:'Y-m-d',
        allowBlank:false,
        editable:false,
        value:'".$meetingDate."',
        name: 'date'
    }
    );

    var timefromstore = new Ext.data.ArrayStore({
        fields: ['timefrom'],
        data : hours
    });


    var timeFromField = new Ext.form.ComboBox({
        store: timefromstore,
        displayField:'timefrom',
        fieldLabel:'Time',
        typeAhead: true,
        mode: 'local',
        editable:false,
        forceSelection: true,
        triggerAction: 'all',
        emptyText:'Select time from...',
        selectOnFocus:true,
        value:'".$timeFrom."',
        name : 'starttime'

    });


    var timetostore = new Ext.data.ArrayStore({
        fields: ['timeto'],
        data :hours
    });


    var timeToField = new Ext.form.ComboBox({
        store: timetostore,
        displayField:'timeto',
        fieldLabel:'Time',
        typeAhead: true,
        mode: 'local',
        forceSelection: true,
        editable:false,
        triggerAction: 'all',
        emptyText:'Select time to...',
        selectOnFocus:true,
        value: '".$timeTo."',
        name: 'endtime'
    });


    var form = new Ext.form.FormPanel({
        baseCls: 'x-plain',
        labelWidth: 55,
        bodyStyle:'padding:5px 5px 0',
        standardSubmit: true,
        url:'".str_replace("amp;", "", $updateSession)."',
        defaultType: 'textfield',
       
        items: [
          {
            fieldLabel: 'Title',
            name: 'title',
            value: '".$sessionTitle."',
            allowBlank:false,
            anchor:'50%'  // anchor width by percentage
          },
          startDateField,timeFromField,timeToField],
          buttons: [{
                    text:'Update',
                    handler: function(){
                        if (form.url){
                            form.getForm().getEl().dom.action = form.url;
                        }
                        form.getForm().submit();
                    }
                   },

                   {
                    text:'Delete',
                    handler: function(){
                        Ext.MessageBox.confirm('Confirm', 'Are you sure you want to do that?', deleteSession);

                    }
                   }]});

                    function deleteSession(btn){
                        if(btn == 'yes'){
                        form.getForm().getEl().dom.action ='".str_replace("amp;", "", $deleteSession)."';
                        form.getForm().submit();
                       }
                   };

                    // shared reader
                    var reader = new Ext.data.ArrayReader({}, [
                       {name: 'names'},
                       {name: 'group'},
                       {name: 'edit'}
                    ]);


                    var panel = new Ext.Panel({
                        renderTo: 'sidebar',
                        title: 'Session',
                        width:600,
                        height:180,
                        activeTab: 0,
                        frame:true,
                        defaults:{autoHeight: true},
                        items: form,
                        renderTo: 'form-panel'
                        });

                    var grid = new xg.GridPanel({
                        store: new Ext.data.GroupingStore({
                        reader: reader,
                        data: xg.Data,
                        sortInfo:{field: 'names', direction: \"ASC\"},
                        groupField:'group'
                        }),

                        columns: [
                            {id:'".$title."',header: \"".$title."\", width: 300, dataIndex: 'names'},
                            {header: \"".$group."\", width: 100, dataIndex: 'group'},
                            {header: \"".$edit."\", width: 100, dataIndex: 'edit'}
                        ],

                        view: new Ext.grid.GroupingView({
                            forceFit:true,
                            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? \"Items\" : \"Item\"]})'
                        }),

                        frame:false,
                        width: 600,
                        height: 350,
                        x: 20,
                        collapsible: false,
                        animCollapse: false,

                        renderTo: 'grouping-grid'
                    });
                });

                // Array data for the grids
                Ext.grid.Data = [".$data."];

    var userdatastore = new Ext.data.ArrayStore({
        fields: ['userid','name'],
        data : [".$userlist."]
    });
    var userField = new Ext.form.ComboBox({
        store: userdatastore,
        displayField:'name',
        valueField: 'userid',
        fieldLabel:'Names',
        typeAhead: true,
        mode: 'local',
        editable:true,
        allowBlank:false,
        forceSelection: true,
        triggerAction: 'all',
        emptyText:'Select user...',
        selectOnFocus:true,
        hiddenName : 'userfield'

    });
    var form = new Ext.form.FormPanel({
        baseCls: 'x-plain',
        labelWidth: 55,
        bodyStyle:'padding:5px 5px 0',
        standardSubmit: true,
        url:'".str_replace("amp;", "", $submitUrl2)."',
        defaultType: 'textfield',
        items: userField

    });

    var addSessionWin;
    var button2 = Ext.get('add-btn');

    button2.on('click', function(){
       if(!addSessionWin){
            addSessionWin = new Ext.Window({
                applyTo:'addsession-win',
                layout:'fit',
                width:500,
                height:250,
                x:250,
                y:50,
                closeAction:'destroy',
                plain: true,

               items: form,

                buttons: [{
                    text:'Save',
                    handler: function(){
                        if (form.url){
                            form.getForm().getEl().dom.action = form.url;
                          }
                        form.getForm().submit();
                    }
                },{
                    text: 'Close',
                    handler: function(){
                       addSessionWin.hide();
                    }
                }]
            });
        }
        addSessionWin.show(this);
});
var detailsWin;
function showDetailsWindow(){
       if(!detailsWin){
            detailsWin = new Ext.Window({
                applyTo:'addsession-win',
                layout:'fit',
                width:400,
                height:250,
                x:250,
                y:50,
                closeAction:'destroy',
                plain: true,

               items: { html: \"My content was added during construction.\"},

                buttons: [{
                    text:'Save',
                    handler: function(){
                        if (form.url)
                            form.getForm().getEl().dom.action = form.url;

                        form.getForm().submit();
                    }
                },{
                    text: 'Close',
                    handler: function(){
                       detailsWin.hide();
                    }
                }]
            });
        }
         detailsWin.show(this);
}
";

        $content.= "<script type=\"text/javascript\">".$mainjs."</script>";


        return $content;
    }

}
?>
