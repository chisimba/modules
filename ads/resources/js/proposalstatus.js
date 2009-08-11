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
            xtype: 'radiogroup',
            itemCls: 'x-check-group-alt',
            fieldLabel: 'Proposal Status',
            columns: 1,
            items: [
                {boxLabel: 'New', name: 'proposalstatus', inputValue: '0', checked: true},
                {boxLabel: 'APO comment', name: 'proposalstatus', inputValue: '1'},
                {boxLabel: 'Library comment', name: 'proposalstatus', inputValue: '2'},
                {boxLabel: 'Subsidy comment', name: 'proposalstatus', inputValue: '3'},
                {boxLabel: 'Faculty committee', name: 'proposalstatus', inputValue: '4'},
                {boxLabel: 'Faculty', name: 'proposalstatus', inputValue: '5'},
                {boxLabel: 'APDC', name: 'proposalstatus', inputValue: '6'}
            ]
    };
    var id = document.getElementById('id').value;
    
    // combine all that into one huge form
    var fp = new Ext.FormPanel({
        standardSubmit: true,
        url: 'index.php?module=ads&action=submitproposalstatus',
        title: 'Change the status of the proposal',
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