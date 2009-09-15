function showCommentAdmin(data){
       // create the data store
    var store = new Ext.data.ArrayStore({
        fields: [
           {name: 'comment'},
           {name: 'moderator'}
       ]
    });
    store.loadData(data);

    // create the Grid
    var grid = new Ext.grid.GridPanel({
        store: store,
        columns: [
            {id:'comment',header: "Comment", width: 400, sortable: true, dataIndex: 'comment'},
            {header: "Moderator", width:300, sortable: true, dataIndex: 'moderator'},
        ],
        stripeRows: true,
        autoExpandColumn: 'comment',
        height:350,
        width:500
    });
    grid.render('commentlist');
}

function initCommentaddWin(myUrl){
   var form = new Ext.FormPanel({
    standardSubmit: true,
    labelWidth: 125,
    url:myUrl,
    frame:true,
    title: 'Add  New Status',
    bodyStyle:'padding:5px 5px 0',
    width: 350,
    defaults: {width: 230},
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

    var commentAdminlWin;
    var commentButton = Ext.get('addcomment-btn');

    commentButton.on('click', function(){
       if(!commentAdminlWin){
            commentAdminlWin = new Ext.Window({
                applyTo:'addcomment-win',
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
                       commentAdminlWin.hide();
                    }
                }]
            });
        }
        commentAdminlWin.show(this);
    });
}