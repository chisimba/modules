function initGrid(cols,url,searchurl){
		
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
     renderTo : 'grouping-grid'
      });
   var buttons= new ButtonPanel(

        [{
            iconCls: 'commentadd',
            text:'Add Gift',
            handler: function(){
                showAddGiftWin(url);
            }
        }
        ]

 );
		// shared reader
		   var reader = new Ext.data.ArrayReader({}, [
		      {name: 'giftname'},
		      {name: 'description'},
		      {name: 'donor'},
		      {name: 'recipient'},
		      {name: 'value'},
                      {name: 'edit'}
		     
		   ]);
    // create the data store
		   var store = new Ext.data.GroupingStore({
    	id:'store',
    	sortInfo:{field: 'donor', direction: 'ASC'},
        groupField:'donor',		
    	reader: reader,
    	groupOnSort:true
    	 	
    	
    });
  
    //load data
    store.loadData(cols);
    // create the Grid
    var grid = new Ext.grid.GridPanel({
        store: store,
        view: new Ext.grid.GroupingView({
            forceFit:true,
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Items" : "Item"]})'
        }),
        columns: [
        {
            id:'giftname',
            header: "Name",
            width: 100,
            dataIndex: 'giftname'
        },

        {
            header: "Description",
            width: 100,
            dataIndex: 'description'
        },

        {
            header:"Donor",
            width: 100,
            dataIndex: 'donor'
        },

        {
            header: "Recipient",
            width: 150,
            dataIndex: 'recipient'
        },

        {
            header: "Value",
            width: 100,
            dataIndex: 'value'
        },

        {
            header: "Edit",
            width: 100,
            dataIndex: 'edit'
        }],

        tbar: [
                 {
                	 text: 'Search',
                	 tooltip: 'Search for gift',  
                	 handler: function(){
                	 				searchGift(searchurl);
                	 			}
                	 
                 }
           ],
         




        stripeRows: true,
        autoExpandColumn: 'giftname',
        height:500,
        width:700,
        frame:true,
        border:true

    });
   // grid.render('grouping-grid');
 var form = new Ext.form.FormPanel({

        baseCls: 'x-plain',
        width:800,
        labelWidth: 135,
        bodyStyle:'padding:5px 5px 0',
        renderTo: 'grouping-grid',
        collapsible: true,
        defaults: {width:750},
        height:600,
        bodyStyle:'background-color:transparent',
        defaultType: 'textfield',
        border:false,
         items: {
            xtype: 'fieldset',
            title: 'Gift Listing',
            autoHeight: true,
            height:800,
            items:[
              buttons,
              grid
            ]
         }
        });

}

function showAddGiftWin(url){
    var form = new Ext.form.FormPanel({
        baseCls: 'x-plain',
        labelWidth: 75,
        bodyStyle:'padding:5px 5px 0',
        standardSubmit: true,
        url:url,
        defaultType: 'textfield',
        items:[
               {
                    fieldLabel: 'Gift Name',
                    name: 'giftnamefield',
                    width:350,
                    allowBlank: false
                },

            new Ext.form.TextArea({
               fieldLabel: 'Description',
               width:350,
               height:150,
               
               name: 'descfield'
               }),

              {
                fieldLabel: 'Donor',
                name: 'donorfield',
                width:350,
                allowBlank: false
                },
              {
                    fieldLabel: 'Value',
                    name: 'valuefield',
                    width:350,
                    allowBlank: false
                }

          ]

      });
         var addGiftWin;

           if(!addGiftWin){
            
            addGiftWin = new Ext.Window({
                applyTo:'add-gift-surface',
                layout:'fit',
                title:'Enter Gift',
                width:500,
                height:350,
                x:250,
                y:50,
                closeAction:'hide',
                plain: true,
                items: [
                 form
                ],
                  buttons: [{
                    text:'Save',
                    handler: function(){
                  if (form.url){
                            form.getForm().getEl().dom.action = form.url;
                          }
                        form.getForm().submit();

                  }
                  }
                  ,{
                    text: 'Cancel',
                    handler: function(){
                       addGiftWin.hide();
                        window.location.reload(true);
                    }
                  }
                ]

            });
        }

        addGiftWin.show(this);

}

function showEditGiftWin(url,giftname,description,donor,val){

	
	var form = new Ext.form.FormPanel({
        baseCls: 'x-plain',
        labelWidth: 75,
        bodyStyle:'padding:5px 5px 0',
        standardSubmit: true,
        url:url,
        defaultType: 'textfield',
        items:[
               {
                    fieldLabel: 'Gift Name',
                    name: 'gname',
                    width:350,
                    allowBlank: false,
                    value:giftname
                    
                },

            new Ext.form.TextArea({
               fieldLabel: 'Descption',
               width:350,
               height:150,
               
               name: 'descripvalue',
               value:description
               }),

              {
                fieldLabel: 'Donor',
                name: 'dnvalue',
                width:350,
                allowBlank: false,
                value:donor
                },
              {
                    fieldLabel: 'Value',
                    name: 'gvalue',
                    width:350,
                    allowBlank: false,
                    value:val
                }

          ]

      });
			
	
         var editGiftWin;

           if(!editGiftWin){
            
            editGiftWin = new Ext.Window({
                applyTo:'edit-gift-surface',
                layout:'fit',
                title:'Edit Gift',
                width:500,
                height:350,
                x:250,
                y:50,
                closeAction:'hide',
                plain: true,
                items: [
                 form
                ],
                  buttons: [{
                    text:'Save',
                    handler: function(){
		                  if (form.url){
		                            form.getForm().getEl().dom.action = form.url;
		                          }
		                        form.getForm().submit();
		
		                  }
                  }
                  ,{
                    text: 'Cancel',
                    handler: function(){
                       editGiftWin.hide();
                        window.location.reload(true);
                    }
                  }
                ]

            });
        }
           
       editGiftWin.show(this);

}

function searchGift(url){
	var GiftName; 
	var form;
	var SearchWindow; 
	
	
		 form = new Ext.form.FormPanel({
			 	renderTo: 'search-gift-surface',
				baseCls: 'x-plain',
		        labelWidth: 75,
		        bodyStyle:'padding:5px 5px 0',
		        standardSubmit: true,
		        url:url,
		        defaultType: 'textfield',
		        items:[
						{
						    fieldLabel: 'Gift Name',
						    name: 'giftname',
						    width:80
						   
						}
		             ]
		 });
	
		 if(!SearchWindow){
			 SearchWindow = new Ext.Window({
				 applyTo:'search-gift-surface',
				 title: 'Gift Search',
		        closeAction:'hide',
		        width: 220,
		        height: 100,
		        x:250,
                y:250,
		        plain:true,
		        layout: 'fit',
		        items:[ form],
		        buttons: [{
		        	text: 'Search',
		        	handler: function(){
	                         if (form.url){
                            form.getForm().getEl().dom.action = form.url;
                          }
                        form.getForm().submit();

	                  }
				        	},{
		            text: 'Close',
		            handler: function(){
		         	 SearchWindow.hide();
		               window.location.reload(true);
		           }
            
          
			}]
			 	});
		 }
	
		 SearchWindow.show(this);
}

var showGiftPolicy = function () {
    var win;
    
    if(!win){
        win = new Ext.Window({
            applyTo:'hello-win',
            layout:'fit',
            autoWidth: true,
            autoHeight:true,
            closeAction:'hide',
            plain: true,

            buttons: [{
                text:'Accept',
                handler: function() {
                    alert("hello accept");
                }
            },{
                text: 'Decline',
                handler: function(){
                    win.hide();
                }
            }]
        });
    }
    win.show(this);
}