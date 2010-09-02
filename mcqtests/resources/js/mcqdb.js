var sm2 = new Ext.grid.CheckboxSelectionModel({
    listeners: {
        rowselect: function(sm2, rowIdx, r) {
        }
    }
});

var courseTitle = 'Course Databank for - ' + courseName;

// Create the data store
var myStore = new Ext.data.Store({
    proxy: new Ext.data.HttpProxy({
        method: 'GET',
        url: myUrl
    }),
    reader: new Ext.data.JsonReader({
        root: 'results',
        totalProperty: 'totalcount',
        fields: [
        {
            name: 'id',
            mapping: 'id'
        },

        {
            name: 'context',
            mapping: 'context'
        },

        {
            name: 'question',
            mapping: 'question'
        },

        {
            name: 'hint',
            mapping: 'hint'
        },

        {
            name: 'questiontype',
            mapping: 'questiontype'
        }
        ]
    })
});

var showQuestionDB = function() {

    // create the Grid
    var grid = new Ext.grid.GridPanel({
        store: myStore,
        columns: [
        sm2,
        {
            header: 'id',
            width: 80,
            sortable: true,
            dataIndex: 'id',
            hidden: true
        },{
            id:'context',
            header: 'Course',
            width: 80,
            sortable: true,
            dataIndex: 'context'
        },{
            id: 'question',
            header: 'Question',
            width: 160,
            sortable: true,
            dataIndex: 'question'
        },{
            header: 'Hint',
            width: 75,
            sortable: true,
            dataIndex: 'hint'
        },{
            header: 'Question Type',
            width: 100,
            sortable: true,
            dataIndex: 'questiontype'
        }],
        tbar: [{
            xtype: 'checkbox',
            name: 'allAssign',
            boxLabel: 'View All Courses',
            handler: function(checkbox, checked){
                if(checked) {
                    // load data for all courses
                    getAllCourses();
                }
            }
        }],
        fbar: [{
            text: 'Save',
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
        title: courseTitle,
        stateId: 'grid'
    });

    // render the grid to the specified div in the page
    grid.render('mcqGrid');
}

function getSelections() {
    var mySelections = sm2.getSelections();
    var ids = "";
    if(sm2.getCount() > 0) {
        //var confirmMsg = "Are you sure you want to submit these " + sm2.getCount() + " questions?";
        //Ext.MessageBox.confirm("Submitting IDs", confirmMsg, function(btn, text){
        //if (btn == 'yes'){
        for(i=0;i<parseInt(sm2.getCount());i++) {
            ids += mySelections[i].get('id');
            if( i < parseInt(sm2.getCount()) - 1) {
                ids += ",";
            }
        }
        // do a submit of these id's and go back to this page when done, with new data
        submitIDs(ids);
    //}
    //});
    }
    
    
}

function submitIDs(ids) {
    // Basic request
    if(ids.length > 0) {
        Ext.Ajax.request({
            url: submitUrl,
            success: ajaxSuccess,
            failure: ajaxFailure,
            params: {
                courseID: courseID,
                idData: ids
            }
        });
    }
}
var ajaxSuccess = function() {
    window.location.href = nextUrl;
}

var ajaxFailure = function() {
    Ext.MessageBox.alert("There was an error submitting your request. Please try again!");
}


function getGridData(dataType) {
    var newUrl = myUrl + '&type=' + dataType;
    myStore.proxy.setUrl(newUrl);
    myStore.load();
    jQuery("#mcqGrid").html("");
    showQuestionDB(newUrl);
}

function getAllCourses() {
    var dataType = jQuery("#qnoption").val();

    var newUrl = myUrl + '&type=' + dataType + '&courses=all';
    myStore.proxy.setUrl(newUrl);
    myStore.load();
    jQuery("#mcqGrid").html("");
    showQuestionDB(newUrl);
}