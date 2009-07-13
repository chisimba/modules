/*!
 * Ext JS Library 3.0.0
 * Copyright(c) 2006-2009 Ext JS, LLC
 * licensing@extjs.com
 * http://www.extjs.com/license
 * The lecturer view displays a list of assignments in a grid
 * to view the submissions of the assignments the user can click
 * on the row  
 */

/**
* The validation of the date fields
* and utility functions
*
*/
 var win; 
Ext.apply(Ext.form.VTypes, {
    daterange : function(val, field) {
        var date = field.parseDate(val);

        if(!date){
            return;
        }
        if (field.startDateField && (!this.dateRangeMax || (date.getTime() != this.dateRangeMax.getTime()))) {
            var start = Ext.getCmp(field.startDateField);
            start.setMaxValue(date);
            start.validate();
            this.dateRangeMax = date;
        } 
        else if (field.endDateField && (!this.dateRangeMin || (date.getTime() != this.dateRangeMin.getTime()))) {
            var end = Ext.getCmp(field.endDateField);
            end.setMinValue(date);
            end.validate();
            this.dateRangeMin = date;
        }
        /*
         * Always return true since we're only using this vtype to set the
         * min/max allowed values (these are tested for after the vtype test)
         */
        return true;
    }
});
    
    // The action
    var action = new Ext.Action({
        text: 'Add Assessment',
        handler: function(){
            //Ext.example.msg('Click','You clicked on "Action 1".');
            if(!win){
	            win = new Ext.Window({
	                
	                layout:'fit',
	                width:500,
	                height:400,
	                closeAction:'hide',
	                plain: true,
	
	                items:[addForm],
	
	                buttons: [submitbutton,
		                {
		                    text: 'Close',
		                    handler: function(){
		                        win.hide();
		                    }
		                }]
	            });
	        }
	        win.show(this);
	        },
        iconCls: 'blist'
    });
   

 // pluggable renders
    function renderTopic(value, p, record){
        return String.format(
                '<b><a href="http://extjs.com/forum/showthread.php?t={2}" target="_blank">{0}</a></b><a href="http://extjs.com/forum/forumdisplay.php?f={3}" target="_blank">{1} Forum</a>',
                value, record.data.forumtitle, record.id, record.data.forumid);
    }
    function renderLast(value, p, r){
        return String.format('{0}<br/>by {1}', value.dateFormat('M j, Y, g:i a'), r.data['lastposter']);
    }
	
    function renderSubmissions(value, p, r){
    	t = new Ext.Button('titititle');
    	return t;
    }
/**
* The add form
*
*/
var addForm = new Ext.FormPanel({
        		labelWidth: 75, // label settings here cascade unless overridden
		        url: 'http://localhost/eteach/index.php?module=turnitin&action=ajax_addassignment',
		        frame:true,
		        //layout:'column',
		        shadow: true,
		        title: 'Add Assessment',
		        bodyStyle:'padding:5px 5px 0',
		        width: 350,
		        defaults: {width: 230},
		        defaultType: 'textfield',
		
		        items: [{
			                fieldLabel: 'Title',
			                name: 'title',			                
			                anchor:'95%',
			                allowBlank:false
			            },{
		        
			        	xtype:'fieldset',
			        	fieldLabel: 'Dates',
				        //columnWidth: 0.5,
				        layout:'column',
				        defaults:{bodyStyle:'padding:10px'},

				        collapsible: true,
				        autoHeight:true,
				        defaults: {
				            anchor: '-20' // leave room for error icon
				        },
				        defaultType: 'textfield',
						
			            //items on the fieldset
			            items :[		        	
				            new Ext.form.DateField({
				          		fieldLabel: 'Start Date',
				          		anchor:'95%',
						        name: 'startdt',
						        id: 'startdt',
						        vtype: 'daterange',
						        endDateField: 'enddt'
				      		}),
				      		 
				      		new Ext.form.TimeField({
				                fieldLabel: '@',
				                name: 'starttime',
				                minValue: '8:00am',
				                maxValue: '6:00pm'
				                
				            }),
				            
					        new Ext.form.DateField({
						        fieldLabel: 'End Date',
						        type: 'datefield',
						        name: 'duedt',
						        id: 'enddt',
						        vtype: 'daterange',
						        startDateField: 'startdt' // id of the start date field
					    	}),
				            
				           new Ext.form.TimeField({
				                fieldLabel: '@',
				                name: 'endtime',
				                minValue: '8:00am',
				                maxValue: '6:00pm'
				            })
        			]},{
			            xtype:'htmleditor',
			            id:'instructions',
			            fieldLabel:'Special Instructions',
			            height:100,
			            anchor:'98%'
	        		}
       		 ]       		 
       		 
    });

    /**
    * The action associated with adding a new assignment
    *
    */
    // explicit add
    var submitbutton = new Ext.Button({
        text: 'Save',
        
        handler: function(){
            addForm.getForm().submit({
            	url:'http://localhost/eteach/index.php', 
            	waitMsg:'Creating new Assignment...',
            	timeout:10,
            	params: {
			        module: 'turnitin',
			        action: 'ajax_addassignment'
			    },
			    success: function(form, action) {
			       win.hide();
			       addForm.getForm().reset();
			       assStore.load({params:{start:0, limit:25}});
		           Ext.example.msg('Success!', action.result.msg);
				  
			    },
			    failure: function(form, action) {
			       Ext.example.msg('Error', action.result.msg);
			    	/* switch (action.failureType) {
			            case Ext.form.Action.CLIENT_INVALID:
			                Ext.Msg.alert('Failure', 'Form fields may not be submitted with invalid values');
			                break;
			            case Ext.form.Action.CONNECT_FAILURE:
			                Ext.Msg.alert('Failure', 'Ajax communication failed');
			                break;
			            case Ext.form.Action.SERVER_INVALID:
			               Ext.Msg.alert('Failure', action.result.msg);
			       }*/
			    }
			});
        }
    });
var but =  new Ext.Button(action);
    /**
* The assignment data store used by the assGrid
*
*/
var assStore = new Ext.data.JsonStore({
        root: 'assignments',
        totalProperty: 'totalCount',
        idProperty: 'puid',
        //remoteSort: true,

        fields: [
            {name: 'title'},
            {name: 'duedate'},
            {name: 'instructions'}
        ],
        proxy: new Ext.data.HttpProxy({
            url: 'http://localhost/eteach/index.php?module=turnitin&action=json_getassessments'
        })
    });
    assStore.setDefaultSort('duedate', 'desc');
    
    //store for the submissions
    submissionsStore =new Ext.data.JsonStore({
        root: 'submissions',
        totalProperty: 'totalCount',
        idProperty: 'username',
        //remoteSort: true,

        fields: [
            {name: 'firstname'},
            {name: 'lastname'},
            {name: 'title'},
            {name: 'score'},
            {name: 'dateposted'}
        ],
        proxy: new Ext.data.HttpProxy({
            url: 'http://localhost/eteach/index.php?module=turnitin&action=json_getsubmissions'
        })
    });
    
    submissionsStore.setDefaultSort('dateposted', 'desc');
/**
* The assigments grid
* the grid will use ajax-json to get the data from
* server
*/

var assGrid = new Ext.grid.GridPanel({
        width:600,
        height:200,
        title:'Assignments',
        store: assStore,
        trackMouseOver:true,
        disableSelection:true,
        loadMask: true,
        stripeRows: true,
		sm: new Ext.grid.RowSelectionModel({singleSelect:true}),
		region:'center',


        // grid columns
        columns:[new Ext.grid.RowNumberer(),{
            id: 'topic', // id assigned so we can apply custom css (e.g. .x-grid-col-topic b { color:#333 })
            header: "Topic",
            dataIndex: 'title',
            width: 420,
            //renderer: renderTopic,
            sortable: true
        },{
            header: "Submissions",
            dataIndex: 'submissioncount',
            width: 120,
            align: 'right',
            sortable: true
        },{
            id: 'last',
            header: "Due Date",
            dataIndex: 'duedate',
            width: 150,
            //renderer: renderLast,
            sortable: true
        }],
		tbar :[ but ],
        // customize view config
        viewConfig: {
            forceFit:true,
            enableRowBody:true,
            showPreview:false,
            getRowClass : function(record, rowIndex, p, store){
                if(this.showPreview){
                    p.body = '<p>'+record.data.instructions+'</p>';
                    return 'x-grid3-row-expanded';
                }
                return 'x-grid3-row-collapsed';
            }
        },
        // paging bar on the bottom
        bbar: new Ext.PagingToolbar({
            pageSize: 25,
            store: assStore,
            displayInfo: true,
            displayMsg: 'Displaying topics {0} - {1} of {2}',
            emptyMsg: "No topics to display",
            items:[
                '-', {
                pressed: false,
                enableToggle:true,
                text: 'Show Instructions',
                cls: 'x-btn-text-icon details',
                toggleHandler: function(btn, pressed){
                    var view = assGrid.getView();
                    view.showPreview = pressed;
                    view.refresh();
                }
            }]
        })

        
});

/**
* Submissions grid 
*/

var submissionsGrid =  new Ext.grid.GridPanel({
        width:600,
        height:200,
        title:'Submissions',
        store: submissionsStore,
        trackMouseOver:true,        
        loadMask: true,
        stripeRows: true,
        minHeight: 100,
        maxHeight: 200,
		//sm: new Ext.grid.RowSelectionModel({singleSelect:true}),
		//region:'center',


        // grid columns
        columns:[new Ext.grid.RowNumberer(), 
        {
            id: 'firstame', // id assigned so we can apply custom css (e.g. .x-grid-col-topic b { color:#333 })
            header: "First Name",
            dataIndex: 'firstname',
            width: 100,
            //renderer: renderTopic,
            sortable: true
        },{id: 'lastname', // id assigned so we can apply custom css (e.g. .x-grid-col-topic b { color:#333 })
            header: "Last Name",
            dataIndex: 'lastname',
            width: 100,
            //renderer: renderTopic,
            sortable: true
        },{id: 'title', // id assigned so we can apply custom css (e.g. .x-grid-col-topic b { color:#333 })
            header: "Title",
            dataIndex: 'title',
            width: 200,
            //renderer: renderTopic,
            sortable: true
        },{id: 'score', // id assigned so we can apply custom css (e.g. .x-grid-col-topic b { color:#333 })
            header: "Score",
            dataIndex: 'score',
            renderer:renderScore,
            width: 100,
            //renderer: renderTopic,
           // sortable: true
        },{id: 'dateposted', // id assigned so we can apply custom css (e.g. .x-grid-col-topic b { color:#333 })
            header: "Date Submitted",
            dataIndex: 'dateposted',
            width: 100,
            //renderer: renderTopic,
           // sortable: true
        }]
        
	});
	
var submissionsPanel = new Ext.Panel({
        width:600,
        height:200,
        layout:'fit',
        margins: '5 5 0',
        region: 'south',
        split: true,
        //hidden:true,
        minHeight: 100,
        maxHeight: 200,

        items:[submissionsGrid]
})

Ext.onReady(function(){

    // render it
    //assGrid.render('topic-grid');

    
      var layout = new Ext.Panel({
        //title: 'Employee Salary by Month',
        layout: 'border',
        layoutConfig: {
            columns: 1
        },
        width:600,
        height: 400,
        items: [assGrid, submissionsPanel]
    });
    layout.render('topic-grid');

    // trigger the data store load
    assStore.load({params:{start:0, limit:25}});
    //submissionsStore.load();
    
    assGrid.getSelectionModel().on('rowselect', function(sm, ri, record){
        //grid.removeBtn.setDisabled(sm.getCount() < 1);
        //submissionsPanel.setVisible(true);
        submissionsStore.load({params: {title:record.data.title}});
        submissionsGrid.setTitle('Submissions for - '+record.data.title);
        //Ext.example.msg('Success!', record.data.title);
    });

});


/*

    Ext.QuickTips.init();
    
    var but =  new Ext.Button(action);
 
    var xg = Ext.grid;

    // shared reader
    var reader = new Ext.data.ArrayReader({}, [
       {name: 'company'},
       {name: 'price', type: 'float'},
       {name: 'change', type: 'float'},
       {name: 'pctChange', type: 'float'},
       {name: 'lastChange', type: 'date', dateFormat: 'n/j h:ia'},
       {name: 'industry'},
       {name: 'desc'}
    ]);
  

    astore = new Ext.data.GroupingStore({
            reader: reader,
           
            sortInfo:{field: 'company', direction: "ASC"},
            groupField:'industry',
            proxy: new Ext.data.HttpProxy({        	 	
            	url: 'http://localhost/eteach/index.php?module=turnitin&action=json_getassessments'
        	})
        });
   
    function renderScore(value, p, record){
    	var cid = 'green';
    	
    	if (value > 20 && value < 40)
    	{
    		cid = 'yellow';
    	} else if (value > 40) {
    		cid = 'red';
    	} else if (value < 1){
    		cid = 'pending';    		
    	}
    	if (value < 1)
    	{
    		value = '--&nbsp;';
    	} else {
    		value = value+'%';
    	}
        return String.format('<a href="#" class="'+cid+'" onClick="window.open(\'http://localhost/eteach/index.php?module=turnitin&action=returnreport&objectid=101049555\',\'rview\',\'height=768,width=1024,location=no,menubar=no,resizable=yes,scrollbars=yes,titlebar=no,toolbar=no,status=no\');" ><span class="white">{0}</span> </a>', 
        	value, record.data.contextcode);
        	
    }
    
    var grid = new xg.GridPanel({
        store: astore,

        columns: [
            {id:'company',header: "Author", width: 60, sortable: true, dataIndex: 'company'},
            {header: "Score", width: 20, align: 'center', sortable: true, renderer: renderScore, dataIndex: 'price'},
            {header: "File", width: 20, hidden: true, sortable: true, dataIndex: 'change', renderer: Ext.util.Format.usMoney},
            {header: "Assessment", width: 20, hidden: true, sortable: true, dataIndex: 'industry'},
            {header: "Date Submitted", width: 20, sortable: true, renderer: Ext.util.Format.dateRenderer('m/d/Y'), dataIndex: 'lastChange'}
        ],

        view: new Ext.grid.GroupingView({
            forceFit:true,
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Items" : "Item"]})'
        }),

        frame:true,
        width: 700,
        height: 450,
        collapsible: true,
        animCollapse: true,
        loadMask: true,
        emptyGroupText: "No assessments were submitted",
        title: 'Assessments',
        iconCls: 'icon-grid',
        renderTo: 'topic-grid',
        
      	tbar :[ but ],
      
       
    });
    
     astore.load();*/

 function renderScore(value, p, record){
    	var cid = 'green';
    	
    	if (value > 20 && value < 40)
    	{
    		cid = 'yellow';
    	} else if (value > 40) {
    		cid = 'red';
    	} else if (value < 1){
    		cid = 'pending';    		
    	}
    	if (value < 1)
    	{
    		value = '--&nbsp;';
    	} else {
    		value = value+'%';
    	}
        return String.format('<a href="#" class="'+cid+'" onClick="window.open(\'http://localhost/eteach/index.php?module=turnitin&action=returnreport&objectid=101049555\',\'rview\',\'height=768,width=1024,location=no,menubar=no,resizable=yes,scrollbars=yes,titlebar=no,toolbar=no,status=no\');" ><span class="white">{0}</span> </a>', 
        	value, record.data.contextcode);
        	
    }