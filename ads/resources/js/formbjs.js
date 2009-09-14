var xcourseid;
function loadFormBJS(){
    var args=loadFormBJS.arguments;
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

    var ds1 = new Ext.data.Store({
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

    var ds2 = new Ext.data.Store({
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

    var ds4a = new Ext.data.Store({
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

    var ds4b = new Ext.data.Store({
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

    var ds4c = new Ext.data.Store({
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

    var ds5a = new Ext.data.Store({
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

    var ds5b = new Ext.data.Store({
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

    var ds6a = new Ext.data.Store({
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

    var ds6b = new Ext.data.Store({
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
 
    var dataview1=new Ext.DataView({
        tpl: resultTpl,
        store: ds1
    });

     var dataview2=new Ext.DataView({
        tpl: resultTpl,
        store: ds2
    });

    var dataview3a=new Ext.DataView({
        tpl: resultTpl,
        store: ds3a
    });

    var dataview3b=new Ext.DataView({
        tpl: resultTpl,
        store: ds3b
    });

    var dataview4a=new Ext.DataView({
        tpl: resultTpl,
        store: ds4a
    });

    var dataview4b=new Ext.DataView({
        tpl: resultTpl,
        store: ds4b
    });

    var dataview4c=new Ext.DataView({
        tpl: resultTpl,
        store: ds4c
    });

    var dataview5a=new Ext.DataView({
        tpl: resultTpl,
        store: ds5a
    });

    var dataview5b=new Ext.DataView({
        tpl: resultTpl,
        store: ds5b
    });

    var dataview6a=new Ext.DataView({
        tpl: resultTpl,
        store: ds6a
    });

    var dataview6b=new Ext.DataView({
        tpl: resultTpl,
        store: ds6b
    });

    var textarea1=  new Ext.form.TextArea({
        width: 500,
        height: 100
    });

    var textarea2=  new Ext.form.TextArea({
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

    var textarea4a=  new Ext.form.TextArea({
        width: 500,
        height: 100
    });

    var textarea4b=  new Ext.form.TextArea({
        width: 500,
        height: 100
    });

    var textarea4c=  new Ext.form.TextArea({
        width: 500,
        height: 100
    });

    var textarea5a=  new Ext.form.TextArea({
        width: 500,
        height: 100
    });

    var textarea5b=  new Ext.form.TextArea({
        width: 500,
        height: 100
    });

    var textarea6a=  new Ext.form.TextArea({
        width: 500,
        height: 100
    });

    var textarea6b=  new Ext.form.TextArea({
        width: 500,
        height: 100
    });

    CommentsPanel.override({ renderTo : 'question1comment' });
  
   commentsPanel1= new CommentsPanel(
    dataview1,
    textarea1,
    new Ext.Button({
        text:'Save',
        handler: function(){
      
        ds1.load({
        params:{
            courseid:args[0],
            formnumber:'B',
            question:'B1',
            comment: textarea1.getValue()
        }
        });
        textarea1.setValue('');
        }
    })
    );

    CommentsPanel.override({ renderTo : 'question2comment' });

    commentsPanel2= new CommentsPanel(
    dataview2,
    textarea2,
    new Ext.Button({
        text:'Save',
        handler: function(){

        ds2.load({
        params:{
            courseid:args[0],
            formnumber:'B',
            question:'B2',
            comment: textarea2.getValue()
        }
        });
        textarea2.setValue('');
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
            formnumber:'B',
            question:'B3a',
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
            formnumber:'B',
            question:'B3b',
            comment: textarea3a.getValue()
        }
        });
        textarea3b.setValue('');
        }
    })
    );

    CommentsPanel.override({ renderTo : 'question4acomment' });

    commentsPanel4a= new CommentsPanel(
    dataview4a,
    textarea4a,
    new Ext.Button({
        text:'Save',
        handler: function(){

        ds4a.load({
        params:{
            courseid:args[0],
            formnumber:'B',
            question:'B4a',
            comment: textarea4a.getValue()
        }
        });
        textarea4a.setValue('');
        }
    })
    );

    CommentsPanel.override({ renderTo : 'question4bcomment' });

    commentsPanel4b= new CommentsPanel(
    dataview4b,
    textarea4b,
    new Ext.Button({
        text:'Save',
        handler: function(){

        ds4b.load({
        params:{
            courseid:args[0],
            formnumber:'B',
            question:'B4b',
            comment: textarea4b.getValue()
        }
        });
        textarea4b.setValue('');
        }
    })
    );

    CommentsPanel.override({ renderTo : 'question4ccomment' });

    commentsPanel4c= new CommentsPanel(
    dataview4c,
    textarea4c,
    new Ext.Button({
        text:'Save',
        handler: function(){

        ds4c.load({
        params:{
            courseid:args[0],
            formnumber:'B',
            question:'B4c',
            comment: textarea4c.getValue()
        }
        });
        textarea4c.setValue('');
        }
    })
    );

    CommentsPanel.override({ renderTo : 'question5acomment' });

    commentsPanel5a= new CommentsPanel(
    dataview5a,
    textarea5a,
    new Ext.Button({
        text:'Save',
        handler: function(){

        ds5a.load({
        params:{
            courseid:args[0],
            formnumber:'B',
            question:'B5a',
            comment: textarea5a.getValue()
        }
        });
        textarea5a.setValue('');
        }
    })
    );

    CommentsPanel.override({ renderTo : 'question5bcomment' });

    commentsPanel5b= new CommentsPanel(
    dataview5b,
    textarea5b,
    new Ext.Button({
        text:'Save',
        handler: function(){

        ds5b.load({
        params:{
            courseid:args[0],
            formnumber:'B',
            question:'B5b',
            comment: textarea5b.getValue()
        }
        });
        textarea5b.setValue('');
        }
    })
    );

    CommentsPanel.override({ renderTo : 'question6acomment' });

    commentsPanel6a= new CommentsPanel(
    dataview6a,
    textarea6a,
    new Ext.Button({
        text:'Save',
        handler: function(){

        ds6a.load({
        params:{
            courseid:args[0],
            formnumber:'B',
            question:'B6a',
            comment: textarea6a.getValue()
        }
        });
        textarea6a.setValue('');
        }
    })
    );

    CommentsPanel.override({ renderTo : 'question6bcomment' });

    commentsPanel6b= new CommentsPanel(
    dataview6b,
    textarea6b,
    new Ext.Button({
        text:'Save',
        handler: function(){

        ds6b.load({
        params:{
            courseid:args[0],
            formnumber:'B',
            question:'B6b',
            comment: textarea6b.getValue()
        }
        });
        textarea6b.setValue('');
        }
    })
    );

    ButtonPanel.override({ renderTo : 'question1comment' });
    new ButtonPanel(
        [{
            id: 'cbutton1',
            iconCls: 'commentadd',
            handler: function(){
                if(Ext.getCmp('cbutton1').iconCls == 'commentadd'){
                    Ext.getCmp('cbutton1').setIconClass('commentcollapse');
                    ds1.load({params:{courseid:xcourseid,formnumber:'B', question:'B1', comment: textarea1.getValue()}});
                    commentsPanel1.expand();
                }else{
                    Ext.getCmp('cbutton1').setIconClass('commentadd');
                   commentsPanel1.collapse()
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

    ButtonPanel.override({
        renderTo : 'question2comment'
    });
    new ButtonPanel(
        [{
            id: 'cbutton2',
            iconCls: 'commentadd',
            handler: function(){
                if(Ext.getCmp('cbutton2').iconCls == 'commentadd'){
                    Ext.getCmp('cbutton2').setIconClass('commentcollapse');
                   ds2.load({params:{courseid:xcourseid,formnumber:'B', question:'B2', comment: textarea2.getValue()}});
                    commentsPanel2.expand();
                }else{
                    Ext.getCmp('cbutton2').setIconClass('commentadd');
                    commentsPanel2.collapse()
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
                    ds3a.load({params:{courseid:xcourseid,formnumber:'B', question:'B3a', comment: textarea3a.getValue()}});
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
                    ds3b.load({params:{courseid:xcourseid,formnumber:'B', question:'B3b', comment: textarea3b.getValue()}});
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

    ButtonPanel.override({ renderTo : 'question4acomment' });
    new ButtonPanel(
        [{
            id: 'cbutton4a',
            iconCls: 'commentadd',
            handler: function(){
                if(Ext.getCmp('cbutton4a').iconCls == 'commentadd'){
                    Ext.getCmp('cbutton4a').setIconClass('commentcollapse');
                    ds4a.load({params:{courseid:xcourseid,formnumber:'B', question:'B4a', comment: textarea4a.getValue()}});
                    commentsPanel4a.expand();
                }else{
                    Ext.getCmp('cbutton4a').setIconClass('commentadd');
                    commentsPanel4a.collapse()
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

    ButtonPanel.override({ renderTo : 'question4bcomment' });
    new ButtonPanel(
        [{
            id: 'cbutton4b',
            iconCls: 'commentadd',
            handler: function(){
                if(Ext.getCmp('cbutton4b').iconCls == 'commentadd'){
                    Ext.getCmp('cbutton4b').setIconClass('commentcollapse');
                    ds4b.load({params:{courseid:xcourseid,formnumber:'B', question:'B4b', comment: textarea4b.getValue()}});
                    commentsPanel4b.expand();
                }else{
                    Ext.getCmp('cbutton4b').setIconClass('commentadd');
                    commentsPanel4b.collapse();
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

    ButtonPanel.override({ renderTo : 'question4ccomment' });
    new ButtonPanel(
        [{
            id: 'cbutton4c',
            iconCls: 'commentadd',
            handler: function(){
                if(Ext.getCmp('cbutton4c').iconCls == 'commentadd'){
                    Ext.getCmp('cbutton4c').setIconClass('commentcollapse');
                    ds4c.load({params:{courseid:xcourseid,formnumber:'B', question:'B4c', comment: textarea4c.getValue()}});
                    commentsPanel4c.expand();
                }else{
                    Ext.getCmp('cbutton4c').setIconClass('commentadd');
                    commentsPanel4c.collapse();
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

    ButtonPanel.override({ renderTo : 'question5acomment' });
    new ButtonPanel(
        [{
            id: 'cbutton5a',
            iconCls: 'commentadd',
            handler: function(){
                if(Ext.getCmp('cbutton5a').iconCls == 'commentadd'){
                    Ext.getCmp('cbutton5a').setIconClass('commentcollapse');
                    ds5a.load({params:{courseid:xcourseid,formnumber:'B', question:'B5a', comment: textarea5a.getValue()}});
                    commentsPanel5a.expand();
                }else{
                    Ext.getCmp('cbutton5a').setIconClass('commentadd');
                    commentsPanel5a.collapse();
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

    ButtonPanel.override({ renderTo : 'question5bcomment' });
    new ButtonPanel(
        [{
            id: 'cbutton5b',
            iconCls: 'commentadd',
            handler: function(){
                if(Ext.getCmp('cbutton5b').iconCls == 'commentadd'){
                    Ext.getCmp('cbutton5b').setIconClass('commentcollapse');
                    ds5b.load({params:{courseid:xcourseid,formnumber:'B', question:'B5b', comment: textarea5b.getValue()}});
                    commentsPanel5b.expand();
                }else{
                    Ext.getCmp('cbutton5b').setIconClass('commentadd');
                    commentsPanel5b.collapse();
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

    ButtonPanel.override({ renderTo : 'question6acomment' });
    new ButtonPanel(
        [{
            id: 'cbutton6a',
            iconCls: 'commentadd',
            handler: function(){
                if(Ext.getCmp('cbutton6a').iconCls == 'commentadd'){
                    Ext.getCmp('cbutton6a').setIconClass('commentcollapse');
                    ds6a.load({params:{courseid:xcourseid,formnumber:'B', question:'B6a', comment: textarea6a.getValue()}});
                    commentsPanel6a.expand();
                }else{
                    Ext.getCmp('cbutton6a').setIconClass('commentadd');
                    commentsPanel6a.collapse();
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

    ButtonPanel.override({ renderTo : 'question6bcomment' });
    new ButtonPanel(
        [{
            id: 'cbutton6b',
            iconCls: 'commentadd',
            handler: function(){
                if(Ext.getCmp('cbutton6b').iconCls == 'commentadd'){
                    Ext.getCmp('cbutton6b').setIconClass('commentcollapse');
                    ds6b.load({params:{courseid:xcourseid,formnumber:'B', question:'B6b', comment: textarea6b.getValue()}});
                    commentsPanel6b.expand();
                }else{
                    Ext.getCmp('cbutton6b').setIconClass('commentadd');
                    commentsPanel6b.collapse();
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