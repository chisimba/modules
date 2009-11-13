/*!
 * Ext JS Library 3.0.0
 * Copyright(c) 2006-2009 Ext JS, LLC
 * licensing@extjs.com
 * http://www.extjs.com/license
 */
/*
Ext.onReady(function(){
*/
    Ext.QuickTips.init();

    // turn on validation errors beside the field globally
    Ext.form.Field.prototype.msgTarget = 'side';

    var sendmsgFormPanel = new Ext.FormPanel({
        labelWidth: 75, // label settings here cascade unless overridden
        url:baseUri+'?module=liftclub&action=sendmessage',
        frame:true,
        title: 'Send Message Form',
        bodyStyle:'padding:5px 5px 0',
        width: 350,
        defaults: {width: 230},
        defaultType: 'textfield',
        items: [{
                fieldLabel: 'Title',
                name: 'msgtitle',
                allowBlank:false
            },{
                fieldLabel: 'Message',
                name: 'msgbody',
                allowBlank:false
            })
        ],

        buttons: [{
            text: 'Save'
        },{
            text: 'Cancel'
        }]
    });
/*
    sendmsgFormPanel.render(document.body);
});
*/
