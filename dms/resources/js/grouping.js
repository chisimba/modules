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
            width:400,
            dataIndex:'filename'
        },{
            header:'File Type',
            width:130,
            dataIndex:'duration'
        }],

        loader: new Ext.tree.TreeLoader({
            dataUrl: url,
            uiProviders:{
                'col': Ext.ux.tree.ColumnNodeUI
            }
        }),

        root: new Ext.tree.AsyncTreeNode({
            text:'Tasks'
        })
    });
}