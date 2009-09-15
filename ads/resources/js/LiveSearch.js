/*!
 * Ext JS Library 3.0.0
 * Copyright(c) 2006-2009 Ext JS, LLC
 * licensing@extjs.com
 * http://www.extjs.com/license
 */
var panel;
var courseId;
var actionUrl;
var actionWord;
var actionFunction;
var renderSurface;
var searchUrl;

function showSearchWinX(){
    var args = showSearchWinX.arguments;
    courseId=args[0];
    actionUrl=args[1];
    actionWord=args[2];
    actionFunction=args[3];
    renderSurface=args[4];
    searchUrl=args[5];
    
    
    var ds = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url:searchUrl
        }),
        reader: new Ext.data.JsonReader({
            root: 'rows',
            totalProperty: 'totalCount',
            id: 'userid'
        }, [
        {
            name: 'userid',
            mapping: 'userid'
        },

        {
            name: 'firstname',
            mapping: 'firstname'
        },

        {
            name: 'lastname',
            mapping: 'lastname'
        },

        {
            name: 'email',
            mapping: 'email'
        }
        ]),

        baseParams: {
            limit:20,
            userId: 1
        }
    });

    // Custom rendering Template for the View
    var resultTpl = new Ext.XTemplate(
        '<tpl for=".">',
        '<div class="search-item">',
        '<h3>{firstname} {lastname}</h3>',
        '<p><span><a href="#" onclick="'+actionFunction+'(\''+actionUrl+'\',\'{email}\',\''+courseId+'\',\'{userid}\');return false;">'+actionWord+'</a></span></p>',
            
        '</div></tpl>'
        );

    panel = new Ext.Panel({
     
        height:300,
        autoScroll:true,
        bodyCssClass:  'search-item',
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
            pageSize: 21,
            displayInfo: true,
            displayMsg: 'User {0} - {1} of {2}',
            emptyMsg: "No  users to display"
        })
    });

    ds.load({
        params:{
            start:0,
            limit:10,
            userId: 1
        }
        });

    // create and show window
    var win;
    //if(!win){
    win = new Ext.Window({
        id:'combo-win'
        ,
        title:'Search users'
        ,
        width:500
        ,
        x:10
        ,
        y: 10
        ,
        height:400
        ,
        closable:false
        ,
        border:false
        ,
        applyTo:renderSurface
        // let window code to focus the combo on show
        ,
        defaultButton:'combo'
        ,
        items:{
            xtype:'form'
            ,
            frame:true
            ,

            items:[
            
            panel

            ]
        },

        buttons:[{
            text:'Close'
            ,
            iconCls:'icon-undo'
            ,
            scope:this
            ,
            handler:function() {
                //this.store.rejectChanges();
                win.hide();
                window.location.reload(true);
                Ext.MessageBox.alert('Please wait...');
            }
        }]

    });
    //}
    win.show();
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