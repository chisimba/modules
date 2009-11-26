

var westPanel = new Ext.Panel({
	region: 'east',
    id: 'Tweet Tools', // see Ext.getCmp() below
    title: 'West',
    split: true,
    width: 200,
    minSize: 175,
    border:true,
    maxSize: 400,
    collapsible: true,
     unstyled: true,

    margins: '0 0 0 5',
    layout: {
        type: 'accordion',
       
    },
     cmargins: '5 5 0 5',

    items: [ 
    	{	    	
	        title: 'Powered By',
	        html: '<p class="warning">Terms (keywords, hashtags etc) currently being tracked:<br>#SITACLOUD<br>	#sitacloud</p>',
	        border: true,
	        iconCls: 'settings'
	    },
	    
	    {              
	        //contentEl: 'west',
	        title: 'Get your company involved',
	        html:'<img src="http://tweetgator.peeps.co.za/skins/_common/icons/sioc.gif">',
	       // border: false,
	        iconCls: 'nav' // see the HEAD section for style used
	    }, {	    	
	        title: 'Featured Blogger',
	        html: '<p class="warning">Total posts so far: 44<br>Total contributors: 5.</p>',
	        border: true,
	        iconCls: 'settings'
	    },
	    {	    	
	        title: 'BrandMonday Campaign',
	       	//items: [searchForm]
	        border: true,
	        iconCls: 'settings'
	    },
	    {	    	
	        title: 'Disclaimer',
	       	//items: [searchForm]
	        border: true,
	        iconCls: 'settings'
	    }
	    
	 ]
    
});