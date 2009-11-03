/*
 * Ext JS Library 3.0 RC2
 * Copyright(c) 2006-2009, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 * By Paul Mungai
 * Searching Plugin: Paul Mungai
 * wandopm@gmail.com
 */
Ext.onReady(function(){
var liftdata = new Ext.data.JsonStore({
        root: 'searchresults',
        totalProperty: 'liftcount',
        idProperty: 'detid',
        remoteSort: false,        
        fields: ['detid', 'orid', 'desid', 'detuserid', 'times','additionalinfo', 'specialoffer', 'emailnotifications', 'userneed','needtype', 'daterequired', 'createdormodified', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday', 'selectedays', 'oriuserid', 'oristreet', 'orisuburb', 'desuserid', 'destreet', 'desuburb'],
        proxy: new Ext.data.HttpProxy({ 
            	url: uri
        }),
        listeners:{ 
    		'loadexception': function(theO, theN, response){
    			//alert(response.responseText);
    		},
    		'load': function(){
    				//alert('load');	
    			}
    	},
	});
	liftdata.setDefaultSort('createdormodified', 'asc');
 // pluggable renders
 function renderTitle(value, p, record){
     return String.format(
     		'<b><a href="'+baseuri+'?module=liftclub&action=viewlift&liftuserid={0}">View</a></b>', record.data.detuserid, record.data.orisuburb, record.data.desuburb);
 }
 function renderDetails(record){
     return String.format(
     		'<p>{0} ( {1} )<br />Created: {2}<br /> {3}{4} {5}</p>', record.data.needtype, record.data.userneed, record.data.createdormodified, record.data.selectedays, record.data.daterequired, record.data.times);
 }
    var grid = new Ext.grid.GridPanel({
        el:'find-grid',
        width:800,
        height:400,
        title:'Lifts',
        store: liftdata,
        trackMouseOver:false,
        disableSelection:true,
        loadMask: true,

        // grid columns
        columns:[
        {
            header: "Origin(Suburb)",
            dataIndex: 'orisuburb',
            width: 120,
            hidden: false,
            sortable: true
        },{
            header: "Destiny(Suburb)",
            dataIndex: 'desuburb',
            width: 120,
            hidden: false,
            sortable: true
        },{
            header: "Find/Offer",
            dataIndex: 'userneed',
            width: 70,
            hidden: false,
            sortable: true
        },{
            header: "Type",
            dataIndex: 'needtype',
            width: 60,
            hidden: false,
            sortable: true
        },{
            header: "Created",
            dataIndex: 'createdormodified',
            width: 130,
            hidden: false,
            sortable: true
        },{
            header: "Selected Days",
            dataIndex: 'selectedays',
            width: 300,
            hidden: false,
            sortable: true
        },{
            id: 'detuserid', // id assigned so we can apply custom css (e.g. .x-grid-col-topic b { color:#333 })
            header: "View",
            dataIndex: 'detuserid',
            width: 50,
            renderer: renderTitle,            
            hidden: false,
            sortable: true
        }],

        // customize view config
        viewConfig: {
            forceFit:true,
            enableRowBody:true,
            showPreview:false,
            getRowClass : function(record, rowIndex, p, store){
                if(this.showPreview){
                //<p>{0} ( {1} )<br />Created: {2}<br /> {3}{4} {5}</p>', record.data.needtype, record.data.userneed, record.data.createdormodified, record.data.selectedays, record.data.daterequired, record.data.times
                 if(record.data.daterequired == null){
                    p.body = '<p>'+record.data.needtype+' ('+record.data.userneed+')<br />Created: '+record.data.createdormodified+'<br />'+record.data.selectedays+' '+record.data.times+' </p>';//'<p>'+record.data.needtype+'</p>';
                 }else{
                    p.body = '<p>'+record.data.needtype+' ('+record.data.userneed+')<br />Created: '+record.data.createdormodified+'<br />'+record.data.daterequired+' '+record.data.times+' </p>';//'<p>'+record.data.needtype+'</p>';                 
                 }   
                    return 'x-grid3-row-expanded';
                }
                return 'x-grid3-row-collapsed';
            }
        },
								plugins:[new Ext.ux.grid.Search({
											iconCls:'zoom'
											//,readonlyIndexes:['lecturers']
											,disableIndexes:['detuserid','createdormodified','selectedays']
											,minChars:1
											,autoFocus:true
											// ,menuStyle:'radio'
									})],
        // paging bar on the bottom
        bbar: new Ext.PagingToolbar({
            pageSize: 5,
            store: liftdata,
            displayInfo: true,
            displayMsg: 'Displaying Page {0} - {1} of {2}',
            emptyMsg: "No Lifts to display",
            items:[
                /*'-', {
                pressed: false,
                enableToggle:true,
                text: 'Show/Hide Details',
                cls: 'x-btn-text-icon details',
                toggleHandler: function(btn, pressed){
                    var view = grid.getView();
                    view.showPreview = pressed;
                    view.refresh();
                }
            }, {
              xtype:'checkbox'
              ,boxLabel:'Lifts Offered '
              ,checked:true
            }, {
              xtype:'checkbox'
              ,boxLabel:'Lifts Wanted'
              ,checked:false
            }*/]
        })
    });
    // render it
    grid.render();

    // trigger the data store load
    liftdata.load({params:{start:0, limit:5}});
	
});
