/*
 * Ext JS Library 3.0 RC2
 * Copyright(c) 2006-2009, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.onReady(function(){

   
var contextdata = new Ext.data.JsonStore({
        root: 'activities',
        totalProperty: 'totalCount',
        idProperty: 'contextcode',
        remoteSort: false,        
        fields: ['contextcode', 'title', 'description', 'createdon', 'createdby','module' ],
        proxy: new Ext.data.HttpProxy({ 
            	url: uri
        })
	});
	 contextdata.setDefaultSort('createdon', 'desc');
	 
    // pluggable renders
    function renderTopic(value, p, record){
        return String.format(
        		'{0}', value, record.data.contextcode);
    }
    function renderLast(value, p, r){
        return String.format('{0}<br/>by {1}', value.dateFormat('M j, Y, g:i a'), r.data['lastposter']);
    }

    var grid = new Ext.grid.GridPanel({
        el:'topic-grid',
        width:700,
        height:400,
        title:'List of Activities',
        store: contextdata,
        trackMouseOver:false,
        disableSelection:true,
        loadMask: true,

        // grid columns
        columns:[
        {
            header: "Title",
            dataIndex: 'title',
            width: 100,            
            sortable: true
        },{
            id: 'description', // id assigned so we can apply custom css (e.g. .x-grid-col-topic b { color:#333 })
            header: "Description",
            dataIndex: 'description',
            width: 220,
            //renderer: renderTopic,
            sortable: true
        },{
            header: "Course Code",
            dataIndex: 'contextcode',
            width: 100,
            hidden: false,
            sortable: true
        },{
            header: "Date Created",
            dataIndex: 'createdon',
            width: 70,
            align: 'right',
            sortable: true
        },{
            header: "Created By",
            dataIndex: 'createdby',
            width: 100,
            align: 'right',
            sortable: true
        }],

        // customize view config
        viewConfig: {
            forceFit:true,
            enableRowBody:true,
            showPreview:false,
            getRowClass : function(record, rowIndex, p, store){
                if(this.showPreview){
                    p.body = '<p>'+record.data.excerpt+'</p>';
                    return 'x-grid3-row-expanded';
                }
                return 'x-grid3-row-collapsed';
            }
        },

        // paging bar on the bottom
        bbar: new Ext.PagingToolbar({
            pageSize: 10,
            store: contextdata,
            displayInfo: true,
            displayMsg: 'Displaying topics {0} - {1} of {2}',
            emptyMsg: "No topics to display",
            items:[
                '-', {
                pressed: false,
                enableToggle:true,
                text: 'Show About',
                cls: 'x-btn-text-icon details',
                toggleHandler: function(btn, pressed){
                    var view = grid.getView();
                    view.showPreview = pressed;
                    view.refresh();
                }
            }]
        })
    });

    // render it
    grid.render();

    // trigger the data store load
    contextdata.load({params:{start:0, limit:10}});
});
