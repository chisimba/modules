/*!
 * Ext JS Library 3.0.0
 * Copyright(c) 2006-2009 Ext JS, LLC
 * licensing@extjs.com
 * http://www.extjs.com/license
 */
var showLatestUploads = function(url) {

    var tree = new Ext.ux.tree.ColumnTree({
        autoWidth: true,
        autoHeight: true,
        rootVisible:false,
        autoScroll:true,
        title: 'Last 10 Uploads',
        renderTo: 'recent-uploads',
        
        columns:[{
            header:'Filename',
            width: 250,
            dataIndex:'filename'
        },{
            header:'File Type',
            width: 80,
            dataIndex:'duration'
        },{
            header:'View Details',
            width: 125,
            dataIndex:'details'
        }, {
            header:'Date Last Modified',
            width: 100,
            dataIndex:'modified'
        }, {
            header:'Status',
            width: 50,
            dataIndex:'status'
        }
        ],

        loader: new Ext.tree.TreeLoader({
            dataUrl: url,
            uiProviders:{
                'col': Ext.ux.tree.ColumnNodeUI
            }
        }),

        listeners: {
            dblclick: function(node, event) {
                Ext.Msg.alert('Navigation Tree Click', 'You Double clicked: ' + node.toString());
            }
        },

        root: new Ext.tree.AsyncTreeNode({
            text:'Filename'
        })
    });
}