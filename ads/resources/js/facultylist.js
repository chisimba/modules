var myData;
var loadTable = function() {
    var args=loadTable.arguments;
    myData = args[0];
    Ext.onReady(function(){
        // create the data store
        var store = new Ext.data.ArrayStore({
            fields: [
               {name: 'faculty'},
               {name: 'moderator'}
            ]
        });
        store.loadData(myData);

        // create the Grid
        var grid = new Ext.grid.GridPanel({
            store: store,
            columns: [
                {header: "Faculty", width: 200, sortable: true, dataIndex: 'faculty'},
                {id: 'moderator', header: "Moderator", width: 85, sortable: true, dataIndex: 'moderator'}
            ],
            stripeRows: true,
            autoExpandColumn: 'moderator',
            height:350,
            width:600,
            title:'Faculty Listing'
        });
        grid.render('facultylisting');
    });

}