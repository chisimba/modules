
function showTabs() {
    var args=showTabs.arguments;
    var selectedTab=args[0];
   
    // basic tabs, first tabs contains the Course details, second tabs contains course history
    var tabs = new Ext.TabPanel({
        renderTo: 'tabs',
        width:800,
        height:1500,
        activeTab: parseInt(selectedTab),
        frame:false,
        
        defaults:{autoHeight: true},
        items:[
            {contentEl:'facultylist', title: 'Faculty List'},
            {contentEl: 'apomoderators', title: 'APO Moderators'},
            {contentEl: 'commentlist', title: 'Custom Units'},
             {contentEl: 'subfacultymoderators', title: 'Faculty Subcommittee Moderators'},
            {contentEl: 'facultymoderators', title: 'Faculty Moderators'},
            {contentEl: 'emailtemplates', title: 'Email Templates'}
        ]
    });
}
function showFacultyList(data,url){
    // create the data store
    var store = new Ext.data.ArrayStore({
        fields: [
        {
            name: 'faculty'
        },
        {
            name: 'id'
        },
        {
            name: 'delete'
        }
        ]
    });
    store.loadData(data);

    // create the Grid
    var grid = new Ext.grid.GridPanel({
        store: store,
        columns: [
        {
            id:'faculty',
            header: "Faculty",
            width: 500,
            sortable: true,
            dataIndex: 'faculty'
        },

        {
            header: "Delete",
            width:100,
            sortable: true,
            dataIndex: 'delete'
        }
        ],
        sm: new Ext.grid.RowSelectionModel({
            singleSelect: true
        }),
        stripeRows: true,
        autoExpandColumn: 'faculty',
        height:350,
        width:500
    });
    grid.render('facultylist');


    var facultyAddForm = new Ext.FormPanel({
            standardSubmit: true,
            labelWidth: 100,
            url:url,
            title: 'Add Faculty',
            bodyStyle:'padding:5px 5px 0',
            width: 350,
            defaultType: 'textfield',

            items: [{
                    fieldLabel: 'Faculty',
                    name: 'addfaculty',
                    id: 'addfaculty_title',
                    allowBlank: false,
                    width: 250
            }]
        });

    var addFacultyWin;
    var facultyAddBtn = Ext.get('addfaculty-btn');

    facultyAddBtn.on('click', function() {
        if(!addFacultyWin){
            addFacultyWin = new Ext.Window({
                applyTo:'addfaculty-win',
                layout:'fit',
                width:400,
                height:150,
                x:250,
                y:150,
                closeAction:'destroy',
                plain: true,

                items: facultyAddForm,
                buttons: [{
                    text:'Save',
                    handler: function(){
                        if (facultyAddForm.url)
                            facultyAddForm.getForm().getEl().dom.action = facultyAddForm.url;

                        facultyAddForm.getForm().submit();
                    }
                },{
                    text: 'Cancel',
                    handler: function(){
                       addFacultyWin.hide();
                    }
                }]
            });
        }
        addFacultyWin.show(this);
    });
}
function showFacultyModeratorList(data,url,modFaculties){
    // create the data store
    var store = new Ext.data.ArrayStore({
        fields: [
        {
            name: 'moderator'
        },
        {
            name: 'faculty'
        },
        {
            name: 'delete'
        }
        ]
    });
    store.loadData(data);

    // create the Grid
    var grid = new Ext.grid.GridPanel({
        store: store,
        columns: [
        {
            id:'moderator',
            header: "Moderator",
            width: 250,
            sortable: true,
            dataIndex: 'moderator'
        },
        {
            id:'faculty',
            header: "Faculty",
            width: 250,
            sortable: true,
            dataIndex: 'faculty'
        },

        {
            header: "Delete",
            width:100,
            sortable: true,
            dataIndex: 'delete'
        }
        ],
        sm: new Ext.grid.RowSelectionModel({
            singleSelect: true
        }),
        stripeRows: true,
        autoExpandColumn: 'moderator',
        height:350,
        width:500
    });
    grid.render('facultymoderators');
   var modFacultyStore = new Ext.data.ArrayStore({
       
       fields: [
        {
            name: 'faculty'
        },
        {
            name: 'id'
        },
        {
            name: 'delete'
        }

         ],
        data : modFaculties
    });
    var modFacultyField = new Ext.form.ComboBox({
        store: modFacultyStore,
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
        valueField : 'id',
        hiddenName : 'facultyid'
    });
    
    var moderatorForm = new Ext.FormPanel({
            standardSubmit: true,
            labelWidth: 125,
            url:url,
            title: 'Add Faculty Moderator',
            bodyStyle:'padding:5px 5px 0',
            width: 350,
            defaults: {width: 230},
            defaultType: 'textfield',

            items: [
                    modFacultyField,
                    {
                    fieldLabel: 'Moderator',
                    name: 'moderator',
                    id: 'moderator_title',
                    allowBlank: false
                }
            ]
        });

    var addModeratorWin;
    var modBtn = Ext.get('addfacultymoderator-btn');

    modBtn.on('click', function() {
        if(!addModeratorWin){
            addModeratorWin = new Ext.Window({
                applyTo:'addfacultymoderator-win',
                layout:'fit',
                width:500,
                height:250,
                x:25,
                y:15,
                closeAction:'destroy',
                plain: true,

                items: moderatorForm,
                buttons: [{
                    text:'Save',
                    handler: function(){
                        if (moderatorForm.url)
                            moderatorForm.getForm().getEl().dom.action = moderatorForm.url;

                        moderatorForm.getForm().submit();
                    }
                },{
                    text: 'Cancel',
                    handler: function(){
                       addModeratorWin.hide();
                    }
                }]
            });
        }
        addModeratorWin.show(this);
    });
}

function showSubFacultyModeratorList(data,url,modFaculties){
    // create the data store
    var store = new Ext.data.ArrayStore({
        fields: [
        {
            name: 'moderator'
        },
        {
            name: 'faculty'
        },
        {
            name: 'delete'
        }
        ]
    });
    store.loadData(data);

    // create the Grid
    var grid = new Ext.grid.GridPanel({
        store: store,
        columns: [
        {
            id:'moderator',
            header: "Moderator",
            width: 250,
            sortable: true,
            dataIndex: 'moderator'
        },
        {
            id:'faculty',
            header: "Faculty",
            width: 250,
            sortable: true,
            dataIndex: 'faculty'
        },

        {
            header: "Delete",
            width:100,
            sortable: true,
            dataIndex: 'delete'
        }
        ],
        sm: new Ext.grid.RowSelectionModel({
            singleSelect: true
        }),
        stripeRows: true,
        autoExpandColumn: 'moderator',
        height:350,
        width:500
    });
    grid.render('subfacultymoderators');
   var modFacultyStore = new Ext.data.ArrayStore({

       fields: [
        {
            name: 'faculty'
        },
        {
            name: 'id'
        },
        {
            name: 'delete'
        }

         ],
        data : modFaculties
    });
    var modFacultyField = new Ext.form.ComboBox({
        store: modFacultyStore,
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
        valueField : 'id',
        hiddenName : 'facultyid'
    });

    var moderatorForm = new Ext.FormPanel({
            standardSubmit: true,
            labelWidth: 125,
            url:url,
            title: 'Add Faculty Subcommittee Moderator',
            bodyStyle:'padding:5px 5px 0',
            width: 350,
            defaults: {width: 230},
            defaultType: 'textfield',

            items: [
                    modFacultyField,
                    {
                    fieldLabel: 'Moderator',
                    name: 'moderator',
                    id: 'moderator_title',
                    allowBlank: false
                }
            ]
        });

    var addModeratorWin;
    var modBtn = Ext.get('addsubfacultymoderator-btn');

    modBtn.on('click', function() {
        if(!addModeratorWin){
            addModeratorWin = new Ext.Window({
                applyTo:'addsubfacultymoderator-win',
                layout:'fit',
                width:500,
                height:250,
                x:25,
                y:15,
                closeAction:'destroy',
                plain: true,

                items: moderatorForm,
                buttons: [{
                    text:'Save',
                    handler: function(){
                        if (moderatorForm.url)
                            moderatorForm.getForm().getEl().dom.action = moderatorForm.url;

                        moderatorForm.getForm().submit();
                    }
                },{
                    text: 'Cancel',
                    handler: function(){
                       addModeratorWin.hide();
                    }
                }]
            });
        }
        addModeratorWin.show(this);
    });
}




function showAPOModeratorList(data,url,modFaculties){
    // create the data store
    var store = new Ext.data.ArrayStore({
        fields: [
        {
            name: 'moderator'
        },
        {
            name: 'faculty'
        },
        {
            name: 'delete'
        }
        ]
    });
    store.loadData(data);

    // create the Grid
    var grid = new Ext.grid.GridPanel({
        store: store,
        columns: [
        {
            id:'moderator',
            header: "Moderator",
            width: 250,
            sortable: true,
            dataIndex: 'moderator'
        },
        {
            id:'faculty',
            header: "Faculty",
            width: 250,
            sortable: true,
            dataIndex: 'faculty'
        },

        {
            header: "Delete",
            width:100,
            sortable: true,
            dataIndex: 'delete'
        }
        ],
        sm: new Ext.grid.RowSelectionModel({
            singleSelect: true
        }),
        stripeRows: true,
        autoExpandColumn: 'moderator',
        height:350,
        width:500
    });
    grid.render('apomoderators');
   var modFacultyStore = new Ext.data.ArrayStore({

       fields: [
        {
            name: 'faculty'
        },
        {
            name: 'id'
        },
        {
            name: 'delete'
        }

         ],
        data : modFaculties
    });
    var modFacultyField = new Ext.form.ComboBox({
        store: modFacultyStore,
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
        valueField : 'id',
        hiddenName : 'facultyid'
    });

    var moderatorForm = new Ext.FormPanel({
            standardSubmit: true,
            labelWidth: 125,
            url:url,
            title: 'Add APO Moderator',
            bodyStyle:'padding:5px 5px 0',
            width: 350,
            defaults: {width: 230},
            defaultType: 'textfield',

            items: [
                    modFacultyField,
                    {
                    fieldLabel: 'Moderator',
                    name: 'moderator',
                    id: 'moderator_title',
                    allowBlank: false
                }
            ]
        });

    var addModeratorWin;
    var modBtn = Ext.get('addapomoderator-btn');

    modBtn.on('click', function() {
        if(!addModeratorWin){
            addModeratorWin = new Ext.Window({
                applyTo:'addapomoderator-win',
                layout:'fit',
                width:500,
                height:250,
                x:25,
                y:15,
                closeAction:'destroy',
                plain: true,

                items: moderatorForm,
                buttons: [{
                    text:'Save',
                    handler: function(){
                        if (moderatorForm.url)
                            moderatorForm.getForm().getEl().dom.action = moderatorForm.url;

                        moderatorForm.getForm().submit();
                    }
                },{
                    text: 'Cancel',
                    handler: function(){
                       addModeratorWin.hide();
                    }
                }]
            });
        }
        addModeratorWin.show(this);
    });
}

function initEmailTemplates(url,contents){
    var form = new Ext.form.FormPanel(
       {
        baseCls: 'x-plain',
        labelWidth:160,
       
        bodyStyle:'padding:5px 5px 0',
        standardSubmit: true,
        renderTo:'emailtemplates',
        url:url,
        defaultType: 'textfield',
        items: [
         {
         fieldLabel: 'Subject',
         value: contents[0],
         width:500,
         name: 'forwardtoworkmatefieldsubject',
         allowBlank: false
        },
        new Ext.form.TextArea({
        fieldLabel: 'Forward to workmate template',
        value: contents[1],
        height: 200,
        width:500,
        name: 'forwardtoworkmatefieldcontent',
        allowBlank: false
        }),
        {
         fieldLabel: 'Subject',
         value: contents[2],
         width:500,
         name: 'forwardtoownerfieldsubject',
         allowBlank: false
        },
        new Ext.form.TextArea({
        fieldLabel: 'Forward to owner template',
        value: contents[3],
        height: 200,
        width:500,
        name: 'forwardtoownerfieldcontent',
        allowBlank: false
        }),
        {
         fieldLabel: 'Subject',
         value: contents[4],
         width:500,
         name: 'addmemberfieldsubject',
         allowBlank: false
        },
        new Ext.form.TextArea({
        fieldLabel: 'Add Member',
        value: contents[5],
        height: 200,
        width:500,
        name: 'addmemberfieldcontent',
        allowBlank: false
        }),
        {
         fieldLabel: 'Subject',
         value: contents[6],
         width:500,
         name: 'addcommentfieldsubject',
         allowBlank: false
        },
        new Ext.form.TextArea({
        fieldLabel: 'Add Comment',
        value: contents[7],
        height: 200,
        width:500,
        name: 'addcommentfieldcontent',
        allowBlank: false
        })
        ],
         buttons: [{
                    text:'Save',
                    handler: function(){
                        if (form.url)
                            form.getForm().getEl().dom.action = form.url;
                            form.getForm().submit();
                    }
                }]
    }
);

}