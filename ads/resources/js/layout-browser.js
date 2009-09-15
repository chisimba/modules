/*!
 * Ext JS Library 3.0.0
 * Copyright(c) 2006-2009 Ext JS, LLC
 * licensing@extjs.com
 * http://www.extjs.com/license
 */

//
// This is the main layout definition.
//
Ext.onReady(function(){
	
	Ext.QuickTips.init();
	
	// This is an inner body element within the Details panel created to provide a "slide in" effect
	// on the panel body without affecting the body's box itself.  This element is created on
	// initial use and cached in this var for subsequent access.
	var detailEl;
        
        /*
         * ================  Start page config  =======================
         */
        // The default start page, also a simple example of a FitLayout.
        var start = {
                id: 'start-panel',
                title: 'Start Page',
                layout: 'fit',
                bodyStyle: 'padding:25px',
                contentEl: 'start-div'  // pull existing content from the page
        };

        /*
         * ================  FormLayout config  =======================
         */
        // NOTE: While you can create a basic Panel with layout:'form', practically
        // you should usually use a FormPanel to also get its form-specific functionality.
        // Note that the layout config is not required on FormPanels.
        var form = {
                xtype: 'form', // since we are not using the default 'panel' xtype, we must specify it
                id: 'form-panel',
            labelWidth: 75,
            title: 'Form Layout',
            bodyStyle: 'padding:15px',
            width: 350,
                labelPad: 20,
                layoutConfig: {
                        labelSeparator: ''
                },
            defaults: {
                        width: 230,
                        msgTarget: 'side'
                },
            defaultType: 'textfield',
            items: [{
                    fieldLabel: 'First Name',
                    name: 'first',
                    allowBlank:false
                },{
                    fieldLabel: 'Last Name',
                    name: 'last'
                },{
                    fieldLabel: 'Company',
                    name: 'company'
                },{
                    fieldLabel: 'Email',
                    name: 'email',
                    vtype:'email'
                }
            ],
            buttons: [{text: 'Save'},{text: 'Cancel'}]
        };
	
	// This is the main content center region that will contain each example layout panel.
	// It will be implemented as a CardLayout since it will contain multiple panels with
	// only one being visible at any given time.
	var contentPanel = {
		id: 'content-panel',
		region: 'center', // this is what makes this panel into a region within the containing layout
		layout: 'card',
		margins: '2 5 5 0',
		activeItem: 0,
		border: false,
		items: [start, form]
	};
    
	// Go ahead and create the TreePanel now so that we can use it below
    var treePanel = new Ext.tree.TreePanel({
    	id: 'tree-panel',
    	title: 'Sample Layouts',
        region:'north',
        split: true,
        height: 300,
        minSize: 150,
        autoScroll: true,
        
        // tree-specific configs:
        rootVisible: false,
        lines: false,
        singleExpand: true,
        useArrows: true,
        
        loader: new Ext.tree.TreeLoader({
                id: 1,
                text: 'A leaf Node',
                leaf: true
            },{
                id: 2,
                text: 'A folder Node',
                children: [{
                    id: 3,
                    text: 'A child Node',
                    leaf: true
                }]
        }),
        
        root: new Ext.tree.AsyncTreeNode()
    });
    
	// Assign the changeLayout function to be called on tree node click.
    treePanel.on('click', function(n){
    	var sn = this.selModel.selNode || {}; // selNode is null on initial selection
    	if(n.leaf && n.id != sn.id){  // ignore clicks on folders and currently selected node 
    		Ext.getCmp('content-panel').layout.setActiveItem(n.id + '-panel');
    	}
    });
    	
	
	// Finally, build the main layout once all the pieces are ready.  This is also a good
	// example of putting together a full-screen BorderLayout within a Viewport.
    new Ext.Viewport({
		layout: 'border',
		title: 'Ext Layout Browser',
		items: [{
			xtype: 'box',
			region: 'north',
			applyTo: 'header',
			height: 30
		},{
			layout: 'border',
	    	id: 'layout-browser',
	        region:'west',
	        border: false,
	        split:true,
			margins: '2 0 5 5',
	        width: 275,
	        minSize: 100,
	        maxSize: 500,
			items: [treePanel]
		}],
        renderTo: 'myLayout'
    });
});
