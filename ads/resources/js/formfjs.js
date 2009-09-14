var xcourseid;
function loadFormFJS(){
    var args=loadFormFJS.arguments;
    xcourseid=args[0];
    Ext.QuickTips.init();
    ButtonPanel = Ext.extend(Ext.Panel, {
            
        layout:'table',
        defaultType: 'button',
        baseCls: 'x-plain',
        cls: 'btn-panel',
        menu: undefined,
        split: true,

        constructor: function(buttons){
            for(var i = 0, b; b = buttons[i]; i++){
                b.menu = this.menu;
                b.enableToggle = this.enableToggle;
                b.split = this.split;
                b.arrowAlign = this.arrowAlign;
            }
            var items = buttons;

            ButtonPanel.superclass.constructor.call(this, {
                items: items
            });
        }
    });



        CommentsPanel = Ext.extend(Ext.Panel, {
      
        id:'commentspanel',
        collapsible:true,
        autoScroll:true,
        border:false,
        collapsed:true,
        textarea:null,
        buttonAlign:'left',
        constructor: function(dataview,textarea,button1){
           
            var items = [dataview,textarea,button1];

            CommentsPanel.superclass.constructor.call(this, {
                items: items
            });
        }});

 var resultTpl = new Ext.XTemplate(
        '<tpl for=".">',
        '<font  color="green"><div class="search-item">',
        '<b>{names}:{commentdate}</b><br/>',
        '<span>{comment}</span></font>',
        '</div></tpl>'
        );

    var ds1a = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url:location.href+"?module=ads&action=addquestioncomment"
        }),
        reader: new Ext.data.JsonReader({
            root: 'rows',
            totalProperty: 'totalCount',
            id: 'qid'
        }, [
            {name: 'names',mapping: 'names'},
            {name: 'commentdate', mapping: 'commentdate'},
            {name: 'comment', mapping: 'comment'}
        ])
    });
    var ds1b = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url:location.href+"?module=ads&action=addquestioncomment"
        }),
        reader: new Ext.data.JsonReader({
            root: 'rows',
            totalProperty: 'totalCount',
            id: 'qid'
        }, [
            {name: 'names',mapping: 'names'},
            {name: 'commentdate', mapping: 'commentdate'},
            {name: 'comment', mapping: 'comment'}
        ])
    });
    
    var ds2a = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url:location.href+"?module=ads&action=addquestioncomment"
        }),
        reader: new Ext.data.JsonReader({
            root: 'rows',
            totalProperty: 'totalCount',
            id: 'qid'
        }, [
            {name: 'names',mapping: 'names'},
            {name: 'commentdate', mapping: 'commentdate'},
            {name: 'comment', mapping: 'comment'}
        ])
    });

    var ds2b = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url:location.href+"?module=ads&action=addquestioncomment"
        }),
        reader: new Ext.data.JsonReader({
            root: 'rows',
            totalProperty: 'totalCount',
            id: 'qid'
        }, [
            {name: 'names',mapping: 'names'},
            {name: 'commentdate', mapping: 'commentdate'},
            {name: 'comment', mapping: 'comment'}
        ])
    });

    var ds3a = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url:location.href+"?module=ads&action=addquestioncomment"
        }),
        reader: new Ext.data.JsonReader({
            root: 'rows',
            totalProperty: 'totalCount',
            id: 'qid'
        }, [
            {name: 'names',mapping: 'names'},
            {name: 'commentdate', mapping: 'commentdate'},
            {name: 'comment', mapping: 'comment'}
        ])
    });

    var ds3b = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url:location.href+"?module=ads&action=addquestioncomment"
        }),
        reader: new Ext.data.JsonReader({
            root: 'rows',
            totalProperty: 'totalCount',
            id: 'qid'
        }, [
            {name: 'names',mapping: 'names'},
            {name: 'commentdate', mapping: 'commentdate'},
            {name: 'comment', mapping: 'comment'}
        ])
    });

    var ds4 = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url:location.href+"?module=ads&action=addquestioncomment"
        }),
        reader: new Ext.data.JsonReader({
            root: 'rows',
            totalProperty: 'totalCount',
            id: 'qid'
        }, [
            {name: 'names',mapping: 'names'},
            {name: 'commentdate', mapping: 'commentdate'},
            {name: 'comment', mapping: 'comment'}
        ])
    });

    var dataview1a=new Ext.DataView({
        tpl: resultTpl,
        store: ds1a
    });
   
     var dataview1b=new Ext.DataView({
        tpl: resultTpl,
        store: ds1b
    });

    var dataview2a=new Ext.DataView({
        tpl: resultTpl,
        store: ds2a
    });

    var dataview2b=new Ext.DataView({
        tpl: resultTpl,
        store: ds2b
    });

    var dataview3a=new Ext.DataView({
        tpl: resultTpl,
        store: ds3a
    });
 
    var dataview3b=new Ext.DataView({
        tpl: resultTpl,
        store: ds3b
    });
    var dataview4=new Ext.DataView({
        tpl: resultTpl,
        store: ds4
    });

    var textarea1a=  new Ext.form.TextArea({
        width: 500,
        height: 100
    });
   
    var textarea1b=  new Ext.form.TextArea({
        width: 500,
        height: 100
    });

    var textarea2a=  new Ext.form.TextArea({
        width: 500,
        height: 100
    });

    var textarea2b=  new Ext.form.TextArea({
        width: 500,
        height: 100
    });
 
    var textarea3a=  new Ext.form.TextArea({
        width: 500,
        height: 100
    });

    var textarea3b=  new Ext.form.TextArea({
        width: 500,
        height: 100
    });

    var textarea4=  new Ext.form.TextArea({
        width: 500,
        height: 100
    });

    CommentsPanel.override({ renderTo : 'question1acomment' });
  
   commentsPanel1a= new CommentsPanel(
    dataview1a,
    textarea1a,
    new Ext.Button({
        text:'Save',
        handler: function(){
      
        ds1a.load({
        params:{
            courseid:args[0],
            formnumber:'F',
            question:'F1a',
            comment: textarea1a.getValue()
        }
        });
        textarea1a.setValue('');
        }
    })
    );

    CommentsPanel.override({ renderTo : 'question1bcomment' });
  
   commentsPanel1b= new CommentsPanel(
    dataview1b,
    textarea1b,
    new Ext.Button({
        text:'Save',
        handler: function(){
      
        ds1b.load({
        params:{
            courseid:args[0],
            formnumber:'F',
            question:'F1b',
            comment: textarea1b.getValue()
        }
        });
        textarea1b.setValue('');
        }
    })
    );

    CommentsPanel.override({ renderTo : 'question1bcomment' });
  
   commentsPanel1b= new CommentsPanel(
    dataview1b,
    textarea1b,
    new Ext.Button({
        text:'Save',
        handler: function(){
      
        ds1b.load({
        params:{
            courseid:args[0],
            formnumber:'F',
            question:'F1b',
            comment: textarea1b.getValue()
        }
        });
        textarea1b.setValue('');
        }
    })
    );

    CommentsPanel.override({ renderTo : 'question2acomment' });
  
   commentsPanel2a= new CommentsPanel(
    dataview2a,
    textarea2a,
    new Ext.Button({
        text:'Save',
        handler: function(){
      
        ds2a.load({
        params:{
            courseid:args[0],
            formnumber:'F',
            question:'F2a',
            comment: textarea2a.getValue()
        }
        });
        textarea2a.setValue('');
        }
    })
    );

    CommentsPanel.override({ renderTo : 'question2bcomment' });
  
   commentsPanel2b= new CommentsPanel(
    dataview2b,
    textarea2b,
    new Ext.Button({
        text:'Save',
        handler: function(){
      
        ds2b.load({
        params:{
            courseid:args[0],
            formnumber:'F',
            question:'F2b',
            comment: textarea2b.getValue()
        }
        });
        textarea2b.setValue('');
        }
    })
    );

    CommentsPanel.override({ renderTo : 'question3acomment' });
  
   commentsPanel3a= new CommentsPanel(
    dataview3a,
    textarea3a,
    new Ext.Button({
        text:'Save',
        handler: function(){
      
        ds3a.load({
        params:{
            courseid:args[0],
            formnumber:'F',
            question:'F3a',
            comment: textarea3a.getValue()
        }
        });
        textarea3a.setValue('');
        }
    })
    );

    CommentsPanel.override({ renderTo : 'question3bcomment' });
  
   commentsPanel3b= new CommentsPanel(
    dataview3b,
    textarea3b,
    new Ext.Button({
        text:'Save',
        handler: function(){
      
        ds3b.load({
        params:{
            courseid:args[0],
            formnumber:'F',
            question:'F3b',
            comment: textarea3b.getValue()
        }
        });
        textarea3b.setValue('');
        }
    })
    );

    CommentsPanel.override({ renderTo : 'question4comment' });

   commentsPanel4= new CommentsPanel(
    dataview4,
    textarea4,
    new Ext.Button({
        text:'Save',
        handler: function(){

        ds4.load({
        params:{
            courseid:args[0],
            formnumber:'F',
            question:'F4',
            comment: textarea4.getValue()
        }
        });
        textarea4.setValue('');
        }
    })
    );

    ButtonPanel.override({ renderTo : 'question1acomment' });
    new ButtonPanel(
        [{
            id: 'cbutton1a',
            iconCls: 'commentadd',
            handler: function(){
                if(Ext.getCmp('cbutton1a').iconCls == 'commentadd'){
                    Ext.getCmp('cbutton1a').setIconClass('commentcollapse');
                    ds1a.load({params:{courseid:xcourseid,formnumber:'F', question:'F1a', comment: textarea1a.getValue()}});
                    commentsPanel1a.expand();
                }else{
                    Ext.getCmp('cbutton1a').setIconClass('commentadd');
                   commentsPanel1a.collapse()
                }
            }
        },
        {
            iconCls: 'help',
            handler: function(){

            }
        }
    ]
    );

    ButtonPanel.override({ renderTo : 'question1bcomment' });
    new ButtonPanel(
        [{
            id: 'cbutton1b',
            iconCls: 'commentadd',
            handler: function(){
                if(Ext.getCmp('cbutton1b').iconCls == 'commentadd'){
                    Ext.getCmp('cbutton1b').setIconClass('commentcollapse');
                    ds1b.load({params:{courseid:xcourseid,formnumber:'F', question:'F1b', comment: textarea1b.getValue()}});
                    commentsPanel1b.expand();
                }else{
                    Ext.getCmp('cbutton1b').setIconClass('commentadd');
                   commentsPanel1b.collapse()
                }
            }
        },
        {
            iconCls: 'help',
            handler: function(){

            }
        }
    ]
    );

    ButtonPanel.override({ renderTo : 'question2acomment' });
    new ButtonPanel(
        [{
            id: 'cbutton2a',
            iconCls: 'commentadd',
            handler: function(){
                if(Ext.getCmp('cbutton2a').iconCls == 'commentadd'){
                    Ext.getCmp('cbutton2a').setIconClass('commentcollapse');
                    ds2a.load({params:{courseid:xcourseid,formnumber:'F', question:'F2a', comment: textarea2a.getValue()}});
                    commentsPanel2a.expand();
                }else{
                    Ext.getCmp('cbutton2a').setIconClass('commentadd');
                   commentsPanel2a.collapse()
                }
            }
        },
        {
            iconCls: 'help',
            handler: function(){

            }
        }
    ]
    );

    ButtonPanel.override({ renderTo : 'question2bcomment' });
    new ButtonPanel(
        [{
            id: 'cbutton2b',
            iconCls: 'commentadd',
            handler: function(){
                if(Ext.getCmp('cbutton2b').iconCls == 'commentadd'){
                    Ext.getCmp('cbutton2b').setIconClass('commentcollapse');
                    ds2b.load({params:{courseid:xcourseid,formnumber:'F', question:'F2b', comment: textarea2b.getValue()}});
                    commentsPanel2b.expand();
                }else{
                    Ext.getCmp('cbutton2b').setIconClass('commentadd');
                   commentsPanel2b.collapse()
                }
            }
        },
        {
            iconCls: 'help',
            handler: function(){

            }
        }
    ]
    );

    ButtonPanel.override({ renderTo : 'question3acomment' });
    new ButtonPanel(
        [{
            id: 'cbutton3a',
            iconCls: 'commentadd',
            handler: function(){
                if(Ext.getCmp('cbutton3a').iconCls == 'commentadd'){
                    Ext.getCmp('cbutton3a').setIconClass('commentcollapse');
                    ds3a.load({params:{courseid:xcourseid,formnumber:'F', question:'F3a', comment: textarea3a.getValue()}});
                    commentsPanel3a.expand();
                }else{
                    Ext.getCmp('cbutton3a').setIconClass('commentadd');
                   commentsPanel3a.collapse()
                }
            }
        },
        {
            iconCls: 'help',
            handler: function(){

            }
        }
    ]
    );

    ButtonPanel.override({ renderTo : 'question3bcomment' });
    new ButtonPanel(
        [{
            id: 'cbutton3b',
            iconCls: 'commentadd',
            handler: function(){
                if(Ext.getCmp('cbutton3b').iconCls == 'commentadd'){
                    Ext.getCmp('cbutton3b').setIconClass('commentcollapse');
                    ds3b.load({params:{courseid:xcourseid,formnumber:'F', question:'F3b', comment: textarea3b.getValue()}});
                    commentsPanel3b.expand();
                }else{
                    Ext.getCmp('cbutton3b').setIconClass('commentadd');
                   commentsPanel3b.collapse()
                }
            }
        },
        {
            iconCls: 'help',
            handler: function(){

            }
        }
    ]
    );

    ButtonPanel.override({ renderTo : 'question4comment' });
    new ButtonPanel(
        [{
            id: 'cbutton4',
            iconCls: 'commentadd',
            handler: function(){
                if(Ext.getCmp('cbutton4').iconCls == 'commentadd'){
                    Ext.getCmp('cbutton4').setIconClass('commentcollapse');
                    ds4.load({params:{courseid:xcourseid,formnumber:'F', question:'F4', comment: textarea4.getValue()}});
                    commentsPanel4.expand();
                }else{
                    Ext.getCmp('cbutton4').setIconClass('commentadd');
                   commentsPanel4.collapse();
                }
            }
        },
        {
            iconCls: 'help',
            handler: function(){

            }
        }
    ]
    );
}