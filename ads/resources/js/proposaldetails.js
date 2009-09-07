function showTabs() {
    // basic tabs, first tabs contains the Course details, second tabs contains course history
    var tabs = new Ext.TabPanel({
        renderTo: 'tabs',
        width:800,
        activeTab: 0,
        frame:true,
        defaults:{autoHeight: true},
        items:[
            {contentEl:'contentcontent', title: 'Course Details'},
            {contentEl: 'tree-div', title: 'Course History'}
        ]
    });
}

function addTree() {
    var args=addTree.arguments;
    var courseid=args[0];
    var url=args[1];
    
    // course history
    var tree = new Ext.tree.TreePanel({
        useArrows: true,
        autoScroll: true,
        animate: true,
        enableDD: true,
        containerScroll: true,
        border: false,
        rootVisible: false,
        applyTo: 'tree-div',

        // auto create TreeLoader
        dataUrl: url,
        root: {
            nodeType: 'async',
            text: 'DATA',
            draggable: false,
            id: 'source'
        },
        listeners: {
            click: function(n) {
                populateForm(n, courseid);
            }
        }

    });

    tree.getRootNode().expand();
}

function populateForm(n, courseid) {
    Ext.onReady(function() {
       // get the data clicked on
        var propChosen = n.attributes.text,
            eachSplit = propChosen.split('_'),
            date = eachSplit[0],
            version = eachSplit[1];

        // get home url
        homeURL = jQuery("input[@name='homeURL']").val();
        url = homeURL + "&action=viewcoursedetails&date=" +date+ "&version=" + version + "&courseid=" + courseid;

        window.location.href = url;
    });
}