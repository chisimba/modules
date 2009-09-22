var myData;
var loadFacultyList = function() {
    var args=loadFacultyList.arguments;
    myData = args[0];
    Ext.onReady(function(){
        // create the data store
        var store = new Ext.data.ArrayStore({
            fields: [
               {name: 'faculty'}  ,
               {name: 'moderator'}  
            ]
        });
        store.loadData(myData);

        // create the Grid
        var grid = new Ext.grid.GridPanel({
            store: store,
            columns: [
                {header: "No", width:50, sortable: true, dataIndex: ''},
                {header: "Faculty", width:550, sortable: true, dataIndex: 'faculty'}
                
            ],
            stripeRows: true,
            height:350,
            width:600,
            title:'Faculty Listing'
        });
        grid.render('facultylist');
    });
}


var loadFacultyModeratorList = function() {
    var args=loadFacultyModeratorList.arguments;
    var modData = args[0];
    Ext.onReady(function(){
        // create the data store
        var store = new Ext.data.ArrayStore({
            fields: [
                 {name: 'moderator'},
                 {name: 'faculty'}
            ]
        });
        store.loadData(modData);

        // create the Grid
        var grid = new Ext.grid.GridPanel({
            store: store,
            columns: [
                {header: "Moderator", width:300, sortable: true, dataIndex: 'moderator'},
                {header: "Faculty", width:300, sortable: true, dataIndex: 'faculty'}

            ],
            stripeRows: true,
            height:350,
            width:600,
            title:'Faculty Moderators'
        });
        grid.render('facultymoderators');
    });

}