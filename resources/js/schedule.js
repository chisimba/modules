
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
var addMemberForm;
var editform;
    var types= [
        ['Private'],
        ['Public']
    ];
function initEditScheduleFrame(meetingDate,timeFrom,timeTo,url,sessionTitle,sessiontype,showEdit){
    var startDateField=new Ext.form.DateField(
    {
        fieldLabel:'Date',
        emptyText:'Select date ...',
        width: 200,
        format:'Y-m-d',
        allowBlank:false,
        editable:false,
        value:meetingDate,
          anchor:'50%', 
        name: 'date'
    }
    );

    var timefromstore = new Ext.data.ArrayStore({
        fields: ['timefrom'],
        data : hours
    });

    var typestore = new Ext.data.ArrayStore({
        fields: ['type'],
        data : types
    });
 var typefield = new Ext.form.ComboBox({
        store: typestore,
        displayField:'type',
        fieldLabel:'Type',
        typeAhead: true,
        mode: 'local',
        editable:false,
        forceSelection: true,
        triggerAction: 'all',
        emptyText:'Select session type...',
        selectOnFocus:true,
        value:sessiontype,
          anchor:'50%',
        name : 'typefield'

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
        value:timeFrom,
          anchor:'50%',
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
        value: timeTo,
        anchor:'50%',
        name: 'endtime'
    });


    editform = new Ext.form.FormPanel({
        baseCls: 'x-plain',
        labelWidth: 55,
        bodyStyle:'padding:5px 5px 0',
        standardSubmit: true,
        url:url,
        defaultType: 'textfield',

        items: [
          {
            fieldLabel: 'Title',
            name: 'title',
            value: sessionTitle,
            allowBlank:false,
            anchor:'80%'  // anchor width by percentage
          },
          startDateField,timeFromField,timeToField,typefield]
    });
 
}

function showEditSessionWin(){
            var editSessionWin;
        if(!editSessionWin){
        editSessionWin = new Ext.Window({
        applyTo:'addsession-win',
        layout:'fit',
        width:500,
        height:250,
        x:250,
        y:50,
        closeAction:'destroy',
        plain: true,

        items: editform,

        buttons: [{
        text:'Save',
        handler: function(){
        if (editform.url){
        editform.getForm().getEl().dom.action = editform.url;
        }
        editform.getForm().submit();
        }
        },{
        text: 'Close',
        handler: function(){
        editSessionWin.hide();
        window.location.reload(true);
        }
        }]
        });
        }
        editSessionWin.show(this);
}

function initAddScheduleFrame(url){
    var startDateField=new Ext.form.DateField(
    {
        fieldLabel:'Date',
        emptyText:'Select date ...',
        width: 200,
        format:'Y-m-d',
        allowBlank:false,
        editable:false,
        anchor:'80%',
        name: 'date'
    }
    );

    var timefromstore = new Ext.data.ArrayStore({
        fields: ['timefrom'],
        data : hours
    });

    var typestore = new Ext.data.ArrayStore({
        fields: ['type'],
        data : types
    });
 var typefield = new Ext.form.ComboBox({
        store: typestore,
        displayField:'type',
        fieldLabel:'Type',
        typeAhead: true,
        mode: 'local',
        editable:false,
        forceSelection: true,
        triggerAction: 'all',
        emptyText:'Select session type...',
        selectOnFocus:true,
        anchor:'50%',
        name : 'typefield'

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
        anchor:'80%',
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
        anchor:'80%',
        triggerAction: 'all',
        emptyText:'Select time to...',
        selectOnFocus:true,
        name: 'endtime'
    });


    var form = new Ext.form.FormPanel({
        baseCls: 'x-plain',
        labelWidth: 55,
        fieldWidth:144,
        bodyStyle:'padding:5px 5px 0',
        standardSubmit: true,
        url:url,
        defaultType: 'textfield',

        items: [
          {
            fieldLabel: 'Title',
            name: 'title',
            allowBlank:false,
            anchor:'80%'  // anchor width by percentage
          },
          startDateField,timeFromField,timeToField,typefield]

});

   var button2 = Ext.get('add-session-btn');
   var addSessionWin;
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

}

function showSessions(data){

var xg = Ext.grid;
// shared reader
var reader = new Ext.data.ArrayReader({}, [
   {name: 'sessiontitle'},
   {name: 'sessiondate'},
   {name: 'edit'},
   {name: 'owner'}
]);
// Array data for the grids
Ext.grid.Data = data;

var grid = new xg.GridPanel({
    store: new Ext.data.GroupingStore({
    reader: reader,
    data: xg.Data,
    sortInfo:{field: 'sessiontitle', direction: "ASC"},
    groupField:'owner'
    }),

    columns: [
        {id:'title',header: "Title", width: 300, dataIndex: 'sessiontitle'},
        {header: "Date", width: 100, dataIndex: 'sessiondate'},
        {header: "Owner", width: 100, dataIndex: 'owner'},
        {header: "Edit", width: 100, dataIndex: 'edit'}
    ],

    view: new Ext.grid.GroupingView({
        forceFit:true,
        groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? \"Schedules\" : \"Schedule\"]})'
    }),

    frame:false,
    width: 600,
    height: 350,
    x: 20,
    collapsible: false,
    animCollapse: false,
    renderTo: 'grouping-grid'
   
});


}
function showSessionDetails(sessiondata,membersdata){
ButtonPanel = Ext.extend(Ext.Panel, {

    layout:'table',
    defaultType: 'button',
    baseCls: 'x-plain',
    cls: 'btn-panel',
    menu: undefined,
    split: true,
    bodyStyle:'margin-top:2em;margin-bottom:2em;',
    constructor: function(buttons){
        for(var i = 0, b; b = buttons[i]; i++){
            b.menu = this.menu;
            b.enableToggle = this.enableToggle;
            b.split = this.split;
            b.arrowAlign = this.arrowAlign;
        }
        var items = buttons;

        ButtonPanel.superclass.constructor.call(this, {
            items: items
        });
    }
});

var xg = Ext.grid;
// shared reader
var reader = new Ext.data.ArrayReader({}, [
   {name: 'names'},
   {name: 'group'},
   {name: 'edit'}
]);
// Array data for the grids
Ext.grid.Data = membersdata;

var grid = new xg.GridPanel({
    store: new Ext.data.GroupingStore({
    reader: reader,
    data: xg.Data,
    sortInfo:{field: 'names', direction: "ASC"},
    groupField:'group'
    }),

    columns: [
        {id:'title',header: "Title", width: 300, dataIndex: 'names'},
        {header: "Group", width: 100, dataIndex: 'group'},
        {header: "Edit", width: 100, dataIndex: 'edit'}
    ],

    view: new Ext.grid.GroupingView({
        forceFit:true,
        groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? \"Members\" : \"Member\"]})'
    }),

    frame:false,
    width: 600,
    height: 350,
    x: 20,
    collapsible: false,
    animCollapse: false
    
});    ButtonPanel.override({
     //renderTo : 'buttons-layer'
      });
  var buttons= new ButtonPanel(

       [
       {
            
            text:'Add Member',
            handler: function(){
           showAddMemberWin();
            }
        },
           {

            text:'Edit Session',
            handler: function(){
           showEditSessionWin();
            }
        }

       ]
    );

var form = new Ext.form.FormPanel({
        baseCls: 'x-plain',
        labelWidth: 100,
        fieldWidth:144,
        bodyStyle:'padding:5px 5px 0',
        standardSubmit: true,
        
        defaultType: 'textfield',
        renderTo: 'grouping-grid',
        //[meetingDate,timeFrom,timeTo,editUrl,sessionTitle,deleteUrl];
        items: [
            new Ext.form.DisplayField({
            fieldLabel: '<b>Session Title<b>',
            value: '<h3>'+sessiondata[4]+'</h3>'
            }),
            
            new Ext.form.DisplayField({
            fieldLabel: '<b>Session Type<b>',
            value: '<h3>'+sessiondata[8]+'</h3>'
            }),
            new Ext.form.DisplayField({

            fieldLabel: '<b>Date<b>',
            value: sessiondata[0]
            }),
            new Ext.form.DisplayField({
            fieldLabel: '<b>Time<b>',
            value: sessiondata[1]+' - '+sessiondata[2]
            }),
            new Ext.form.TextField({
            fieldLabel: '<b>New Register URL<b>',
            anchor:'60%',
            value: sessiondata[6]
            }),
            new Ext.form.TextField({
            fieldLabel: '<b>Existing user Register URL<b>',
            anchor:'60%',
            value: sessiondata[7]
            }),
        buttons,
        grid
     ]

});

}
function deleteSchedule(sessionid){

Ext.MessageBox.confirm('Delete Schedule?', 'Are you sure you want to delete this schedule?', function(btn){

  if (btn == 'yes') {
    window.location.href='?module=realtime&action=deletesession'+'&sessionid='+sessionid;
  }

});
}
function initAddMember(userlist,url){
    var userdatastore = new Ext.data.ArrayStore({
        fields: ['userid','name'],
        data : userlist
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
    addMemberForm = new Ext.form.FormPanel({
        baseCls: 'x-plain',
        labelWidth: 55,
        bodyStyle:'padding:5px 5px 0',
        standardSubmit: true,
        url:url,
        defaultType: 'textfield',
        items: userField

    });

}

function showAddMemberWin(){
    var addMemberWin;
       if(!addMemberWin){
            addMemberWin = new Ext.Window({
                applyTo:'addsession-win',
                layout:'fit',
                width:500,
                height:250,
                x:250,
                y:50,
                closeAction:'destroy',
                plain: true,

               items: addMemberForm,

                buttons: [{
                    text:'Save',
                    handler: function(){
                        if (addMemberForm.url){
                            addMemberForm.getForm().getEl().dom.action = addMemberForm.url;
                          }
                        addMemberForm.getForm().submit();
                    }
                },{
                    text: 'Close',
                    handler: function(){
                       addMemberWin.hide();
                    }
                }]
            });
        }
        addMemberWin.show(this);

}

