var url;
Ext.onReady(function() {
    url = Ext.get('uploadURL').dom.value;
    var p = new Ext.Panel({
        width: 100,
        border: false,
        defaultType: 'button',
        id: 'upload-button',
        items: {
            text: 'Upload File',
            iconCls: 'add16',
            iconAlign: 'right',
            scale: 'medium',
            baseCls: 'x-plain',
            cls: 'btn-panel',
            handler: function() {
                goUploadPage();
            }
        }
    });

    p.render("buttons");
});

var goUploadPage = function() {
    window.location.href = url;
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
        title:'Forum Search',
        height:100,
        autoScroll:true,
        width: 600,

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