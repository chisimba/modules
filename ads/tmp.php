<?php ?>
<script type="text/javascript">
Ext.onReady(function(){

	Ext.QuickTips.init();

	var store2 = new Ext.data.Store({
        	proxy: new Ext.data.HttpProxy({url: 'index.php',method: "GET"}),
        	baseParams:{option:'gl_content',task:'loadCats'},
        	reader: new Ext.data.JsonReader({
        	totalProperty: 'results',
        	root:'items'
        }, [{name: 'id'}, {name: 'title'}])
    });


	var contentSection = new Ext.form.ComboBox({
   		typeAhead: true,
		triggerAction: 'all',
    		transform: 'contentSection',
		width:135,
		editable: false,
		forceSelection:true
	});

	var contentCategory = new Ext.form.ComboBox({
		store: store2,
   		typeAhead: true,
		displayField:'title',
        	valueField: 'id' ,
		triggerAction: 'all',
		mode: 'local',
    		transform: 'contentCategory',
		emptyText:'Select a Category ...',
		width:135,
		editable: false,
		forceSelection:true
	});

	contentSection.on('select', function () {
		contentCategory.reset();
		contentCategory.store.load({params:{sectId:contentSection.getValue()}});
	});

});
</script>
<? ?>
