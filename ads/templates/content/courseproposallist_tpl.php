<?php
//load class
$this->loadclass('link','htmlelements');
$objIcon= $this->newObject('geticon','htmlelements');

// objects
$courseProposals = $this->objCourseProposals->getCourseProposals($this->objUser->userId());

// scripts
$extbase = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/adapter/ext/ext-base.js','htmlelements').'" type="text/javascript"></script>';
$extalljs = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/ext-all.js','htmlelements').'" type="text/javascript"></script>';
$extallcss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('ext-3.0-rc2/resources/css/ext-all.css','htmlelements').'"/>';
$maincss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('css/courseproposal.css').'"/>';

$this->appendArrayVar('headerParams', $extbase);
$this->appendArrayVar('headerParams', $extalljs);
$this->appendArrayVar('headerParams', $extallcss);
$this->appendArrayVar('headerParams', $maincss);

// language stuff
$note = $this->objLanguage->languageText('mod_ads_note', 'ads');
$proposalTitle=$this->objLanguage->languageText('mod_ads_proposals','ads');
$title = $this->objLanguage->languageText('mod_ads_title', 'ads');
$dateCreated = $this->objLanguage->languageText('mod_ads_datecreated', 'ads');
$owner = $this->objLanguage->languageText('mod_ads_owner', 'ads');
$status = $this->objLanguage->languageText('mod_ads_status', 'ads');
$currVersion = $this->objLanguage->languageText('mod_ads_currversion', 'ads');
$lastEdit = $this->objLanguage->languageText('mod_ads_lastedit', 'ads');
$edit = $this->objLanguage->languageText('mod_ads_edit', 'ads');
$faculty = $this->objLanguage->languageText('mod_ads_faculty', 'ads');

$addButton = new button('add','Add Proposal');
$returnUrl = $this->uri(array('action' => 'addcourseproposal'));
//$addButton->setOnClick("window.location='$returnUrl'");
$addButton->setId('addproposal-btn');

//prints out add comment message

if ($this->addCommentMessage){
    $message = "<span id=\"commentSuccess\">".$this->objLanguage->languageText('mod_ads_commentSuccess', 'ads')."</span><br />";
    $this->addCommentMessage = false;
} else $message = "";
$content = $message;

// Create an instance of the css layout class
$cssLayout = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
$cssLayout->setNumColumns(2);

$postLoginMenu  = $this->newObject('postloginmenu','toolbar');
$cssLayout->setLeftColumnContent($postLoginMenu->show());
$rightSideColumn =  '<h1>Course/Unit Proposals</h1><div id ="proposal-note">'.$content.$note.'</div><div id="grouping-grid">'.$addButton->show().'<br /><br /></div>';
//where we render the 'popup' window
$renderSurface='<div id="addsession-win" class="x-hidden">
        <div class="x-window-header"></div>
        </div>';
$rightSideColumn.=$renderSurface;
$cssLayout->setMiddleColumnContent($rightSideColumn);

echo $cssLayout->show();

$statusLink = new link();
$titleLink = new link();
$deleteLink=new link();
$editLink=new link();
$submitLink = new link();
$reviewLink=new link();
$commentLink=new link();

$data = "";
$numberOfRows = $this->objCourseProposals->getNumberOfCourses();

foreach($courseProposals as $value) {
    $verarray = $this->objDocumentStore->getVersion($value['id'], $this->objUser->userId());
    $titleLink->link($this->uri(array('action'=>'showcourseprophist', 'courseid'=>$value['id'])));
    $titleLink->link=$value['title'];

    $statusLink->link($this->uri(array('action'=>'viewcourseproposalstatus','id'=>$value['id'], 'status'=>$value['status'])));
    switch($value['status']) {
        case 0: $statusLink->link='New';
            break;
        case 1: $statusLink->link='APO Comment';
            break;
        case 2: $statusLink->link='Library comment';
            break;
        case 3: $statusLink->link='Subsidy comment';
            break;
            case 4: $statusLink->link='Faculty subcommittee';
                break;
                case 5: $statusLink->link='Faculty';
                    break;
                    case 6: $statusLink->link='APDC';
                        break;
                        default: $statusLink->link= 'New';
                        }

                        $deleteLink->link($this->uri(array('action'=>'deletecourseproposal','id'=>$value['id'])));
                        $editLink->link($this->uri(array('action'=>'editcourseproposal','id'=>$value['id'])));

                        //review
                        $reviewLink->link($this->uri(array('action'=>'reviewcourseproposal','id'=>$value['id'])));

                        $data .= "['".$titleLink->show();


                        $data .= "',";
                        $data .= "'".$value['creation_date']."',";
                        $data .= "'".$this->objUser->fullname($value['userid'])."',";
                        $data .= "'".$statusLink->show()."',";
                      //  $data .= "'".$verarray['version'] .".00',";

                        $tmpID = $this->objDocumentStore->getUserId($verarray['currentuser']);
                        $name = $this->objUser->fullname($tmpID);
                        //$data .= "'".$name."',";

                        $objIcon->setIcon('delete');
                        $deleteLink->link=$objIcon->show();
                        $data .= "'".$deleteLink->show();

                        $objIcon->setIcon('edit');
                        $editLink->link=$objIcon->show();
                        $data .= $editLink->show();

                        $objIcon->setIcon('view');
                        //$objIcon->setAlt('review');
                        $reviewLink->link=$objIcon->show();
                        //$data .= $reviewLink->show();
                        //$objIcon->resetAlt();

                        if (strcmp($this->objUser->fullname($verarray['currentuser']),'Administrative User') == 0) {
                            $commentLink->link($this->uri(array('action'=>'addcomment',
                                                'id'=>$value['id'],
                                                'title'=>$value['title'],
                                                'date'=>$value['creation_date'],
                                                'owner'=>$this->objUser->fullname($value['userid']),
                                                'status'=>$status,
                                                'version'=>$verarray['version'],
                                                'lastedit'=>$this->objUser->fullname($verarray['currentuser']))));
                            $objIcon->setIcon('comment');
                            $commentLink->link = $objIcon->show();
                            $data .= $commentLink->show();
                        }
                        $data .= "',";

                        $data .= "'".$value['faculty']."'";
                        $data .= "]";
                        if($value['puid'] != $numberOfRows) {
                            $data .= ",";
                        }

                    }
$faculty = $objLanguage->languageText('mod_ads_faculty','ads');
$unitName = $objLanguage->languageText('mod_ads_unitname','ads');
$submitUrl = $this->uri(array('action'=>'savecourseproposal'));
$cancelUrl = $this->uri(array('action'=>'NULL'));
$addProposalWindowJS=
"
    var addProposalWin;
    var button = Ext.get('addproposal-btn');

    button.on('click', function(){
       if(!addProposalWin){
            addProposalWin = new Ext.Window({
                applyTo:'addsession-win',
                layout:'fit',
                width:500,
                height:250,
                x:250,
                y:150,
                closeAction:'destroy',
                plain: true,

               items: form,
               buttons: [{
                    text:'Save',
                    handler: function(){
                        if (form.url)
                            form.getForm().getEl().dom.action = form.url;

                        form.getForm().submit();
                    }
                },{
                    text: 'Cancel',
                    handler: function(){
                       addProposalWin.hide();
                    }
                }]
            });
        }
        addProposalWin.show(this);
});
";

$faculties=
    "
var faculties= [
        ['Commerce, Law and Management'],
        ['Engineering and the Built Environment'],
        ['Health Sciences'],
        ['Humanities'],
        ['Science']
      ]

   var facutlystore = new Ext.data.ArrayStore({
        fields: ['faculty'],
        data : faculties
    });
    var facultyField = new Ext.form.ComboBox({
        store: facutlystore,
        displayField:'faculty',
        fieldLabel:'Faculty',
        typeAhead: true,
        mode: 'local',
        editable:false,
        allowBlank: false,
        forceSelection: true,
        triggerAction: 'all',
        emptyText:'Select faculty...',
        selectOnFocus:true,
        name : 'faculty'

    });
";

$proposalForm=
 "
       var form = new Ext.FormPanel({
            standardSubmit: true,
            labelWidth: 125, // label settings here cascade unless overridden
            url:'".str_replace("amp;", "", $submitUrl)."',
            frame:true,
            title: 'Add  New Course Proposal',
            bodyStyle:'padding:5px 5px 0',
            width: 350,
            defaults: {width: 230},
            defaultType: 'textfield',

            items: [
                     facultyField,
                    {
                    fieldLabel: '".$unitName."',
                    name: 'title',
                    id: 'input_title',
                    allowBlank: false
                }
            ]

        });
 ";
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
                       {name: 'dateCreated'},
                       {name: 'owner'},
                       {name: 'status'},
                       //{name: 'currVersion'},
                       //{name: 'lastEdit'},
                       {name: 'edit'},
                       {name: 'faculty'}
                    ]);

                    var grid = new xg.GridPanel({
                        store: new Ext.data.GroupingStore({
                            reader: reader,
                            data: xg.Data,
                            sortInfo:{field: 'title', direction: \"ASC\"},
                            groupField:'faculty'
                        }),

                        columns: [
                            {id:'".$title."',header: \"".$title."\", width: 200, dataIndex: 'title'},
                            {header: \"".$dateCreated."\", width: 150},
                            {header: \"".$owner."\", width: 120},
                            {header: \"".$status."\", width: 50, dataIndex: 'status'},
                            //{header: \"".$currVersion."\", width: 20, dataIndex: 'currVersion'},
                            {header: \"".$faculty."\", width: 100, dataIndex: 'faculty'},
                            //{header: \"".$lastEdit."\", width: 50, dataIndex: 'lastEdit'},
                            {header: \"".$edit."\", width: 70, dataIndex: 'edit'}
                        ],

                        view: new Ext.grid.GroupingView({
                            forceFit:true,
                            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? \"Items\" : \"Item\"]})'
                        }),

                        frame:false,
                        width: 750,
                        height: 450,
                        collapsible: true,
                        animCollapse: false,
                       
                        renderTo: 'grouping-grid'
                    });
                });


                // Array data for the grids
               Ext.grid.Data = [".$data."];
                  ".$faculties."
                  ".$proposalForm."
                  ".$addProposalWindowJS."

               ";

                    echo "<script type=\"text/javascript\">".$mainjs."</script>";
                    $tooltipHelp =& $this->getObject('tooltip','htmlelements');
                    $tooltipHelp->setCaption('Help');
                    $tooltipHelp->setText('Some help text...');
                    $tooltipHelp->setCursor('help');
                    echo $tooltipHelp->show();



                    ?>
