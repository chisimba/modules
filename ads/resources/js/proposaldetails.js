
function showTabs() {
    var args=showTabs.arguments;
    var selectedTab=args[0];
   
    // basic tabs, first tabs contains the Course details, second tabs contains course history
    var tabs = new Ext.TabPanel({
        renderTo: 'tabs',
        width:800,
        activeTab: parseInt(selectedTab),
        frame:true,
        defaults:{autoHeight: true},
        items:[
            {contentEl:'contentcontent', title: 'Proposal Summary'},
            {contentEl: 'tree-div', title: 'Proposal History'},
            {contentEl: 'memberscontent', title: 'Proposal Comment Editors'}
        ]
    });
}

function addTree() {
    var args=addTree.arguments;
    var courseid=args[0];
    var url=args[1];
    
    // course history
    var tree = new Ext.tree.TreePanel({
        useArrows: true,
        autoScroll: true,
        animate: true,
        enableDD: true,
        containerScroll: true,
        border: false,
        rootVisible: false,
        applyTo: 'tree-div',

        // auto create TreeLoader
        dataUrl: url,
        root: {
            nodeType: 'async',
            text: 'DATA',
            draggable: false,
            id: 'source'
        },
        listeners: {
            click: function(n) {
                populateForm(n, courseid);
            }
        }

    });

    tree.getRootNode().expand();
}

function populateForm(n, courseid) {
    Ext.onReady(function() {
       // get the data clicked on
        var propChosen = n.attributes.text,
            eachSplit = propChosen.split('_'),
            date = eachSplit[0],
            version = eachSplit[1];

        // get home url
        homeURL = jQuery("input[@name='homeURL']").val();
        url = homeURL + "&action=viewcoursedetails&date=" +date+ "&version=" + version + "&courseid=" + courseid;

        window.location.href = url;
    });
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
    grid.render('memberscontent');

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

    var xvsearch = new Ext.form.ComboBox({
        store: ds,
        displayField:'title',
        typeAhead: false,
        loadingText: 'Searching...',
        width: 600,
        pageSize:10,
        hideTrigger:true,
        tpl: resultTplc,
        applyTo: 'xxxsearch',
        itemSelector: 'div.search-item',
        onSelect: function(record){ // override default onSelect to do redirect
            window.location =
                String.format('http://extjs.com/forum/showthread.php?t={0}&p={1}', record.data.topicId, record.id);
        }
    });
}

var searchWin;
function showSearchWin(){
        Ext.onReady(function() {
         if(!searchWin){
            searchWin = new Ext.Window({
               applyTo:'search-user-win',
                layout:'fit',
                title:'Update Status',
                width:600,
                height:300,
                x:250,
                y:50,
                closeAction:'hide',
                plain: true,
                items:

                    [{html:'<div style="width:600px;"><div class="x-box-tl"><div class="x-box-tr"><div class="x-box-tc"></div></div></div><div class="x-box-ml"><div class="x-box-mr"><div class="x-box-mc"> <h3 style="margin-bottom:5px;">Search the Ext Forums</h3><input type="text" size="10" name="xxxsearch" id="xxxsearch" /> <div style="padding-top:4px;">Live search requires a minimum of 4 characters.</div> </div></div></div> <div class="x-box-bl"><div class="x-box-br"><div class="x-box-bc"></div></div></div></div>'} ]

            });
        }
       searchWin.show(this);
 });
}


