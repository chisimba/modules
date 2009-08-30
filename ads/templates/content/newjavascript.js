
 Ext.ns('Example');

 Example.comboConfig = {
 xtype:'combo'

 // we need id to focus this field. See window::defaultButton
 ,id:'combo'

 // we want to submit id, not text
 ,valueField:'persID'
 ,hiddenName:'persID'

 // could be undefined as we use custom template
 ,displayField:'persLastName'

 // query all records on trigger click
 ,triggerAction:'all'

 // minimum characters to start the search
 ,minChars:2

 // do not allow arbitrary values
 ,forceSelection:true

 // otherwise we will not receive key events
 ,enableKeyEvents:true

 // let's use paging combo
 ,pageSize:5

 // make the drop down list resizable
 ,resizable:true

 // we need wider list for paging toolbar
 ,minListWidth:220

 // force user to fill something
 ,allowBlank:false

 // store getting items from server
 ,store:new Ext.data.JsonStore({
 id:'persID'
 ,root:'rows'
 ,totalProperty:'totalCount'
 ,fields:[
 {name:'persID', type:'int'}
 ,{name:'persLastName', type:'string'}
 ,{name:'persFirstName', type:'string'}
 ]
 ,url:'".str_replace("amp;", "",$searchusers->href)."'
 ,baseParams:{
 cmd:'getData'
 ,objName:'person2'
 ,fields:'[\"persLastName\",\"persFirstName\"]'
 }
 })

 // concatenate last and first names
 ,tpl:'<tpl for="."><div class=\"x-combo-list-item\">{persLastName}, {persFirstName}</div></tpl>'

 // listeners
 ,listeners:{
 // sets raw value to concatenated last and first names
 select:function(combo, record, index) {
 this.setRawValue(record.get('persLastName') + ', ' + record.get('persFirstName'));
 }

 // repair raw value after blur
 ,blur:function() {
 var val = this.getRawValue();
 this.setRawValue.defer(1, this, [val]);
 }

 // set tooltip and validate
 ,render:function() {
 this.el.set(
 {qtip:'Type at least ' + this.minChars + ' characters to search in last name'}
 );
 this.validate();
 }

 // requery if field is cleared by typing
 ,keypress:{buffer:100, fn:function() {
 if(!this.getRawValue()) {
 this.doQuery('', true);
 }
 }}
 }

 // label
 ,fieldLabel:'Combo'
 };