/*
 * Ext JS Library 3.0 RC2
 * Copyright(c) 2006-2009, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 * By Qhamani Fenama
 * qfenama@gmail.com/qfenama@uwc.ac.za
 */
    var node1;
    var filepath;  
    var fileid;
    var filename;
    var winup;
    var selectedfolder;
    var fp;
    Ext.QuickTips.init();

    var myMask = new Ext.LoadMask(Ext.getBody(), {msg:"Please wait..."});
        
    //Uploading form
    var uploadform = new Ext.FormPanel({
	fileUpload: true,
	frame:true,
	title: 'Upload File(s)',
	bodyStyle:'padding:5px 5px 0',
	width: 700,
	autoHeight: true,
	bodyStyle: 'padding: 10px 10px 0 10px;',
        labelWidth: 50,
      	items: [{
            xtype: 'fileuploadfield',
            id: 'form-file1',
            emptyText: 'Select an file',
            fieldLabel: 'File',
            name: 'photo-path1',
            buttonText: 'Browse...'            
        },{
            xtype: 'fileuploadfield',
            id: 'form-file2',
            emptyText: 'Select an file',
            fieldLabel: 'File',
            name: 'photo-path2',
            buttonText: 'Browse...'            
        },{
            xtype: 'fileuploadfield',
            id: 'form-file3',
            emptyText: 'Select an file',
            fieldLabel: 'File',
            name: 'photo-path3',
            buttonText: 'Browse...'            
        }],
		
	buttons: [{
		text: 'Upload File(s)',
		handler: function (){
		if(uploadform.getForm().isValid())
		{
			uploadform.getForm().submit({
				url: baseuri,
				params:{
					module: 'filemanager2',
					action: 'json_uploadFile',
					selectedfolder: selectedfolder
				},
				waitMsg: 'Uploading your file...',
				success: function(fp, o){
				datastore.load({params:{id:selectedfolder}});
    				}});

				winup.hide();
						
				}
		}
		
	}]
	});

   var upButton = new Ext.Button({
	text:'Upload',
	tooltip:'Upload File',
	iconCls: 'silk-add',
	disabled: true,
	handler: function (){
	winup = new Ext.Window({
	                layout:'fit',
	                width:400,
	                height:200,
	                closeAction:'hide',
	                plain: true,						
	                items: [uploadform]});	
	winup.show(this);
        }});

   var dlButton = new Ext.Button({
	text:'Download',
	tooltip:'Download File',
	iconCls: 'silk-disk',
	disabled: true,
	handler: function (){
	doDownloadFile();	
        }});

    var dltButton = new Ext.Button({
	text:'Delete',
	tooltip:'Delete File(s)',
	iconCls: 'silk-delete',
	disabled: true,
	handler: function (){
    	Ext.MessageBox.confirm('Delete User', "Are you sure you want to delete the selected File(s)?", function(btn, text) 			{
	if (btn == 'yes')
	{
		doRemoveFiles()
	}
	});
	}
       });

          
    var Tree = Ext.tree;

    var newIndex = 3;

    var tree;

    var tb = new Ext.Toolbar({
	items:[{
	    text: 'New Folder',
    	    iconCls: 'silk-folder',
	    disabled: true,
            handler: function(){

		createNewDir(node1);
	    }
	}]
	});


    tree = new Tree.TreePanel({
	//id:'itree',
	animate:true, 
        autoScroll:true,
        loader: new Tree.TreeLoader({dataUrl: baseuri+'?module=filemanager2&action=getDirectory'}),
        enableDD:true,
        containerScroll: true,
        border: true,
        width: 250,
        height: 300,
        dropConfig: {appendOnly:false},
	tbar: tb,
	listeners: {
            'render': function(tp){
		
        tp.getSelectionModel().on('selectionchange', function(tree, node){
		node1 = node
		upButton.enable();
		tb.enable();
		selectedfolder = node.id;
		datastore.load({params:{id:selectedfolder}});
	})
            }}

    });
    
    // add a tree sorter in folder mode
    new Tree.TreeSorter(tree, {folderSort:true});
    
    // set the root node
    var root = new Tree.AsyncTreeNode({
        text: 'My Files', 
        draggable:false, // disable root node dragging
        id:defId
    });

    tree.setRootNode(root);
                        
    //root.expand(true, /*no anim*/ true);

   
    Ext.state.Manager.setProvider(new Ext.state.CookieProvider());

    // create the Data Store
    var datastore = new Ext.data.JsonStore({
        root: 'files',
        totalProperty: 'totalCount',
        idProperty: 'id',
	remoteSort: true,
		
        fields: [
	    'id',
            'filename', 
            'filesize',
	    'fileicon',
	    'filepath'],
        proxy: new Ext.data.HttpProxy({
            url: baseuri+'?module=filemanager2&action=getFolderContent'}) 
    });
        
    function renderIcon(value, p, record)
    {
	return String.format('<b>{0}</b>' ,record.data.fileicon);
    }

    var toolBar = new Ext.Toolbar({
	items:[upButton, dlButton, dltButton]});
	
    var sm2 = new Ext.grid.CheckboxSelectionModel({
        listeners: {
            // On selection change, set enabled state of the removeButton
	    selectionchange: function(sm) {
		dlButton.disable();
		
		if (sm.getCount()) {
		    if (sm.getCount() == 1){
		    	dlButton.enable();
			}
		    dltButton.enable();
		    } else {
		    dltButton.disable();
	       }
	    }
        }
    });

   var dirbrowser = new Ext.grid.GridPanel({
	region: 'center',
	id: 'center-panel', 
	split: true,
	width: 200,
	minSize: 175,
	maxSize: 400,
	margins: '0 0 0 5',
	frame:true,
	layout: 'fit',
	tbar: toolBar,        
	store: datastore,    
	iconCls:'icon-grid',
	loadMask: true,
	sm: sm2,
	viewConfig: {
	emptyText: 'No Files found'
	},

	// grid columns
	cm: new Ext.grid.ColumnModel([
	{
	id: 'fileicon',
	header: "Icon",
	dataIndex: 'fileicon',
	renderer: renderIcon,
	width: 80           
	},
	{
	id: 'filename',
	header: "File Name",
	dataIndex: 'filename',
	width: 320,            
	sortable: true
	},
	{
	id: 'filesize',
	header: "Size",
	dataIndex: 'filesize',
	width: 170,            
	sortable: true
	}
	]),
    	});
        
    var viewport = new Ext.Panel({
	el:'mainpanel',
	layout: 'border',
	width: 800,
	height: 350,
	title:'File Manager',
	items: [
	{
	region: 'west',
	title: 'My Files',
	width: 200,
	minSize: 175,
	maxSize: 400,
	collapsible: true,
	margins: '0 0 0 5',
	items: [tree]
	},dirbrowser]
	});

    function doDownloadFile()
    {
	filepath = encodeURIComponent(dirbrowser.getSelectionModel().getSelected().get('filepath'));
	var path = uri+'usrfiles/'+filepath.replace(/%2F/g, "/");
	window.open(path,'Download'); 
    }

    //method that removes files from a folder
    function doRemoveFiles()
    {	
	myMask.show();
	//get the selected files
	var selArr = dirbrowser.getSelectionModel().getSelections();

	//get the selected id's
	var idString = "";

	Ext.each( selArr, function( r ) 
	{
		idString = r.id +','+ idString ;		
	}); 
			
		//post to server
		Ext.Ajax.request({
		    url: baseuri,
		    method: 'POST',
		    params: {
		       	module: 'filemanager2',
		   		action: 'json_removefiles',
		   		ids: idString
		    },
		    success: function(xhr,params) {
			datastore.load({params:{id:selectedfolder}});
			myMask.hide();
		    },
		    failure: function(xhr,params) {
			alert('Failure!\n'+xhr.responseText);
			myMask.hide();
		    }
	});
     }

    function createNewDir(node){
	 	 
	var treeEditor =  new Ext.tree.TreeEditor(tree, {
		allowBlank:false
		,cancelOnEsc:true
		,completeOnEnter:true
		,ignoreNoChange:true
		,selectOnFocus:true
		});
	var newNode;
	 
	// get node to append the new directory to
	var appendNode = node.isLeaf() ? node.parentNode : node;
	 
	// create new folder after the appendNode is expanded
	appendNode.expand(false, false, function(n) {
	// create new node
	newNode = n.appendChild(new Ext.tree.AsyncTreeNode({text:'New Folder', iconCls:'folder'}));
	 
	// setup one-shot event handler for editing completed
	treeEditor.on("complete",
	function(o,newText,oldText){
	
	//post to server
		Ext.Ajax.request({
		    url: baseuri,
		    method: 'POST',
		    params: {
		       	module: 'filemanager2',
		   	action: 'jsoncreatefolder',
		   	parentfolder: selectedfolder,
			foldername: newText
		    },
		    success: function(response) {
		    var jsonData = Ext.util.JSON.decode(response.responseText);
		    if(jsonData.error)
			{
			Ext.Msg.alert('Error', jsonData.error);
			n.removeChild(newNode);
			}
		    else{
			newNode.setId(jsonData.data);
			}
		    },
		    failure: function(xhr,params) {
		}
		});
			
	}, this, true
	);
	 
		  
	// start editing after short delay
	(function(){treeEditor.triggerEdit(newNode);}.defer(10));
	// expand callback needs to run in this context
	}.createDelegate(this));
    }
	
    Ext.onReady(function(){
    // render mainpanel
    viewport.render();
    });

