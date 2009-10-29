function initPersonalDetailsForm(){
    Ext.QuickTips.init();
    ButtonPanel = Ext.extend(Ext.Panel, {
        layout:'table',
        defaultType: 'button',
        baseCls: 'x-plain',
        cls: 'btn-panel',
        menu: undefined,
        split: true,
        bodyStyle:'margin-top:2em;margin-bottom:2em;',
        constructor: function(buttons){
            for(var i = 0, b; b = buttons[i]; i++){
                b.menu = this.menu;
                b.enableToggle = this.enableToggle;
                b.split = this.split;
                b.arrowAlign = this.arrowAlign;
            }
            var items = buttons;

            ButtonPanel.superclass.constructor.call(this, {
                items: items
            });
        }
    });

    ButtonPanel.override({
        renderTo : 'surface'
    });
    var buttons= new ButtonPanel(

        [
        {
            iconCls: 'back',
            text:'Back',
            
            handler: function(){
               
            }
        },
        {
            iconCls: 'next',
            text:'Next',

            handler: function(){

            }
        },
        {
            iconCls: 'saveforlater',
            text:'Save for Later',

            handler: function(){

            }
        },
        {
            iconCls: 'submitfinalapp',
            text:'Submit Final Application',

            handler: function(){

            }
        },
        {
            iconCls: 'cancel',
            text:'Cancel',

            handler: function(){

            }
        }

        ]
   
        );
    var titles= [
    ['Mr.'],
    ['Mrs.'],
    ['Ms.'],
    ['Dr.'],
    ['Prof.'],
    ['Rev.']
    ];

    var titlestore = new Ext.data.ArrayStore({
        fields: ['title'],
        data : titles
    });

    var titlefield = new Ext.form.ComboBox({
        store: titlestore,
        displayField:'title',
        fieldLabel:'Title',
        typeAhead: true,
        mode: 'local',
        editable:false,
        forceSelection: true,
        triggerAction: 'all',
        emptyText:'Select title...',
        selectOnFocus:true,
        anchor: '37.5%',
        name : 'titlefield'

    });

    var gender= [
    ['Male'],
    ['Female']
    ];

    var genderstore = new Ext.data.ArrayStore({
        fields: ['gender'],
        data : gender
    });

    var genderfield = new Ext.form.ComboBox({
        store: genderstore,
        displayField:'gender',
        fieldLabel:'Gender',
        typeAhead: true,
        mode: 'local',
        editable:false,
        forceSelection: true,
        triggerAction: 'all',
        emptyText:'Select gender...',
        selectOnFocus:true,
        name : 'genderfield'

    });
    var personaldetailsform = new Ext.form.FormPanel({
        
        baseCls: 'x-plain',
        width:750,
        labelWidth: 135,
       bodyStyle:'padding: 15px 35px 75px 95px;',
        renderTo: 'surface',
        collapsible: true,
        defaults: {
            width: 750
        },
        layout:'column',

        bodyStyle:'background-color:transparent',
        border:false,
        items:[ {
            xtype: 'fieldset',
            title: 'Personal details',
            bodyStyle:'margin-bottom:2em;',
            bodyStyle:'margin-left:1em;margin-top:1em;',
            autoHeight: true,
            items:[
            buttons,
            new Ext.form.DisplayField({
                value:'<h4>   </h4>'
            }),
            new Ext.form.TextField({
                fieldLabel: 'Last name/surname',
                anchor:'90%',
                allowBlank:false
            }),
            new Ext.form.TextField({
                fieldLabel: 'First name'
            }),
            titlefield,
            new Ext.form.TextField({
                fieldLabel: 'Middle name',
                anchor:'90%',
                allowBlank:false
            }),
            new Ext.form.TextField({
                fieldLabel: 'Preferred first name',
                anchor:'90%',
                allowBlank:false
            }),
            new Ext.form.TextField({
                fieldLabel: 'Date of Birth',
                anchor:'37.5%'
            }),
            genderfield,
            new Ext.form.TextField({
                fieldLabel: 'South African ID Number',
                anchor:'37.5%'
            }),
            {
                xtype: 'fieldset',
                title: 'Name change (if applicable)',
                autoHeight: true,
                bodyStyle:'margin-left:1em;margin-top:1em;',
                items:[
                    new Ext.form.TextField({
                    fieldLabel: 'Previous surname',
                    anchor:'90%',
                    allowBlank:false
                    })
               ]
            }
            ],
            buttons: [
             {
                 iconCls: 'back',
                 text:'Back',

                 handler: function(){

                 }
             },
             {
                 iconCls: 'next',
                 text:'Next',

                 handler: function(){

                 }
             },
             {
                 iconCls: 'saveforlater',
                 text:'Save for Later',

                 handler: function(){

                 }
             },
             {
                 iconCls: 'submitfinalapp',
                 text:'Submit Final Application',

                 handler: function(){

                 }
             },
             {
                 iconCls: 'cancel',
                 text:'Cancel',

                 handler: function(){

                 }
             }
             ]
        }
        ]
    });

}