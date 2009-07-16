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


    /*====================================================================
     * Individual checkbox/radio examples
     *====================================================================*/
    
    // Using checkbox/radio groups will generally be easier and more flexible than
    // using individual checkbox and radio controls, but this shows that you can
    // certainly do so if you only need a single control, or if you want to control  
    // exactly where each check/radio goes within your layout.
    
    /*====================================================================
     * RadioGroup examples
     *====================================================================*/
    // NOTE: These radio examples use the exact same options as the checkbox ones
    // above, so the comments will not be repeated.  Please see comments above for
    // additional explanation on some config options.
    
    var radioGroup = {
            xtype: 'radiogroup',
            itemCls: 'x-check-group-alt',
            fieldLabel: 'Proposal Status',
            columns: 1,
            items: [
                {boxLabel: 'New', name: 'proposalstatus', inputValue: '0', checked: true},
                {boxLabel: 'Under Review', name: 'proposalstatus', inputValue: '1'},
                {boxLabel: 'Accepted', name: 'proposalstatus', inputValue: '2'},
                {boxLabel: 'Rejected', name: 'proposalstatus', inputValue: '3'}
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
                //var O = this.ownerCt;
                if (fp.getForm().isValid()) {
                    if (fp.url)
                        fp.getForm().getEl().dom.action = fp.url + "&id=" + id;
                    /*if (fp.baseParams) {
                        for (i in fp.baseParams) {
                            fp.add({
                                xtype: 'hidden',
                                name: i,
                                value: fp.baseParams[i]
                            })
                        }
                        fp.doLayout();
                    }*/
                    fp.getForm().submit();
                }

               /*if(fp.getForm().isValid()){
                   Ext.Msg.alert('Submitted Values', 'The following will be sent to the server: <br />'+
                        fp.getForm().getValues(true).replace(/&/g,', '));
                }*/
            }
        },{
            text: 'Reset',
            handler: function(){
                fp.getForm().reset();
            }
        }]
    });
});