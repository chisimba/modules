
function showTabs() {
    var args=showTabs.arguments;
    var selectedTab=args[0];
   
    // basic tabs, first tabs contains the Course details, second tabs contains course history
    var tabs = new Ext.TabPanel({
        renderTo: 'tabs',
        autoWidth:true,
        autoHeight: true,
        activeTab: parseInt(selectedTab),
        frame:false,
        
        defaults:{
            autoHeight: true
        },
        items:[
        {
            contentEl:'facultylist',
            title: 'Faculty List'
        },

        {
            contentEl:'schoollist',
            title: 'School List'
        },

        {
            contentEl: 'apomoderators',
            title: 'APO Moderators'
        },

        {
            contentEl: 'commentlist',
            title: 'Custom Units'
        },

        {
            contentEl: 'subfacultymoderators',
            title: 'Faculty Subcommittee Moderators'
        },

        {
            contentEl: 'facultymoderators',
            title: 'Faculty Moderators'
        },

        {
            contentEl: 'emailtemplates',
            title: 'Email Templates'
        }
        ]
    });
}
function showFacultyList(data,url){
    // create the data store
    var store = new Ext.data.ArrayStore({
        fields: [{
            name: 'faculty'
        },{
            name: 'id'
        },{
            name: 'delete'
        }]
    });
    store.loadData(data);

    // create the Grid
    var grid = new Ext.grid.GridPanel({
        store: store,
        border:false,
        columns: [{
            id:'faculty',
            header: "Faculty",
            width: 400,
            sortable: true,
            dataIndex: 'faculty'
        },{
            header: "Delete",
            width:100,
            sortable: true,
            dataIndex: 'delete'
        }],
        sm: new Ext.grid.RowSelectionModel({
            singleSelect: true
        }),
        stripeRows: true,
        autoExpandColumn: 'faculty',
        width:350,
        autoHeight:true
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
function showSchoolList(facultyData, data, url) {

    var schoolFacultyStore = new Ext.data.ArrayStore({
        fields:[{
            name: 'faculty'
        },{
            name: 'id'
        }],
        data : facultyData
    });
    
    var schoolFacultyField = new Ext.form.ComboBox({
        store: schoolFacultyStore,
        displayField:'faculty',
        fieldLabel:'Faculty',
        typeAhead: true,
        mode: 'local',
        //value: selectedFaculty,
        editable:false,
        allowBlank: false,
        forceSelection: true,
        triggerAction: 'all',
        emptyText:'Select faculty...',
        selectOnFocus:true,
        valueField: 'id',
        hiddenName : 'facultyid'

    });

    // shared reader
    var schoolStoreReader = new Ext.data.ArrayReader({}, [
    {
        name: 'schoolname'
    },{
        name: 'faculty'
    },{
        name: 'delete'
    }
    ]);
    var schoolStore = new Ext.data.GroupingStore({
        reader:schoolStoreReader,
        sortInfo:{
            field: 'faculty',
            direction: 'ASC'
        },
        groupField:'faculty',
        groupOnSort:true
    });
    schoolStore.loadData(data);

    // create the Grid
    var schoolGrid = new Ext.grid.GridPanel({
        store: schoolStore,

        view: new Ext.grid.GroupingView({
            forceFit:true,
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Schools" : "School"]})'
        }),
        columns: [
        {
            id: 'school',
            header: "School",
            sortable: true,
            width: 300,
            dataIndex: 'schoolname'
        },{
            header: "Faculty",
            width: 300,
            sortable: true,
            dataIndex: 'faculty'
        },{
            header: "Delete",
            width:150,
            sortable: true,
            dataIndex: 'delete'
        }],
        sm: new Ext.grid.RowSelectionModel({
            singleSelect: true
        }),
        stripeRows: true,
        autoExpandColumn: 'school',
        autoHeight: true,
       
        width:750
    });
    schoolGrid.render('schoollist');

    var schoolAddForm = new Ext.FormPanel({
        standardSubmit: true,
        labelWidth: 100,
        url:url,
        title: 'Add School',
        bodyStyle:'padding:5px 5px 0',
        width: 350,
        defaultType: 'textfield',

        items: [
        schoolFacultyField,
        {
            fieldLabel: 'School',
            name: 'addschool',
            id: 'addschool_title',
            allowBlank: false,
            width: 250
        }]
    });

    var addSchoolWin;
    var schoolAddBtn = Ext.get('addschool-btn');

    schoolAddBtn.on('click', function() {
        if(!addSchoolWin){
            addSchoolWin = new Ext.Window({
                applyTo:'addschool-win',
                layout:'fit',
                width:400,
                height:200,
                x:250,
                y:150,
                closeAction:'destroy',
                plain: true,

                items: schoolAddForm,
                buttons: [{
                    text:'Save',
                    handler: function(){
                        if (schoolAddForm.url)
                            schoolAddForm.getForm().getEl().dom.action = schoolAddForm.url;

                        schoolAddForm.getForm().submit();
                    }
                },{
                    text: 'Cancel',
                    handler: function(){
                        addSchoolWin.hide();
                    }
                }]
            });
        }
        addSchoolWin.show(this);
    });
}
function showFacultyModeratorList(data,url,modFaculties, schoolurl){
    // create the data store
    var store = new Ext.data.ArrayStore({
        fields: [{
            name: 'moderator'
        },{
            name: 'faculty'
        },{
            name: 'school'
        },{
            name: 'delete'
        }]
    });
    store.loadData(data);

    // create the Grid
    var grid = new Ext.grid.GridPanel({
        store: store,
        columns: [{
            id:'moderator',
            header: "Moderator",
            width: 250,
            sortable: true,
            dataIndex: 'moderator'
        },{
            id:'faculty',
            header: "Faculty",
            width: 200,
            sortable: true,
            dataIndex: 'faculty'
        },{
            id:'school',
            header: "School",
            width: 100,
            sortable: true,
            dataIndex: 'school'
        },{
            header: "Delete",
            width:50,
            sortable: true,
            dataIndex: 'delete'
        }],
        sm: new Ext.grid.RowSelectionModel({
            singleSelect: true
        }),
        stripeRows: true,
        autoExpandColumn: 'moderator',
       autoHeight:true,
       width:600
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

    var schoolstore = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url: schoolurl,
            method: "GET"
        }),
        reader: new Ext.data.JsonReader({
            totalProperty: 'totalCount',
            root:'rows'
        },[{
            name: 'schoolid'
        },{
            name: 'schoolname'
        }]
        )
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

    var schoolField = new Ext.form.ComboBox({
        store:schoolstore,
        displayField:'schoolname',
        fieldLabel:'School',
        typeAhead: true,
        mode: 'local',
        editable: false,
        allowBlank: false,
        forceSelection: true,
        triggerAction:'all',
        emptyText: 'Select school...',
        selectOnFocus: true,
        valueField:'schoolid',
        hiddenName: 'schoolname'
    });
    schoolField.store.load({
        params:{
            faculty:modFacultyField.getValue()
        }
    });

    var moderatorForm = new Ext.FormPanel({
        standardSubmit: true,
        labelWidth: 125,
        url:url,
        title: 'Add Faculty Moderator',
        bodyStyle:'padding:5px 5px 0',
        width: 350,
        defaults: {
            width: 230
        },
        defaultType: 'textfield',

        items: [
        modFacultyField,
        schoolField,
        {
            fieldLabel: 'Moderator',
            name: 'moderator',
            id: 'moderator_title',
            allowBlank: false
        }
        ]
    });

    modFacultyField.on('change', function() {
        schoolField.reset();
        schoolField.clearValue();
        schoolField.store.load({
            params:{
                faculty:modFacultyField.getValue()
            }
        });
    })

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

function showSubFacultyModeratorList(data,url,modFaculties,schoolurl){
    // create the data store
    var store = new Ext.data.ArrayStore({
        fields: [{
            name: 'moderator'
        },{
            name: 'faculty'
        },{
            name: 'school'
        },{
            name: 'delete'
        }]
    });
    store.loadData(data);

    // create the Grid
    var grid = new Ext.grid.GridPanel({
        store: store,
        columns: [{
            id:'moderator',
            header: "Moderator",
            width: 250,
            sortable: true,
            dataIndex: 'moderator'
        },{
            id:'faculty',
            header: "Faculty",
            width: 200,
            sortable: true,
            dataIndex: 'faculty'
        },{
            id:'school',
            header: "School",
            width: 100,
            sortable: true,
            dataIndex: 'school'
        },{
            header: "Delete",
            width:50,
            sortable: true,
            dataIndex: 'delete'
        }],
        sm: new Ext.grid.RowSelectionModel({
            singleSelect: true
        }),
        stripeRows: true,
        autoExpandColumn: 'moderator',
        height:350,
        width:600
    });
    grid.render('subfacultymoderators');
    var modFacultyStore = new Ext.data.ArrayStore({
        fields: [{
            name: 'faculty'
        },{
            name: 'id'
        },{
            name: 'delete'
        }],
        data : modFaculties
    });
    var schoolstore = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url: schoolurl,
            method: "GET"
        }),
        reader: new Ext.data.JsonReader({
            totalProperty: 'totalCount',
            root:'rows'
        },[{
            name: 'schoolid'
        },{
            name: 'schoolname'
        }]
        )
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
    var schoolField = new Ext.form.ComboBox({
        store:schoolstore,
        displayField:'schoolname',
        fieldLabel:'School',
        typeAhead: true,
        mode: 'local',
        editable: false,
        allowBlank: false,
        forceSelection: true,
        triggerAction:'all',
        emptyText: 'Select school...',
        selectOnFocus: true,
        valueField:'schoolid',
        hiddenName: 'schoolname'
    });
    schoolField.store.load({
        params:{
            faculty:modFacultyField.getValue()
        }
    });

    var moderatorForm = new Ext.FormPanel({
        standardSubmit: true,
        labelWidth: 125,
        url:url,
        title: 'Add Faculty Subcommittee Moderator',
        bodyStyle:'padding:5px 5px 0',
        width: 350,
        defaults: {
            width: 230
        },
        defaultType: 'textfield',

        items: [
        modFacultyField,
        schoolField,
        {
            fieldLabel: 'Moderator',
            name: 'moderator',
            id: 'moderator_title',
            allowBlank: false
        }
        ]
    });

    modFacultyField.on('change', function() {
        schoolField.reset();
        schoolField.clearValue();
        schoolField.store.load({
            params:{
                faculty:modFacultyField.getValue()
            }
        });
    })

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




function showAPOModeratorList(data,url,modFaculties,schoolurl){



   // shared reader
    var apoStoreReader = new Ext.data.ArrayReader({}, [
    {
            name: 'moderator'
        },{
            name: 'faculty'
        },{
            name: 'school'
        },{
            name: 'delete'
        }
    ]);
    var apoStore = new Ext.data.GroupingStore({
        reader: apoStoreReader,
        sortInfo:{
            field: 'moderator',
            direction: 'ASC'
        },
        groupField:'moderator',
        groupOnSort:true
    });

    apoStore.loadData(data);
    // create the Grid
    var grid = new Ext.grid.GridPanel({
        store: apoStore,
        view: new Ext.grid.GroupingView({
            forceFit:true,
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Moderators" : "Moderator"]})'
        }),
        columns: [{
            id:'moderator',
            header: "Moderator",
            width: 150,
            sortable: true,
            dataIndex: 'moderator'
        },{
            id:'faculty',
            header: "Faculty",
            width: 250,
            sortable: true,
            dataIndex: 'faculty'
        },{
            id:'school',
            header: "School",
            width: 100,
            sortable: true,
            dataIndex: 'school'
        },{
            header: "Delete",
            width:100,
            sortable: true,
            dataIndex: 'delete'
        }],
        sm: new Ext.grid.RowSelectionModel({
            singleSelect: true
        }),
        stripeRows: true,
        autoExpandColumn: 'moderator',
        autoHeight:true,
        width:750
    });
    grid.render('apomoderators');
    var modFacultyStore = new Ext.data.ArrayStore({
        fields: [{
            name: 'faculty'
        },{
            name: 'id'
        },{
            name: 'delete'
        }],
        data : modFaculties
    });
    var schoolstore = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url: schoolurl,
            method: "GET"
        }),
        reader: new Ext.data.JsonReader({
            totalProperty: 'totalCount',
            root:'rows'
        },[{
            name: 'schoolid'
        },{
            name: 'schoolname'
        }]
        )
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
    var schoolField = new Ext.form.ComboBox({
        store:schoolstore,
        displayField:'schoolname',
        fieldLabel:'School',
        typeAhead: true,
        mode: 'local',
        editable: false,
        allowBlank: false,
        forceSelection: true,
        triggerAction:'all',
        emptyText: 'Select school...',
        selectOnFocus: true,
        valueField:'schoolid',
        hiddenName: 'schoolname'
    });
    schoolField.store.load({
        params:{
            faculty:modFacultyField.getValue()
        }
    });

    var moderatorForm = new Ext.FormPanel({
        standardSubmit: true,
        labelWidth: 125,
        url:url,
        title: 'Add APO Moderator',
        bodyStyle:'padding:5px 5px 0',
        width: 350,
        defaults: {
            width: 230
        },
        defaultType: 'textfield',

        items: [
        modFacultyField,
        schoolField,
        {
            fieldLabel: 'Moderator',
            name: 'moderator',
            id: 'moderator_title',
            allowBlank: false
        }
        ]
    });
    modFacultyField.on('change', function() {
        schoolField.reset();
        schoolField.clearValue();
        schoolField.store.load({
            params:{
                faculty:modFacultyField.getValue()
            }
        });
    })

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
        }),
        {
            fieldLabel: 'Subject',
            value: contents[8],
            width:500,
            name: 'updatephasefieldsubject',
            allowBlank: false
        },
        new Ext.form.TextArea({
            fieldLabel: 'Update Phase',
            value: contents[9],
            height: 200,
            width:500,
            name: 'updatephasefieldcontent',
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