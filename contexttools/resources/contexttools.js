

var filtername;
var filterTag;
var filterInstructions;
var filterType;
var filterParamName;
var filterParamValue;
function initContextTools(url,contexturl,filtersurl,baseurl){

    var tabs = new Ext.TabPanel({
        activeTab:0,
        renderTo: 'contexttools',
        frame:false,
        defaults:{
            autoHeight: true
        },
        items:[
        {
            contentEl:'contextlist',
            title: 'Contexts'
        },

        {
            contentEl:'filterlist',
            title: 'Filters'
        }
        ]
    });

    var contextreader=new Ext.data.JsonReader({
        root: "usercourses",
        id: "id"
    },[
    {
        name:'code'
    },

    {
        name:'title'
    }
        
    ]);
    
    var contextds = new Ext.data.Store({
        autoLoad:true,
        proxy: new Ext.data.HttpProxy({
            url: url,
            method: 'GET'
        }),
        reader: contextreader
    });


    var contextlistfield = new Ext.form.ComboBox({
        store: contextds,
        displayField:'title',
        fieldLabel:'Context links',
        typeAhead: true,
        mode: 'local',
        editable:false,
        forceSelection: true,
        triggerAction: 'all',
        emptyText:'Select context...',
        selectOnFocus:true,
        valueField:'code',
        hiddenName : 'contextlistfield'

    });

    var contextlistform = new Ext.form.FormPanel({

        baseCls: 'x-plain',
        width:550,
        labelWidth: 135,
        bodyStyle:'margin-left:2em;margin-top:2em;margin-bottom:2em;background-color:transparent;',
        renderTo: 'contextlist',
        collapsible: true,
        buttonAlign: 'left',
        border:false,
        items:[
        {
            xtype: 'fieldset',
            title: 'Context Links',
            autoHeight: true,
            bodyStyle:'margin-top:2em;margin-bottom:2em;',
            layout:'column',
            width:500,
            items: [
            contextlistfield

            ]
        }],


        buttons: [
        {
            iconCls: 'insert',
            text:'Insert',

            handler: function(){
                var contextcode=contextlistfield.value;
                var selectedText=window.opener.CKEDITOR.instances[instancename].getSelection().getNative();
                var link='<a href="'+contexturl+"&contextcode="+contextcode+'">'+selectedText+'</a>';
                window.opener.CKEDITOR.instances[instancename].insertHtml(link);
                window.close();
            }
        },        {
            iconCls: 'cancel',
            text:'Cancel',

            handler: function(){
                window.close();
            }
        }

        ]

    });

    var filtersreader=new Ext.data.JsonReader({
        root: "filters",
        id: "id4"
    },
    [
    {
        name:'name'
    },

    {
        name:'type'
    },
    {
        name:'label'
    },
    {
        name:'instructions'
    },
    {
        name:'tag'
    }
    ]);

    var filterparamsreader=new Ext.data.JsonReader({
        root: "params",
        id: "id4"
    },
    [
    {
        name:'name'
    },

    {
        name:'value'
    }
    ]);
    var filtersds = new Ext.data.Store({
        autoLoad:true,
        proxy: new Ext.data.HttpProxy({
            url: filtersurl,
            method: 'GET'
        }),
        reader: filtersreader
    });
    var paramsProxy=new Ext.data.HttpProxy({
        url: baseurl,
        method: 'GET'
    });
    var filterparamssds = new Ext.data.Store({
        autoLoad:false,
        proxy: paramsProxy,
        reader: filterparamsreader
    });
    var instructionsfield=new Ext.form.DisplayField(
    {
       
        }
        );
    var filterlistfield = new Ext.form.ComboBox({
        store: filtersds,
        displayField:'label',
        fieldLabel:'Filter list',
        typeAhead: true,
        mode: 'local',
        editable:false,
        forceSelection: true,
        triggerAction: 'all',
        emptyText:'Select filter...',
        selectOnFocus:true,
        valueField:'name',
        hiddenName : 'filterlistfield',
        listeners:{
            select: function(combo, record, index){
                filterTag= record.data.tag;
                filterInstructions=record.data.instructions;
                filterType=record.data.type;
                filtername=record.data.name;
                instructionsfield.setValue('<h4>'+filterInstructions+'</h4>' );
                filterparamssds.proxy=new Ext.data.HttpProxy({
                    url: baseurl+"&filtername="+filtername,
                    method: 'GET'
                });
                
                filterparamssds.load();
            }

        }

    });


    var filterparamfield = new Ext.form.ComboBox({
        store: filterparamssds,
        displayField:'value',
        fieldLabel:'Parameters list',
        typeAhead: true,
        mode: 'local',
        editable:false,
        forceSelection: true,
        triggerAction: 'all',
        emptyText:'Select parameter...',
        //selectOnFocus:true,
        valueField:'name',
        listeners:{
            select: function(combo, record, index){
                filterParamName=record.data.name;
                filterParamValue=record.data.value;

            }

        }

    });



    var filterlistform = new Ext.form.FormPanel({

        baseCls: 'x-plain',
        width:550,
        labelWidth: 135,
        bodyStyle:'margin-left:2em;margin-top:2em;margin-bottom:2em;background-color:transparent;',
        renderTo: 'filterlist',
        collapsible: true,
        buttonAlign: 'left',
        border:false,
        items:[
       
        {
            html:'<h3><font color="red">NOTE: The filter will modify the text selected in the editor</font></h3>',
            border:false,
            bodyStyle:'margin-bottom:3em'
        }
        ,
        filterlistfield,
        filterparamfield,
        
        {
            xtype: 'fieldset',
            title: 'Filter instructions',
            autoHeight: true,
            width:500,
            items:[
            instructionsfield
            ]
        }
         
        ],


        buttons: [
        {
            iconCls: 'insert',
            text:'Insert filter',

            handler: function(){
                
                var selectedText=window.opener.CKEDITOR.instances[instancename].getSelection().getNative();
                var filter='';
                if(filterType=='parametized')
                    filter='['+filterTag+':'+filterParamName+'='+filterParamValue+']' +selectedText+'[/'+filterTag+']';
                else
                    filter='['+filterTag+']' +selectedText+'[/'+filterTag+']';

                window.opener.CKEDITOR.instances[instancename].insertHtml(filter);
                window.close();
            }
        },        {
            iconCls: 'cancel',
            text:'Cancel',

            handler: function(){
                window.close();
            }
        }

        ]

    });
}