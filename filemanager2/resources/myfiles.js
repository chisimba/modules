/**
 * This script has functions for My Files folder. See context.js for functions for
 * context files folder
 */


var MyFilesTree = Ext.tree;
var newIndex = 3;
var myFilesTree;

var myFilesUrl=baseuri+'?module=filemanager2&action=getDirectory';
var myFilesLoader= new MyFilesTree.TreeLoader({
        dataUrl: myFilesUrl
    });

var deleteMyFilesFolderMenuItem = new Ext.menu.Item({
    disabled: true,
     iconCls: 'sexy-deletef',
    text: "Delete",
     handler: function(){
        deleteMyFilesDir(node1);
    }
});

var newMyFilesFolderMenuItem = new Ext.menu.Item({
    text: "New Folder",
    disabled: true,
    iconCls: 'sexy-newf',
    handler: function(){

        createNewDir('New Folder', myFilesTree);
    }
});

var renameMyFilesFolderMenuItem = new Ext.menu.Item({
    disabled: true,
    text: "Rename",
    iconCls: 'sexy-pencil',
    handler: function(){
        renameMyFilesDir(node1);
    }

});


myFilesTree = new MyFilesTree.TreePanel({
    animate:true,
    autoScroll:true,
    loader:myFilesLoader,
    enableDD:true,
    containerScroll: true,
    border: false,
    width: 250,
    height: "100%",
    dropConfig: {
        appendOnly:true
    },
    listeners: {
        'render': function(tp){
            tp.getSelectionModel().on('selectionchange', function(tree, node){
                node1 = node;
                contextId="";
                upButton.enable();
                newMyFilesFolderMenuItem.enable();
                renameMyFilesFolderMenuItem.enable();
                deleteMyFilesFolderMenuItem.enable();
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

var myFilesContextMenu = new Ext.menu.Menu();
    myFilesTree.on('contextmenu', function(node){
    myFilesContextMenu.add(newMyFilesFolderMenuItem,renameMyFilesFolderMenuItem, deleteMyFilesFolderMenuItem);
    myFilesContextMenu.show(node.ui.getAnchor());
});

new MyFilesTree.TreeSorter(myFilesTree, {
    folderSort:true
});


// set the root node
var myFilesRoot = new MyFilesTree.AsyncTreeNode({
    text: 'My Files ('+usrQuotas+')',
    draggable:false, // disable root node dragging
    id:myFilesId

});


myFilesTree.setRootNode(myFilesRoot);
myFilesRoot.expand(false, /*no anim*/ false);

function deleteMyFilesDir(node)
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
                    myFilesTree.getNodeById(defId).select();
                    node.remove();
                },
                failure: function(xhr,params) {
                }
            });
        }
    })
}


function renameMyFilesDir(node)
{
        var treeEditor =  new Ext.tree.TreeEditor(myFilesTree, {
        allowBlank:false,
        cancelOnEsc:true,
        completeOnEnter:true,
        ignoreNoChange:true,
        selectOnFocus:false
    });
    // create new folder after the appendNode is expanded
    node.expand(false, false, function(n) {
     // setup one-shot event handler for editing completed
        treeEditor.on("complete",
            function(o,newText,oldText){
            //post to server
            Ext.Ajax.request({
                url: baseuri,
                method: 'POST',
                params: {
                    module: 'filemanager2',
                    action: 'renameFolder',
                    id: node.id,
                    newname:newText
                },
                success: function(response) {
                   },
                failure: function(xhr,params) {
                }
            });
            }, this, true
            );

        // start editing after short delay
        (function(){
            treeEditor.triggerEdit(n);
        }.defer(1));
    // expand callback needs to run in this context
    }.createDelegate(this));

}


