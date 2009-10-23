/*!
 * Ext JS Library 3.0.0
 * Copyright(c) 2006-2009 Ext JS, LLC
 * licensing@extjs.com
 * http://www.extjs.com/license
 */
Ext.onReady(function(){  
    var dataStoreParts = new Ext.data.Store({
     proxy: new Ext.data.HttpProxy({  
     url: uri, method: 'GET'
    }),
     reader: new Ext.data.JsonReader({  
      root: 'searchresults',
     },[  
      {name: 'jid', mapping :'jid' },  
      {name: 'jname', mapping : 'jname' }  
     ])
    });
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
        width: 352,
        pageSize:10,
        hideTrigger:false,
        hiddenName: 'query',
        triggerAction: 'all',
        tpl: resultTpl,
        applyTo: 'input_journalname2',
        itemSelector: 'div.search-item',
        selectOnFocus:true,
        onSelect: function(record){ // override default onSelect to do redirect]
            var currentData = record.data.jname;
            jQuery("input[id='input_journalname']").val(record.data.jname);
            //jQuery("input[id='input_journalname2']").val(record.data.titlej);
            this.collapse();
        }        
    });
});
