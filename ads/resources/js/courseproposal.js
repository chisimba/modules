

function deleteProposal(courseid){

Ext.MessageBox.confirm('Delete Proposal?', 'Are you sure you want to delete this proposal?', function(btn){

  if (btn == 'yes') {
    window.location.href='?module=ads&action=deletecourseproposal'+'&id='+courseid;
  }

});
}

function initGrid(cols){
    var xg = Ext.grid;

    var reader = new Ext.data.ArrayReader({}, [
    {name: 'title'},
    {name: 'dateCreated'},
    {name: 'owner'},
    {name: 'status'},
    {name: 'faculty'},
    {name: 'edit'}
    ]);

    var grid = new xg.GridPanel({
    store: new Ext.data.GroupingStore({
        reader: reader,
        data: xg.Data,
        sortInfo:{field: 'title', direction: "ASC"},
        groupField:'faculty'
    }),

    columns: cols,

    view: new Ext.grid.GroupingView({
        forceFit:true,
        groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? \"Proposals\" : \"Proposal\"]})'
    }),

    frame:false,
    width: 750,
    height: 450,
    collapsible: true,
    animCollapse: false,
    border:false,
    renderTo: 'grouping-grid'
    });
    }


function initAddProposal(faculties,url){
  var facutlystore = new Ext.data.ArrayStore({
        fields:
         [
         {name: 'faculty'},
         {name: 'id'}
         ],
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
        valueField: 'id',
        hiddenName : 'facultyid'

    });
      var form = new Ext.FormPanel({
            standardSubmit: true,
            labelWidth: 125,
            url:url,
            frame:true,
            title: 'Add  New Course Proposal',
            bodyStyle:'padding:5px 5px 0',
            width: 350,
            defaults: {width: 230},
            defaultType: 'textfield',

            items: [
                     facultyField,
                    {
                    fieldLabel: 'Title',
                    name: 'title',
                    id: 'input_title',
                    allowBlank: false
                }
            ]

        });

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
}
