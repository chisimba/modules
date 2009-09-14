function showCommentAdmin(data){
       // create the data store
    var store = new Ext.data.ArrayStore({
        fields: [
           {name: 'comment'},
           {name: 'moderator'},
           {name: 'delete'}

        ]
    });
    store.loadData(data);

    // create the Grid
    var grid = new Ext.grid.GridPanel({
        store: store,
        columns: [
            {id:'comment',header: "Comment", width: 400, sortable: true, dataIndex: 'comment'},
            {header: "Moderator", width:300, sortable: true, dataIndex: 'moderator'},
            {header: "Delete", width: 100, sortable: true, dataIndex: 'delete'}
        ],
        stripeRows: true,
        autoExpandColumn: 'comment',
        height:350,
        width:500,
        border:false
        

    });
    grid.render('commentlist');

   
}

function initCommentaddWin(){
       var ds = new Ext.data.Store({
        proxy: new Ext.data.ScriptTagProxy({
            url: 'http://extjs.com/forum/topics-remote.php'
        }),
        reader: new Ext.data.JsonReader({
            root: 'topics',
            totalProperty: 'totalCount',
            id: 'post_id'
        }, [
            {name: 'title', mapping: 'topic_title'},
            {name: 'topicId', mapping: 'topic_id'},
            {name: 'author', mapping: 'author'},
            {name: 'lastPost', mapping: 'post_time', type: 'date', dateFormat: 'timestamp'},
            {name: 'excerpt', mapping: 'post_text'}
        ])
    });

    // Custom rendering Template
    var resultTplc = new Ext.XTemplate(
        '<tpl for="."><div class=\"search-item\">',
            '<h3><span>{lastPost:date(\"M j, Y\")}<br />by {author}</span>{title}</h3>',
            '{excerpt}',
        '</div></tpl>'
    );
           var form = new Ext.FormPanel({
            standardSubmit: true,
            labelWidth: 125, // label settings here cascade unless overridden
            url:'".str_replace("amp;", "", $submitUrl)."',
            frame:true,
            title: 'Add  New Comment',
            bodyStyle:'padding:5px 5px 0',
            width: 350,
            defaults: {width: 230},
            defaultType: 'textfield',

            items: [
                   {
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
                  }

            ]

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


    var xvsearch = new Ext.form.ComboBox({
        store: ds,
        displayField:'title',
        typeAhead: false,
        loadingText: 'Searching...',
        width: 600,
        pageSize:10,
        hideTrigger:true,
        tpl: resultTplc,
        applyTo: 'input_moderator',
        itemSelector: 'div.search-item',
        onSelect: function(record){ // override default onSelect to do redirect
            window.location =
                String.format('http://extjs.com/forum/showthread.php?t={0}&p={1}', record.data.topicId, record.id);
        }
    });
}