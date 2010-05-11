/* 
 * This is the main file that contains the mainpanel as well as most global
 * declarations
 */

var node1;
var filepath;
var fileid;
var filename;
var winup;
var selectedfolder;
var fp;
Ext.QuickTips.init();

var loadingMask = new Ext.LoadMask(Ext.getBody(), {
    msg:"Please wait..."
});

var viewport = new Ext.Panel({
    id:'main',
    el:'filemanager2-mainpanel',
    layout: 'border',
    width: "80%",
    height: 600,
    containerScroll: true,
    title:'File Manager',
    items: [
    {
        region: 'west',
        width: 200,
        frame:false,
        split:true,
        autoScroll: true,
        minSize: 175,
        maxSize: 400,
        collapsible: true,
        margins: '0 0 0 5',
        items: [contextTree,myFilesTree]
    },dirbrowser,filesDetailsPanel]
});

function createNewDir(text,tree){
    try{
        folderContextMenu.hide(true);
    }catch(err){}

    try{
    //   myFilesContextMenu.hide(true);
    }catch(err){}
    var treeEditor =  new Ext.tree.TreeEditor(tree, {
        allowBlank:false
        ,
        cancelOnEsc:true
        ,
        completeOnEnter:true
        ,
        ignoreNoChange:true
        ,
        selectOnFocus:false
    });
    var newNode;

    // get node to append the new directory to
    var appendNode = node1.isLeaf() ? node1.parentNode : node1;

    // create new folder after the appendNode is expanded
    appendNode.expand(false, false, function(n) {
// create new node
        newNode = n.appendChild(new Ext.tree.AsyncTreeNode({
            text:text,
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
    viewport.render();
});
