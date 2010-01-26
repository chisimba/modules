/*
 * Ext JS Library 3.0 RC2
 * Copyright(c) 2006-2009, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 * By Qhamani Fenama
 * qfenama@gmail.com/qfenama@uwc.ac.za
 */

    var filepath;  
    var fileid;
    var filename;
    var winup;
    var selectedfolder;
    //var newIndex = 3;

    var myMask = new Ext.LoadMask(Ext.getBody(), {msg:"Please wait..."});
    
   /* var up = new Ext.ux.form.FileUploadField({
        listeners: {
            'fileselected': function(fb, v){
                var el = Ext.fly('fi-button-msg');
                el.update('<b>Selected:</b> '+v);
                if(!el.isVisible()){
                    el.slideIn('t', {
                        duration: .2,
                        easing: 'easeIn',
                        callback: function(){
                            el.highlight();
                        }
                    });
                }else{
                    el.highlight();
                }
            }
        }
    	});


    //Uploading form
    var uploadform = new Ext.FormPanel({
	//standardSubmit: true,
	url: baseuri + "",
	frame:true,
	title: 'Add User',
	bodyStyle:'padding:5px 5px 0',
	width: 600,
	items: [{
	layout:'column',
	items:[up],
	buttons: [{
		text: 'Upload File(s)',
		handler: function (){}
		
	}]
	}]});
*/
    


    var upButton = new Ext.Button({
	text:'Upload',
	tooltip:'Upload File',
	iconCls: 'silk-add',
	disabled: true,
	handler: function (){
		/*if(!winup){
		    winup = new Ext.Window({
		    layout:'fit',
				width:320,
				height:320,
				closeAction:'hide',
				plain: true,					
				items: [uploadform]	
		     });
		}
			winup.show(this);
		
		;*/}	
        })

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

    var rnButton = new Ext.Button({
            text:'Rename',
            tooltip:'Rename File',
            iconCls: 'silk-pencil',
	    disabled: true,
            handler: function (){}
        });
        
    var Tree = Ext.tree;

    var tb = new Ext.Toolbar({
	items:[{
	    text: 'New Folder',
    	    iconCls: 'silk-folder',
	    disabled: true,
            handler: function(){
	        /*var node = root.appendChild(new Ext.tree.TreeNode({
		
	        text:'Album ' + (++newIndex),
	        cls:'album-node',
	        allowDrag:false
	        }));
		alert(node);
	      tree.getSelectionModel().select(node);
	      setTimeout(function(){
	      ge.editNode = node;
	      ge.startEdit(node.ui.textNode);
	 }, 10);*/
    }
}]
});


    var tree = new Tree.TreePanel({
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
		upButton.enable();
		tb.enable();
		selectedfolder = node.id;
		datastore.load({params:{id:selectedfolder}});
	})
            }}

    });
    
    // add a tree sorter in folder mode
    //new Tree.TreeSorter(tree, {folderSort:true});
    
    // set the root node
    var root = new Tree.AsyncTreeNode({
        text: 'My Files', 
        draggable:false, // disable root node dragging
        id:defId
    });
    tree.setRootNode(root);
                        
    //root.expand(false, /*no anim*/ false);

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
	items:[upButton, dlButton, dltButton, rnButton		
	]});
	
    var sm2 = new Ext.grid.CheckboxSelectionModel({
        listeners: {
            // On selection change, set enabled state of the removeButton
	    selectionchange: function(sm) {
		dlButton.disable();
		rnButton.disable();

		if (sm.getCount()) {
		    if (sm.getCount() == 1){
		    	dlButton.enable();
			rnButton.enable();
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
	width: 120           
	},
	{
	id: 'filename',
	header: "File Name",
	dataIndex: 'filename',
	width: 300,            
	sortable: true
	},
	{
	id: 'filesize',
	header: "Size",
	dataIndex: 'filesize',
	width: 150,            
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
/*region: 'south', contentEl: 'south', split: true, height: 100, minSize: 100, maxSize: 200, collapsible: true, title: 'South', margins: '0 0 0 0'*/
	},
	{
	region: 'west',
	id: 'west-panel',
	title: 'My Files',
	width: 200,
	minSize: 175,
	maxSize: 400,
	collapsible: true,
	margins: '0 0 0 5',
	//layout: {
	//    type: 'accordion',
	//    animate: true
	//},
	items: [tree]
	},dirbrowser]
	});

    function doDownloadFile()
    {
	filepath = encodeURIComponent(dirbrowser.getSelectionModel().getSelected().get('filepath'));
	var link = uri+'usrfiles/'+filepath.replace(/%2F/g, "/");
	window.open(link,'Download');  
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

	
    Ext.onReady(function(){
    //alert(uri);
    datastore.load({params:{id:defId}});
    viewport.render();
    });

