
function showTabs() {
    var args=showTabs.arguments;
    var selectedTab=args[0];
    var showMembers='true';//args[1];
     var tabs=[];
      if(showMembers == 'true'){
         tabs= [
            {contentEl:'contentcontent', title: 'Proposal Summary'},
            {contentEl: 'tree-div', title: 'Proposal History'},
            {contentEl: 'memberscontent', title: 'Proposal Comment Editors'}
        ];
      }else{
        tabs= [
           {contentEl:'contentcontent', title: 'Proposal Summary'},
           {contentEl: 'tree-div', title: 'Proposal History'}

        ];
      }
    // basic tabs, first tabs contains the Course details, second tabs contains course history
    var tabs = new Ext.TabPanel({
        renderTo: 'tabs',
        width:800,
        activeTab: parseInt(selectedTab),
        frame:false,
        
        defaults:{autoHeight: true},
        items:tabs
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


function forwardForAPOComment(courseid,version){

Ext.MessageBox.confirm('Forward Proposal?', 'Are you sure you want to forward this proposal for APO Comments?', function(btn){

  if (btn == 'yes') {
    window.location.href='?module=ads&action=updatephase'+'&phase=1&id='+courseid+'&version='+version;
  }

});
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
    window.location.href='?module=ads&action=sendproposal'+'&email='+email+'&courseid='+courseid;
  }
});
}

function forwardToOwnerFromAPO(courseid,email){

  Ext.MessageBox.confirm('Forward Proposal?', 'Are you sure you want to forward the proposal to owner  ['+email+']?', function(btn){

  if (btn == 'yes') {
    window.location.href='?module=ads&action=forwardtoownerfromapo'+'&email='+email+'&courseid='+courseid;
  }
});
}
function forwardProposalToModerator(data,courseid){
    var fowardWin;
    var myForm = getMyForm(data);
    fowardWin = new Ext.Window({
        id:'fowardWin',
        title:'Forward To Moderator',
        width:500,
        x:10,
        y: 10,
        height:350,
        closable:false,
        border:false,
        applyTo:'fowardwin',
        defaultButton:'combo',
        items: myForm,
        buttons:[{
            text:'Close',
            iconCls:'icon-undo',
            scope:this,
            handler:function() {
                fowardWin.hide();
                window.location.reload(true);
            }
        }]
    });
    fowardWin.show();
    myForm.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
           fowardToModerator(r.get('faculty'),courseid);
    });
}

function addProposalMember(){
   var args=addProposalMember.arguments;
   var url=args[0];
   var email=args[1];
   var courseid=args[2];
   var userid=args[3];
   window.location.href='?module=ads&action=addproposalmember'+'&email='+email+'&courseid='+courseid+"&userid="+userid;
}

function getMyForm(data) {
    // create the data store
    var store = new Ext.data.ArrayStore({
        fields: [
           {name: 'faculty'},
           {name: 'moderator'}
        ]
    });
    store.loadData(data);

    // create the Grid
    var grid = new Ext.grid.GridPanel({
        store: store,
        columns: [
            {id: 'faculty', header: "Faculty", width: 160, sortable: true, dataIndex: 'faculty'},
            {header: "Moderator", width: 120, sortable: true, dataIndex: 'moderator'}
        ],
        stripeRows: true,
        autoExpandColumn: 'faculty',
        height:250,
        width:460,

        border:false,
        sm: new Ext.grid.RowSelectionModel({singleSelect: true})
    });

    return grid;
}

function fowardToModerator(faculty,courseid) {
    if(confirm("Are you sure you want to foward to the faculty of " + faculty)) {
        window.location.href = "?module=ads&action=sendproposaltomoderator&faculty="+faculty+"&courseid="+courseid;
    }
}


function showEditProposalWin(faculties,url,selectedFaculty,proposalName){
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
                    {
                    fieldLabel: 'Title',
                    value:proposalName,
                    name: 'title',
                    id: 'input_title',
                    allowBlank: false
                }
            ]

        });

    var addProposalWin;
    if(!addProposalWin){
            addProposalWin = new Ext.Window({
                applyTo:'out-search-xwin',
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