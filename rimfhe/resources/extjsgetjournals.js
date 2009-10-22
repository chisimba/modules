/*!
 * Ext JS Library 3.0.0
 * Copyright(c) 2006-2009 Ext JS, LLC
 * licensing@extjs.com
 * http://www.extjs.com/license
 */
Ext.onReady(function(){
    var dataRecordParts = new Ext.data.Record.create([  
     {name: 'jid'},  
     {name: 'jname'}  
    ]); 
    var dataReaderParts = new Ext.data.JsonReader({  
      root: 'searchresults',
      totalProperty: 'journalcount',
      id: 'jid'
     },
     dataRecordParts  
    );      
    var dataProxyParts = new Ext.data.HttpProxy({  
     url: uri
    });
    
    var dataStoreParts = new Ext.data.Store({
     proxy: dataProxyParts,  
     reader: dataReaderParts, 
    });
    // Custom rendering Template
    var resultTpl = new Ext.XTemplate(
        '<tpl for="."><div class="search-item">',
            '{jname}',
        '</div></tpl>'
    );
    
    var jsearch = new Ext.form.ComboBox({
        store: dataStoreParts,
        displayField:'jname',
        typeAhead: false,
        emptyText: 'Start typing...',
        loadingText: 'Searching...',
        width: 350,
        pageSize:10,
        hideTrigger:false,
        tpl: resultTpl,
        applyTo: 'input_journalname2',
        itemSelector: 'div.search-item',
        onSelect: function(record){ // override default onSelect to do redirect]
            var currentData = record.data.jname;
            jQuery("input[id='input_journalname']").val(record.data.jname);
            //jQuery("input[id='input_journalname2']").val(record.data.titlej);
            this.collapse();
        }        
    });
    /*
				var parts = new Ext.form.ComboBox({  
				 store: dataStoreParts,  
				 fieldLabel: 'Journal Name',   
				 displayField:'jname',  
				 valueField: 'Journal Id',  
				 hiddenName: 'jid',  
				 allowBlank: false,  
				 pageSize: 5,  
				 minChars: 2,  
				 hideTrigger: true,  
				 typeAhead: true,  
				 mode: 'remote',   
				 triggerAction: 'all',   
				 emptyText: 'Select a Part...',   
				 selectOnFocus: false,  
				 width: 260  
				 }); */
});
