/**
 * This class handles functionality for filebrowser
 */

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
    items:[upButton, dlButton, dltButton]
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
                    showFileInfo();
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
var filesDetailsPanel = {
    id: 'file-details-panel',
    title: 'Details',
    height:220,
    split:true,
    region: 'south',
    bodyStyle: 'padding-bottom:15px;background:#eee;',
    autoScroll: true,
    html: '<p class="details-info">When you select a file from the grid, additional details will display here.</p>'
};
var dirbrowser = new Ext.grid.GridPanel({
    region: 'center',
    id: 'center-panel',
    margins: '0 0 0 5',
    layout: 'fit',
    tbar: toolBar,
    ds: datastore,
    loadMask: true,
    sm: sm2,
    autoScroll: true,
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
            loadingMask.show();
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
                    loadingMask.hide();
                },
                failure: function(xhr,params) {
                    alert('Failure!\n'+xhr.responseText);
                    loadingMask.hide();
                }
            });
        }
    })
}


function showFileInfo()
{
    loadingMask.show();
    //get the selected files
    var selArr = dirbrowser.getSelectionModel().getSelections();

    //get the selected id's
    var idString = "";

    Ext.each( selArr, function( r )
    {
        idString = r.id ;
    });

    //post to server
    Ext.Ajax.request({
        url: baseuri,
        method: 'POST',
        params: {
            module: 'filemanager2',
            action: 'json_viewfile',
            id: idString
        },
        success: function(xhr,params) {
            var bd = Ext.getCmp('file-details-panel').body;
            bd.update(xhr.responseText);
            
            loadingMask.hide();
        },
        failure: function(xhr,params) {
            alert('Failure!\n'+xhr.responseText);
            loadingMask.hide();
        }
    });

}

function viewFile()
{

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


