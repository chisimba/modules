


var BrandPanel = new Ext.Panel({
	//width: 950,
    height: 400,
    plain:true,
	layout:'border',	
    items:[BrandPlus, BrandMinus]
});

var MentionsPanel = new Ext.Panel({
	//width: '90%',
    height: 400,
    plain:true,
	layout:'border',	
    items:[MentionsSubPanel]
});


var middlePanel = new Ext.TabPanel({
	//title: "#BrandMonday: Tweet about Brands on Mondays",
    region: 'center',
    plain:true,
    margins:'0 20px 0 20px',
	width: '90%',
	activeTab: 0,

	padding: '5px',	
	autoScroll: true,
	loadMask: true,
	items:[{
                title: 'Brands',
                border:false,
                plain:true,
                
                items: BrandPanel
            },
            {
                title: 'Mentions',
                border:false,
                plain:true,
                
                items: MentionsPanel
            },
            {
                title: 'Awards',                
                items: [new Ext.TabPanel({
                	//plain:true,
                	border:false,
                	activeTab:0,
                	defaults:{autoScroll: true},
                	items:[
                			{
                				title:'Happiest Peeps',
                				autoLoad:baseUri+'?module=brandmonday&action=happypeeps',
                				border:false,
                				cssCls:'tagTab',
                				autoScroll:true,
                				margins: '10 10 10 10',
    							padding: '10 10 10 10'
                			},
                			{
                				title:'Sad Peeps',
                				autoLoad:baseUri+'?module=brandmonday&action=sadpeeps',
                				autoScroll:true,
                				border:false
                			},
                			{
                				title:'Most Active Peeps',
                				autoLoad:baseUri+'?module=brandmonday&action=activepeeps',
                				autoScroll:true,
                				border:false
                			},
                			{
                				title:'Best Service',
                				autoLoad:baseUri+'?module=brandmonday&action=bestserv',
                				//border:true,
                				autoScroll:true
                			},
                			{
                				title:'Worst Service',
                				autoLoad:baseUri+'?module=brandmonday&action=worstserv',
                				border:false,
                				autoScroll:true
                			},
                			{
                				title:'Most Mentioned',
                				autoLoad:baseUri+'?module=brandmonday&action=mentions',
                				border:false,
                				autoScroll:true
                			}
                			]
                })]
            }]
	
	
});