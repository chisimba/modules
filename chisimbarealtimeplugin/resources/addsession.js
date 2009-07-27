Ext.onReady(function(){
    var win;
    var button = Ext.get('add-session');
    var input=Ext.get('submit-url');
    var submitUrl=input.value;
    var timefromstore = new Ext.data.ArrayStore({
        fields: ['timefrom'],
        data : Ext.datedata.hours // from states.js
    });
    var timeFromField = new Ext.form.ComboBox({
        store: timefromstore,
        displayField:'timefrom',
        fieldLabel:'Time',
        typeAhead: true,
        mode: 'local',
        editable:false,
        forceSelection: true,
        triggerAction: 'all',
        emptyText:'Select time from...',
        selectOnFocus:true,

    });
    var timetostore = new Ext.data.ArrayStore({
        fields: ['timeto'],
        data : Ext.datedata.hours // from states.js
    });
    var timeToField = new Ext.form.ComboBox({
        store: timetostore,
        displayField:'timeto',
        fieldLabel:'Time',
        typeAhead: true,
        mode: 'local',
        forceSelection: true,
        editable:false,
        triggerAction: 'all',
        emptyText:'Select time to...',
        selectOnFocus:true,
    });
    var startDateField=new Ext.form.DateField(
    {
        fieldLabel:'Start',
        emptyText:'Select start date ...',
        width: 200,
        allowBlank:false,
        editable:false
    }
    );
    var endDateField=new Ext.form.DateField(
    {
        fieldLabel:'End',
        emptyText:'Select end date...',
        width: 200,
        allowBlank:false,
        editable:false
    }
    );
    var form = new Ext.form.FormPanel({
        baseCls: 'x-plain',
        labelWidth: 55,
        bodyStyle:'padding:5px 5px 0',
        standardSubmit: true,
        url:submitUrl,
        defaultType: 'textfield',

        items: [{
            fieldLabel: 'Title',
            name: 'to',
            allowBlank:false,
            anchor:'100%'  // anchor width by percentage
        },

        startDateField,timeFromField, endDateField,timeToField,
        {
            xtype: 'textarea',
            fieldLabel: 'Desc',
            name: 'msg',
            anchor: '100% -53'  // anchor width by percentage and height by raw adjustment
        }]
    });



    button.on('click', function(){
        // create the window on the first click and reuse on subsequent clicks
        alert(submitUrl);
        if(!win){
            win = new Ext.Window({
                applyTo:'addsession-win',
                layout:'fit',
                width:500,
                height:350,
                x:250,
                y:150,
                closeAction:'hide',
                plain: true,

                items: form,

                buttons: [{
                    text:'Save',
                    handler: function(){
                        if (form.url)
                            form.getForm().getEl().dom.action = form.url;

                        form.getForm().submit();
                    }
                },{
                    text: 'Close',
                    handler: function(){
                        win.hide();
                    }
                }]
            });
        }
        win.show(this);
    });
});