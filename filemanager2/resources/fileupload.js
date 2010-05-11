/**
 * this script builds up the file upload dialog
 */

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
