
var pageSize = 25;
var userOffset = 0;
var selectedTab = "A";
var selectedGroupId;
var recipentId;
var win;
var myMask = new Ext.LoadMask(Ext.getBody(), {msg:"Please wait..."});

//the main function 
Ext.onReady(function(){
	Ext.QuickTips.init();
	
	alphaGroupStore.load({params:{start:0, limit:25, letter:'A'}});
	myBorderPanel.render('mainPanel');
	
	SiteAdminGrid.setVisible(false);

	groupsGrid.getSelectionModel().on('rowselect', function(sm, ri, record)
	{ 
		
       SiteAdminGrid.setVisible(true);
       selectedGroupId = record.data.msgid;
       recipentId = record.data.recipentuserid;
       senderId = record.data.senderuserid;
       loadGroup(record.data.msgid, record.data.sender, record.data.timesent, record.data.messagetitle, record.data.messagebody);
      
    });       

});

////////////////////////
/// Data Stores ////////
////////////////////////
var proxyGroupStore = new Ext.data.HttpProxy({
            url: baseUri+'?module=liftclub&action=json_getallmessages&limit=25&start=0'
        });

        //msgid sender recipentuserid timesent markasread markasdeleted messagetitle messagebody
 var alphaGroupStore = new Ext.data.JsonStore({
        root: 'searchresults',
        totalProperty: 'msgcount',
        idProperty: 'msgid',
        remoteSort: true,
		//baseParams: [{'letter':selectedTab}],
        fields: [
        	'msgid',
            'sender', 
            'recipentuserid',
            'senderuserid',
            'timesent',
            'markasread',
            'markasdeleted',
            'messagetitle',
            'messagebody'
            
        ],
        
		listeners:{ 
    		'loadexception': function(theO, theN, response){
    			//alert(response.responseText);
    		},
    		'beforeload': function(thisstore, options){
    			//thisstore.setBaseParam('letter', selectedTab);
    		},
    		'load': function(){
    				//alert('alphagroup store load');
					//loadGroups(tabPanel, tab);	
    			}
    	},
        // load using script tags for cross domain, if the data in on the same domain as
        // this page, an HttpProxy would be better
        proxy:proxyGroupStore 
    });
    
var proxyStore = new Ext.data.HttpProxy({
            url: baseUri+'?module=groupadmin&action=json_getgroupusers&groupid='
        });
        

  // create the Data Store
 var abstractStore = new Ext.data.JsonStore({
        root: 'searchresults',
        totalProperty: 'msgcount',
        idProperty: 'msgid',
        remoteSort: true,
		//baseParams: [{'letter':selectedTab}],
        fields: [
        	'msgid',
            'sender', 
            'recipentuserid',
            'senderuserid',
            'timesent',
            'markasread',
            'markasdeleted',
            'messagetitle',
            'messagebody'
            
        ],
        listeners:{ 
    		'loadexception': function(theO, theN, response){
    			//alert(response.responseText);
    		},
    		'load': function(thestore, records){    				
    				//alert('user group loaded');
    				
    			}
		},

        // load using script tags for cross domain, if the data in on the same domain as
        // this page, an HttpProxy would be better
        proxy:proxyStore 
    });
    //store.setDefaultSort('lastpost', 'desc');
    
    var proxySubGroupStore = new Ext.data.HttpProxy({
            url: baseUri+'?module=liftclub&action=json_getallmessages&limit=25&start=0'
        });
        
    var subGroupStore = new Ext.data.JsonStore({
        root: 'searchresults',
        totalProperty: 'msgcount',
        idProperty: 'msgid',
        remoteSort: true,
		//baseParams: [{'letter':selectedTab}],
        fields: [
        	'msgid',
            'sender', 
            'recipentuserid',
            'timesent',
            'markasread',
            'markasdeleted',
            'messagetitle',
            'messagebody'
            
        ],
		listeners:{ 
    		'loadexception': function(theO, theN, response){
    			//alert('subGroupStore error\n'+response.responseText);
    		},
    		'load': function(thestore, records){    				
    				//alert('subgroupstore loaded');
    				//loadSubgroupMenu(records);			
    			}
		}
	});
   
 //////////////////////////////////////////////////////////
   
///////////////////////
/// Toolbars //////////
////////////////////////



//the page navigation for the users in a group
var pageNavigation = new Ext.PagingToolbar({
            pageSize: pageSize,
            store: abstractStore,
            displayInfo: true,
            
            displayMsg: 'Displaying Users {0} - {1} of {2}',
            emptyMsg: "No Users to display",
            listeners:{ 	    		
	    		beforechange: function(ptb, params){	
	    			userOffset = params.start; 			
	    			proxyStore.setUrl(baseUri+'?module=liftclub&action=json_getallmessages&id='+selectedGroupId+'&limit='+params.start+'&offset='+params.start);
	    		}  
            }
            
        });

//the page navigation for the top level groups        
var groupsPageNavigation = new Ext.PagingToolbar({
            pageSize: 25,
            store: alphaGroupStore,
            displayInfo: true,
            
            displayMsg: 'Messages {0} - {1} of {2}',
            emptyMsg: "No Messages to display",
            listeners:{ 
            	beforechange: function(ptb, params){	    			
	    			proxyGroupStore.setUrl(baseUri+'?module=liftclub&action=json_getallmessages&limit='+params.start+'&start='+params.start);	    			
	    		}            
            }
            
        });

// the list of sugroups in the form of a dropdown
var subGroupsCombo = new Ext.form.ComboBox({
	//store: subGroupStore,
	displayField:'name',
	valueField: 'groupid',
	typeAhead: true,
	//mode: 'local',
	triggerAction: 'all',
	forceSelection:true,

	emptyText:'Select a Sub Group...',
	selectOnFocus:true,
	listeners:{
		select: function(item, record) {
                //alert(record.data.groupid);
                //alert('subgroup select listener');
                loadSubgroup(record.data.groupid)
            }
	}
	//applyTo: 'local-states'
});

//the dropdown for the subgroups
var scrollMenu = new Ext.menu.Menu();

var rmButton = new Ext.Button({
            text:'Send to Trash',
            tooltip:'Send message to trash',
            iconCls:'silk-delete',
			id:'rmgroup',
            // Place a reference in the GridPanel
            ref: '../../removeButton',
            disabled: false,
            handler: function(){
            	sendToTrash();
            }
        });
        

    var sendmsgFormPanel = new Ext.FormPanel({
        id: 'mainForm',
        labelWidth: 75, // label settings here cascade unless overridden
        url:baseUri+'?module=liftclub&action=extjssendmessage&msgid='+selectedGroupId,
        frame:true,
        title: 'Send Message Form',
        bodyStyle:'padding:5px 5px 0',
        width: 350,
        defaults: {width: 230},
        defaultType: 'textfield',
        items: [{
                xtype:'textfield',
                fieldLabel: 'Title',
                name: 'msgtitle',
                allowBlank:false
            },{
                xtype:'textarea',
                fieldLabel: 'Message',
                name: 'msgbody',
                allowBlank:false
            }
        ],
        buttons: [{
            text: 'Save',
            handler: function() {
                Ext.getCmp('mainForm').getForm().submit({
                    url:baseUri+'?module=liftclub&action=extjssendmessage&favusrid='+senderId,
                    method: 'POST',
                    //params: { msgid: selectedGroupId },
                    waitTitle: 'Processing...',
                    waitMsg: 'Please wait...',
                    success: function(f, a){
																				 Ext.Msg.alert('Processing Complete','Message Sent Successfully');
																				 sendmsgFormPanel.getForm().reset();
																				 win.hide();
																					SiteAdminGrid.setVisible(false);
																					alphaGroupStore.load({params:{start:0, limit:25}});
																				},
																				failure: function(f, a)
																				{
																			  Ext.Msg.alert('Processing Complete','Error Encountered, try again!');
																			  //win.hide();
																				}
                });
            }
        },{
												text: 'Reset',
												handler: function() {
												 sendmsgFormPanel.getForm().reset();
								    }
								},{
            text: 'Cancel',
            handler: function(){
                       win.hide();
            }
        }]
    });

// The toolbar for the user grid
var toolBar = new Ext.Toolbar({
	items:[{
            text:'Reply',
            tooltip:'Reply to sender',
            iconCls: 'silk-add',
            handler: function (){
	        	if(!win){
	        	    //alert(selectedGroupId);
		            win = new Ext.Window({
		                layout:'fit',
		                width:415,
		                //height:250,
		                autoHeight:true,
		                closeAction:'hide',
		                plain: true,
		                items: [sendmsgFormPanel]			                
		            });
		        }
		        win.show(this);
          }
        }, '-',rmButton]
});


  var sm2 = new Ext.grid.CheckboxSelectionModel({
        listeners: {
            // On selection change, set enabled state of the removeButton
            // which was placed into the GridPanel using the ref config
            selectionchange: function(sm) {
                if (sm.getCount()) {
                    rmButton.enable();
                } else {
                    rmButton.disable();
                }
            }
        }
    });


////////////////////////////////
//// Grids ////////////////////
////////////////////////////////

//the top level groups grid
var groupsGrid = new Ext.grid.GridPanel({
		region: 'west',
		//closable:true,
		split:true,
		
		margins: '10 10 10 10',
	 	collapsible: true,   // make collapsible
    	cmargins: '10 10 10 10', // adjust top margin when collapsed
    	id: 'west-region-container',
    	layout: 'fit',
		
		width:460,
        height:300,
       // frame:true,
        store: alphaGroupStore,
        title:'Inbox',
        iconCls:'icon-grid',
        loadMask: true,
		//stripeRows: true,//msgid sender recipentuserid timesent markasread markasdeleted messagetitle messagebody
		sm: new Ext.grid.RowSelectionModel({singleSelect:true}),
        // grid columns  
        tbar:[],         
    	bbar: groupsPageNavigation,
        
        columns:[{
	            id: 'timesent', // id assigned so we can apply custom css (e.g. .x-grid-col-topic b { color:#333 })
	            header: "Time",
	            dataIndex: 'timesent',
	            width: 110,
	            align: 'left',
	            //renderer: renderTopic,
	            sortable: true
	        },{
	            id: 'sender', 
	            header: "Sender",
	            dataIndex: 'sender',
	            width: 110,
	            align: 'left',
	            //renderer: renderTopic,
	            sortable: true
	        },{
	            id: 'messagetitle', 
	            header: "Title",
	            dataIndex: 'messagetitle',
	            width: 200,
	            align: 'left',
	            //renderer: renderTopic,
	            sortable: true
	        }],
	    	viewConfig: {
            //forceFit:true,
             emptyText: 'No Messages found'

        	}, plugins:[new Ext.ux.grid.Search({
				 iconCls:'zoom'
				 //,readonlyIndexes:['emailaddress']
				 ,disableIndexes:['sender']
				 ,minChars:2
				 ,position:'top'
				 ,autoFocus:true
				 ,minCharsTipText:'Type at least 2 characters'
				 //,searchTipText :'Type at least 2 characters'
				 // ,menuStyle:'radio'
		 })],
			listeners:{ 
    		'loadexception': function(theO, theN, response){
    			//alert(response.responseText);
    		},
    		'beforeload': function(thisstore, options){
    			//thisstore.setBaseParam('letter', selectedTab);
    		},
    		'load': function(){
    				loadGroups(tabPanel, tab)
					//loadGroups(tabPanel, tab);	
    			}
    	}
	
});
//'<table border="1"><tr><td>'+record.data.messagebody+'</td></tr></table>'
function renderBody(value, p, record){
        return String.format(
        		'<TEXTAREA NAME="MESSAGE" COLS=54 ROWS=15 WRAP=SOFT readonly="readonly">'+record.data.messagebody+'</TEXTAREA>');
}
var SiteAdminGrid = new Ext.grid.GridPanel({
	title:'Mesage',
	region: 'center',
	split:true,
	frame:true,
    layout: 'fit',
    margins: '10 10 10 10',	 
	tbar: toolBar,        
    //bbar:pageNavigation,    
    width:400,
    height:300,   
    store: abstractStore,    
    iconCls:'icon-grid',
    loadMask: true,
	sm: sm2,
	
    // grid columns
    cm: new Ext.grid.ColumnModel([
            {
	            id: 'msgid',
            header: "Message",
            dataIndex: 'messagebody',
	           renderer: renderBody,            
            resizable: true,
            width: 500,
	           align: 'center',
            sortable: false
        }]),    
     viewConfig: {
        forceFit:true,
        emptyText: 'Message Body not found'

    	}
});

var myBorderPanel = new Ext.Panel({
    //renderTo: document.body,
    width: 950,
    height: 400,
    margins: '10 10 10 10',
    padding: '10 10 10 10',
    title: 'Lift Club Messages',
    layout: 'border',
    items: [groupsGrid, SiteAdminGrid]
});




////////////////////////////////
//// HELPER METHODS ////////////
///////////////////////////////


//this function will be called when the
//page is loaded and when the an tab on the
//alphabet list is clicked
function loadGroups(tabPanel, tab){
	//load the groups
	//groupsGrid.setTitle('Groups starting with \''+tab.id+'\'');
	//groupsGrid.render('main-interface');
	//alert('Here');
	//window.alert("I am an alert box");

	//alphaGroupStore.setUrl(baseUri+'?module=groupadmin&action=json_getgroupsbysearch&limit=25&start=0');
}

//this function will be called when 
//the group is selected in the groups grid

function loadGroup(nodeId, sender, timesent, messagetitle, messagebody){
	
	//load the subgroups
	//subGroupStore.load({params:{start:0, limit:25, msgid: nodeId}});	
	
	SiteAdminGrid.setTitle(timesent+" - " +messagetitle);
		//SiteAdminGrid.render('groupusers');
	//alert(messagebody);
	proxyStore.setUrl(baseUri+'?module=liftclub&action=json_getallmessages&limit=25&start=0&id='+nodeId);
	//load the data for this group
	abstractStore.load({params:{start:0, limit:25}}); 	
}

function loadSubgroup1(nodeId)
{
	proxyStore.setUrl(baseUri+'?module=liftclub&action=json_getallmessages&limit=25&start=0&id='+nodeId);
	abstractStore.load({params:{start:0, limit:25}});
}

function loadSubgroupMenu(records){
	scrollMenu.removeAll();
	//var scrollMenu = new Ext.menu.Menu();
    for (var i = 0; i < records.length; ++i){
        scrollMenu.add({
            text: records[i].data.name,
             iconCls:'groups',
            itemId: records[i].data.groupid,
            handler: onSubGroupClick
        });
    }	
}

function onSubGroupClick(item){	
	proxyStore.setUrl(baseUri+'?module=liftclub&action=json_getallmessages&limit=25&start=0&id='+item.getItemId());
	abstractStore.load({params:{start:0, limit:25}}); 
	selectedGroupId = item.getItemId();
	//SiteAdminGrid.setTitle(SiteAdminGrid.getTitle()+' - '+item.getText());
}

//method that moves message to trash
function sendToTrash()
{	
	myMask.show();
				//alert(selectedGroupId);
		//post to server
	Ext.Ajax.request({
	    url: baseUri,
	    method: 'POST',
	    params: {
	       	module: 'liftclub',
	   		action: 'json_movetotrash',
	   		msgid: selectedGroupId
	    },
	    success: function(xhr,params) {
	        alert('Message Trashed Successfully!\n');
	        SiteAdminGrid.setVisible(false);
	        alphaGroupStore.load({params:{start:0, limit:25}});
/*	        abstractStore.load({
	        	params:{
	        			start:userOffset, 
	        			limit:pageSize,
	        			id:selectedGroupId,
	        			module:'liftclub',
	        			action:'json_getallmessages'
	        	}
	        }); */
	        myMask.hide();
	    },
	    failure: function(xhr,params) {
	        alert('Failure!\n'+xhr.responseText);
	        myMask.hide();
	    }
	});
	
}


 function doAddUsers(){
 	myMask.show();
	//get the selected users
	var selArr = usersGridPanel.getSelectionModel().getSelections();
	
	//get the selected id's
	var idString = "";
	
	Ext.each( selArr, function( r ) 
	{
		idString = r.id +','+ idString ;		
	});   	
			
		//post to server
	Ext.Ajax.request({
	    url: baseUri,
	    method: 'POST',
	    params: {
	       	module: 'groupadmin',
	   		action: 'json_addusers',
	   		groupid: selectedGroupId,
	   		ids: idString
	    },
	    success: function(xhr,params) {
	        //alert('Success!\n'+xhr.responseText);
	        abstractStore.load({
	        	params:{
	        			start:userOffset, 
	        			limit:pageSize,
	        			groupid:selectedGroupId,
	        			module:'groupadmin',
	        			action:'json_getgroupusers'
	        	}
	        }); 
	        win.hide();
	        myMask.hide();
	    },
	    failure: function(xhr,params) {
	        alert('Failure!\n'+xhr.responseText);
	        myMask.hide();
	    }
	});
	
 }
