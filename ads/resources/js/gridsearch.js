// vim: sw=4:ts=4:nu:nospell:fdc=4
/**
 * Ext.ux.grid.Search Plugin Example Application
 *
 * @author    Ing. Jozef Sakáloš
 * @copyright (c) 2008, by Ing. Jozef Sakáloš
 * @date      5. April 2008
 * @version   $Id: gridsearch.js 62 2008-04-30 22:36:42Z jozo $
 *
 * @license gridsearch.js is licensed under the terms of the Open Source
 * LGPL 3.0 license. Commercial use is permitted to the extent that the 
 * code/component(s) do NOT become part of another Open Source or Commercially
 * licensed development library or toolkit without explicit permission.
 * 
 * License details: http://www.gnu.org/licenses/lgpl.html
 */
 
/*global Ext, Example, WebPage, window */
 
Ext.ns('Example', 'WebPage');
Ext.BLANK_IMAGE_URL = location.href+'/core_modules/htmlelements/ext/resources/ext-3.0-rc2/resources/images/default/s.gif';
Ext.state.Manager.setProvider(new Ext.state.CookieProvider());
Example.version = '1.1';

// {{{
Example.Grid1 = Ext.extend(Ext.grid.EditorGridPanel, {
    layout:'fit'
    ,
    border:false
    ,
    stateful:false
    ,
    url:location.href+"?module=ads&action=searchusers"
    ,
    objName:'username'
    ,
    idName:'userID'
    ,
    height:350,
    initComponent:function() {

        // create row actions
        this.rowActions = new Ext.ux.grid.RowActions({
            actions:[{
                iconCls:'icon-minus'
                ,
                qtip:'Delete Record'
                ,
                fieldLabel:'Forward'
                ,
                style:'margin:0 0 0 3px'
            }]
        });
        this.rowActions.on('action', this.onRowAction, this);

        Ext.apply(this, {
            // {{{
            store:new Ext.data.Store({
                reader:new Ext.data.JsonReader({
                    id:'userID'
                    ,
                    totalProperty:'totalCount'
                    ,
                    root:'rows'
                    ,
                    fields:[
                    {
                        name:'userID',
                        type:'int'
                    }
                    ,{
                        name:'username',
                        type:'string'
                    }
                    ,{
                        name:'firstname',
                        type:'string'
                    }
                    ,{
                        name:'lastname',
                        type:'string'
                    }
                    ,{
                        name:'email',
                        type:'string'
                    },this.rowActions
                    
                    ]
                })
                ,
                proxy:new Ext.data.HttpProxy({
                    url:this.url
                })
                ,
                baseParams:{
                    cmd:'getData',
                    objName:this.objName
                }
                ,
                remoteSort:true
            })

            ,
            columns:[{
                header:'username'
                ,
                id:'username'
                ,
                dataIndex:'username'
                ,
                width:200
                ,
                sortable:true
                ,
                editable:false
                ,
                editor:new Ext.form.TextField({
                    allowBlank:false
                })
            },{
                header:'First Name'
                ,
                dataIndex:'firstname'
                ,
                width:200
                ,
                sortable:true
                ,
                editable:false
                ,
                editor:new Ext.form.TextField({
                    allowBlank:false
                })
            },{
                header:'Last Name'
                ,
                dataIndex:'lastname'
                ,
                width:200
                ,
                sortable:true
                ,
                editable:false
                ,
                editor:new Ext.form.TextField({
                    allowBlank:false
                })
            },{
                header:'Email'
                ,
                dataIndex:'email'
                ,
                width:200
                ,
                sortable:true
                ,
                editable:false
                ,
                editor:new Ext.form.TextField({
                    allowBlank:false
                })
            }, 
            
            this.rowActions
            ]
            
            ,
            plugins:[new Ext.ux.grid.Search({
                iconCls:'icon-zoom'
                ,
                readonlyIndexes:['note']
                ,
                disableIndexes:['email']
                ,
                minChars:3
                ,
                autoFocus:true
            //				,menuStyle:'radio'
            }), this.rowActions]
            ,
            viewConfig:{
                forceFit:true
            }
            ,
            
            tbar:[]
        }); // eo apply

        this.bbar = new Ext.PagingToolbar({
            store:this.store
            ,
            displayInfo:true
            ,
            pageSize:10
        });

        // call parent
        Example.Grid1.superclass.initComponent.apply(this, arguments);
    } // eo function initComponent
    // {{{
    ,
    onRender:function() {
        // call parent
        Example.Grid1.superclass.onRender.apply(this, arguments);

        this.bbar2 = new Ext.Toolbar({
            renderTo:this.bbar
            ,
            items:['Example of second toolbar', '-', {
                text:'Button'
                ,
                iconCls:'icon-key'
            }, '-'
            ]
        });

        // load store
        this.store.load({
            params:{
                start:0,
                limit:10
            }
        });

    } // eo function onRender
    // }}}

    ,
    addRecord:function() {
    //		console.info("adding record");
    } // eo function addRecord

    ,
    onRowAction:function(grid, record, action, row, col) {
        
        switch(action) {
            case 'email':
                this.deleteRecord(record);
                break;

            default:
                break;
        }
    } // eo onRowAction

    ,
    commitChanges:function() {
        var records = this.store.getModifiedRecords();
        if(!records.length) {
            return;
        }
        var data = [];
        Ext.each(records, function(r, i) {
            data.push(r.data);
        }, this);
        var o = {
            url:this.url
            ,
            method:'post'
            ,
            callback:this.requestCallback
            ,
            scope:this
            ,
            params:{
                cmd:'saveData'
                ,
                objName:this.objName
                ,
                data:Ext.encode(data)
            }
        };
        Ext.Ajax.request(o);
    } // eo function commitChanges

    ,
    requestCallback:function(options, success, response) {
        if(true !== success) {
            this.showError(response.responseText);
            return;
        }
        try {
            var o = Ext.decode(response.responseText);
        }
        catch(e) {
            this.showError(response.responseText, 'Cannot decode JSON object');
            return;
        }
        if(true !== o.success) {
            this.showError(o.error || 'Unknown error');
            return;
        }

        switch(options.params.cmd) {
            case 'saveData':
                var records = this.store.getModifiedRecords();
                Ext.each(records, function(r, i) {
                    if(o.insertIds && o.insertIds[i]) {
                        r.set(this.idName, o.insertIds[i]);
                        delete(r.data.newRecord);
                    }
                });
                this.store.commitChanges();
                break;

            case 'deleteData':
                break;
        }
    } // eo function requestCallback

    ,
    showError:function(msg, title) {
        Ext.Msg.show({
            title:title || 'Error'
            ,
            msg:Ext.util.Format.ellipsis(msg, 2000)
            ,
            icon:Ext.Msg.ERROR
            ,
            buttons:Ext.Msg.OK
            ,
            minWidth:1200 > String(msg).length ? 360 : 600
        });
    } // eo function showError

    ,
    deleteRecord:function(record) {
        Ext.Msg.show({
            title:'Delete record?'
            ,
            msg:'Do you really want to delete <b>' + record.get('username') + '</b><br/>There is no undo.'
            ,
            icon:Ext.Msg.QUESTION
            ,
            buttons:Ext.Msg.YESNO
            ,
            scope:this
            ,
            fn:function(response) {
                if('yes' !== response) {
                    return;
                }
            //				console.info('Deleting record');
            }
        });
    } // eo function deleteRecord

}); // eo extend

// register xtype
Ext.reg('examplegrid1', Example.Grid1);
// }}}

 
// application main entry point
Ext.onReady(function() {
    Ext.QuickTips.init();

    var adsenseHost =
    'gridsearch.localhost' === window.location.host
    || 'gridsearch.extjs.eu' === window.location.host
;


}); // eo function onReady

function showSearchWinX(){
    // create and show window
    var win;
    //if(!win){
    win = new Ext.Window({
        id:'combo-win'
        ,
        title:'Search users'
        
        ,
        width:700
        ,
        height:350
        ,
        closable:false
        ,
        border:false
        ,
        applyTo:'search-xwin'
        // let window code to focus the combo on show
        ,
        defaultButton:'combo'
        ,
        items:{
            xtype:'form'
            ,
            frame:true
            ,
           
            items:[
            {
                xtype:'examplegrid1',
                id:'examplegrid1'
            }
            ]
        },

        buttons:[{
            text:'Forward'
            ,
            iconCls:'icon-disk'
            ,
            scope:this
            ,
            handler:function() {
                //this.store.rejectChanges();
                win.hide();
                window.location.reload(true);
                Ext.MessageBox.alert('Please wait...');
            }
        },{
            text:'Close'
            ,
            iconCls:'icon-undo'
            ,
            scope:this
            ,
            handler:function() {
                //this.store.rejectChanges();
                win.hide();
                window.location.reload(true);
                Ext.MessageBox.alert('Please wait...');
            }
        }]
            
    });
    //}
    win.show();
}
// eof
