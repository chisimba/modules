

var ds = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
           url: baseUri+'?module=twitterizer&action=json_gettweets'
        }),
        reader: new Ext.data.JsonReader({
            root: 'tweets',
            totalProperty: 'totalCount',
            id: 'id'
        }, [
            {name: 'postId', mapping: 'id'},            
            {name: 'image', mapping: 'image'},
            {name: 'name', mapping: 'name'},
            {name: 'screen_name', mapping: 'screen_name'},
            {name: 'lastPost', mapping: 'createdat'},//, type: 'date', dateFormat: 'timestamp'},
            {name: 'excerpt', mapping: 'tweet'}
        ]),

        baseParams: {limit:20, forumId: 4}
    });
//'<span>{lastPost:date("M j, Y")}<br />@ {lastPost:date("g:i a")}</span>', 
    // Custom rendering Template for the View
    var resultTpl = new Ext.XTemplate(
        '<tpl for=".">',
        '<div class="search-item">', 
        	'<a href="http://www.twitter.com/{screen_name}" target="_blank"><img src="{image}"></a>',    	     	
            '<span>{lastPost}</span>',                       
            '<div style="margin-top:8px;padding-left:5px;"><a href="http://www.twitter.com/{screen_name}" target="_blank">{name}</a>',           
            '<p>{excerpt}</p></div>',
        '</div></tpl>'
    );

    var statsButton  = new Ext.Button({
            text:'Stats',
            tooltip:'View the current stats...',
            iconCls:'silk-user-comment',
			id:'statsbut',
            // Place a reference in the GridPanel
            //ref: '../../removeButton',
            //disabled: true,
            handler: function(){
            	//doAddUsers();
            	alert('clicked');
            }
        });
        
      var SIOCButton  = new Ext.Button({
            text:'SIOC export',
            tooltip:'SIOC export...',
            iconCls:'silk-rss',
			id:'SIOC',
            // Place a reference in the GridPanel
            //ref: '../../removeButton',
            //disabled: true,
            handler: function(){
            	//doAddUsers();
            	alert('clicked');
            }
        });
        
    var middlePanel = new Ext.Panel({
       // applyTo: 'search-panel',
        title: terms,
        region: 'center',
        margins:'0 20px 0 20px',
		width: 800,
		padding: '5px',	
		autoScroll: true,
		loadMask: true,		

        items: new Ext.DataView({
            tpl: resultTpl,
            store: ds,
            itemSelector: 'div.search-item'
        }),

        tbar: [
	       
            'Search: ', ' ',
            new Ext.ux.form.SearchField({
                store: ds,
                iconCls:'zoom',
                width:320
            }), '-',  statsButton, 
	        SIOCButton
        ],

        bbar: new Ext.PagingToolbar({
            store: ds,
            pageSize: 20,
            displayInfo: true,
            displayMsg: 'Topics {0} - {1} of {2}',
            emptyMsg: "No topics to display"
        })
        
        
    });

    
