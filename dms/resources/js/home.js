var currentPath,
    createFolderUrl,
    renameFolderUrl,
    deleteFolderUrl,
    uploadUrl,
    uploadWindow;
function showHome(dataurl,xcreateFolderUrl, xrenameFolderUrl,xdeleteFolderUrl, xuploadUrl){
    createFolderUrl=xcreateFolderUrl;
    renameFolderUrl = xrenameFolderUrl;
    deleteFolderUrl = xdeleteFolderUrl;
    uploadUrl = xuploadUrl;
    var Tree = Ext.tree;
    var detailsText = '<center><h1>File manager 0.1 beta</h1> &copy;2010.\n\
<ul>\n\
<li>Getting Started</li>\n\
</ul>\n\
</center>';
    var tpl = new Ext.Template(
        
        '<fieldset style="margin-left:10px;margin-right:10px;">',
        '<legend >File details</legend>',
        '<p><h1 style="margin-left:10px;margin-right:10px;">Ref No: {refno}</h1></p>',
        '<h2 class="title" style="margin-left:10px;margin-right:10px;">{title}</h2>',
        '<p><b style="margin-left:10px;margin-right:10px;">Name</b>: {text}</p>',
        '<p><b style="margin-left:10px;margin-right:10px;">Type</b>: {cls}</p>',
        '<p><b style="margin-left:10px;margin-right:10px;">Last modified</b>: {lastmodified}</p>',
        '<p><b style="margin-left:10px;margin-right:10px;">Size</b>: {size}</p>',
        '<font style="margin-left:10px;margin-right:10px;"><a href={downloadurl}>Download<img src="{downloadimgurl}"></a>&nbsp;&nbsp;<a href={deleteurl}>Delete<img src="{deleteimgurl}"></a></font>',

        '</fieldset>'
   
        );
    tpl.compile();
    var tb = new Ext.Toolbar();
    tb.render('toolbar');

    tb.add({
        text:'Settings',
        iconCls: 'settings',
        handler: function() {
            alert('Settings!!');
        }

    });

    tb.add({
        text:'Help',
        iconCls: 'helpimg',
        handler: function() {
            alert('Help!!');
        }
           
    });

    tb.doLayout();

    var tree = new Tree.TreePanel({
        useArrows: true,
        autoScroll: true,
        animate: true,
        enableDD: false,
        containerScroll: true,
        border: false,
        // auto create TreeLoader
        dataUrl: dataurl,
        listeners: {
            'render': function(tp){
                tp.getSelectionModel().on('selectionchange', function(tree, node){
                    currentPath=node.attributes.parent;
                    if(currentPath == null){
                        currentPath="/";
                    }else{
                        currentPath+="/"+node.text;
                    }
                    
                   
                    detailsText = '<fieldset style="margin-left:10px;margin-right:10px;"><hr/>\n\
<legend >Folder details</legend>                      \n\
 <b style="margin-left:10px;margin-right:10px;">Path:</b>'+currentPath+'\n\
<p><b style="margin-left:10px;margin-right:10px;">Last modified</b>: '+node.attributes.lastmodified+'</p>                        \n\
<font style="padding: 10px;"\n\
                        <a href="#" onClick="showUploadForm();return false;">Upload Document</a>\n\
                        &nbsp;&nbsp;\n\
                        <a href="#" onClick="createFolder();return false;">Create folder</a>\n\
&nbsp;&nbsp;\n\
<a href="#" onClick="accessRights();return false;">Access rights</a> \n\
</font>\n\
                        </fieldset>';
                    var el = Ext.getCmp('details-panel').body;
                    if(node && node.leaf){
                        tpl.overwrite(el, node.attributes);
                    }else{
                        el.update(detailsText);
                    }
                })
            }

        },

        root: {
            nodeType: 'async',
            text: 'Folders/Files',
            draggable: false,
            id: '/'
        }
    });
    tree.on('contextmenu', function(node){
       
        if(node && node.leaf){
        //do nothing
        }else{
            //Set up some buttons
            var createFolder = new Ext.menu.Item({
                text: "New Folder",
                iconCls: 'folderadd',
                handler: function() {
                    var name = prompt( "Please enter folder name:");
                    if (name != '' && name != null) {
                        window.location.href=createFolderUrl+"&foldername="+name+"&folderpath="+currentPath;
                    }
                }
            });
            var renameFolder = new Ext.menu.Item({
                text: "Rename",
                handler: function() {
                    var name = prompt( "Please enter folder name:");
                    if (name != '' && name != null) {
                        window.location.href=renameFolderUrl+"&foldername="+name+"&folderpath="+currentPath;
                    }
                }
            });
            var deleteFolder = new Ext.menu.Item({
                text: "Delete",
                iconCls: 'delete',
                handler: function() {
                    Ext.Msg.show({
                       title:'Delete Folder?',
                       msg: 'Are you sure you want to delete this folder?',
                       buttons: Ext.Msg.YESNO,
                       fn: deletefolder
                    });
                }
            });
            //Create the context menu to hold the buttons
            var contextMenu = new Ext.menu.Menu();
            contextMenu.add(createFolder, renameFolder,deleteFolder);


            //Show the menu
            contextMenu.show(node.ui.getAnchor());
        }
    });
    tree.getRootNode().expand();

    var detailsPanel = new Ext.Panel({
        region: 'center',
        margins:'3 3 3 0',
        id: 'details-panel',
        defaults:{
            autoScroll:true
        },
        html: detailsText
    });

    // Panel for the west
    var nav = new Ext.Panel({
        title: 'Navigation',
        region: 'west',
        split: true,
        width: 200,
        collapsible: true,
        margins:'3 0 3 3',
        cmargins:'3 3 3 3',
        items:[tree]
    });
    // buttons
    var p = new Ext.Panel({
        layout: 'border',
        autoWidth: true,
        style: 'marginRight: 10px',
        baseCls: 'x-plain',
        cls: 'btn-panel',
        border: false,
        
        height:600,
        //border:false,
        plain:true,
        items: [nav, detailsPanel]

    });
    p.render("mainContent");
}

function createFolder(){
    Ext.MessageBox.prompt('New folder', 'Please enter folder name', handleCreateFolder);

}

function handleCreateFolder(btn, text){
    if(btn == 'ok'){
        window.location.href=createFolderUrl+"&foldername="+text+"&folderpath="+currentPath;
    }
}

function deletefolder(btn, text) {
    if(btn == 'yes') {
        window.location.href=deleteFolderUrl+"&folderpath="+currentPath;
    }
}

function accessRights(){
    
}

function showUploadForm(){
    var fibasic = new Ext.ux.form.FileUploadField({
        id: 'form-file',
        emptyText: 'Select a file',
        fieldLabel: 'File',
        name: 'filename',
        width: 300
    });
    var type= [
    ['Private'],
    ['Public']

    ];

    var typestore = new Ext.data.ArrayStore({
        fields: ['type'],
        data : type
    });

    var typefield = new Ext.form.ComboBox({
        store: typestore,
        displayField:'type',
        fieldLabel:'Access type:',
        typeAhead: true,
        mode: 'local',
        editable:false,
        forceSelection: true,
        triggerAction: 'all',
        emptyText:'Select access...',
        selectOnFocus:true,
        allowBlank:false,
        name : 'permissions'

    });

    if(!uploadWindow){
        uploadWindow = new Ext.Window({
            applyTo:'upload-win',
            width:500,
            height:250,
            x:125,
            y:50,
            closeAction:'destroy',
            plain: true,
            labelWidth: 155,
            items: [{
                xtype: 'fieldset',
                title: 'Upload new document',
                autoHeight: true,
                bodyStyle:'margin-left:1em;margin-top:1em;',
                items:[
                fibasic,typefield

                ]
            }],
            buttons: [{
                text:'Upload',
                handler: function(){
                    var v = fibasic.getValue();
                    alert('Selected File', v && v != '' ? v : 'None');
                    /*if(fp.getForm().isValid()){
	                if (fp.url) {
                            fp.getForm().getEl().dom.action = fp.url + '&path=' + currentPath;
                        }
                        fp.getForm().submit();
                    }*/
                }
            },{
                text: 'Cancel',
                handler: function(){
                    uploadWindow.hide();
                }
            }]
        });
    }
    uploadWindow.show(this);
}