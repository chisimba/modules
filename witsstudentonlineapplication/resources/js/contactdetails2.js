function initContactDetails2Form(){
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
    
    var contactdetails2form = new Ext.form.FormPanel({
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
         bodyStyle:'margin-top:2em;margin-bottom:2em;',
         bodyStyle:'background-color:transparent',
         border:false,
         items:[ {
             xtype: 'fieldset',
             title: 'Address details',
             bodyStyle:'margin-bottom:2em;',
             autoHeight: true,
             items:[
             buttons,
             new Ext.form.DisplayField({
                 value:'<h4>   </h4>'
             }),
             {
             xtype: 'fieldset',
             title: 'Postal addresss',
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
                 anchor:'90%',
                 allowBlank:false
             }),
             new Ext.form.TextField({
                 anchor:'90%',
                 allowBlank:false
             }),
             new Ext.form.TextField({
                 fieldLabel: 'Postal code'
             })
             ]
             },
             {
             xtype: 'fieldset',
             title: 'Residential / street addresss',
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
                 anchor:'90%',
                 allowBlank:false
             }),
             new Ext.form.TextField({
                 anchor:'90%',
                 allowBlank:false
             }),
             new Ext.form.TextField({
                 fieldLabel: 'Postal code'
             })
             ]
             },
             {
                 xtype: 'fieldset',
                 title: 'Business addresss',
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
                     anchor:'90%',
                     allowBlank:false
                 }),
                 new Ext.form.TextField({
                     anchor:'90%',
                     allowBlank:false
                 }),
                 new Ext.form.TextField({
                     fieldLabel: 'Postal code'
                 })
                 ]
                 },
                 {
                     xtype: 'fieldset',
                     title: 'If an alternate address for correspondence, please provide, with start and end dates for use',
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
                         anchor:'90%',
                         allowBlank:false
                     }),
                     new Ext.form.TextField({
                         anchor:'90%',
                         allowBlank:false
                     }),
                     new Ext.form.TextField({
                         fieldLabel: 'Postal code'
                     }),
                     {
                      //   xtype: 'fieldset',
                     //    title: 'Contact numbers',
                     //    autoHeight: true,
                    	 border:false,
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
                                     fieldLabel: 'Use from',
                                     name: 'hometelfield',
                                     anchor:'95%'
                                 }]
                                 },{
                                 columnWidth:.5,
                                 layout: 'form',
                                 border:false,
                                 bodyStyle:'margin-left:1em;margin-top:1em;',
                                 items: [{
                                     xtype:'textfield',
                                     fieldLabel: 'Use till',
                                     name: 'businesstelfield',
                                     anchor:'95%'
                                 }]
                             }]
                         }]
                       }
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
           },
           {
             xtype: 'fieldset',
             title: 'Email',
             autoHeight: true,
             items:[
                 new Ext.form.TextField({
                 fieldLabel: 'Email',
                 anchor:'90%',
                 allowBlank:false
                 })
             ]
           }
         ] ,
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
         }]
     });

}