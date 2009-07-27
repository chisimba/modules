<?php
/**
 * This template creates ext js data grid containing a list of schedules
 * The 'Add' button invokes a pop out window used for capturing the data
 */

//load the classes
$this->loadclass('link','htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');
$objIcon= $this->newObject('geticon','htmlelements');


// load the ext js and module specific scripts
$extbase = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/adapter/ext/ext-base.js','htmlelements').'" type="text/javascript"></script>';
$extalljs = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/ext-all.js','htmlelements').'" type="text/javascript"></script>';
$extallcss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('ext-3.0-rc2/resources/css/ext-all.css','htmlelements').'"/>';

$modPath=$this->objAltConfig->getModulePath();
$replacewith="";
$docRoot=$_SERVER['DOCUMENT_ROOT'];
$resourcePath=str_replace($docRoot,$replacewith,$modPath);
$codebase="http://" . $_SERVER['HTTP_HOST']."/".$resourcePath.'/realtime/resources/';

$objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
$servletURL=$objSysConfig->getValue('SERVLETURL', 'chisimbarealtimeplugin');
$openfireHost=$objSysConfig->getValue('OPENFIRE_HOST', 'chisimbarealtimeplugin');
$openfirePort=$objSysConfig->getValue('OPENFIRE_CLIENT_PORT', 'chisimbarealtimeplugin');
$openfireHttpBindUrl=$objSysConfig->getValue('OPENFIRE_HTTP_BIND', 'chisimbarealtimeplugin');

$username=$this->objUser->userName();
$fullnames=$this->objUser->fullname();
$email=$this->objUser->email();
$slidesDir='notdefined';
$ispresenter=$this->objUser->isLecturer() ? 'yes':'no';
$presentationId='notdefined';
$presentationName='notdefined';
$inviteUrl=$this->objAltConfig->getSiteRoot();

$action='
<script language="JavaScript">

</script>
';

//initialize js file containing the time entries
$datedata = '<script language="JavaScript" src="'.$this->getResourceUri('datedata.js').'" type="text/javascript"></script>';

$this->appendArrayVar('headerParams', $extbase);
$this->appendArrayVar('headerParams', $extalljs);
$this->appendArrayVar('headerParams', $extallcss);
$this->appendArrayVar('headerParams', $action);
$this->appendArrayVar('headerParams', $datedata);


//initialize language items
$title= 'Session';
$owner='Instructor';
$startDate='Start Date';
$endDate='End Date';
$enrolled='Enrolled';
$session='Session';
$edit='Edit';
//where we render the 'popup' window
$content='<div id="addsession-win" class="x-hidden">
<div class="x-window-header">Add Session</div>
</div>';

$chapters="[";
$xchapters=$this->objDbSchedules->getChapters($this->contextCode);
foreach($xchapters as $c){
    $chapters.="['".$c["chapter"]."'],";
}
$chapters.="]";

// Create an instance of the css layout class
$cssLayout = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
$cssLayout->setNumColumns(2);


$addButton = new button('add-session','Add Schedule');
$addButton->setOnClick("showAddSessionWindow();");
$addButton->setId('add-session');

$addButtonShow=$this->objUser->isLecturer()? $addButton->show():"";
$toolbar = $this->getObject('contextsidebar','context');
$cssLayout->setLeftColumnContent($toolbar->show());
$rightSideColumn = '<h1>'.$this->objContext->getTitle($this->contextcode).'</h1><div id="grouping-grid">'.$content.$addButtonShow.'<br/><br /></div>';
$cssLayout->setMiddleColumnContent($rightSideColumn);
$submitUrl = $this->uri(array('action' => 'saveschedule'));
$data='';

//data grid from db
$dbdata=$this->objDbSchedules->getSchedules($this->contextCode);

foreach($dbdata as $row){
    $deleteLink=new link();
    $editLink=new link();

    $roomUrl='';
    $roomUrl.=$servletURL.'?';
    $roomUrl.='port='.$openfirePort.'&';
    $roomUrl.='host='.$openfireHost.'&';
    $roomUrl.='username='.$username.'&';
    $roomUrl.='roomname='.$row[title].'&';
    $roomUrl.='audiovideourl='.$openfireHttpBindUrl.'&';
    $roomUrl.='slidesdir='.$slidesDir.'&';
    $roomUrl.='ispresenter='.$ispresenter.'&';
    $roomUrl.='presentationid='.$presentationId.'&';
    $roomUrl.='presentationName='.$presentationName.'&';
    $roomUrl.='names='.$fullnames.'&';
    $roomUrl.='email='.$email.'&';
    $roomUrl.='inviteurl='.$inviteUrl.'&';
    $roomUrl.='useec2=false&';
    $roomUrl.='joinid=none&';
    $roomUrl.='codebase='.$codebase;

    $deleteLink->link($this->uri(array('action'=>'deleteschedule','id'=>$row['id'])));
    $objIcon->setIcon('delete');
    $deleteLink->link=$objIcon->show();
    

    $editLink->link($this->uri(array('action'=>'editschedule','id'=>$row['id'])));
    $objIcon->setIcon('edit');
    $editLink->link=$objIcon->show();

    $editDeleteLink=$this->objUser->isLecturer($this->contextcode) ? $editLink->show().$deleteLink->show():"Open";
    $data.="[";
    $data.= "'<a href=\"".$roomUrl."\">".$row['title']."</a>',";
    $data.="'".$this->objUser->fullname($row['owner'])."',";
    $data.="'".$row['start_date']." ".$row['start_time']."',";
    $data.="'".$row['end_date']." ".$row['end_time']."',";
    $data.="'".$row['participants']."',";
    $data.="'".$row['category']."',";
    $data.="'".$editDeleteLink."',";
    $data.="],";
}

//contruct the ext js window
$mainjs = " Ext.onReady(function(){

                    Ext.QuickTips.init();

                    var xg = Ext.grid;

                    // shared reader
                    var reader = new Ext.data.ArrayReader({}, [
                       {name: 'title'},
                       {name: 'owner'},
                       {name: 'startDate'},
                       {name: 'endDate'},
                       {name: 'enrolled'},
                       {name: 'session'},
                       {name: 'edit'},
                    ]);

                    var grid = new xg.GridPanel({
                        store: new Ext.data.GroupingStore({
                            reader: reader,
                            data: xg.data,
                            sortInfo:{field: 'session', direction: \"ASC\"},
                            groupField:'session'
                        }),

                        columns: [
                            {id:'".$title."',header: \"".$title."\", width: 60, dataIndex: 'title'},
                            {header: \"".$owner."\", width: 30,dataIndex: 'owner'},
                            {header: \"".$startDate."\", width: 40, dataIndex: 'startDate'},
                            {header: \"".$endDate."\", width: 40, dataIndex: 'endDate'},
                            {header: \"".$session."\", width: 20, dataIndex: 'session'},
                            {header: \"".$enrolled."\", width: 20, dataIndex: 'enrolled'},
                            {header: \"".$edit."\", width: 20, dataIndex: 'edit'},
                        ],

                        view: new Ext.grid.GroupingView({
                            forceFit:true,
                            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? \"Items\" : \"Item\"]})'
                        }),

                        frame:true,
                        width: 750,
                        height: 250,
                        collapsible: true,
                        animCollapse: false,
                        title: 'The Schedules',
                        renderTo: 'grouping-grid'
                    });
                });

// Array data for the grids
Ext.grid.data = [
    ".$data."

];


    var timefromstore = new Ext.data.ArrayStore({
        fields: ['timefrom'],
        data : Ext.datedata.hours // from states.js
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
        data : Ext.datedata.hours // from states.js
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

    var categorystore = new Ext.data.ArrayStore({
        fields: ['chapter'],
        data : ".$chapters."
    });
    var categoryField = new Ext.form.ComboBox({
        store: categorystore,
        displayField:'chapter',
        fieldLabel:'Category',
        typeAhead: true,
        mode: 'local',
        forceSelection: true,
        editable:false,
        triggerAction: 'all',
        emptyText:'Select category...',
        selectOnFocus:true,
        name: 'category'
    });
    var startDateField=new Ext.form.DateField(
    {
        fieldLabel:'Start',
        emptyText:'Select start date ...',
        width: 200,
        format:'Y-m-d',
        allowBlank:false,
        editable:false,
        name: 'startdate'
    }
    );
    var endDateField=new Ext.form.DateField(
    {
        fieldLabel:'End',
        emptyText:'Select end date...',
        width: 200,
        format:'Y-m-d',
        allowBlank:false,
        editable:false,
        name: 'enddate'
    }
    );
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
            allowBlank:false,
            anchor:'100%'  // anchor width by percentage
        },
        
        categoryField,startDateField,timeFromField, endDateField,timeToField,
        {
            xtype: 'textarea',
            fieldLabel: 'About',
            name: 'desc',
            anchor: '100% -53'  // anchor width by percentage and height by raw adjustment
        }]
    });


var win;
function showAddSessionWindow(){
       if(!win){
            win = new Ext.Window({
                applyTo:'addsession-win',
                layout:'fit',
                width:500,
                height:350,
                x:250,
                y:150,
                closeAction:'hide',
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
                    text: 'Close',
                    handler: function(){
                        win.hide();
                    }
                }]
            });
        }
        win.show(this);
}

   

";

echo "<script type=\"text/javascript\">".$mainjs."</script>";
echo $cssLayout->show();
?>
