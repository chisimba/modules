/**
 * this script contains methods for manipulating context files folder. For My Files
 * folder, see myfiles.js
 */

var ContextTree = Ext.tree;
var contextTree;
var contextUrl=baseuri+'?module=filemanager2&action=getContextDirectory';
var newContextFolderMenuItem = new Ext.menu.Item({
    text: "New Folder",
    disabled: true,
    iconCls: 'sexy-newf',
    handler: function(){

        createNewDir('New Folder',contextTree);
    }
});

var renameContextFolderMenuItem = new Ext.menu.Item({
    disabled: true,
    text: "Rename",
    iconCls: 'sexy-pencil',
    handler: function(){
        renameContextDir(node1);
    }

});
var deleteContextFolderMenuItem = new Ext.menu.Item({
    disabled: true,
    iconCls: 'sexy-deletef',
    text: "Delete",
     handler: function(){
        deleteContextDir(node1);
    }
});
contextTree = new ContextTree.TreePanel({
    animate:true,
    autoScroll:false,
    loader: new ContextTree.TreeLoader({
        dataUrl: contextUrl
    }),
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
            tp.getSelectionModel().on('selectionchange', function(tree2, node){
                node1 = node;
                
                upButton.enable();
                newContextFolderMenuItem.enable();
                renameContextFolderMenuItem.enable();
                deleteContextFolderMenuItem.enable();
                selectedfolder = node.id;
                datastore.load({
                    params:{
                        id:selectedfolder,
                        contextid:contextId
                    }
                });
            })
        }
    }

});


var folderContextMenu = new Ext.menu.Menu();
contextTree.on('contextmenu', function(node){
    folderContextMenu.add(newContextFolderMenuItem,renameContextFolderMenuItem, deleteContextFolderMenuItem);
    folderContextMenu.show(node.ui.getAnchor());
});

new ContextTree.TreeSorter(contextTree, {
    folderSort:true
});

// set the root node
var contextRoot = new ContextTree.AsyncTreeNode({
    text: 'Course Files ('+cnxtQuotas+')',
    draggable:false, // disable root node dragging
    id:contextId

});


contextTree.setRootNode(contextRoot);
contextRoot.expand(false, /*no anim*/ false);
function deleteContextDir(node)
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
                    contextTree.getNodeById(contextId).select();
                    node.remove();
                },
                failure: function(xhr,params) {
                }
            });
        }
    })
}



function renameContextDir(node)
{
        var treeEditor =  new Ext.tree.TreeEditor(contextTree, {
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




