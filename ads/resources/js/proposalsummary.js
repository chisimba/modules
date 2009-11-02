function showSummary(
data,
comments,
commentsSaveUrl,
phaseTitle,
disableAddCommentsButton,
disableForwardToWorkmates,
forwardText,
disablePhaseForward,
forwardPhaseText,
searchdata,
forwarddata,
forwardactions){
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

    ButtonPanel.override({
     renderTo : 'summary'
      });
   var buttons= new ButtonPanel(

        [{
            iconCls: 'commentadd',
            text:'Add Comment',
            disabled:disableAddCommentsButton,
            handler: function(){
                showAddCommentsWin(phaseTitle, commentsSaveUrl);
            }
        },
        /*{
            iconCls: 'email',
            id:'forwardbutton',
            text:forwardText,
            disabled:disableForwardToWorkmates,
            handler: function(){
            if(Ext.getCmp('forwardbutton').text == 'Transfer to workmate'){
             showSearchWinX(searchdata[0],searchdata[1],searchdata[2],searchdata[3],searchdata[4],searchdata[5]);
            }
            if(Ext.getCmp('forwardbutton').text == 'Transfer to owner'){
             forwardToOwner(forwarddata[0],forwarddata[1]);
            }

        }
         },*/
           {
            iconCls: 'email',
            id:'forwardphasebutton',
            text:forwardPhaseText,
            disabled:disablePhaseForward,
            menu : {items: [{text:'Menu Item 1'},{text:'Menu Item 2'},{text:'Menu Item 3'}]},
            handler: function(){
             if(Ext.getCmp('forwardphasebutton').text == 'Forward to APO'){
                 forwardForAPOComment(forwarddata[0],forwarddata[1]);
             }
             if(Ext.getCmp('forwardphasebutton').text == 'Forward to Faculty Subcommittee'){
                 forwardForFacultySub(forwarddata[0],forwarddata[1]);
             }
            if(Ext.getCmp('forwardphasebutton').text == 'Forward'){
                showMultipleForwardOptions(forwarddata,forwardactions,searchdata);
            }
            }
        }

        ]
   
 );


var summaryform = new Ext.form.FormPanel({
        
        baseCls: 'x-plain',
        width:750,
        labelWidth: 135,
        bodyStyle:'padding:5px 5px 0',
        renderTo: 'summary',
        collapsible: true,
        defaults: {width: 750},
        bodyStyle:'background-color:transparent',
        border:false,
         items: {
            xtype: 'fieldset',
            title: 'Course proposal details',
            autoHeight: true,
            items:[
              buttons,
               new Ext.form.DisplayField({
               fieldLabel: 'Faculty',
               value: data[0]
               }),
               new Ext.form.DisplayField({
                  fieldLabel: 'School' ,
                  value: data[1]
               }),
               new Ext.form.DisplayField({
               fieldLabel: 'Owner',
               value: data[2]
               }),
               new Ext.form.DisplayField({
               fieldLabel: '<b>Current editor</b>',
               value: data[3]
               }),
               new Ext.form.DisplayField({
               fieldLabel: 'Last edit date',
               value: data[4]
               }),
               new Ext.form.DisplayField({
               fieldLabel: '<b>Status</b>',
               value: data[5]
               }),
               new Ext.form.DisplayField({
               fieldLabel: 'Comments',
               value: comments
               })
            ]
         }
        });

}

function showUnitCommentEditors(data){
   // create the data store
    var store = new Ext.data.ArrayStore({
        fields: [
           {name: 'type'},
           {name: 'email'}
        ]
    });
    store.loadData(data);
    // create the Grid
    var grid = new Ext.grid.GridPanel({
        store: store,
        columns: [
            {id:'type',header: "Faculty/Department", width: 400, sortable: true, dataIndex: 'type'},
            {header: "Contact Person", width:400, sortable: true, dataIndex: 'email'}
        ],
        stripeRows: true,
        autoExpandColumn: 'type',
        height:550,
        width:800,
        frame:false,
        border:false

    });
    grid.render('unitcommenteditors');

}

function showForwardForCommentsWin(commenttypes,url){
  var cmstore = new Ext.data.ArrayStore({
        fields:
         [
         {name: 'apocommenttype'},
         {name: 'id'}
         ],
        data : commenttypes
    });
    var commentTypeField = new Ext.form.ComboBox({
        store: cmstore,
        displayField:'apocommenttype',
        fieldLabel:'Comment Type',
        typeAhead: true,
        mode: 'local',
        editable:false,
        allowBlank: false,
        forceSelection: true,
        triggerAction: 'all',
        emptyText:'Select comment type...',
        selectOnFocus:true,
        valueField: 'id',
        hiddenName : 'commenttypeid'

    });
      var form = new Ext.FormPanel({
            standardSubmit: true,
            labelWidth: 125,
            url:url,
            frame:true,
            title: 'Add Unit Commentor',
            bodyStyle:'padding:5px 5px 0',
            width: 350,
            defaults: {width: 230},
            defaultType: 'textfield',

            items: [
                   commentTypeField
            ]

        });

    var win;
    if(!win){
            win = new Ext.Window({
                applyTo:'popup-surface',
                layout:'fit',
                width:500,
                height:250,
                x:250,
                y:150,
                closeAction:'destroy',
                plain: true,

               items: form,
               buttons: [{
                   text:'Add',
                    handler: function(){
                        if (form.url)
                            form.getForm().getEl().dom.action = form.url;
                            form.getForm().submit();
                    }
                },{
                    text: 'Cancel',
                    handler: function(){
                       win.hide();
                       window.location.reload(true);
                    }
                }]
            });
        }
        win.show(this);
}

function showProposalMembers(data){
       // create the data store
    var store = new Ext.data.ArrayStore({
        fields: [
           {name: 'names'},
           {name: 'email'},
           {name: 'delete'}

        ]
    });
    store.loadData(data);

    // create the Grid
    var grid = new Ext.grid.GridPanel({
        store: store,
        columns: [
            {id:'names',header: "Names", width: 400, sortable: true, dataIndex: 'names'},
            {header: "Email", width:300, sortable: true, dataIndex: 'email'},
            {header: "Delete", width: 100, sortable: true, dataIndex: 'delete'}
        ],
        stripeRows: true,
        autoExpandColumn: 'names',
        height:550,
        width:800,
        frame:false,
        border:false

    });
    grid.render('commenteditors');
}
function showHistory(myData){
   
    // create the data store
    var store = new Ext.data.ArrayStore({
        fields: [
           {name: 'phase'},
           {name: 'date'},
           {name: 'forwardedTo'},
           {name: 'forwardedBy'}
        ]
    });
    store.loadData(myData);

    // create the Grid
    var grid = new Ext.grid.GridPanel({
        store: store,
        columns: [
            {id:'phase',header: "Phase", width: 200, sortable: true, dataIndex: 'phase'},
            {header: "Date", width: 100, sortable: true, dataIndex: 'date'},
            {header: "Forwarded To", width: 150, sortable: true, dataIndex: 'forwardedTo'},
            {header: "Forwarded By", width: 150, sortable: true, dataIndex: 'forwardedBy'}
        ],
        stripeRows: true,
        height:350,
        width:600,
        title:'Document Flow History'
    });
    grid.render('historyGrid');

}

function addProposalMember(){
   var args=addProposalMember.arguments;
   var url=args[0];
   var email=args[1];
   var courseid=args[2];
   var userid=args[3];
   var phase=args[4];
   window.location.href='?module=ads&action=addproposalmember'+'&email='+email+'&phase='+phase+'&courseid='+courseid+"&userid="+userid;
}

function showEditProposalWin(faculties,schools,url,selectedFaculty,selectedSchool,proposalName, schoolurl){
  var facutlystore = new Ext.data.ArrayStore({
        fields:
         [
         {name: 'faculty'},
         {name: 'id'}
         ],
        data : faculties
    });

    var schoolstore = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({url: schoolurl,method: "GET"}),
        reader: new Ext.data.JsonReader({
                    totalProperty: 'totalCount',
                    root:'rows'
                },
                [{
                        name: 'schoolid'
                    },
                    {
                        name: 'schoolname'
                    }
                ])
    });
    
    var facultyField = new Ext.form.ComboBox({
        store: facutlystore,
        displayField:'faculty',
        fieldLabel:'Faculty',
        typeAhead: true,
        mode: 'local',
        value: selectedFaculty,
        editable:false,
        allowBlank: false,
        forceSelection: true,
        triggerAction: 'all',
        emptyText:'Select faculty...',
        selectOnFocus:true,
        valueField: 'id',
        hiddenName : 'facultyid'

    });
    var schoolField = new Ext.form.ComboBox({
        store:schoolstore,
        displayField:'schoolname',
        fieldLabel:'School',
        typeAhead: true,
        mode: 'local',
        value: selectedSchool,
        editable: false,
        allowBlank: false,
        forceSelection: true,
        triggerAction:'all',
        emptyText: 'Select school...',
        selectOnFocus: true,
        valueField:'schoolid',
        hiddenName: 'schoolname'
    });
    schoolField.store.load({params:{faculty:facultyField.getValue()}});

      var form = new Ext.FormPanel({
            standardSubmit: true,
            labelWidth: 125,
            url:url,
            frame:true,
            title: 'Edit Course Proposal',
            bodyStyle:'padding:5px 5px 0',
            width: 350,
            defaults: {width: 230},
            defaultType: 'textfield',

            items: [
                     facultyField,
                     schoolField,
                    {
                    fieldLabel: 'Title',
                    value:proposalName,
                    name: 'title',
                    id: 'input_title',
                    allowBlank: false
                }
            ]

        });

    facultyField.on('change', function() {
        schoolField.reset();
        schoolField.clearValue();
        schoolField.store.load({params:{faculty:facultyField.getValue()}});
    })

    var addProposalWin;
    if(!addProposalWin){
            addProposalWin = new Ext.Window({
                applyTo:'popup-surface',
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
}


function showAddCommentsWin(phaseTitle,url){
    var form = new Ext.form.FormPanel({
        baseCls: 'x-plain',
        labelWidth: 55,
        bodyStyle:'padding:5px 5px 0',
        standardSubmit: true,
        url:url,
        defaultType: 'textfield',
        items:[
           new Ext.form.DisplayField({
               value: phaseTitle
               }),
          {
           xtype:'htmleditor',
           name: 'commentField',
            id: 'commentsFieldId'

         }
          ]

      });
         var addCommentsWin;

           if(!addCommentsWin){
            addCommentsWin = new Ext.Window({
                applyTo:'popup-surface',
                layout:'fit',
                title:'Enter Comments',
                width:600,
                height:450,
                x:250,
                y:50,
                closeAction:'hide',
                plain: true,
                items: [
                 form
                ],
                  buttons: [{
                    text:'Save',
                    handler: function(){
                  if (form.url){
                            form.getForm().getEl().dom.action = form.url;
                          }
                        form.getForm().submit();

                  }
                  }
                  ,{
                    text: 'Cancel',
                    handler: function(){
                       addCommentsWin.hide();
                        window.location.reload(true);
                    }
                  }
                ]

            });
        }

        addCommentsWin.show(this);

}

function forwardProposal(){
    var args=forwardProposal.arguments;
    var url=args[0];
    var email=args[1];
    var courseid=args[2];

  Ext.MessageBox.confirm('Forward Proposal?', 'Are you sure you want to forward the proposal to '+email+'?', function(btn){

  if (btn == 'yes') {
    window.location.href='?module=ads&action=sendproposal'+'&email='+email+'&courseid='+courseid;
  }
});
}

function forwardToOwner(courseid,email){

  Ext.MessageBox.confirm('Forward Proposal?', 'Are you sure you want to forward the proposal to owner  ['+email+']?', function(btn){

  if (btn == 'yes') {
    window.location.href='?module=ads&action=forwardtoowner'+'&email='+email+'&courseid='+courseid;
  }
});
}

function forwardForAPOComment(courseid,version){

Ext.MessageBox.confirm('Forward Proposal?', 'Are you sure you want to forward this proposal for APO Comments?', function(btn){

  if (btn == 'yes') {
    window.location.href='?module=ads&action=updatephase'+'&phase=1&id='+courseid+'&version='+version;
  }

});
}

function forwardForFacultySub(courseid,version){

Ext.MessageBox.confirm('Forward Proposal?', 'Are you sure you want to forward this proposal for Faculty Subcommittee comments?', function(btn){

  if (btn == 'yes') {
    window.location.href='?module=ads&action=updatephase'+'&phase=2&id='+courseid+'&version='+version;
  }

});
}


function showMultipleForwardOptions(forwarddata,actions,searchdata){
   var courseid=forwarddata[0];
   var owneremail=forwarddata[2];
    var store = new Ext.data.ArrayStore({
        fields: ['id','action'],
        data : actions
    });
    var combo = new Ext.form.ComboBox({
        store: store,
        displayField:'action',
        typeAhead: true,
        mode: 'local',
        forceSelection: true,
        triggerAction: 'all',
        emptyText:'Select a action...',
        selectOnFocus:true,
        allowBlank:false,
        id:'forwardfield'
        
    });

     var form = new Ext.form.FormPanel({
        baseCls: 'x-plain',
        labelWidth: 55,
        bodyStyle:'padding:5px 5px 0',
        standardSubmit: true,
       
        items:[
          combo
          ]

      });
  var showActionsWin;

           if(!showActionsWin){
            showActionsWin = new Ext.Window({
                applyTo:'popup-surface',
                layout:'fit',
                title:'Forward to',
                width:300,
                height:150,
                x:250,
                y:150,
                closeAction:'hide',
                plain: true,
                items: [
                 form
                ],
                  buttons: [{
                    text:'Forward',
                    handler: function(){
                         if(Ext.getCmp('forwardfield').value == 'Forward to workmate'){
                          showSearchWinX(searchdata[0],searchdata[1],searchdata[2],searchdata[3],searchdata[4],searchdata[5]);

                      }

                      if(Ext.getCmp('forwardfield').value == 'Forward to owner'){
                      Ext.MessageBox.confirm('Forward Proposal?', 'Are you sure you want to forward the proposal to owner ['+owneremail+']?', function(btn){
                      if (btn == 'yes') {
                          window.location.href='?module=ads&action=forwardtoowner'+'&email='+owneremail+'&courseid='+courseid;
                         }
                      });
                      }
                      if(Ext.getCmp('forwardfield').value == 'Forward to APO'){
                      Ext.MessageBox.confirm('Forward Proposal?', 'Are you sure you want to forward the proposal to APO?', function(btn){
                      if (btn == 'yes') {
                       window.location.href='?module=ads&action=updatephase'+'&phase=1&id='+courseid;
                        }
                      });
                      }

                      if(Ext.getCmp('forwardfield').value == 'Forward to Faculty subcommittee'){
                        Ext.MessageBox.confirm('Forward Proposal?', 'Are you sure you want to forward the proposal to Faculty subcommittee?', function(btn){
                        if (btn == 'yes') {
                          window.location.href='?module=ads&action=updatephase'+'&phase=2&id='+courseid;
                         }
                      });
                  }

                      if(Ext.getCmp('forwardfield').value == 'Forward to Faculty board'){
                                                  Ext.MessageBox.confirm('Forward Proposal?', 'Are you sure you want to forward the proposal to Faculty board?', function(btn){
                        if (btn == 'yes') {
                          window.location.href='?module=ads&action=updatephase'+'&phase=3&id='+courseid;
                             }
                      });
                      }
                  }
                  }
                  ,{
                    text: 'Cancel',
                    handler: function(){
                       showActionsWin.hide();
                       window.location.reload(true);
                    }
                  }
                ]

            });
        }
showActionsWin.show(this);
}