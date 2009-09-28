/*!
 * Ext JS Library 3.0.0
 * Copyright(c) 2006-2009 Ext JS, LLC
 * licensing@extjs.com
 * http://www.extjs.com/license
 */
var panel;
var courseId;
var actionUrl;
var actionWord;
var actionFunction;
var renderSurface;
var searchUrl;
var phase;
function showSearchWinX(){
    var args = showSearchWinX.arguments;
    courseId=args[0];
    actionUrl=args[1];
    actionWord=args[2];
    actionFunction=args[3];
    renderSurface=args[4];
    searchUrl=args[5];
    phase=args[6];
    
    
    var ds = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url:searchUrl
        }),
        reader: new Ext.data.JsonReader({
            root: 'rows',
            totalProperty: 'totalCount',
            id: 'userid'
        }, [
        {
            name: 'userid',
            mapping: 'userid'
        },

        {
            name: 'firstname',
            mapping: 'firstname'
        },

        {
            name: 'lastname',
            mapping: 'lastname'
        },

        {
            name: 'email',
            mapping: 'email'
        }
        ]),

        baseParams: {
            limit:20,
            userId: 1
        }
    });

    // Custom rendering Template for the View
    var resultTpl = new Ext.XTemplate(
        '<tpl for=".">',
        '<div class="search-item">',
        '<h3>{firstname} {lastname}</h3><h6>{email}</h6>',
        '<p><span><a href="#" onclick="'+actionFunction+'(\''+actionUrl+'\',\'{email}\',\''+courseId+'\',\'{userid}\',\''+phase+'\');return false;">'+actionWord+'</a></span></p>',
            
        '</div></tpl>'
        );

    panel = new Ext.Panel({
     
        height:300,
        autoScroll:true,
        bodyCssClass:  'search-item',
        items: new Ext.DataView({
            tpl: resultTpl,
            store: ds,
            itemSelector: 'div.search-item'
        }),

        tbar: [
        'Search: ', ' ',
        new Ext.ux.form.SearchField({
            store: ds,
            width:320
        })
        ],

        bbar: new Ext.PagingToolbar({
            store: ds,
            pageSize: 21,
            displayInfo: true,
            displayMsg: 'User {0} - {1} of {2}',
            emptyMsg: "No  users to display"
        })
    });

    ds.load({
        params:{
            start:0,
            limit:10,
            userId: 1
        }
        });

    // create and show window
    var win;
    //if(!win){
    win = new Ext.Window({
        id:'combo-win'
        ,
        title:'Search users'
        ,
        width:500
        ,
        x:250
        ,
        y: 150
        ,
        height:400
        ,
        closable:false
        ,
        border:false
        ,
        applyTo:renderSurface
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
            
            panel

            ]
        },

        buttons:[{
            text:'Close'
            ,
            iconCls:'icon-undo'
            ,
            scope:this
            ,
            handler:function() {
                //this.store.rejectChanges();
                win.hide();
              window.location.href='?module=ads&action=showcourseprophist'+'&selectedtab=0&courseid='+courseId;
                
            }
        }]

    });
    //}
    win.show();
}
