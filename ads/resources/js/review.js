/*!
 * Ext JS Library 3.0.0
 * Copyright(c) 2006-2009 Ext JS, LLC
 * licensing@extjs.com
 * http://www.extjs.com/license
 */
Ext.onReady(function(){

    Ext.QuickTips.init();
    
    Ext.form.Field.prototype.msgTarget = 'side';

    var radioGroup = {
        labelWidth: 75, // label settings here cascade unless overridden
        url:'save-form.php',
        frame:true,
        bodyStyle:'padding:5px 5px 0',
        width: 500,
        defaults: {width: 480},
        defaultType: 'textarea',

        items: [{
                //fieldLabel: 'Comments',
                name: 'title'
                //allowBlank:false

                }]
    };
    var id = document.getElementById('id').value;
    
    // combine all that into one huge form
    var fp = new Ext.FormPanel({
        standardSubmit: true,
        url: 'index.php?module=ads&action=savecoursereview',
        title: 'Review the course',
        frame: true,
        labelWidth: 110,
        width: 600,
        renderTo:'form-ct',
        bodyStyle: 'padding:0 0 0;',
        items: [
            radioGroup
        ],
        buttons: [{
            text: 'Save',
            handler: function(){
                if (fp.getForm().isValid()) {
                    if (fp.url)
                        fp.getForm().getEl().dom.action = fp.url + "&id=" + id;
                    
                    fp.getForm().submit();
                }   
            }
        },{
            text: 'Reset',
            handler: function(){
                fp.getForm().reset();
            }
        }]
    });
});