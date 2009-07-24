Ext.onReady(function(){
    var simple = new Ext.FormPanel({
        labelWidth: 75, // label settings here cascade unless overridden
        url:'index.php?module=ads&action=savecourseproposal',
        frame:true,
        title: 'Add  New Course Proposal',
        bodyStyle:'padding:5px 5px 0',
        width: 350,
        defaults: {width: 230},
        defaultType: 'textfield',

        items: [{
                fieldLabel: 'Faculty',
                name: 'faculty',
                allowBlank:false
            },{
                fieldLabel: 'Name of course/unit',
                name: 'title',
                id: 'input_title',
                allowBlank: false
            }
        ],

        buttons: [{
            text: 'Save'
        },{
            text: 'Cancel'
        }]
    });

    simple.render('courseProposal');
});