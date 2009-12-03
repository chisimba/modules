/*!
 * Ext JS Library 3.0.0
 * Copyright(c) 2006-2009 Ext JS, LLC
 * licensing@extjs.com
 * http://www.extjs.com/license
 */

var typeURL;

Ext.onReady(function(){
    Ext.QuickTips.init();

    typeURL = Ext.get('typeURL').dom.value;
    showButtons();
    showForm();
    showTypeForm();
});

var showButtons = function() {
    // buttons
    var p = new Ext.Panel({
        layout: 'table',
        autoWidth: true,
        style: 'marginRight: 10px',
        baseCls: 'x-plain',
        cls: 'btn-panel',
        border: false,
        defaultType: 'button',
        id: 'upload-button',
        items: [{
            text: 'Add Type',
            scale: 'medium',
            baseCls: 'x-plain',
            cls: 'btn-panel',
            handler: function() {
                goAddType();
            }
        }]
    });
    p.render("buttons");
}

var showForm = function() {
    Ext.namespace('Ext.exampledata');

    Ext.exampledata.states = [
        ['odt', 'Open Office Document'],
    ];

    var store = new Ext.data.ArrayStore({
        fields: ['filetype', 'filevalue'],
        data : Ext.exampledata.states
    });

    var combo = new Ext.form.ComboBox({
        store: store,
        fieldLabel: 'Permitted Types',
        displayField:'filevalue',
        valueField: 'filetype',
        typeAhead: true,
        mode: 'local',
        forceSelection: true,
        triggerAction: 'all',
        emptyText:'Select permitted type...',
        selectOnFocus:true
    });

    var simple = new Ext.FormPanel({
        labelWidth: 100, // label settings here cascade unless overridden
        url:'save-form.php',
        frame:true,
        title: 'Configurations',
        bodyStyle:'padding:5px 5px 0',
        width: 500,
        defaults: {width: 230},

        items: [
            combo
        ],

        buttons: [{
            text: 'Save'
        },{
            text: 'Cancel'
        }]
    });

    simple.render('config');
}

var goAddType = function() {
    var win;

    var myForm = new Ext.FormPanel({
        standardSubmit: true,
        labelWidth: 125,
        url: typeURL,
        frame:true,
        title: 'Add  New File Type',
        bodyStyle:'padding:5px 5px 0',
        width: 350,
        defaults: {width: 230},
        defaultType: 'textfield',

        items: [{
                fieldLabel: 'Title',
                name: 'title',
                id: 'input_title',
                allowBlank: false
            }
        ]

    });

    if(!win){
        win = new Ext.Window({
            applyTo:'addtype-win',
            layout:'fit',
            autoWidth: true,
            autoHeight:true,
            closeAction:'hide',
            plain: true,
            items: myForm,

            buttons: [{
                text: 'Save',
                handler: function(){
                    if (myForm.url)
                        myForm.getForm().getEl().dom.action = myForm.url;

                    myForm.getForm().submit();
                }
            },{
                text: 'Cancel',
                handler: function(){
                    win.hide();
                }
            }]
        });
    }
    win.show(this);
}

var showTypeForm = function() {
    var simple = new Ext.FormPanel({
        labelWidth: 100, // label settings here cascade unless overridden
        url:'typeURL',
        frame:true,
        title: 'Configurations',
        bodyStyle:'padding:5px 5px 0',
        width: 500,
        defaults: {width: 230},

        items: [
            combo
        ],

        buttons: [{
            text: 'Save'
        },{
            text: 'Cancel'
        }]
    });
}