
var pageSize = 25;
var userOffset = 0;
var selectedTab = "A";
var selectedGroupId;
var win;
var myMask = new Ext.LoadMask(Ext.getBody(), {msg:"Please wait..."});

//the main function 
Ext.onReady(function(){
	Ext.QuickTips.init();
	
	alphaGroupStore.load({params:{start:0, limit:25, letter:'A'}});
	myBorderPanel.render('mainPanel');
	
	SiteAdminGrid.setVisible(true);

	groupsGrid.getSelectionModel().on('rowselect', function(sm, ri, record)
	{ 
		
       SiteAdminGrid.setVisible(true);
       selectedGroupId = record.data.id;
       loadGroup(record.data.id, record.data.group_define_name, record.data.title);
      
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
        root: 'users',
        totalProperty: 'totalCount',
        idProperty: 'id',
        remoteSort: true,
		
        fields: [
            'username', 
            'firstname', 
            'surname', 
            'userid',           
            'lastloggedin',
            'emailaddress'
            //{name: 'lastloggedin', mapping: 'lastloggedin', type: 'date', dateFormat: 'timestamp'}
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
    				loadSubgroupMenu(records);			
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
	    			proxyStore.setUrl(baseUri+'?module=groupadmin&action=json_getgroupusers&groupid='+selectedGroupId+'&limit='+params.start+'&offset='+params.start);
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
            text:'Remove User',
            tooltip:'Remove the selected User',
            iconCls:'silk-delete',
			id:'rmgroup',
            // Place a reference in the GridPanel
            ref: '../../removeButton',
            disabled: true,
            handler: function(){
            	doRemoveUsers();
            }
        });
        


// The toolbar for the user grid
var toolBar = new Ext.Toolbar({
	items:[{
            text:'Add User',
            tooltip:'Add a User to this group',
            iconCls: 'silk-add',
            handler: function (){
	        	if(!win){
		            win = new Ext.Window({
		                
		                layout:'fit',
		                width:615,
		                height:350,
		                closeAction:'hide',
		                plain: true,						
		                items: [usersGridPanel]		
		                
		            });
		        }
		        win.show(this);
		        userStore.load({params:{start:0, limit:pageSize}})
            }
        }, '-',rmButton, 
        '-', 
        {
        	iconCls: 'blist',
        	text: 'Sub Groups',
        	menu: scrollMenu
        }]
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
        title:'Messages',
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

var SiteAdminGrid = new Ext.grid.GridPanel({
	title:'Mesage Body',
	region: 'center',
	split:true,
	frame:true,
    layout: 'fit',
    margins: '10 10 10 10',	 
	tbar: toolBar,        
    bbar:pageNavigation,    
    width:400,
    height:300,   
    store: abstractStore,    
    iconCls:'icon-grid',
    loadMask: true,
	sm: sm2,
	
    // grid columns
    cm: new Ext.grid.ColumnModel([
            sm2,{

   // columns:[{
            header: "Last Name",
            dataIndex: 'surname',
            width: 150,            
            sortable: true
        },{
            id: 'firstname', // id assigned so we can apply custom css (e.g. .x-grid-col-topic b { color:#333 })
            header: "First Name",
            dataIndex: 'firstname',
            width: 150,            
            sortable: true
        },{
            header: "Username",
            dataIndex: 'username',
            width: 100,           
            sortable: true
        },{
            header: "Email",
            dataIndex: 'emailaddress',
            width: 150           
        },{
            id: 'last',
            header: "Last Logged In",
            dataIndex: 'lastloggedin',
            width: 100,            
             align: 'right',
            sortable: true
   		 }]),
    
     viewConfig: {
        forceFit:true,
        emptyText: 'No Record found - Try the sub groups'

    	}
});

var myBorderPanel = new Ext.Panel({
    //renderTo: document.body,
    width: 950,
    height: 400,
    margins: '10 10 10 10',
    padding: '10 10 10 10',
    title: 'Group Administration',
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
function loadGroup(nodeId, groupname, grouptitle){
	
	//load the subgroups
	subGroupStore.load({params:{start:0, limit:25, groupid: nodeId}});	
	
	SiteAdminGrid.setTitle(groupname+" - " +grouptitle);
	SiteAdminGrid.render('groupusers');
	//alert(nodeId);
	proxyStore.setUrl(baseUri+'?module=groupadmin&action=json_getgroupusers&groupid='+nodeId);
	//load the data for this group
	abstractStore.load({params:{start:0, limit:25}}); 	
}

function loadSubgroup1(groupId)
{
	proxyStore.setUrl(baseUri+'?module=groupadmin&action=json_getgroupusers&groupid='+groupId);
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
	proxyStore.setUrl(baseUri+'?module=groupadmin&action=json_getgroupusers&groupid='+item.getItemId());
	abstractStore.load({params:{start:0, limit:25}}); 
	selectedGroupId = item.getItemId();
	//SiteAdminGrid.setTitle(SiteAdminGrid.getTitle()+' - '+item.getText());
}

//method tht removes users from a group
function doRemoveUsers()
{	
	myMask.show();
	//get the selected users
	var selArr = SiteAdminGrid.getSelectionModel().getSelections();
	
	//get the selected id's
	var idString = "";
	
	Ext.each( selArr, function( r ) 
	{
		idString = r.id +','+ idString ;		
	});   	
				//alert(userOffset);
		//post to server
	Ext.Ajax.request({
	    url: baseUri,
	    method: 'POST',
	    params: {
	       	module: 'groupadmin',
	   		action: 'json_removeusers',
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
