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
var proxyLiftsStore = new Ext.data.HttpProxy({
            url:baseuri+'?module=liftclub&action=jsongetlifts&userneed='+userneed+'&start=0&limt=25'
        });

var liftdata = new Ext.data.JsonStore({
        root: 'searchresults',
        totalProperty: 'liftcount',
        idProperty: 'detid',
        remoteSort: false,        
        fields: ['detid', 'orid', 'desid', 'detuserid', 'times', 'userneed','needtype', 'createdormodified', 'daterequired','selectedays', 'orisuburb','desuburb'],
        
        proxy:proxyLiftsStore,
        listeners:{ 
    		'loadexception': function(theO, theN, response){
    			//alert(response.responseText);
    		},
    		'load': function(){
    				//alert('load');	
    			}
    	}
	});
	 liftdata.setDefaultSort('createdormodified', 'asc');
	 
    // pluggable renders
    function renderTitle(value, p, record){
        return String.format(
        		'<b><a href="'+baseuri+'?module=liftclub&action=viewlift&liftuserid={0}">{1} - {2}</a></b>', record.data.detuserid, record.data.orisuburb, record.data.desuburb);
    }
    function renderDetails(record){
        return String.format(
        		'<p>{0} ( {1} )<br />Created: {2}<br /> {3}{4} {5}</p>', record.data.needtype, record.data.userneed, record.data.createdormodified, record.data.selectedays, record.data.daterequired, record.data.times);
    }

    var liftgrid = new Ext.grid.GridPanel({
        el:'liftsrenderer',
        width:540,
        height:400,
        title:'Search Lifts',
        store: liftdata,
        trackMouseOver:false,
        disableSelection:true,
        loadMask: true,
		      emptyText:'No Lifts Found',
		
        // grid columns
        columns:[
        {
         {
            //id: 'code', // id assigned so we can apply custom css (e.g. .x-grid-col-topic b { color:#333 })
            header: "Title",
            dataIndex: 'detuserid',
            width: 540,
            renderer: renderTitle,
            sortable: true
        }],

        // customize view config
        viewConfig: {
            forceFit:true,
            enableRowBody:true,
            showPreview:false,
            getRowClass : function(record, rowIndex, p, store){
                if(this.showPreview){
                    p.body = "<p>"+record.data.needtype+"</p>";
                    return 'x-grid3-row-expanded';
                }
                return 'x-grid3-row-collapsed';
            }
        },
		    	
		plugins:[new Ext.ux.grid.Search({
				 iconCls:'zoom'
				 //,readonlyIndexes:['lecturers']
				 ,disableIndexes:['detid']
				 ,minChars:1
				 ,autoFocus:true
				 // ,menuStyle:'radio'
		 })],

        // paging bar on the bottom
        bbar: new Ext.PagingToolbar({
            pageSize: 25,
            store: liftdata,
            displayInfo: true,
            displayMsg: 'Lifts {0} - {1} of {2}',
            emptyMsg: "No lifts to display"
            
        })
    });
    // render it
    liftgrid.render();
    // trigger the data store load
    liftdata.load({params:{start:0, limit:25}});
});
