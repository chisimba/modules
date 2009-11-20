

function showCommentAdmin(data, url){
    // create the data store
    var store = new Ext.data.ArrayStore({
        fields: [
        {
            name: 'comment'
        },

        {
            name: 'moderator'
        },
         {
            name: 'id'
        },
        {
            name:'delete'
        }
        ]
    });
    store.loadData(data);

    // create the Grid
    var grid = new Ext.grid.GridPanel({
        store: store,
        columns: [
        {
            id:'comment',
            header: "Comment",
            width: 200,
            sortable: true,
            dataIndex: 'comment'
        },

        {
            header: "Moderator",
            width:200,
            sortable: true,
            dataIndex: 'moderator'
        },        {
          header: "Delete",
            width:100,
            sortable: true,
            dataIndex: 'delete'
        }
        ],
      //  sm: new Ext.grid.RowSelectionModel({
        //    singleSelect: true
       // }),
        stripeRows: true,
        autoExpandColumn: 'comment',
        height:350,
        width:500
    });
    grid.render('commentlist');
    grid.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
    //    editCommentaddWin(url,r.get('comment'),r.get('moderator'),r.get('id'));
        
    });
}

function initCommentaddWin(){
    var args=initCommentaddWin.arguments;
    var myUrl=args[0];
   
    var form = new Ext.FormPanel({
        standardSubmit: true,
        labelWidth: 125,
        url:myUrl,
        frame:true,
        title: 'Add  New Custom Unit',
        bodyStyle:'padding:5px 5px 0',
        width: 350,
        defaults: {
            width: 230
        },
        defaultType: 'textfield',

        items: [{
            fieldLabel: 'Title',
            name: 'title',
            id: 'input_title',
            allowBlank: false
        },
        {
            fieldLabel: 'Moderator',
            name: 'moderator',
            id: 'input_moderator',
     
            allowBlank: false
        }]
    });

   
    var commentButton = Ext.get('addcomment-btn');
    var commentAdminlWin;
    commentButton.on('click', function(){
        if(!commentAdminlWin){
         commentAdminlWin = new Ext.Window({
        applyTo:'addcomment-win',
        layout:'fit',
        width:500,
        height:250,
        x:25,
        y:15,
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
                commentAdminlWin.hide();
            }
        }]
    });
        }
        commentAdminlWin.show(this);
    });
}

function editCommentaddWin(){
    var args=editCommentaddWin.arguments;
    var myUrl=args[0];
    var xtitle=args[1];
    var xmod=args[2];
    var xid=args[3];

    var form = new Ext.FormPanel({
        standardSubmit: true,
        labelWidth: 125,
        url:myUrl,
        frame:true,
        title: 'Edit Status',
        bodyStyle:'padding:5px 5px 0',
        width: 350,
        defaults: {
            width: 230
        },
        defaultType: 'textfield',

        items: [{
            fieldLabel: 'Title',
            name: 'title',
            id: 'input_title',
            value:xtitle,
            allowBlank: false
        },
        {
            fieldLabel: 'Moderator',
            name: 'moderator',
            id: 'input_moderator',
            value:xmod,
            allowBlank: false
        }]
    });


   
    var commentAdminlWin;
      if(!commentAdminlWin){
         commentAdminlWin = new Ext.Window({
        applyTo:'addcomment-win',
        layout:'fit',
        width:500,
        height:250,
        x:25,
        y:15,
        closeAction:'destroy',
        plain: true,

        items: form,
        buttons: [{
            text:'Save',
            handler: function(){
                if (form.url)
                form.getForm().getEl().dom.action = form.url+"&id="+xid;
                form.getForm().submit();
            }
        },{
            text: 'Cancel',
            handler: function(){
                location.reload();
            }
        }]
    });
        }
        commentAdminlWin.show(this);
  

}