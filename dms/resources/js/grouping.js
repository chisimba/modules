var showLatestUploads = function(gridData) {
    var xg = Ext.grid;

    // shared reader
    var reader = new Ext.data.ArrayReader({}, [
       {name: 'filename'},
       {name: 'status'},
       {name: 'lastChange'},
       {name: 'filetype'},
       {name: 'filetypedesc'},
       {name: 'details'}
    ]);

    var grid = new xg.GridPanel({
        store: new Ext.data.GroupingStore({
            reader: reader,
            data: gridData,
            sortInfo:{field: 'filename', direction: "ASC"},
            groupField:'filetype'
        }),

        columns: [
            {id:'filename',header: "Filename", width: 60, sortable: true, dataIndex: 'filename'},
            {header: "Status", width: 15, sortable: true, dataIndex: 'status'},
            {header: "Date Last Modified", width: 30, sortable: true, dataIndex: 'lastChange'},
            {header: "File Type", width: 30, sortable: true, dataIndex: 'filetype'},
            {header: "File Type Description", width: 50, sortable: true, dataIndex: 'filetypedesc'},
            {header: "Details", width: 20, sortable: true, dataIndex: 'details'}
        ],

        view: new Ext.grid.GroupingView({
            forceFit:true,
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Items" : "Item"]})',
            startCollapsed: true,
            hideGroupedColumn: true
        }),

        frame:true,
        width: 800,
        height: 450,
        collapsible: true,
        animCollapse: false,
        title: 'Last 10 Uploaded files',
        renderTo: 'recent-uploads'
    });
}