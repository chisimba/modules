var currentPath;
var createFolderUrl;
var uploadWindow;
function showHome(dataurl,xcreateFolderUrl){
    createFolderUrl=xcreateFolderUrl;
    var Tree = Ext.tree;
    var detailsText = '<i>Folder actions</i><hr/><a href="#" onClick="createFolder();return false;">Create folder</a>';
    var tpl = new Ext.Template(
        '<fieldset>',
        '<legend style"margin-left:10px;">File details</legend>',
        '<p><h1>Ref No: {refno}</h1></p>',
        '<h2 class="title">{title}</h2>',
        '<p><b>Name</b>: {text}</p>',
        '<p><b>Type</b>: {cls}</p>',
        '<p><b>Last modified</b>: {lastmodified}</p>',
        '<p><b>Size</b>: {size}</p>',
        '<a href={downloadurl}>Download<img src="{downloadimgurl}"></a>&nbsp;&nbsp;<a href={deleteurl}>Delete<img src="{deleteimgurl}"></a>',

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
                    
                   
                    detailsText = '<i>Folder actions</i><hr/><h1>'+currentPath+'</h1><a href="#" onClick="showUploadForm();return false;">Upload Document</a>&nbsp;&nbsp;<a href="#" onClick="createFolder();return false;">Create folder</a>';
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


function showUploadForm(){
    var fibasic = new Ext.ux.form.FileUploadField({
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
        name : 'typefield'

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