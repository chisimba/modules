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

var myMask = new Ext.LoadMask(Ext.getBody(), {
    msg:"Please wait..."
});

//////////////////////////////////////
//////////////////////////////////////
var treeCtxMenu = new Ext.menu.Menu({
    id:'treeCtxMenu',
    items: [{
        id: 'gc_upload',
        iconCls: 'sexy-add',
        text: 'Upload',
        handler: function() {
            doUpload();
        }
    },
    {
        id: 'tc_newfolder',
        iconCls: 'sexy-newf',
        text: 'New Folder',
        handler: function() {
            createNewDir(node1);
        } 
    },
    {
        id: 'tc_deletefolder',
        iconCls: 'sexy-deletef',
        text: 'Delete Folder',
        handler: function() {
            deleteDir(node1);
        } 
    },
    '-',
    {
        id: 'tc_cancel',
        iconCls: 'sexy-cancel',
        text: 'Cancel',
        handler: function() {
            treeCtxMenu.hide();
        }
    }]
});
//////////////////////////////////////////////
var gridCtxMenu = new Ext.menu.Menu({
    id:'gridCtxMenu',
	height: "100%",
    items: [{
        id: 'gc_view',
        iconCls: 'sexy-view',
        text: 'View',
        handler: function() {
            viewFile();
        }
    },{
        id: 'gc_download',
        iconCls: 'sexy-download',
        text: 'Download',
        handler: function() {
            doDownloadFile();
        }
    },
    {
        id: 'gc_rename',
        iconCls: 'sexy-pencil',
        text: 'Rename',
        handler: function() {
        }
    },
    {
        id: 'gc_delete',
        iconCls: 'sexy-delete',
        text: 'Delete',
        handler: function() {
            doRemoveFiles();
        } 
    },
    '-',
    {
        id: 'gc_cancel',
        iconCls: 'sexy-cancel',
        text: 'Cancel',
        handler: function() {
            gridCtxMenu.hide();
        }
    }]
});

//////////////////////////////////////
//Uploading form
var uploadform = new Ext.FormPanel({
    fileUpload: true,
    frame:true,
    title: 'Upload File(s)',
    width: 700,
    autoHeight: true,
    bodyStyle: 'padding: 10px 10px 0 10px;',
    labelWidth: 50,
    items: [{
        xtype: 'fileuploadfield',
        id: 'form-file1',
        emptyText: 'Select a file',
        fieldLabel: 'File',
        name: 'photo-path1',
        buttonText: 'Browse...'
    },{
        xtype: 'fileuploadfield',
        id: 'form-file2',
        emptyText: 'Select a file',
        fieldLabel: 'File',
        name: 'photo-path2',
        buttonText: 'Browse...'
    },{
        xtype: 'fileuploadfield',
        id: 'form-file3',
        emptyText: 'Select a file',
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
                    waitMsg: 'Uploading your file(s)...',
                    success: function(fp, o){
                        datastore.load({
                            params:{
                                id:selectedfolder
                            }
                        });
                    }
                });

                winup.hide();

            }
        }

    }]
});

var upButton = new Ext.Button({
    text:'Upload',
    tooltip:'Upload File',
    iconCls: 'sexy-add',
    disabled: true,
    handler: function (){
        doUpload()
    }
});

var dlButton = new Ext.Button({
    text:'Download',
    tooltip:'Download File',
    iconCls: 'sexy-download',
    disabled: true,
    handler: function (){
        doDownloadFile();
    }
});

var rnmButton = new Ext.Button({
    text:'Rename',
    tooltip:'Rename File',
    iconCls: 'sexy-pencil',
    disabled: true,
    handler: function (){//doRename();
    }
});

var dltButton = new Ext.Button({
    text:'Delete',
    tooltip:'Delete File(s)',
    iconCls: 'sexy-delete',
    disabled: true,
    handler: function (){
        doRemoveFiles();
    }
});


var Tree = Ext.tree;

var newIndex = 3;

var tree;
var tree2;

var tb = new Ext.Toolbar({
    items:[{
        text: 'New Folder',
        iconCls: 'sexy-newf',
        disabled: true,
        handler: function(){

            createNewDir(node1);
        }
    },{
        text: 'Delete Folder',
        iconCls: 'sexy-deletef',
        disabled: true,
        handler: function(){
            deleteDir(node1);
              
        }
    }]
});

var tb2 = new Ext.Toolbar({
    items:[{
        text: 'New Folder',
        iconCls: 'sexy-newf',
        disabled: true,
        handler: function(){

            createNewDir(node1);
        }
    },{
        text: 'Delete Folder',
        iconCls: 'sexy-deletef',
        disabled: true,
        handler: function(){
            deleteDir(node1);
              
        }
    }]
});


tree = new Tree.TreePanel({
    id: 'tree',
    animate:true,
    autoScroll:true,
    loader: new Tree.TreeLoader({
        dataUrl: baseuri+'?module=filemanager2&action=getDirectory'
    }),
    enableDD:true,
    containerScroll: true,
    border: false,
    width: 250,
    height: 300,
    dropConfig: {
        appendOnly:true
    },
    tbar: tb,
    listeners: {
        'render': function(tp){

            tp.getSelectionModel().on('selectionchange', function(tree, node){
                node1 = node
                upButton.enable();
                tb.enable();
                selectedfolder = node.id;
                datastore.load({
                    params:{
                        id:selectedfolder
                    }
                });
            })
        }
    }
});

tree2 = new Tree.TreePanel({
    id: 'tree2',
    animate:true,
    autoScroll:true,
    loader: new Tree.TreeLoader({
        dataUrl: baseuri+'?module=filemanager2&action=getDirectory'
    }),
    enableDD:true,
    containerScroll: true,
    border: false,
    width: 250,
    height: 300,
    dropConfig: {
        appendOnly:true
    },
    tbar: tb2,
    listeners: {
        'render': function(tp){

            tp.getSelectionModel().on('selectionchange', function(tree2, node){
                node1 = node
                upButton.enable();
                tb2.enable();
                selectedfolder = node.id;
                datastore.load({
                    params:{
                        id:selectedfolder
                    }
                });
            })
        }
    }
});

// add a tree sorter in folder mode
new Tree.TreeSorter(tree, {
    folderSort:true
});

// add a tree sorter in folder mode
new Tree.TreeSorter(tree2, {
    folderSort:true
});

// set the root node
var root = new Tree.AsyncTreeNode({
    text: 'My Files',
    draggable:false, // disable root node dragging
    id:defId
});

// set the root node
var root2 = new Tree.AsyncTreeNode({
    text: 'Course Files',
    draggable:false, // disable root node dragging
    id:contextfolderId
});

//root.on('contextmenu', treeContext );
tree.setRootNode(root);
tree2.setRootNode(root2);

root.expand(false, true);
//root2.expand(false, true);

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
        url: baseuri+'?module=filemanager2&action=getFolderContent'
    })
});

function renderIcon(value, p, record)
{
    return String.format('<b>{0}</b>' ,record.data.fileicon);
}

var toolBar = new Ext.Toolbar({
    items:[upButton, dlButton, rnmButton, dltButton]
});

var sm2 = new Ext.grid.RowSelectionModel({
    listeners: {
        // On selection change, set enabled state of the removeButton
        selectionchange: function(sm) {
            dlButton.disable();
            rnmButton.disable();

            if (sm.getCount()) {
                if (sm.getCount() == 1){
                    dlButton.enable();
                    rnmButton.enable();
                }
                dltButton.enable();
            } else {
                dltButton.disable();
            }
        }
    }
});

var cm = new Ext.grid.ColumnModel([
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
]);

var dirbrowser = new Ext.grid.GridPanel({
    region: 'center',
    id: 'center-panel',
    margins: '0 0 0 5',
    layout: 'fit',
    tbar: toolBar,
    ds: datastore,
    loadMask: true,
    sm: sm2,
    viewConfig: {
        emptyText: 'No Files found'
    },
    cm: cm
});

var gsm = dirbrowser.getSelectionModel();
gsm.on('rowselect', handleRowClick );
gsm.on('selectionchange', handleRowClick );
dirbrowser.on('rowcontextmenu', rowContextMenu);
dirbrowser.on('rowdblclick', rowContextMenu);
dirbrowser.on('celldblclick', rowContextMenu);

var membar = new Ext.ux.StatusBar({
    defaultText: 'Free Space: '+usrQuotas,
    id: 'basic-statusbar'
});

var membar2 = new Ext.ux.StatusBar({
    defaultText: 'Free Space: '+cnxtQuotas,
    id: 'basic-statusbar2'
});

var westpanel = new Ext.Panel({
		id:'westpanel',
        region: 'west',
		title: 'My Files',
        width: 200,
        minSize: 175,
        maxSize: 400,
        collapsible: true,
        margins: '0 0 0 0',
        layout: {
            type: 'accordion',
            animate: true
        },
        items: [{
            title: 'My Files',
            border: true,
            items: [tree],
            bbar: membar
        }]
});

var viewport = new Ext.Panel({
    id:'main',
    el:'mainpanel',
    layout: 'border',
    width: "80%",
    height: 350,
    title:'File Manager',
    items: [
    	westpanel,
		dirbrowser
	]
});
var contexttree = new Ext.Panel({
	title: 'Course Files',
    border: true,	
    items: [tree2],
    bbar: membar2
});

function doUpload(){
    winup = new Ext.Window({
        layout:'fit',
        width:400,
        height:200,
        closeAction:'hide',
        plain: true,
        items: [uploadform]
    });
    winup.show(this);
}
function handleRowClick(sm, rowIndex) {
    var selections = sm.getSelections();
    return true;
}
function rowContextMenu(grid, rowIndex, e, f) {
    if( typeof e == 'object') {
        e.preventDefault();
    } else {
        e = f;
    }
    gsm.clickedRow = rowIndex;
    var selections = gsm.getSelections();
    if( selections.length > 1 ) {
        gridCtxMenu.items.get('gc_delete').enable();
        gridCtxMenu.items.get('gc_rename').disable();
        gridCtxMenu.items.get('gc_download').disable();

    } else if(selections.length == 1) {
        gridCtxMenu.items.get('gc_delete').enable();
        gridCtxMenu.items.get('gc_rename').enable();
        gridCtxMenu.items.get('gc_download').enable();
    }
    gridCtxMenu.show(e.getTarget(), 'tr-br?' );

}

function treeContext(node, e ) {
    // Select the node that was right clicked
    node.select();
    // Unselect all files in the grid
    dirbrowser.getSelectionModel().clearSelections();

    treeCtxMenu.items.get('tc_deletefolder').disable();
    //treeCtxMenu.items.get('remove')[node.attributes.is_deletable ? 'enable' : 'disable']();
    //treeCtxMenu.items.get('chmod')[node.attributes.is_chmodable ? 'enable' : 'disable']();

    treeCtxMenu.node = node;
    treeCtxMenu.show(e.getTarget(), 't-b?' );

}

function doDownloadFile()
{
    filepath = encodeURIComponent(dirbrowser.getSelectionModel().getSelected().get('filepath'));
    var path = uri+'usrfiles/'+filepath.replace(/%2F/g, "/");
    window.open(path,'Download');
}

//method that removes files from a folder
function doRemoveFiles()
{	
    Ext.MessageBox.confirm('Delete Files', "Are you sure you want to delete the selected File(s)?", function(btn, text){
        if (btn == 'yes')
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
                    datastore.load({
                        params:{
                            id:selectedfolder
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
    })
}

function viewFile()
{

    //requestParams = getRequestParams();
    //requestParams.action = action;
						
    var dialog = new Ext.LayoutDialog("action-dlg", {
        autoCreate: true,
        modal:true,
        width:600,
        height:400,
        shadow:true,
        minWidth:300,
        minHeight:300,
        proxyDrag: true,
        resizable: true,
        //animateTarget: typeof caller.getEl == 'function' ? caller.getEl() : caller,
        title: 'View File',
        center: {
            autoScroll:true
        }
    });
	dialog.addKeyListener(27, dialog.hide, dialog);
	dialog_panel = new Ext.ContentPanel('dialog-center', {
							autoCreate: true,
							fitToFrame: true
						});
	/*dialog_panel.load( { url: '<?php echo basename($GLOBALS['script_name']) ?>', 
						params: Ext.urlEncode( requestParams ),
						scripts: true,
						callback: function(oElement, bSuccess, oResponse) {
									if( oResponse && oResponse.responseText ) {
									try{ json = Ext.decode( oResponse.responseText );
										if( json.error != '' && typeof json.error != 'xml' ) {													
											Ext.Msg.alert( '<?php echo ext_Lang::err('error', true ) ?>', json.error );
											dialog.destroy();
										}
									} catch(e) {}
									}
								}
					});*/
    var layout = dialog.getLayout();
    layout.beginUpdate();
    layout.add('center', dialog_panel );
    //layout.add('south', dialog_status );
    layout.endUpdate();

    dialog.on( 'hide', function() {
        dialog.destroy(true);
    } );

    dialog.show();

}

function deleteDir(node)
{
    Ext.MessageBox.confirm('Delete Folder', "Are you sure you want to delete the selected folder and it's content?", function	(btn, text){
        if (btn == 'yes')
        {
            //post to server
            Ext.Ajax.request({
                url: baseuri,
                method: 'POST',
                params: {
                    module: 'filemanager2',
                    action: 'deleteFolder',
                    id: node.id
                },
                success: function(response) {
                    tree.getNodeById(defId).select();
                    node.remove();
                },
                failure: function(xhr,params) {
                }
            });
        }
    })
}

function createNewDir(node){

    var treeEditor =  new Ext.tree.TreeEditor(tree, {
        allowBlank:false
        ,
        cancelOnEsc:true
        ,
        completeOnEnter:true
        ,
        ignoreNoChange:true
        ,
        selectOnFocus:true
    });
    var newNode;

    // get node to append the new directory to
    var appendNode = node.isLeaf() ? node.parentNode : node;

    // create new folder after the appendNode is expanded
    appendNode.expand(false, false, function(n) {
        // create new node
        newNode = n.appendChild(new Ext.tree.AsyncTreeNode({
            text:'New Folder',
            iconCls:'folder'
        }));

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
                            treeEditor.destroy();
                        }
                        else{
                            newNode.setId(jsonData.data);
                            treeEditor.destroy();
                        }
                    },
                    failure: function(xhr,params) {
                    }
                });

            }, this, true
            );

        // start editing after short delay
        (function(){
            treeEditor.triggerEdit(newNode);
        }.defer(10));
    // expand callback needs to run in this context
    }.createDelegate(this));
}

Ext.onReady(function(){
    //Check if course is entered
	if(contextId)
    {
		var col1 = Ext.getCmp('westpanel').add(contexttree);
        col1.doLayout();
    }
	//render mainpanel
	viewport.render();
});
