/*!
 * Ext JS Library 3.0.0
 * Copyright(c) 2006-2009 Ext JS, LLC
 * licensing@extjs.com
 * http://www.extjs.com/license
 */
Ext.onReady(function(){
    var ds = new Ext.data.Store({
        proxy: new Ext.data.ScriptTagProxy({
            url: uri
        }),
        reader: new Ext.data.JsonReader({
            root: 'searchresults',
            totalProperty: 'journalcount',
            id: 'jid'
        }, [
            {name: 'journalid', mapping: 'jid'},
            {name: 'jname', mapping: 'jname'}
        ])
    });
    // Custom rendering Template
    var resultTpl = new Ext.XTemplate(
        '<tpl for="."><div class="search-item">',
            '<p>{jname}</p>','{excerpt}',
        '</div></tpl>'
    );
    var search = new Ext.form.ComboBox({
        store: ds,
        displayField:'jname',
        typeAhead: false,
        loadingText: 'Searching...',
        width: 570,
        pageSize:10,
        hideTrigger:true,
        tpl: resultTpl,
        applyTo: 'search',
        itemSelector: 'div.search-item',
        onSelect: function(record){
            jQuery('#search').html(record.data.jname);
        }
    });
});
