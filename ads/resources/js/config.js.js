/*!
 * Ext JS Library 3.0.0
 * Copyright(c) 2006-2009 Ext JS, LLC
 * licensing@extjs.com
 * http://www.extjs.com/license
 */
Ext.onReady(function(){

    Ext.QuickTips.init();

    // turn on validation errors beside the field globally
    Ext.form.Field.prototype.msgTarget = 'side';

    checked = Ext.get('isChecked');
    showCheckBoxes(checked);
});

var firstTime = true;

var showCheckBoxes = function(isChecked) {

    var individual = [{
        bodyStyle: 'padding-right:5px;',
        items: {
            xtype: 'fieldset',
            title: 'Email Notifications',
            autoHeight: true,
            defaultType: 'checkbox',
            items: {
                checked: isChecked,
                fieldLabel: 'Turn Email Notifications on/off',
                labelSeparator: '',
                boxLabel: '',
                name: 'email-not-on-off',
                handler: function(e, checked) {
                    check: saveNotification(checked, isChecked)
                }
            }
        }
    }];

    // combine all that into one huge form
    var fp = new Ext.FormPanel({
        title: 'Configurations',
        frame: true,
        labelWidth: 200,
        width: 600,
        renderTo:'config',
        bodyStyle: 'padding:0 10px 0;',
        items: [
            {
                layout: 'column',
                border: false,
                // defaults are applied to all child items unless otherwise specified by child item
                defaults: {
                    columnWidth: '.5',
                    border: false
                },
                items: individual
            }
        ]
    });

}

var goBack = function() {
    window.location.href = 'http://localhost/chisimba/app/index.php?module=ads';
}

var saveNotification = function(isChecked) {
    if(firstTime) {
        firstTime = false;
    }
    else {
        alert(isChecked);
        // save this value into the database using ajax :-)
    }
}
