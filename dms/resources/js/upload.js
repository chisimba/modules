var showUploadForm = function(myUrl) {
    var fp = new Ext.FormPanel({
        standardSubmit: true,
        url: myUrl,
        renderTo: 'fi-form',
        fileUpload: true,
        width: 500,
        frame: true,
        title: 'File Upload Form',
        autoHeight: true,
        bodyStyle: 'padding: 10px 10px 0 10px;',
        labelWidth: 50,
        defaults: {
            anchor: '95%',
            allowBlank: false,
            msgTarget: 'side'
        },
        items: [{
            xtype: 'fileuploadfield',
            id: 'form-file',
            emptyText: 'Select a file',
            fieldLabel: 'File',
            name: 'filename'
        }],
        buttons: [{
            text: 'Save',
            handler: function(){
                if(fp.getForm().isValid()){
	                if (fp.url) {
                            fp.getForm().getEl().dom.action = fp.url;
                        }
                        fp.getForm().submit();
                }
            }
        },{
            text: 'Cancel',
            handler: function(){
                //fp.getForm().;
            }
        }]
    });
}


