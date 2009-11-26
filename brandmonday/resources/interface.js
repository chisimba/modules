 //1259225301;

Ext.onReady(function(){
	
	/**** Polling the server for new conversations ****/
      
	//twitPoll.disconnect();

	
	var viewport = new Ext.Viewport({
            layout: 'border',
            items: [
            	// create instance immediately
	            new Ext.BoxComponent({
	                region: 'north',
	                height: 32, // give north and south regions a height
	                autoEl: {
	                    tag: 'div',
	                    html:'<p>#BrandMonday: Tweet about Brands on Mondays</p>'
	                }
	            }),
	            new Ext.BoxComponent({
	                region: 'south',
	                height: 32, // give north and south regions a height
	                autoEl: {
	                    tag: 'div',
	                   // html:'<img src="http://avoir.uwc.ac.za/usrfiles/filemanager_thumbnails/gen14Srv6Nme27_2590_1221319552.jpg" />'
	                }
	            }),
	             /*{
	                // lazily created panel (xtype:'panel' is default)
	                region: 'south',
	                contentEl: 'south',
	                split: true,
	                height: 100,
	                minSize: 100,
	                maxSize: 200,
	                collapsible: true,
	                title: 'South',
	                margins: '0 0 0 0',
	                html:'<img src="http://avoir.uwc.ac.za/usrfiles/filemanager_thumbnails/gen14Srv6Nme27_2590_1221319552.jpg" />'
	            },*/
	            
	            westPanel,    
	            middlePanel
             ] 
        });       
		
		dsBrandPlus.load({params:{start:0, limit:20}});
		dsBrandMinus.load({params:{start:0, limit:20}});
		dsMentions.load({params:{start:0, limit:20}});
		
		//alert(dts.format("U"));
    });

    
   