function initNextOfKinDetailsForm(){
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

    var relationship= [
    ['Father.'],
    ['Mother.'],
    ['Son'],
    ['Daughter']
    ];

    var relationshipstore = new Ext.data.ArrayStore({
        fields: ['relationship'],
        data : relationship
    });

    var relationshipfield = new Ext.form.ComboBox({
        store: relationshipstore,
        displayField:'relationship',
        fieldLabel:'Relationship',
        typeAhead: true,
        mode: 'local',
        editable:false,
        forceSelection: true,
        triggerAction: 'all',
        emptyText:'Select relationship...',
        selectOnFocus:true,
        name : 'relationshipfield'

    });
    var nextofokindetailsform = new Ext.form.FormPanel({
        
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
            autoHeight: true,
            items:[
            buttons,
            new Ext.form.DisplayField({
                value:'<h4>Important Note:</h4>'
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
                fieldLabel: 'Initials',
                anchor:'37.5%'
            }),
            relationshipfield,
            new Ext.form.TextField({
                fieldLabel: 'ID Number'
            })
            ]

        },
        {
            xtype: 'fieldset',
            title: 'Residential addresss',
            autoHeight: true,
            items:[
            new Ext.form.TextField({
                anchor:'90%',
                allowBlank:false
            }),
            new Ext.form.TextField({
                anchor:'90%',
                allowBlank:false
            }),
            new Ext.form.TextField({
                fieldLabel: 'City'
            }),
            new Ext.form.TextField({
                fieldLabel: 'Province'
            }),
            new Ext.form.TextField({
                fieldLabel: 'Country'
            }),
            new Ext.form.TextField({
                fieldLabel: 'Postal code'
            })
            ]

        },

        {
            xtype: 'fieldset',
            title: 'Contact numbers',
            autoHeight: true,
  
            layout:'column',
            items: [{
             border:false,
             layout:'column',
                items:[{
                    columnWidth:.5,
                    layout: 'form',
                     border:false,
                    bodyStyle:'margin-left:1em;margin-top:1em;',
                    items: [{
                        xtype:'textfield',
                        fieldLabel: 'Home',
                        name: 'hometelfield',
                        anchor:'95%'
                    }, {
                        xtype:'textfield',
                        fieldLabel: 'Cellphone',
                        name: 'cellhphonefield',
                        anchor:'95%'
                    }]
                },{
                    columnWidth:.5,
                    layout: 'form',
                    border:false,
                    bodyStyle:'margin-left:1em;margin-top:1em;',
                    items: [{
                        xtype:'textfield',
                        fieldLabel: 'Business',
                        name: 'businesstelfield',
                        anchor:'95%'
                    },{
                        xtype:'textfield',
                        fieldLabel: 'Fax',
                        name: 'faxfield',
                        anchor:'95%'
                    }]
                }]
            }]

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

    });

}