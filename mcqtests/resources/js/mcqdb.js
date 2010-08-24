var sm2 = new Ext.grid.CheckboxSelectionModel({
    listeners: {
        rowselect: function(sm2, rowIdx, r) {
            //Ext.MessageBox.alert(sm2.getCount());
        }
    }
});

var showQuestionDB = function(myData) {
    // create the data store
    var store = new Ext.data.ArrayStore({
        fields: [
           {name: 'id'},
           {name: 'context'},
           {name: 'question'},
           {name: 'hint'},
           {name: 'questiontype'},
        ]
    });

    // manually load local data
    store.loadData(myData);
    
    // create the Grid
    var grid = new Ext.grid.GridPanel({
        store: store,
        columns: [
            sm2,
            {header: 'id', width: 80, sortable: true, dataIndex: 'id', hidden: true},
            {id:'context',header: 'Context', width: 80, sortable: true, dataIndex: 'context'},
            {id: 'question', header: 'question', width: 160, sortable: true, dataIndex: 'question'},
            {header: 'hint', width: 75, sortable: true, dataIndex: 'hint'},
            {header: 'questiontype', width: 85, sortable: true, dataIndex: 'questiontype'}
        ],
        tbar: [{
            text: 'Add',
            iconCls: 'silk-add',
            listeners: {
                click: function() {
                    getSelections();
                }
            }
        }],
        sm: sm2,
        stripeRows: true,
        autoExpandColumn: 'question',
        height: 350,
        width: 600,
        title: 'Array Grid',
        stateId: 'grid'
    });

    // render the grid to the specified div in the page
    grid.render('mcqGrid');
}

function getSelections() {
    var mySelections = sm2.getSelections();
    var ids = "";
    
    for(i=0;i<parseInt(sm2.getCount());i++) {
        ids += mySelections[i].get('id');
        if( i < parseInt(sm2.getCount()) - 1) {
            ids += ",";
        }
    }
    //Ext.MessageBox.alert("HELLO", ids);
}