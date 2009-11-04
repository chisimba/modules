/*!
 * Ext JS Library 3.0.0
 * Copyright(c) 2006-2009 Ext JS, LLC
 * licensing@extjs.com
 * http://www.extjs.com/license
 */
var firstTime = true;

var showCheckBoxes = function(isChecked, url) {

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
                name: 'emailNotification',
                handler: function(e, checked) {
                    check: saveNotification(checked, url)
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

var saveNotification = function(isChecked, url) {
    if(firstTime) {
        firstTime = false;
    }
    else {
        Ext.Ajax.request({
           url: url,
           failure: otherFn,
           params: { emailOption: isChecked }
        });
    }
}

var otherFn = function() {
    Ext.Msg.alert("Submission Failure", "There was an error submitting you request");
}