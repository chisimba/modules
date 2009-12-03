var url,
    searchURL,
    adminURL;
Ext.onReady(function() {
    url = Ext.get('uploadURL').dom.value;
    searchURL = Ext.get('searchURL').dom.value,
    adminURL = Ext.get('adminURL').dom.value;

    var p = new Ext.Panel({
        layout: 'table',
        autoWidth: true,
        style: 'marginRight: 10px',
        baseCls: 'x-plain',
        cls: 'btn-panel',
        border: false,
        defaultType: 'button',
        id: 'upload-button',
        items: [{
            text: 'Upload File',
            iconCls: 'add16',
            iconAlign: 'right',
            scale: 'medium',
            baseCls: 'x-plain',
            cls: 'btn-panel',
            handler: function() {
                goUploadPage();
            }
        },{
            text: 'Search',
            scale: 'medium',
            baseCls: 'x-plain',
            cls: 'btn-panel',
            handler: function() {
                goSearchPage();
            }
        },{
            text: 'Admin',
            scale: 'medium',
            baseCls: 'x-plain',
            cls: 'btn-panel',
            handler: function() {
                goAdminPage();
            }
        }]
    });

    p.render("buttons");
});

var goUploadPage = function() {
    window.location.href = url;
}

var goSearchPage = function() {
    window.location.href = searchURL;
}

var goAdminPage = function() {
    window.location.href = adminURL;
}

var showSearchForm = function(url) {
    var ds = new Ext.data.Store({
        proxy: new Ext.data.ScriptTagProxy({
            url: url
        }),
        reader: new Ext.data.JsonReader({
            root: 'topics',
            totalProperty: 'totalCount',
            id: 'post_id'
        }, [
            {name: 'postId', mapping: 'post_id'},
            {name: 'title', mapping: 'topic_title'},
            {name: 'topicId', mapping: 'topic_id'},
            {name: 'author', mapping: 'author'},
            {name: 'lastPost', mapping: 'post_time', type: 'date', dateFormat: 'timestamp'},
            {name: 'excerpt', mapping: 'post_text'}
        ]),

        baseParams: {limit:20, forumId: 4}
    });

    // Custom rendering Template for the View
    var resultTpl = new Ext.XTemplate(
        '<tpl for=".">',
        '<div class="search-item">',
            '<h3><span>{lastPost:date("M j, Y")}<br />by {author}</span>',
            '<a href="http://extjs.com/forum/showthread.php?t={topicId}&p={postId}" target="_blank">{title}</a></h3>',
            '<p>{excerpt}</p>',
        '</div></tpl>'
    );

    var panel = new Ext.Panel({
        applyTo: 'searchpane',
        title:'File Search',
        height:300,
        autoScroll:true,
        autoWidth: true,

        items: new Ext.DataView({
            tpl: resultTpl,
            store: ds,
            itemSelector: 'div.search-item'
        }),

        tbar: [
            'Search: ', ' ',
            new Ext.ux.form.SearchField({
                store: ds,
                width:320
            })
        ],

        bbar: new Ext.PagingToolbar({
            store: ds,
            pageSize: 10,
            displayInfo: true,
            displayMsg: 'Topics {0} - {1} of {2}',
            emptyMsg: "No topics to display"
        })
    });

    ds.load({params:{start:0, limit:20, forumId: 4}});
}